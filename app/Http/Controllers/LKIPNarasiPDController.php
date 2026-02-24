<?php

namespace App\Http\Controllers;

use App\Http\Requests\LKIPNarasiPD\StoreLKIPNarasiPD;
use App\Http\Requests\LKIPNarasiPD\UpdateLKIPNarasiPD;
use App\Models\IndikatorSasaranStrategis;
use App\Models\LKIPNarasiPD;
use App\Models\Role;
use App\Models\SasaranStrategis;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use App\Services\FilterQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LKIPNarasiPDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = LKIPNarasiPD::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($data, json_decode($request->filter, true));

        $data = $data->with([
            'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            'sasaranStrategis:id,sasaran',
            'indikatorSasaranStrategis:id,indikator',
        ])
            ->paginate(20);

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $satkerId = Auth::user()->satuan_kerja_id;
        $sasaranStrategisRpjmd = SasaranStrategisRpjmd::select('sasaran_strategis_id', 'indikator_sasaran_strategis_id')
            ->tahunMulai()
            ->where('satuan_kerja_id', $satkerId)
            ->with([
                'sasaranStrategis',
                'indikatorSasaranStrategis',
            ])
            ->get();
        $sasaranStrategisIds = $sasaranStrategisRpjmd->pluck('sasaran_strategis_id');
        $indikatorSasaranStrategisIds = $sasaranStrategisRpjmd->pluck('indikator_sasaran_strategis_id');

        $sasaranStrategisList = SasaranStrategis::tahunMulai()->select('id', 'sasaran')->whereIn('id', $sasaranStrategisIds)->get();
        $indikatorSasaranStrategisList = IndikatorSasaranStrategis::tahunMulai()->select('id', 'indikator')->whereIn('id', $indikatorSasaranStrategisIds)->get();

        $sasaranStrategisPd = SasaranStrategisPd::select('sasaran_strategis_satker', 'iku')
            ->tahunMulai()
            ->where('satuan_kerja_id', $satkerId)
            ->get();

        $sasaranStrategisPD = $sasaranStrategisPd->pluck('sasaran_strategis_satker');
        $ikuPD = $sasaranStrategisPd->pluck('iku');

        return response()->json(compact('sasaranStrategisList', 'indikatorSasaranStrategisList', 'sasaranStrategisPD', 'ikuPD'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLKIPNarasiPD $request)
    {
        $data = $request->validated();

        $data['satuan_kerja_id'] = Auth::user()->satuan_kerja_id;
        $data['tahun_kinerja'] = getTahunKinerja();

        LKIPNarasiPD::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambah data',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LKIPNarasiPD  $lKIPNarasiPD
     * @return \Illuminate\Http\Response
     */
    public function show(LKIPNarasiPD $narasiPd)
    {
        $narasiPd->load([
            'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            'sasaranStrategis:id,sasaran',
            'indikatorSasaranStrategis:id,indikator',
        ]);

        return response()->json($narasiPd);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LKIPNarasiPD  $lKIPNarasiPD
     * @return \Illuminate\Http\Response
     */
    public function edit(LKIPNarasiPD $narasiPd)
    {
        $satkerId = Auth::user()->satuan_kerja_id;
        $sasaranStrategisRpjmd = SasaranStrategisRpjmd::select('sasaran_strategis_id', 'indikator_sasaran_strategis_id')
            ->tahunMulai()
            ->where('satuan_kerja_id', $satkerId)
            ->with([
                'sasaranStrategis',
                'indikatorSasaranStrategis',
            ])
            ->get();
        $sasaranStrategisIds = $sasaranStrategisRpjmd->pluck('sasaran_strategis_id');
        $indikatorSasaranStrategisIds = $sasaranStrategisRpjmd->pluck('indikator_sasaran_strategis_id');

        $sasaranStrategisList = SasaranStrategis::tahunMulai()->select('id', 'sasaran')->whereIn('id', $sasaranStrategisIds)->get();
        $indikatorSasaranStrategisList = IndikatorSasaranStrategis::tahunMulai()->select('id', 'indikator')->whereIn('id', $indikatorSasaranStrategisIds)->get();

        $sasaranStrategisPd = SasaranStrategisPd::select('sasaran_strategis_satker', 'iku')
            ->tahunMulai()
            ->where('satuan_kerja_id', $satkerId)
            ->get();

        $sasaranStrategisPD = $sasaranStrategisPd->pluck('sasaran_strategis_satker');
        $ikuPD = $sasaranStrategisPd->pluck('iku');
        $form = $narasiPd;

        return response()->json(compact('sasaranStrategisList', 'indikatorSasaranStrategisList', 'sasaranStrategisPD', 'ikuPD', 'form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LKIPNarasiPD  $lKIPNarasiPD
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLKIPNarasiPD $request, LKIPNarasiPD $narasiPd)
    {
        $this->authorizeBySatuanKerja($narasiPd->satuan_kerja_id, [Role::PERANGKAT_DAERAH]);

        $narasiPd->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Berhasil simpan data',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LKIPNarasiPD  $lKIPNarasiPD
     * @return \Illuminate\Http\Response
     */
    public function destroy(LKIPNarasiPD $narasiPd)
    {
        $this->authorizeBySatuanKerja($narasiPd->satuan_kerja_id, [Role::PERANGKAT_DAERAH]);

        $narasiPd->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data',
        ]);
    }
}
