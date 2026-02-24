<?php

namespace App\Console\Commands;

use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FixParentKinerja extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-parent-kinerja {tahun-kinerja}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix relasi parent kinerja';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        setTahunKinerja($this->argument('tahun-kinerja'));

        $this->kinerjaKegiatan();
        $this->kinerjaSubKegiatan();
    }

    private function kinerjaKegiatan()
    {
        KinerjaKegiatan::tahunKinerja()
            ->whereDoesntHave('kinerjaProgram', fn (Builder $query) => $query->tahunKinerja())
            ->chunk(100, function (Collection $data) {
                $kinerjaProgramList = KinerjaProgram::tahunKinerja()
                    ->whereIn('program_id', $data->pluck('program_id'))
                    ->whereIn('satuan_kerja_id', $data->pluck('satuan_kerja_id'))
                    ->get();

                foreach ($data as $item) {
                    $kinerjaProgram = $kinerjaProgramList
                        ->where('program_id', $item->program_id)
                        ->where('satuan_kerja_id', $item->satuan_kerja_id)
                        ->first();

                    if (! $kinerjaProgram) {
                        continue;
                    }

                    $item->update([
                        'kinerja_program_id' => $kinerjaProgram->id,
                    ]);
                }
            });
    }

    private function kinerjaSubKegiatan()
    {
        KinerjaSubKegiatan::tahunKinerja()
            ->whereDoesntHave('kinerjaKegiatan', fn (Builder $query) => $query->tahunKinerja())
            ->chunk(100, function (Collection $data) {
                $kinerjaKegiatanList = KinerjaKegiatan::tahunKinerja()
                    ->whereIn('kegiatan_id', $data->pluck('kegiatan_id'))
                    ->whereIn('satuan_kerja_id', $data->pluck('satuan_kerja_id'))
                    ->get();

                foreach ($data as $item) {
                    $kinerjaKegiatan = $kinerjaKegiatanList
                        ->where('kegiatan_id', $item->kegiatan_id)
                        ->where('satuan_kerja_id', $item->satuan_kerja_id)
                        ->first();

                    if (! $kinerjaKegiatan) {
                        continue;
                    }

                    $item->update([
                        'kinerja_kegiatan_id' => $kinerjaKegiatan->id,
                    ]);
                }
            });
    }
}
