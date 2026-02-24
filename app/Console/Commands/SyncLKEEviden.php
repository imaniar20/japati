<?php

namespace App\Console\Commands;

use App\Models\LKE\Eviden;
use App\Models\LKE\Kriteria;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class SyncLKEEviden extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:lke-eviden {tahunKinerja} {satuanKerja}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync eviden LKE';

    private int $tahunKinerja;

    private int $satkerId;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->tahunKinerja = $this->argument('tahunKinerja');
        $this->satkerId = $this->argument('satuanKerja');
        $kriteriaList = Kriteria::query()
            ->with([
                'parameter',
                'eviden' => fn (Builder $query) => $query->roleSatuanKerja($this->satkerId)->tahunKinerja($this->tahunKinerja),
            ])
            ->whereHas('subKomponen.komponen', fn (Builder $query) => $query->tahunKinerja($this->tahunKinerja))
            ->get();

        /**
         * @var \App\Models\LKE\Kriteria
         */
        foreach ($kriteriaList as $kriteria) {
            $data = [];

            // jika kriteria dikunci, maka set parameter ke `Ya` (skor tertinggi)
            if ($kriteria->is_locked) {
                $parameter = $kriteria->parameter->where('nama', 'Ya')->first();

                if (! $parameter) {
                    throw new Exception('Parameter pada kriteria terkunci tidak ditemukan');
                }

                $data['parameter_id'] = $parameter->id;
            }

            if ($kriteria->is_auto || $kriteria->jumlah_eviden > 1) {
                $data['eviden'] = $this->getAutoEviden($kriteria);
            }

            Eviden::query()->updateOrCreate([
                'tahun_kinerja' => $this->tahunKinerja,
                'satuan_kerja_id' => $this->satkerId,
                'kriteria_id' => $kriteria->id,
            ], $data);
        }
    }

    /**
     * TODO:
     * auto eviden
     */
    private function getAutoEviden(Kriteria $kriteria): array
    {
        $eviden = [];

        foreach ($kriteria->keterangan_eviden as $index => $keterangan) {
            if (in_array($keterangan, [
                'https://kinerja.jabarprov.go.id/files12/sakip/lke/2024/SALINAN-PERGUB%20NO%2054%20THN%202023%20TTG%20SAKIP%20.pdf',
                'https://kinerja.jabarprov.go.id/files12/sakip/lke/2024/1.%20RPD%20PROVINSI%20JAWA%20BARAT%20TAHUN%202024-2026.pdf',
                'https://kinerja.jabarprov.go.id/files12/sakip/lke/2024/3.%20RKPD%20Tahun%202024.pdf',
                'https://kinerja.jabarprov.go.id/files12/sakip/lke/2024/16.%20RPJPD%20PROVINSI%20JAWA%20BARAT%20.pdf',
                'https://kinerja.jabarprov.go.id/files12/sakip/lke/2024/FIX_FN_RPD%20PROVINSI%20JAWA%20BARAT%20TAHUN%202024-2026.pdf',
                'https://kinerja.jabarprov.go.id/files12/sakip/lke/2024/Pergub%20Renstra%20Perangkat%20Daerah%20Tahun%202024-2026_compressed.pdf',
                'https://kinerja.jabarprov.go.id/files12/sakip/lke/2024/SALINAN-PERGUB%20NO%2054%20THN%202023%20TTG%20SAKIP%20.pdf',
                'https://kinerja.jabarprov.go.id/files12/sakip/lke/2024/2.2.1.pdf',
                'https://kinerja.jabarprov.go.id/files12/sakip/lke/2024/7.%20LKIP%20PEMPROV%20JABAR%20Tahun%202023.pdf',
                'https://kinerja.jabarprov.go.id/files12/sakip/lke/2024/Checklist%20Desk%20LKIP%20PD.xlsx',
                'https://kinerja.jabarprov.go.id/files12/sakip/lke/2024/SALINAN-PERGUB%20NO%2054%20THN%202023%20TTG%20SAKIP%20.pdf',
                'https://kinerja.jabarprov.go.id/files9/sakip/lke/2023/RPJPD%20PROVINSI%20JAWA%20BARAT%20FINAL%20CETAK.pdf',
                'https://kinerja.jabarprov.go.id/files12/sakip/lke/2024/2.1.3.png',
                'https://kinerja.jabarprov.go.id/files12/sakip/lke/2024/4.2.1.png',
            ])) {
                // file upload, sama semua seluruh dinas
                $eviden[] = $keterangan;
            } elseif (in_array($keterangan, [
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-makro/rpjmd',
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/renstra',
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/rkt',
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/perjanjian-kinerja',
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/rencana-aksi',
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/capaian-kinerja-pd',
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/capaian-kinerja-efisiensi-anggaran',
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/capaian-kinerja-langkah-aksi',
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/cascading',
                'https://kinerja.jabarprov.go.id/sakip/public-display/capaian-iku',
                'https://kinerja.jabarprov.go.id/simtunjangan/nilai_kinerja_public',
                'https://kinerja.jabarprov.go.id/sakip/rapor-kinerja/1/diagram',
                'https://kinerja.jabarprov.go.id/kampanye-kinerja',
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/capaian-kinerja-program',
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/capaian-kinerja-kegiatan',
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/capaian-kinerja-sub-kegiatan',
                'https://kinerja.jabarprov.go.id/sakip/public-display/lke/hasil-self-assessment',
                'https://kinerja.jabarprov.go.id/sakip/public-display/lke/hasil-akhir',
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/rencana-aksi-terintegrasi',
                'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/realisasi-rencana-aksi-terintegrasi',
            ])) {
                // link internal sakip, dinamis tergantung id satker dan tahun kinerja
                if (! App::environment('production')) {
                    $link = str_replace('https://kinerja.jabarprov.go.id/sakip', config('app.client_url'), $keterangan);
                } else {
                    $link = $keterangan;
                }

                if ($keterangan == 'https://kinerja.jabarprov.go.id/simtunjangan/nilai_kinerja_public') {
                    $isBiro = isBiro($this->satkerId);
                    $satkerId = $isBiro ? SATKER_SETDA : $this->satkerId;
                    $ukerId = $isBiro ? $this->satkerId : '';
                    $periode = $this->tahunKinerja.'03';

                    $eviden[] = "{$link}?skpd_id={$satkerId}&uker_id={$ukerId}&periode={$periode}";
                } elseif ($keterangan == 'https://kinerja.jabarprov.go.id/sakip/rapor-kinerja/1/diagram') {
                    $eviden[] = config('app.client_url')."/public-display/rapor-kinerja/1/{$this->satkerId}";
                } elseif ($keterangan == 'https://kinerja.jabarprov.go.id/kampanye-kinerja') {
                    $eviden[] = "{$keterangan}/satuan-kerja/{$this->satkerId}";
                } elseif (in_array($keterangan, ['https://kinerja.jabarprov.go.id/sakip/public-display/lke/hasil-self-assessment', 'https://kinerja.jabarprov.go.id/sakip/public-display/lke/hasil-akhir'])) {
                    // tahun kinerja sebelumnya
                    $prevTahunKinerja = $this->tahunKinerja - 1;
                    $eviden[] = "{$link}?tahun_kinerja={$prevTahunKinerja}&satuan_kerja_id={$this->satkerId}";
                } elseif (in_array($kriteria->nomor_full, ['2.2.2', '2.2.3']) && $keterangan == 'https://kinerja.jabarprov.go.id/sakip/public-display/display-mikro/capaian-kinerja-pd') {
                    // tahun kinerja sebelumnya
                    $prevTahunKinerja = $this->tahunKinerja - 1;
                    $eviden[] = "{$link}?tahun_kinerja={$prevTahunKinerja}&satuan_kerja_id={$this->satkerId}";
                } else {
                    $eviden[] = "{$link}?tahun_kinerja={$this->tahunKinerja}&satuan_kerja_id={$this->satkerId}";
                }
            } else {
                $eviden[] = $kriteria->eviden ? $kriteria->eviden->eviden[$index] ?? null : null;
            }
        }

        return $eviden;
    }
}
