<?php

namespace App\Console\Commands;

use App\Http\Controllers\SKPController;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\SasaranStrategisPd;
use App\Models\SKP;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class GenerateSKP2025 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-skp-2025 {--satker=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate SKP 2025';

    private array $satker;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->satker = $this->option('satker');

        setTahunKinerja(2025);

        $this->validate();
        $this->setFlagSkp();

        $this->info('Sync parent SKP');
        $this->call('sync:parent-skp', [
            '--tahun' => 2025,
            '--satker' => $this->satker,
        ]);
    }

    private function validate()
    {
        $baseQuery = [
            'sasaran-strategis-pd' => SasaranStrategisPd::tahunMulai(),
            'kinerja-program' => KinerjaProgram::tahunKinerja(),
            'kinerja-kegiatan' => KinerjaKegiatan::tahunKinerja(),
            'kinerja-sub-kegiatan' => KinerjaSubKegiatan::tahunKinerja(),
        ];

        foreach ($baseQuery as $key => $query) {
            $this->info($key);

            $query->when(count($this->satker), fn (Builder $query) => $query->whereIn('satuan_kerja_id', $this->satker));

            $bar = $this->output->createProgressBar($query->count());

            $query
                ->select('id')
                ->chunkById(100, function (Collection $data) use ($key, $bar) {
                    foreach ($data as $item) {
                        $controller = new SKPController;
                        $request = new Request([
                            'class' => $key,
                            'id' => $item->id,
                            'keterangan' => 'Divalidasi oleh sistem',
                        ]);

                        $controller->validateSkp($request);
                        $bar->advance();
                    }
                });

            $bar->finish();
            $this->newLine();
        }
    }

    /**
     * sasaran strategis PD dan kinerja program:
     * semua masuk sebagai SKP
     *
     * kinerja kegiatan
     * tidak masuk ke SKP:
     * - bukan setda/biro
     * - yang diampu Sekretaris dan Kepala Bidang (Es III.a)
     * - ⁠yang diampu Kepala UPTD dan Kepala KCD (Es III.a)
     * - di INSPEKTORAT DAERAH yang diampu INSPEKTUR PEMBANTU
     * - dinkes:
     * -- kalau biru muda diampu Es III dari dinkes pusat (unit kerja non RS dan unit kerja non WAKIL DIREKTUR khusus di al ihsan) maka dia bukan SKP
     * -- ⁠kalau biru muda diampu Es III dari RS (unit kerja RS) atau parentnya unit kerja RS maka dia masuk SKP
     * - DPMPTSP
     * -- yang ketua tim kerja sama dengan kinerja program nya maka bukan SKP
     *
     * kinerja sub kegiatan
     * semua masuk sebagai SKP kecuali nama sub kegiatan yang ada di list:
     * - komponen instalasi listrik / komponen listrik
     * - barang cetakan
     */
    private function setFlagSkp()
    {
        $this->info('Unset kinerja kegiatan');
        KinerjaKegiatan::tahunKinerja()
            ->select('id')
            ->where('pengampu', 'unit-kerja')
            ->whereRaw('satuan_kerja_id::VARCHAR NOT LIKE ?', [SATKER_SETDA.'%'])
            ->whereHas('strukturOrganisasi', fn (Builder $query) => $query
                ->whereIn('eselon_id', [31, 32])
            )
            ->when(count($this->satker), fn (Builder $query) => $query->whereIn('satuan_kerja_id', $this->satker))
            ->chunkById(100, function (Collection $data) {
                SKP::tahunKinerja()
                    ->where('model_class', KinerjaKegiatan::class)
                    ->whereIn('model_id', $data->pluck('id'))
                    ->update([
                        'is_skp' => false,
                    ]);
            });

        $this->info('Unset kinerja kegiatan dinkes unit kerja non RS');
        KinerjaKegiatan::tahunKinerja()
            ->select('id')
            ->where('pengampu', 'unit-kerja')
            ->where('satuan_kerja_id', SATKER_DINKES)
            ->whereHas('strukturOrganisasi', fn (Builder $query) => $query
                ->where('unit_kerja_nama', 'NOT LIKE', '%RUMAH SAKIT%')
                ->where('unit_kerja_nama', 'NOT LIKE', '%WAKIL DIREKTUR%')
            )
            ->when(count($this->satker), fn (Builder $query) => $query->whereIn('satuan_kerja_id', $this->satker))
            ->chunkById(100, function (Collection $data) {
                SKP::tahunKinerja()
                    ->where('model_class', KinerjaKegiatan::class)
                    ->whereIn('model_id', $data->pluck('id'))
                    ->update([
                        'is_skp' => false,
                    ]);
            });

        $this->info('Set kinerja kegiatan dinkes unit kerja RS');
        KinerjaKegiatan::tahunKinerja()
            ->select('id')
            ->where('pengampu', 'unit-kerja')
            ->where('satuan_kerja_id', SATKER_DINKES)
            ->where(fn (Builder $query) => $query
                ->whereHas('strukturOrganisasi', fn (Builder $query) => $query
                    ->where('unit_kerja_nama', 'LIKE', '%RUMAH SAKIT%')
                )
                ->orWhereHas('kinerjaProgram.strukturOrganisasi', fn (Builder $query) => $query
                    ->where('unit_kerja_nama', 'LIKE', '%RUMAH SAKIT%')
                )
            )
            ->when(count($this->satker), fn (Builder $query) => $query->whereIn('satuan_kerja_id', $this->satker))
            ->chunkById(100, function (Collection $data) {
                SKP::tahunKinerja()
                    ->where('model_class', KinerjaKegiatan::class)
                    ->whereIn('model_id', $data->pluck('id'))
                    ->update([
                        'is_skp' => true,
                    ]);
            });

        $this->info('Unset kinerja kegiatan DPMPTSP ketua tim kerja sama dengan kinerja program nya');
        KinerjaKegiatan::tahunKinerja()
            ->select('id', 'tim_kerja_id', 'kinerja_program_id')
            ->where('pengampu', 'tim-kerja')
            ->where('satuan_kerja_id', SATKER_DPMPTSP)
            ->whereHas('kinerjaProgram', fn (Builder|KinerjaProgram $query) => $query
                ->tahunKinerja()
                ->where('pengampu', 'tim-kerja')
            )
            ->when(count($this->satker), fn (Builder $query) => $query->whereIn('satuan_kerja_id', $this->satker))
            ->with([
                'timKerja:id,nip_ketua',
                'kinerjaProgram:id,tim_kerja_id',
                'kinerjaProgram.timKerja:id,nip_ketua',
            ])
            ->chunkById(100, function (Collection $data) {
                $items = $data->filter(fn (KinerjaKegiatan $item) => $item->timKerja
                    && $item->kinerjaProgram->timKerja
                    && $item->timKerja->nip_ketua === $item->kinerjaProgram->timKerja->nip_ketua
                );

                if ($items->isNotEmpty()) {
                    SKP::tahunKinerja()
                        ->where('model_class', KinerjaKegiatan::class)
                        ->whereIn('model_id', $items->pluck('id'))
                        ->update([
                            'is_skp' => false,
                        ]);
                }
            });

        $this->info('Unset kinerja sub kegiatan untuk sub kegiatan di list');
        KinerjaSubKegiatan::tahunKinerja()
            ->select('id')
            ->whereHas('subKegiatan', fn (Builder $query) => $query
                ->where('nama', 'ILIKE', '%komponen instalasi listrik%')
                ->orWhere('nama', 'ILIKE', '%komponen listrik%')
                ->orWhere('nama', 'ILIKE', '%barang cetakan%')
            )
            ->when(count($this->satker), fn (Builder $query) => $query->whereIn('satuan_kerja_id', $this->satker))
            ->chunkById(100, function (Collection $data) {
                SKP::tahunKinerja()
                    ->where('model_class', KinerjaSubKegiatan::class)
                    ->whereIn('model_id', $data->pluck('id'))
                    ->update([
                        'is_skp' => false,
                    ]);
            });
    }
}
