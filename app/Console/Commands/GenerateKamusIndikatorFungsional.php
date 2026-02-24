<?php

namespace App\Console\Commands;

use App\Models\KamusIndikatorFungsional;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class GenerateKamusIndikatorFungsional extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:kamus-indikator-fungsional {tahun-kinerja}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tabel kamus_indikator_fungsional';

    private int $tahunKinerja;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->tahunKinerja = $this->argument('tahun-kinerja');
        setTahunKinerja($this->tahunKinerja);

        $this->kinerjaProgram();
        $this->KinerjaKegiatan();
        $this->kinerjaSubKegiatan();

        return Command::SUCCESS;
    }

    private function kinerjaProgram()
    {
        KinerjaProgram::tahunKinerja($this->tahunKinerja)
            ->with([
                'strukturOrganisasi' => fn (Builder $query) => $query
                    ->selectRaw('id, jabatan_nama')
                    ->withoutGlobalScope('active'),
                'timKerja:id,nama',
            ])
            ->chunk(100, function (Collection $data) {
                foreach ($data as $item) {
                    $pengampu = $item->pengampu == 'unit-kerja'
                        ? $item->strukturOrganisasi?->jabatan_nama
                        : $item->timKerja?->nama;

                    $exists = KamusIndikatorFungsional::query()
                        ->where('tahun_kinerja', $item->tahun_kinerja)
                        ->where('model_class', KinerjaProgram::class)
                        ->where('model_id', $item->id)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    KamusIndikatorFungsional::query()->create([
                        'tahun_mulai' => getTahunMulai(),
                        'tahun_kinerja' => $item->tahun_kinerja,
                        'satuan_kerja_id' => $item->satuan_kerja_id,
                        'model_class' => KinerjaProgram::class,
                        'model_id' => $item->id,
                        'pengampu' => $pengampu,
                    ]);
                }
            });
    }

    private function KinerjaKegiatan()
    {
        KinerjaKegiatan::tahunKinerja($this->tahunKinerja)
            ->with([
                'strukturOrganisasi' => fn (Builder $query) => $query
                    ->selectRaw('id, jabatan_nama')
                    ->withoutGlobalScope('active'),
                'timKerja:id,nama',
            ])
            ->chunk(100, function (Collection $data) {
                foreach ($data as $item) {
                    $pengampu = $item->pengampu == 'unit-kerja'
                        ? $item->strukturOrganisasi?->jabatan_nama
                        : $item->timKerja?->nama;

                    $exists = KamusIndikatorFungsional::query()
                        ->where('tahun_kinerja', $item->tahun_kinerja)
                        ->where('model_class', KinerjaKegiatan::class)
                        ->where('model_id', $item->id)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    KamusIndikatorFungsional::query()->create([
                        'tahun_mulai' => getTahunMulai(),
                        'tahun_kinerja' => $item->tahun_kinerja,
                        'satuan_kerja_id' => $item->satuan_kerja_id,
                        'model_class' => KinerjaKegiatan::class,
                        'model_id' => $item->id,
                        'pengampu' => $pengampu,
                    ]);
                }
            });
    }

    private function kinerjaSubKegiatan()
    {
        KinerjaSubKegiatan::tahunKinerja($this->tahunKinerja)
            ->with([
                'strukturOrganisasi' => fn (Builder $query) => $query
                    ->selectRaw('id, jabatan_nama')
                    ->withoutGlobalScope('active'),
                'timKerja:id,nama',
            ])
            ->chunk(100, function (Collection $data) {
                foreach ($data as $item) {
                    $pengampu = $item->pengampu == 'unit-kerja'
                        ? $item->strukturOrganisasi?->jabatan_nama
                        : $item->timKerja?->nama;

                    $exists = KamusIndikatorFungsional::query()
                        ->where('tahun_kinerja', $item->tahun_kinerja)
                        ->where('model_class', KinerjaSubKegiatan::class)
                        ->where('model_id', $item->id)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    KamusIndikatorFungsional::query()->create([
                        'tahun_mulai' => getTahunMulai(),
                        'tahun_kinerja' => $item->tahun_kinerja,
                        'satuan_kerja_id' => $item->satuan_kerja_id,
                        'model_class' => KinerjaSubKegiatan::class,
                        'model_id' => $item->id,
                        'pengampu' => $pengampu,
                    ]);
                }
            });
    }
}
