<?php

namespace App\Http\Controllers;

use App\Http\Requests\LKIPNarasiPemda\StoreLKIPNarasiPemda;
use App\Http\Requests\LKIPNarasiPemda\UpdateLKIPNarasiPemda;
use App\Models\IndikatorSasaranStrategis;
use App\Models\LKIPNarasiPemda;
use App\Models\Role;
use App\Models\SasaranStrategis;
use App\Models\SasaranStrategisRpjmd;
use App\Services\FilterQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LKIPNarasiPemdaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = LKIPNarasiPemda::tahunKinerja()->roleSatuanKerja();

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
        $indikatorSasaranStrategisList = IndikatorSasaranStrategis::tahunMulai()->tahunMulai()->select('id', 'indikator')->whereIn('id', $indikatorSasaranStrategisIds)->get();

        return response()->json(compact('sasaranStrategisList', 'indikatorSasaranStrategisList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLKIPNarasiPemda $request)
    {
        $data = $request->validated();

        $data['satuan_kerja_id'] = Auth::user()->satuan_kerja_id;
        $data['tahun_kinerja'] = getTahunKinerja();

        LKIPNarasiPemda::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambah data',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LKIPNarasiPemda  $lKIPNarasiPemda
     * @return \Illuminate\Http\Response
     */
    public function show(LKIPNarasiPemda $narasiPemda)
    {
        $narasiPemda->load([
            'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            'sasaranStrategis:id,sasaran',
            'indikatorSasaranStrategis:id,indikator',
        ]);

        return response()->json($narasiPemda);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LKIPNarasiPemda  $lKIPNarasiPemda
     * @return \Illuminate\Http\Response
     */
    public function edit(LKIPNarasiPemda $narasiPemda)
    {
        $this->authorizeBySatuanKerja($narasiPemda->satuan_kerja_id, [Role::PEMERINTAH_DAERAH]);

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
        $form = $narasiPemda;

        return response()->json(compact('sasaranStrategisList', 'indikatorSasaranStrategisList', 'form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LKIPNarasiPemda  $lKIPNarasiPemda
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLKIPNarasiPemda $request, LKIPNarasiPemda $narasiPemda)
    {
        $this->authorizeBySatuanKerja($narasiPemda->satuan_kerja_id, [Role::PEMERINTAH_DAERAH]);

        $narasiPemda->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Berhasil simpan data',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LKIPNarasiPemda  $lKIPNarasiPemda
     * @return \Illuminate\Http\Response
     */
    public function destroy(LKIPNarasiPemda $narasiPemda)
    {
        $this->authorizeBySatuanKerja($narasiPemda->satuan_kerja_id, [Role::PEMERINTAH_DAERAH]);

        $narasiPemda->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data',
        ]);
    }
}
