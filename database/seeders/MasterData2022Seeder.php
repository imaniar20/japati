<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\Program;
use App\Models\SubKegiatan;
use Illuminate\Database\Seeder;

class MasterData2022Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // set data sebelumnya ke tahun 2021
        Program::whereNull('tahun_kinerja')->update(['tahun_kinerja' => 2021]);
        Kegiatan::whereNull('tahun_kinerja')->update(['tahun_kinerja' => 2021]);
        SubKegiatan::whereNull('tahun_kinerja')->update(['tahun_kinerja' => 2021]);

        if (Program::tahunKinerja(2022)->exists()) {
            echo 'Skip data program 2022 karena sudah ada data'.PHP_EOL;
        } else {
            echo 'Insert data program 2022'.PHP_EOL;
            $this->program();
        }

        if (Kegiatan::tahunKinerja(2022)->exists()) {
            echo 'Skip data kegiatan 2022 karena sudah ada data'.PHP_EOL;
        } else {
            echo 'Insert data kegiatan 2022'.PHP_EOL;
            $this->kegiatan();
        }

        if (SubKegiatan::tahunKinerja(2022)->exists()) {
            echo 'Skip data sub kegiatan 2022 karena sudah ada data'.PHP_EOL;
        } else {
            echo 'Insert data sub kegiatan 2022'.PHP_EOL;
            $this->subKegiatan();
        }
    }

    private function program()
    {
        $now = now()->toDateTimeString();

        $program = $this->csvToArray('program');

        foreach ($program as $data) {
            foreach ($data as &$entry) {
                $entry['tahun_kinerja'] = 2022;
                $entry['updated_at'] = $now;
                $entry['created_at'] = $now;

                unset($entry['satuan_kerja']);
            }

            Program::insert($data);
        }
    }

    private function kegiatan()
    {
        $now = now()->toDateTimeString();

        $kegiatan = $this->csvToArray('kegiatan');

        foreach ($kegiatan as $data) {
            foreach ($data as &$entry) {
                $programId = Program::tahunKinerja(2022)
                    ->where('kode', $entry['program_kode'])
                    ->where('satuan_kerja_id', $entry['satuan_kerja_id'])
                    ->value('id');

                $entry['tahun_kinerja'] = 2022;
                $entry['program_id'] = $programId;
                $entry['updated_at'] = $now;
                $entry['created_at'] = $now;

                unset($entry['satuan_kerja'], $entry['program_kode']);
            }

            Kegiatan::insert($data);
        }
    }

    private function subKegiatan()
    {
        $now = now()->toDateTimeString();

        $kegiatan = $this->csvToArray('subkegiatan');

        foreach ($kegiatan as $data) {
            foreach ($data as &$entry) {
                $kegiatanId = Kegiatan::tahunKinerja(2022)
                    ->where('kode', $entry['kegiatan_kode'])
                    ->where('satuan_kerja_id', $entry['satuan_kerja_id'])
                    ->value('id');

                $entry['tahun_kinerja'] = 2022;
                $entry['kegiatan_id'] = $kegiatanId;
                $entry['updated_at'] = $now;
                $entry['created_at'] = $now;

                unset($entry['satuan_kerja'], $entry['kegiatan_kode']);
            }

            SubKegiatan::insert($data);
        }
    }

    private function csvToArray(string $path)
    {
        $file = fopen(database_path("seeders/master-data/2022/{$path}.csv"), 'r');
        $delimiter = ',';
        $iterator = 0;
        $header = null;
        $data = [];
        $chunk = 1000;

        while (($row = fgetcsv($file, $chunk, $delimiter)) !== false) {
            $isMultiple = false;

            if (! $header) {
                $header = $row;
            } else {
                $iterator++;
                $data[] = array_combine($header, $row);

                if ($iterator != 0 && $iterator % $chunk == 0) {
                    $isMultiple = true;
                    $temp = $data;
                    $data = [];

                    yield $temp;
                }
            }
        }

        fclose($file);

        if (! $isMultiple) {
            yield $data;
        }
    }
}
