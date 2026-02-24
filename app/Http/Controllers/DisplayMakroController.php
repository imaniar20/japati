<?php

namespace App\Http\Controllers;

use App\Models\KinerjaKegiatan;
use App\Models\KinerjaLangkahAksi;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\Role;
use App\Models\SasaranStrategisRpjmd;
use App\Models\SatuanKerja;
use App\Models\Visi;
use App\Services\DiagramSasaran;
use App\Services\FilterQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisplayMakroController extends Controller
{
    public function rpjmd(Request $request)
    {
        $visi = Visi::tahunMulai()->first();

        $sasaranStrategisRpjmd = SasaranStrategisRpjmd::tahunMulai()->roleSatuanKerja();
        $isDataOnly = $request->is_data_only;

        FilterQuery::parseFilter($sasaranStrategisRpjmd, json_decode($request->filter, true));

        $sasaranStrategisRpjmd = $sasaranStrategisRpjmd->with([
            'satuanKerja',
            'sasaranStrategis',
            'indikatorSasaranStrategis',
            'misi',
            'tujuan',
            'indikatorTujuan',
            'targetVisiMisi',
        ])
            ->paginate();

        if ($isDataOnly) {
            return response()->json($sasaranStrategisRpjmd);
        }

        return response()->json([
            'visi' => $visi,
            'sasaranStrategis' => $sasaranStrategisRpjmd,
        ]);
    }

    public function rkpd(Request $request)
    {
        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaSubKegiatan, json_decode($request->filter, true));

        $kinerjaSubKegiatan = $kinerjaSubKegiatan->with([
            'satuanKerja',
            'subKegiatan',
            'kegiatan',
            'sasaranStrategisRpjmd.sasaranStrategis',
            'sasaranStrategisRpjmd.indikatorSasaranStrategis',
            'kinerjaProgram.program',
        ])
            ->paginate();

        return response()->json($kinerjaSubKegiatan);
    }

    public function perjanjianKinerja(Request $request)
    {
        $sasaranStrategisRpjmd = SasaranStrategisRpjmd::tahunMulai()->roleSatuanKerja();

        FilterQuery::parseFilter($sasaranStrategisRpjmd, json_decode($request->filter, true));

        $sasaranStrategisRpjmd = $sasaranStrategisRpjmd->selectRaw('id, sasaran_strategis_id, indikator_sasaran_strategis_id, tahun_mulai, target_1, target_2, target_3, target_4, target_5, satuan_kerja_id')
            ->with([
                'satuanKerja',
                'sasaranStrategis',
                'indikatorSasaranStrategis',
            ])
            ->paginate();

        return response()->json($sasaranStrategisRpjmd);
    }

    public function capaianKinerjaPemda(Request $request)
    {
        $sasaranStrategisRpjmd = SasaranStrategisRpjmd::tahunMulai()->roleSatuanKerja();

        FilterQuery::parseFilter($sasaranStrategisRpjmd, json_decode($request->filter, true));

        $sasaranStrategisRpjmd = $sasaranStrategisRpjmd->with([
            'satuanKerja',
            'sasaranStrategis',
            'indikatorSasaranStrategis',
        ])
            ->paginate();

        return response()->json($sasaranStrategisRpjmd);
    }

    public function capaianKinerjaEfisiensiAnggaran(Request $request)
    {
        $sasaranStrategisRpjmd = SasaranStrategisRpjmd::tahunMulai()->roleSatuanKerja();

        FilterQuery::parseFilter($sasaranStrategisRpjmd, json_decode($request->filter, true));

        $sasaranStrategisRpjmd = $sasaranStrategisRpjmd->with([
            'satuanKerja',
            'sasaranStrategis',
            'indikatorSasaranStrategis',
        ])
            ->paginate();

        $sasaranStrategisRpjmd = tap($sasaranStrategisRpjmd, function ($pagination) {
            return $pagination->getCollection()
                ->transform(function ($item) {
                    $program = KinerjaProgram::tahunKinerja()
                        ->select('anggaran', 'realisasi_anggaran')
                        ->where('satuan_kerja_id', $item->satuan_kerja_id)
                        ->whereHas('satkerIku', function ($query) use ($item) {
                            return $query->where('indikator_sasaran_strategis_id', $item->indikator_sasaran_strategis_id);
                        })
                        ->get();

                    $item->target_anggaran_program = $program->sum('anggaran');
                    $item->realisasi_anggaran_program = $program->sum('realisasi_anggaran');

                    return $item;
                });
        });

        return response()->json($sasaranStrategisRpjmd);
    }

    public function programInovatif(Request $request)
    {
        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaSubKegiatan, json_decode($request->filter, true));

        $kinerjaSubKegiatan = $kinerjaSubKegiatan->whereNotNull('inovasi_uraian')
            ->with([
                'satuanKerja',
                'sasaranStrategisRpjmd.sasaranStrategis',
                'sasaranStrategisRpjmd.indikatorSasaranStrategis',
            ])
            ->paginate();

        return response()->json($kinerjaSubKegiatan);
    }

    public function capaianKinerjaKeuangan(Request $request)
    {
        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaSubKegiatan, json_decode($request->filter, true));

        $kinerjaSubKegiatan = $kinerjaSubKegiatan->with([
            'satuanKerja',
            'kinerjaKegiatan.kegiatan',
            'kinerjaProgram.program',
            'sasaranStrategisRpjmd.sasaranStrategis',
            'sasaranStrategisRpjmd.indikatorSasaranStrategis',
            'subKegiatan',
        ])
            ->paginate();

        return response()->json($kinerjaSubKegiatan);
    }

    public function capaianKinerjaSubKegiatan(Request $request)
    {
        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaSubKegiatan, json_decode($request->filter, true));

        $kinerjaSubKegiatan = $kinerjaSubKegiatan->with([
            'satuanKerja',
            'kinerjaKegiatan.kegiatan',
            'kinerjaProgram.program',
            'sasaranStrategisRpjmd.sasaranStrategis',
            'sasaranStrategisRpjmd.indikatorSasaranStrategis',
            'subKegiatan',
        ])
            ->paginate();

        return response()->json($kinerjaSubKegiatan);
    }

    public function rencanaAksi(Request $request)
    {
        $kinerjaLangkahAksi = KinerjaLangkahAksi::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaLangkahAksi, json_decode($request->filter, true));

        $kinerjaLangkahAksi = $kinerjaLangkahAksi->with([
            'satuanKerja',
            'sasaranStrategisRpjmd.sasaranStrategis',
            'sasaranStrategisRpjmd.indikatorSasaranStrategis',
            'sasaranStrategisPd',
            'kinerjaProgram.program',
            'kinerjaKegiatan.kegiatan',
            'kinerjaSubKegiatan.subKegiatan',
        ])
            ->paginate();

        return response()->json($kinerjaLangkahAksi);
    }

    public function capaianKinerjaAktivitas(Request $request)
    {
        $kinerjaLangkahAksi = KinerjaLangkahAksi::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaLangkahAksi, json_decode($request->filter, true));

        $kinerjaLangkahAksi = $kinerjaLangkahAksi->with([
            'satuanKerja',
            'sasaranStrategisRpjmd.sasaranStrategis',
            'sasaranStrategisRpjmd.indikatorSasaranStrategis',
            'sasaranStrategisPd',
            'kinerjaProgram.program',
            'kinerjaKegiatan.kegiatan',
            'kinerjaSubKegiatan.subKegiatan',
        ])
            ->paginate();

        return response()->json($kinerjaLangkahAksi);
    }

    public function capaianKinerjaKegiatan(Request $request)
    {
        $kinerjaKegiatan = KinerjaKegiatan::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaKegiatan, json_decode($request->filter, true));

        $kinerjaKegiatan = $kinerjaKegiatan->with([
            'satuanKerja',
            'sasaranStrategisRpjmd.sasaranStrategis',
            'sasaranStrategisRpjmd.indikatorSasaranStrategis',
            'kinerjaProgram.program',
            'kegiatan',
        ])
            ->paginate();

        return response()->json($kinerjaKegiatan);
    }

    public function cascading(Request $request)
    {
        $filter = json_decode($request->filter, true);
        $isDataOnly = $request->is_data_only;
        $sasaranStrategisRpjmd = [];
        $satkerList = [];
        $user = Auth::user();

        if (Role::isSuper() || Role::isSetda()) {
            $satkerId = $filter['satuan_kerja_id'] ?? null;
        } else {
            $satkerId = $user->satuan_kerja_id ?? null;
        }

        if (! $isDataOnly) {
            $satkerList = SatuanKerja::listForFilter();
        }

        if (! $satkerId && ! Role::isPemda()) {
            return response()->json(compact('sasaranStrategisRpjmd', 'satkerList'));
        }

        if (Role::isPemda()) {
            $sasaranStrategisRpjmd = DiagramSasaran::getCompact($satkerId, 2);
        } else {
            $sasaranStrategisRpjmd = DiagramSasaran::get($satkerId, 2);
        }

        return response()->json(compact('sasaranStrategisRpjmd', 'satkerList'));
    }
}
