<?php

namespace Database\Seeders;

use App\Imports\MasterData\T2023\Importer;
use App\Models\IndikatorSubKegiatan;
use App\Models\Kegiatan;
use App\Models\Program;
use App\Models\SatuanKerja;
use App\Models\SubKegiatan;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class MasterData2023Seeder extends Seeder
{
    private array $satker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', -1);

        // $this->mapSatker();
        // $this->syncIndikatorSubKegiatan();

        // if (!Program::tahunKinerja(2023)->exists()) {
        //     $this->command->warn('Skip data program 2023 karena sudah ada data');
        // } else {
        //     $this->command->info('Insert data program 2023');
        //     $this->program();
        // }

        // if (!Kegiatan::tahunKinerja(2023)->exists()) {
        //     $this->command->warn('Skip data kegiatan 2023 karena sudah ada data');
        // } else {
        //     $this->command->info('Insert data kegiatan 2023');
        //     $this->kegiatan();
        // }

        // if (!SubKegiatan::tahunKinerja(2023)->exists()) {
        //     $this->command->warn('Skip data sub kegiatan 2023 karena sudah ada data');
        // } else {
        //     $this->command->info('Insert data sub kegiatan 2023');
        //     $this->subKegiatan();
        // }

        // $this->syncAnggaran();

        $this->updateIndikatorSubKegiatan();
    }

    private function isSetda(string $satker)
    {
        return strtolower($satker) == 'sekretariat daerah';
    }

    private function program()
    {
        $now = now()->toDateTimeString();
        $data = $this->import('program')
            ->unique(fn ($item) => $this->isSetda($item['nama_skpd']) ? $item['id_sub_skpd'].$item['kode_program'] : $item['id_skpd'].$item['kode_program'])
            ->transform(fn ($item) => [
                'foo' => $item['nama_skpd'],
                'tahun_kinerja' => 2023,
                'kode' => $item['kode_program'],
                'nama' => $item['nama_program'],
                'satuan_kerja_id' => $this->parseSatuanKerjaId($item['nama_skpd'], $item['nama_sub_skpd']),
                'anggaran' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->values();

        foreach ($data as $item) {
            if (! $this->isSetda($item['foo'])) {
                continue;
            }

            Program::query()
                ->where('tahun_kinerja', 2023)
                ->where('satuan_kerja_id', 1001)
                ->where('kode', $item['kode'])
                ->update([
                    'satuan_kerja_id' => $item['satuan_kerja_id'],
                ]);

            $exists = Program::query()
                ->where('tahun_kinerja', 2023)
                ->where('satuan_kerja_id', $item['satuan_kerja_id'])
                ->where('kode', $item['kode'])
                ->exists();

            if (! $exists) {
                unset($item['foo']);

                Program::query()->create($item);
            }
        }

        // $this->insertWithChunk($data, new Program());
    }

    private function kegiatan()
    {
        $programList = Program::tahunKinerja(2023)
            ->select('id', 'kode', 'satuan_kerja_id')
            ->get();

        $now = now()->toDateTimeString();
        $data = $this->import('kegiatan')
            ->unique(fn ($item) => $this->isSetda($item['nama_skpd']) ? $item['id_sub_skpd'].$item['kode_program'].$item['kode_giat'] : $item['id_skpd'].$item['kode_program'].$item['kode_giat'])
            ->transform(function ($item) use ($programList, $now) {
                $satker = $this->parseSatuanKerjaId($item['nama_skpd'], $item['nama_sub_skpd']);

                $programRelation = $programList->first(fn (Program $program) => $program->kode == $item['kode_program']
                    && $program->satuan_kerja_id == $satker
                );

                if (! $programRelation) {
                    Log::error('Relasi program tidak ditemukan', (array) $item);
                    throw new Exception('Relasi program tidak ditemukan');
                }

                return [
                    'foo' => $item['nama_skpd'],
                    'kode_program' => $item['kode_program'],
                    'tahun_kinerja' => 2023,
                    'kode' => $item['kode_giat'],
                    'nama' => $item['nama_giat'],
                    'satuan_kerja_id' => $satker,
                    'program_id' => $programRelation->id,
                    'anggaran' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })
            ->values();

        foreach ($data as $item) {
            if (! $this->isSetda($item['foo'])) {
                continue;
            }

            Kegiatan::query()
                ->where('tahun_kinerja', 2023)
                ->where('satuan_kerja_id', 1001)
                ->where('kode', $item['kode'])
                ->where('program_id', $item['program_id'])
                ->update([
                    'satuan_kerja_id' => $item['satuan_kerja_id'],
                ]);

            $programRelation = $programList->first(fn (Program $program) => $program->kode == $item['kode_program']
                && $program->satuan_kerja_id == $item['satuan_kerja_id']
            );

            $exists = Kegiatan::query()
                ->where('tahun_kinerja', 2023)
                ->where('satuan_kerja_id', $item['satuan_kerja_id'])
                ->where('kode', $item['kode'])
                ->where('program_id', $programRelation->id)
                ->exists();

            if (! $exists) {
                unset($item['foo'], $item['kode_program']);

                Kegiatan::query()->create($item);
            }
        }

        // $this->insertWithChunk($data, new Kegiatan());
    }

    private function subKegiatan()
    {
        $kegiatanList = Kegiatan::tahunKinerja(2023)
            ->select('id', 'kode', 'satuan_kerja_id', 'program_id')
            ->with('program')
            ->get()
            ->transform(fn (Kegiatan $kegiatan) => (object) [
                'id' => $kegiatan->id,
                'kode' => $kegiatan->kode,
                'satuan_kerja_id' => $kegiatan->satuan_kerja_id,
                'program_id' => $kegiatan->program_id,
                'program_kode' => $kegiatan->program->kode,
            ]);

        $now = now()->toDateTimeString();
        $data = $this->import('subkegiatan')
            ->transform(function (Collection $item) use ($kegiatanList, $now) {
                $satker = $this->parseSatuanKerjaId($item['nama_skpd'], $item['nama_sub_skpd']);

                $kegiatanRelation = $kegiatanList->first(fn (object $kegiatan) => $kegiatan->program_kode == $item['kode_program']
                    && $kegiatan->kode == $item['kode_giat']
                    && $kegiatan->satuan_kerja_id == $satker
                );

                if (! $kegiatanRelation) {
                    Log::error('Relasi kegiatan tidak ditemukan', $item);
                    throw new Exception('Relasi kegiatan tidak ditemukan');
                }

                return [
                    'foo' => $item['nama_skpd'],
                    'kode_program' => $item['kode_program'],
                    'kode_giat' => $item['kode_giat'],
                    'tahun_kinerja' => 2023,
                    'kode' => $item['kode_sub_giat'],
                    'nama' => $item['nama_sub_giat'],
                    'satuan_kerja_id' => $satker,
                    'kegiatan_id' => $kegiatanRelation->id,
                    'anggaran' => 0,
                    'indikator' => $this->parseNull($item['tolok_ukur_sub']),
                    'target' => $this->parseNull($item['target_sub']),
                    'satuan' => $this->parseNull($item['satuan_sub']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            });

        $groupData = $data->groupBy(fn (array $item) => "{$item['satuan_kerja_id']}|{$item['kegiatan_id']}|{$item['kode']}");
        $bar = $this->command->getOutput()->createProgressBar($groupData->count());

        foreach ($groupData as $item) {
            $sample = $item[0];

            if (! $this->isSetda($sample['foo'])) {
                continue;
            }

            SubKegiatan::query()
                ->where('tahun_kinerja', 2023)
                ->where('satuan_kerja_id', 1001)
                ->where('kode', $sample['kode'])
                ->where('kegiatan_id', $sample['kegiatan_id'])
                ->update([
                    'satuan_kerja_id' => $sample['satuan_kerja_id'],
                ]);

            $kegiatanRelation = $kegiatanList->first(fn (object $kegiatan) => $kegiatan->program_kode == $sample['kode_program']
                && $kegiatan->kode == $sample['kode_giat']
                && $kegiatan->satuan_kerja_id == $sample['satuan_kerja_id']
            );

            $exists = SubKegiatan::query()
                ->where('tahun_kinerja', 2023)
                ->where('satuan_kerja_id', 1001)
                ->where('kode', $sample['kode'])
                ->where('kegiatan_id', $kegiatanRelation->id)
                ->exists();

            if (! $exists) {
                unset($sample['foo'], $sample['kode_program'], $sample['kode_giat']);

                try {
                    DB::transaction(function () use ($sample, $item) {
                        $subKegiatan = SubKegiatan::query()->create([
                            'tahun_kinerja' => $sample['tahun_kinerja'],
                            'kode' => $sample['kode'],
                            'nama' => $sample['nama'],
                            'satuan_kerja_id' => $sample['satuan_kerja_id'],
                            'kegiatan_id' => $sample['kegiatan_id'],
                            'anggaran' => $sample['anggaran'],
                        ]);

                        $indikator = $item
                            ->filter(fn (array $el) => $el['indikator'])
                            ->map(fn (array $el) => [
                                'sub_kegiatan_id' => $subKegiatan->id,
                                'indikator' => $el['indikator'],
                                'target' => $el['target'],
                                'satuan' => $el['satuan'],
                                'created_at' => $subKegiatan->created_at,
                                'updated_at' => $subKegiatan->created_at,
                            ]);

                        IndikatorSubKegiatan::query()->insert($indikator->toArray());

                    });
                } catch (Exception $e) {
                    throw $e;
                }
            }

            DB::transaction(function () {
                // $subKegiatan = SubKegiatan::query()->create([
                //     'tahun_kinerja' => $sample['tahun_kinerja'],
                //     'kode' => $sample['kode'],
                //     'nama' => $sample['nama'],
                //     'satuan_kerja_id' => $sample['satuan_kerja_id'],
                //     'kegiatan_id' => $sample['kegiatan_id'],
                //     'anggaran' => $sample['anggaran'],
                // ]);

                // $indikator = $item
                //     ->filter(fn (array $el) => $el['indikator'])
                //     ->map(fn (array $el) => [
                //         'sub_kegiatan_id' => $subKegiatan->id,
                //         'indikator' => $el['indikator'],
                //         'target' => $el['target'],
                //         'satuan' => $el['satuan'],
                //         'created_at' => $subKegiatan->created_at,
                //         'updated_at' => $subKegiatan->created_at,
                //     ]);

                // IndikatorSubKegiatan::query()->insert($indikator->toArray());
            });

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
    }

    private function syncAnggaran()
    {
        $this->command->info('Sync anggaran sub kegiatan');
        $this->syncAnggaranSubKegiatan();

        $this->command->info('Sync anggaran kegiatan');
        $this->syncAnggaranKegiatan();

        $this->command->info('Sync anggaran program');
        $this->syncAnggaranProgram();
    }

    private function syncAnggaranSubKegiatan()
    {
        $data = $this->import('anggaran-subkegiatan');
        $data = $data->filter(fn ($item) => ! is_null($item['nama_skpd'])
            && ! is_null($item['kode_sub_giat'])
            && ! is_null($item['nama_sub_giat'])
            && ! is_null($item['pagu_sub_kegiatan'])
        );
        $data->transform(function ($item) {
            $item['satuan_kerja_id'] = $this->parseSatuanKerjaId($item['nama_skpd'], $item['nama_sub_skpd']);

            return $item;
        });

        $bar = $this->command->getOutput()->createProgressBar($data->count());

        foreach ($data as $item) {
            SubKegiatan::tahunKinerja(2023)
                ->leftJoin('kegiatan', 'kegiatan.id', 'kegiatan_id')
                ->leftJoin('program', 'program.id', 'program_id')
                ->where('sub_kegiatan.satuan_kerja_id', $item['satuan_kerja_id'])
                ->where('kegiatan.satuan_kerja_id', $item['satuan_kerja_id'])
                ->where('program.satuan_kerja_id', $item['satuan_kerja_id'])
                ->where('sub_kegiatan.kode', $item['kode_sub_giat'])
                ->where('kegiatan.kode', $item['kode_giat'])
                ->where('program.kode', $item['kode_program'])
                ->update([
                    'anggaran' => $item['pagu_sub_kegiatan'],
                ]);

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
    }

    private function syncAnggaranKegiatan()
    {
        $subQuery = SubKegiatan::tahunKinerja(2023)
            ->selectRaw('DISTINCT satuan_kerja_id, kode, kegiatan_id, anggaran');

        $data = DB::query()
            ->selectRaw('satuan_kerja_id, kegiatan_id, SUM(anggaran) anggaran')
            ->fromSub($subQuery, 'a')
            ->groupBy('satuan_kerja_id', 'kegiatan_id')
            ->get();

        $bar = $this->command->getOutput()->createProgressBar($data->count());

        foreach ($data as $item) {
            Kegiatan::tahunKinerja(2023)
                ->where('id', $item->kegiatan_id)
                ->where('satuan_kerja_id', $item->satuan_kerja_id)
                ->update([
                    'anggaran' => $item->anggaran,
                ]);

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
    }

    private function syncAnggaranProgram()
    {
        $subQuery = Kegiatan::tahunKinerja(2023)
            ->selectRaw('DISTINCT satuan_kerja_id, kode, program_id, anggaran');

        $data = DB::query()
            ->selectRaw('satuan_kerja_id, program_id, SUM(anggaran) anggaran')
            ->fromSub($subQuery, 'a')
            ->groupBy('program_id', 'satuan_kerja_id')
            ->get();

        $bar = $this->command->getOutput()->createProgressBar($data->count());

        foreach ($data as $item) {
            Program::tahunKinerja(2023)
                ->where('id', $item->program_id)
                ->where('satuan_kerja_id', $item->satuan_kerja_id)
                ->update([
                    'anggaran' => $item->anggaran,
                ]);

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
    }

    private function insertWithChunk(Collection $data, Program|Kegiatan|SubKegiatan $class)
    {
        $bar = $this->command->getOutput()->createProgressBar($data->count());

        try {
            DB::transaction(function () use ($data, $class, $bar) {
                $chunk = $data->chunk(200);

                foreach ($chunk as $batch) {
                    $class::query()->insert($batch->toArray());
                    $bar->advance($batch->count());
                }
            });
        } catch (Exception $e) {
            throw $e;
        }

        $bar->finish();
        $this->command->newLine();
    }

    private function import(string $fileName): Collection
    {
        return Excel::toCollection(new Importer, database_path("seeders/master-data/2023/{$fileName}.xlsx"))[0];
    }

    private function parseNull(?string $text): ?string
    {
        return (! $text || strtolower($text) == 'null') && $text != 0 ? null : $text;
    }

    private function parseSatuanKerjaId(string $satker, string $subSatker): int
    {
        $satker = strtolower($satker);

        if ($this->isSetda($satker)) {
            $satker = strtolower($subSatker);
        }

        switch ($satker) {
            case 'satuan polisi dan pamong praja':
                $satker = 'satuan polisi pamong praja';
                break;
            case 'dinas pemberdayaan perempuan, perlindungan anak, dan keluarga berencana':
                $satker = 'dinas pemberdayaan perempuan, perlindungan anak dan keluarga berencana';
                break;
            case 'dinas perpustakaan dan kearsipan':
                $satker = 'dinas perpustakaan dan kearsipan daerah';
                break;
            case 'sekretariat dewan perwakilan rakyat daerah':
                $satker = 'sekretariat dprd';
                break;
            case 'inspektorat':
                $satker = 'inspektorat daerah';
                break;
            case 'biro pengadaan barang/jasa':
                $satker = 'biro pengadaan barang dan jasa';
                break;
        }

        return $this->satker[$satker];
    }

    private function mapSatker()
    {
        $this->satker = SatuanKerja::query()
            ->select('satuan_kerja_id', 'satuan_kerja_nama')
            ->get()
            ->keyBy(fn (SatuanKerja $item) => strtolower($item->satuan_kerja_nama))
            ->transform(fn (SatuanKerja $item) => $item->satuan_kerja_id)
            ->toArray();
    }

    private function syncIndikatorSubKegiatan()
    {
        $kegiatanList = Kegiatan::tahunKinerja(2023)
            ->select('id', 'kode', 'satuan_kerja_id', 'program_id')
            ->with('program')
            ->get()
            ->transform(fn (Kegiatan $kegiatan) => (object) [
                'id' => $kegiatan->id,
                'kode' => $kegiatan->kode,
                'satuan_kerja_id' => $kegiatan->satuan_kerja_id,
                'program_id' => $kegiatan->program_id,
                'program_kode' => $kegiatan->program->kode,
            ]);

        $now = now()->toDateTimeString();
        $data = $this->import('subkegiatan')
            ->transform(function (Collection $item) use ($kegiatanList, $now) {
                $satker = $this->parseSatuanKerjaId($item['nama_skpd'], $item['nama_sub_skpd']);

                $kegiatanRelation = $kegiatanList->first(fn (object $kegiatan) => $kegiatan->program_kode == $item['kode_program']
                    && $kegiatan->kode == $item['kode_giat']
                    && $kegiatan->satuan_kerja_id == $satker
                );

                if (! $kegiatanRelation) {
                    Log::error('Relasi kegiatan tidak ditemukan', $item);
                    throw new Exception('Relasi kegiatan tidak ditemukan');
                }

                return [
                    'foo' => $item['nama_skpd'],
                    'kode_program' => $item['kode_program'],
                    'kode_giat' => $item['kode_giat'],
                    'tahun_kinerja' => 2023,
                    'kode' => $item['kode_sub_giat'],
                    'nama' => $item['nama_sub_giat'],
                    'satuan_kerja_id' => $satker,
                    'kegiatan_id' => $kegiatanRelation->id,
                    'anggaran' => 0,
                    'indikator' => $this->parseNull($item['tolok_ukur_sub']),
                    'target' => $this->parseNull($item['target_sub']),
                    'satuan' => $this->parseNull($item['satuan_sub']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            });

        $groupData = $data->groupBy(fn (array $item) => "{$item['satuan_kerja_id']}|{$item['kegiatan_id']}|{$item['kode']}");

        foreach ($groupData as $item) {
            $sample = $item[0];

            $subKegiatan = SubKegiatan::query()
                ->where('tahun_kinerja', 2023)
                ->where('satuan_kerja_id', $sample['satuan_kerja_id'])
                ->where('kode', $sample['kode'])
                ->where('kegiatan_id', $sample['kegiatan_id'])
                ->first();

            if (! $subKegiatan) {
                Log::error('Data sub kegiatan tidak ditemukan', $sample);
                throw new Exception('Data sub kegiatan tidak ditemukan');
            }

            $indikator = $item
                ->map(fn (array $el) => [
                    'sub_kegiatan_id' => $subKegiatan->id,
                    'indikator' => $el['indikator'] ?? 'NULL',
                    'target' => $el['target'] ?? 'NULL',
                    'satuan' => $el['satuan'] ?? 'NULL',
                    'created_at' => $subKegiatan->created_at,
                    'updated_at' => $subKegiatan->created_at,
                ]);

            IndikatorSubKegiatan::query()->insert($indikator->toArray());
        }
    }

    private function updateIndikatorSubKegiatan()
    {
        $this->command->info('Update data indikator sub kegiatan 2023');
        $data = $this->import('indikator-subkegiatan');
        $bar = $this->command->getOutput()->createProgressBar($data->count());

        foreach ($data as $item) {
            IndikatorSubKegiatan::query()
                ->where('id', $item['id'])
                ->update([
                    'indikator' => $item['indikator'],
                    'target' => $item['target'],
                    'satuan' => $item['satuan'],
                ]);

            $bar->advance();
        }

        $bar->finish();
    }
}
