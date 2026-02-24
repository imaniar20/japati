<?php

namespace App\Http\Controllers;

use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\Role;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use App\Services\CompactQuery;
use App\Services\DiagramSasaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LKIPController extends Controller
{
    public function arsitekturKinerja()
    {
        $satkerId = Auth::user()->satuan_kerja_id;

        if (Role::isPemda()) {
            $sasaranStrategisRpjmd = DiagramSasaran::getCompact($satkerId);
        } else {
            $sasaranStrategisRpjmd = DiagramSasaran::get($satkerId);
        }

        return response()->json($sasaranStrategisRpjmd);
    }

    public function tabel21(Request $request)
    {
        $satkerId = $request->satuan_kerja_id;

        $sasaranStrategisRpjmd = SasaranStrategisRpjmd::selectRaw('id, sasaran_strategis_id, indikator_sasaran_strategis_id, tahun_mulai, target_1, target_2, target_3, target_4, target_5, tujuan_id')
            ->tahunMulai()
            ->with([
                'sasaranStrategis:id,sasaran',
                'indikatorSasaranStrategis:id,indikator',
                'tujuan:id,tujuan',
            ])
            ->when($satkerId, function ($query) use ($satkerId) {
                return $query->where('satuan_kerja_id', $satkerId);
            });

        $sasaranStrategisRpjmd = CompactQuery::compactWithoutPagination($sasaranStrategisRpjmd, 'tujuan_id')
            ->groupBy([
                'tujuan_id',
            ])
            ->values();

        return response()->json($sasaranStrategisRpjmd);
    }

    public function perjanjianKinerja(Request $request)
    {
        $satkerId = $request->satuan_kerja_id;

        $sasaranStrategisRpjmd = SasaranStrategisRpjmd::selectRaw('id, sasaran_strategis_id, indikator_sasaran_strategis_id, tahun_mulai, target_1, target_2, target_3, target_4, target_5')
            ->tahunMulai()
            ->with([
                'sasaranStrategis:id,sasaran',
                'indikatorSasaranStrategis:id,indikator',
            ])
            ->when($satkerId, function ($query) use ($satkerId) {
                return $query->where('satuan_kerja_id', $satkerId);
            });

        $sasaranStrategisRpjmd = CompactQuery::compactWithoutPagination($sasaranStrategisRpjmd, 'indikator_sasaran_strategis_id')
            ->groupBy([
                'sasaran_strategis_id',
            ])
            ->values();

        return response()->json($sasaranStrategisRpjmd);
    }

    public function tabel31(Request $request)
    {
        $satkerId = $request->satuan_kerja_id;

        $sasaranStrategisRpjmd = SasaranStrategisRpjmd::selectRaw('id, sasaran_strategis_id, indikator_sasaran_strategis_id, tahun_mulai, capaian_1, capaian_2, capaian_3, capaian_4, capaian_5')
            ->tahunMulai()
            ->with([
                'sasaranStrategis:id,sasaran',
                'indikatorSasaranStrategis:id,indikator',
            ])
            ->when($satkerId, function ($query) use ($satkerId) {
                return $query->where('satuan_kerja_id', $satkerId);
            });

        $sasaranStrategisRpjmd = CompactQuery::compactWithoutPagination($sasaranStrategisRpjmd, 'indikator_sasaran_strategis_id')
            ->groupBy([
                'sasaran_strategis_id',
            ])
            ->values();

        return response()->json($sasaranStrategisRpjmd);
    }

    public function programInovatif(Request $request)
    {
        $satkerId = $request->satuan_kerja_id;

        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()
            ->whereNotNull('inovasi_uraian')
            ->with([
                'satuanKerja',
                'sasaranStrategisRpjmd.sasaranStrategis',
                'sasaranStrategisRpjmd.indikatorSasaranStrategis',
            ])
            ->when($satkerId, function ($query) use ($satkerId) {
                return $query->where('satuan_kerja_id', $satkerId);
            });

        $sortBySasaran = $this->sortBySasaranClosure();

        $sortByIku = $this->sortByIkuClosure();

        $kinerjaSubKegiatan = CompactQuery::compact($kinerjaSubKegiatan, true, $sortBySasaran, 'sub_kegiatan_id', $sortByIku);

        return response()->json($kinerjaSubKegiatan);
    }

    public function tabel32(Request $request)
    {
        $satkerId = $request->satuan_kerja_id;

        $sasaranStrategisPd = SasaranStrategisPd::select('id', 'sasaran_strategis_id', 'capaian_1', 'capaian_2', 'capaian_3', 'capaian_4', 'capaian_5')
            ->tahunMulai()
            ->when($satkerId, function ($query) use ($satkerId) {
                return $query->where('satuan_kerja_id', $satkerId);
            })
            ->with([
                'kinerjaProgram' => fn ($query) => $query->selectRaw('id,sasaran_strategis_pd_id,anggaran,realisasi_anggaran,program_id')->tahunKinerja(),
                'sasaranStrategis:id,sasaran',
            ])
            ->get()
            ->groupBy('sasaran_strategis_id')
            ->transform(function ($sasaranStrategisPdList, $sasaranStrategisId) {
                return [
                    'id' => $sasaranStrategisId,
                    'sasaran' => $sasaranStrategisPdList[0]->sasaranStrategis->sasaran,
                    'sasaran_strategis_pd' => $sasaranStrategisPdList->transform(function ($sasaranStrategisPd) {
                        $pagu = $sasaranStrategisPd->kinerjaProgram->sum('anggaran');
                        $realisasi = $sasaranStrategisPd->kinerjaProgram->sum('realisasi_anggaran');

                        if ($pagu == 0) {
                            $capaian = null;
                            $efisiensiNominal = null;
                            $efisiensiPersen = null;
                        } else {
                            $capaian = round($realisasi / $pagu * 100, 2);
                            $efisiensiNominal = $pagu - $realisasi;
                            $efisiensiPersen = round(100 - ($realisasi / $pagu * 100), 2);
                        }

                        return [
                            'id' => $sasaranStrategisPd->id,
                            'capaian_1' => $sasaranStrategisPd->capaian_1,
                            'capaian_2' => $sasaranStrategisPd->capaian_2,
                            'capaian_3' => $sasaranStrategisPd->capaian_3,
                            'capaian_4' => $sasaranStrategisPd->capaian_4,
                            'capaian_5' => $sasaranStrategisPd->capaian_5,
                            'pagu_anggaran' => $pagu,
                            'realisasi_anggaran' => $realisasi,
                            'capaian_anggaran' => $capaian,
                            'efisiensi_nominal' => $efisiensiNominal,
                            'efisiensi_persen' => $efisiensiPersen,
                        ];
                    }),
                ];
            })
            ->values();

        return response()->json($sasaranStrategisPd);
    }

    public function pengelolaanDataKinerja(Request $request)
    {
        $satkerId = $request->satuan_kerja_id;

        $kinerjaProgram = KinerjaProgram::tahunKinerja()
            ->roleSatuanKerja($satkerId)
            ->with([
                'program',
                'sasaranStrategisPd.sasaranStrategisRpjmd',
                'satkerIku',
            ])
            ->paginate(20);

        return response()->json($kinerjaProgram);
    }

    private function sortBySasaranClosure()
    {
        return function ($item) {
            return $item->sasaranStrategisRpjmd ? 1 : PHP_INT_MAX;
        };
    }

    private function sortByIkuClosure()
    {
        return function ($item) {
            return $item->sasaranStrategisRpjmd ? $item->sasaranStrategisRpjmd->indikator_sasaran_strategis_id : PHP_INT_MAX;
        };
    }
}
