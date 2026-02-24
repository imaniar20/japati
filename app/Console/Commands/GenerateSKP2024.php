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

class GenerateSKP2024 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-skp-2024 {--satker=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate SKP 2024';

    private array $satker;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->satker = $this->option('satker');

        $this->validate();
        $this->setFlagSkp();
    }

    private function validate()
    {
        $baseQuery = [
            'sasaran-strategis-pd' => SasaranStrategisPd::tahunMulai(2024),
            'kinerja-program' => KinerjaProgram::tahunKinerja(2024),
            'kinerja-kegiatan' => KinerjaKegiatan::tahunKinerja(2024),
            'kinerja-sub-kegiatan' => KinerjaSubKegiatan::tahunKinerja(2024),
        ];

        foreach ($baseQuery as $key => $query) {
            $this->info($key);

            $query->when(count($this->satker), fn (Builder $query) => $query->whereIn('satuan_kerja_id', $this->satker));

            $bar = $this->output->createProgressBar($query->count());

            $query
                ->select('id')
                ->chunk(100, function (Collection $data) use ($key, $bar) {
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
     * -- ⁠kalau biru muda diampu Es III dari RS (unit kerja RS) maka dia masuk SKP
     *
     * kinerja sub kegiatan
     * semua masuk sebagai SKP kecuali nama sub kegiatan yang ada di list
     */
    private function setFlagSkp()
    {
        $this->info('Unset kinerja kegiatan');
        KinerjaKegiatan::tahunKinerja(2024)
            ->where('pengampu', 'unit-kerja')
            ->whereRaw('satuan_kerja_id::VARCHAR NOT LIKE ?', [SATKER_SETDA.'%'])
            ->whereHas('strukturOrganisasi', fn (Builder $query) => $query
                ->orWhere('jabatan_nama', 'SEKRETARIS')
                ->orWhere('jabatan_nama', 'LIKE', 'KEPALA BIDANG %')
                ->orWhere('jabatan_nama', 'LIKE', 'KEPALA UPTD %')
                ->orWhere('jabatan_nama', 'LIKE', 'KEPALA CABANG DINAS %')
                ->orWhere('jabatan_nama', 'LIKE', 'INSPEKTUR PEMBANTU %')
            )
            ->when(count($this->satker), fn (Builder $query) => $query->whereIn('satuan_kerja_id', $this->satker))
            ->chunk(100, function (Collection $data) {
                SKP::tahunKinerja(2024)
                    ->where('model_class', KinerjaKegiatan::class)
                    ->whereIn('model_id', $data->pluck('id'))
                    ->update([
                        'is_skp' => false,
                    ]);
            });

        $this->info('Unset kinerja kegiatan dinkes unit kerja non RS');
        KinerjaKegiatan::tahunKinerja(2024)
            ->where('pengampu', 'unit-kerja')
            ->where('satuan_kerja_id', SATKER_DINKES)
            ->whereHas('strukturOrganisasi', fn (Builder $query) => $query
                ->where('unit_kerja_nama', 'NOT LIKE', '%RUMAH SAKIT%')
                ->where('unit_kerja_nama', 'NOT LIKE', '%WAKIL DIREKTUR%')
            )
            ->when(count($this->satker), fn (Builder $query) => $query->whereIn('satuan_kerja_id', $this->satker))
            ->chunk(100, function (Collection $data) {
                SKP::tahunKinerja(2024)
                    ->where('model_class', KinerjaKegiatan::class)
                    ->whereIn('model_id', $data->pluck('id'))
                    ->update([
                        'is_skp' => false,
                    ]);
            });

        $this->info('Set kinerja kegiatan dinkes unit kerja RS');
        KinerjaKegiatan::tahunKinerja(2024)
            ->where('pengampu', 'unit-kerja')
            ->where('satuan_kerja_id', SATKER_DINKES)
            ->whereHas('strukturOrganisasi', fn (Builder $query) => $query
                ->where('unit_kerja_nama', 'LIKE', '%RUMAH SAKIT%')
            )
            ->when(count($this->satker), fn (Builder $query) => $query->whereIn('satuan_kerja_id', $this->satker))
            ->chunk(100, function (Collection $data) {
                SKP::tahunKinerja(2024)
                    ->where('model_class', KinerjaKegiatan::class)
                    ->whereIn('model_id', $data->pluck('id'))
                    ->update([
                        'is_skp' => true,
                    ]);
            });
    }
}
