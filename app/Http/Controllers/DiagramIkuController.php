<?php

namespace App\Http\Controllers;

use App\Models\IndikatorSasaranStrategis;
use App\Models\KinerjaProgram;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DiagramIkuController extends Controller
{
    public function ikuGubernur($satker = null)
    {
        /**
         * override tahun kinerja,
         * studi kasus di welcome page
         */
        if ($tahunKinerja = request('tahun_kinerja')) {
            //   setTahunKinerja($tahunKinerja);
        }

        $iku = $this->getIkuGubernur($satker == 'null' ? null : $satker);
        $iku = $this->transformIkuGubernur($iku);

        return response()->json($iku);
    }

    /**
     * SasaranStrategisRpjmd dan SasaranStrategisPd khusus di Sekretariat Daerah itu menggunakan satuan_kerja_id setda (1001),
     * maka setda dan semua biro mengacu ke satuan_kerja_id setda (1001)
     */
    private function getIkuGubernur(?int $satkerId): array
    {
        $ikuIds = null;
        $satkerIds = [];

        if ($satkerId) {
            // jika satkerId = biro, maka ubah ke setda
            $satkerId = parseSatuanKerjaId($satkerId);

            // jika satkerId = setda, maka ambil setda + semua biro
            $satkerIds = getSatuanKerjaIds($satkerId);

            $ikuIds = SasaranStrategisRpjmd::query()
                ->whereIn('satuan_kerja_id', $satkerIds)
                ->tahunMulai()
                ->pluck('indikator_sasaran_strategis_id');
        }

        $iku = IndikatorSasaranStrategis::tahunMulai()
            ->select('id', 'nomor', 'indikator')
            ->when($ikuIds, fn (Builder $query) => $query->whereIn('id', $ikuIds))
            ->orderBy('nomor')
            ->with([
                'sasaranStrategisRpjmd' => fn (Builder|SasaranStrategisRpjmd $query) => $query
                    ->selectRaw('id, sasaran_strategis_id, indikator_sasaran_strategis_id, target_baseline, target_1, target_2, target_3, target_4, target_5,
                        realisasi_baseline, realisasi_1, realisasi_2, realisasi_3, realisasi_4, realisasi_5,
                        capaian_baseline, capaian_1, capaian_2, capaian_3, capaian_4, capaian_5,
                        capaian_terhadap_target_akhir, rata_nasional,
                        perbandingan_realisasi_target_1, perbandingan_realisasi_target_2, perbandingan_realisasi_target_3, perbandingan_realisasi_target_4, perbandingan_realisasi_target_5,
                        perbandingan_realisasi_tahun_1, perbandingan_realisasi_tahun_2, perbandingan_realisasi_tahun_3, perbandingan_realisasi_tahun_4, perbandingan_realisasi_tahun_5'
                    )
                    ->tahunMulai()
                    ->when($satkerIds, fn (Builder $query) => $query->whereIn('satuan_kerja_id', $satkerIds))
                    ->with([
                        'sasaranStrategis:id,sasaran',
                    ]),
                'sasaranStrategisPd' => fn (Builder|SasaranStrategisPd $query) => $query
                    ->select('id', 'indikator_sasaran_strategis_id', 'sasaran_strategis_satker')
                    ->tahunMulai()
                    ->when($satkerIds, fn (Builder $query) => $query->whereIn('satuan_kerja_id', $satkerIds))
                    ->with([
                        'kinerjaProgram' => fn (Builder|KinerjaProgram $query) => $query->selectRaw('id,sasaran_strategis_pd_id,anggaran,realisasi_anggaran,program_id')->tahunKinerja(),
                    ]),
            ])
            ->get()
            ->toArray();

        return $iku;
    }

    private function transformIkuGubernur(array $iku): Collection
    {
        $iku = collect($iku)
            ->transform(function (array $item) {
                $item['sasaran_strategis_rpjmd'] = collect($item['sasaran_strategis_rpjmd'])->first();

                $pagu = collect($item['sasaran_strategis_pd'])->sum(function ($sasaranStrategisPd) {
                    return collect($sasaranStrategisPd['kinerja_program'])->sum('anggaran');
                });
                $realisasiAnggaran = collect($item['sasaran_strategis_pd'])->sum(function ($sasaranStrategisPd) {
                    return collect($sasaranStrategisPd['kinerja_program'])->sum('realisasi_anggaran');
                });

                if ($pagu == 0) {
                    $efisiensiNominal = null;
                    $efisiensiPersen = null;
                } else {
                    $efisiensiNominal = $pagu - $realisasiAnggaran;
                    $efisiensiPersen = round(100 - ($realisasiAnggaran / $pagu * 100), 2);
                }

                $item['efisiensi_sumber_daya'] = [
                    'nominal' => $efisiensiNominal,
                    'persen' => $efisiensiPersen,
                ];

                $item['sasaran_strategis_satker'] = collect($item['sasaran_strategis_pd'])->pluck('sasaran_strategis_satker');

                unset($item['sasaran_strategis_pd']);

                return $item;
            })
            ->filter(function ($item) {
                return $item['sasaran_strategis_rpjmd'];
            })
            ->values();

        return $iku;
    }
}
