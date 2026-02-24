<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeKerja\StoreTimKerja;
use App\Http\Requests\TimKerja\SearchPegawaiRequest;
use App\Models\Ekinerja\TimKerja;
use App\Models\Ekinerja\VPegawaiData;

class TimKerjaController extends Controller
{
    public function store(StoreTimKerja $request)
    {
        $data = $request->validated();

        // $isExists = TimKerja::where('satuan_kerja_id', $data['satuan_kerja_id'])
        //     ->where('v_struktur_organisasi_id', $data['v_struktur_organisasi_id'])
        //     ->where('nama', trim($data['nama']))
        //     ->exists();

        // if ($isExists) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Nama Tim Kinerja sudah ada',
        //     ]);
        // }

        $data = TimKerja::create($data);
        $data->load('ketua:peg_nip,peg_nama');

        return response()->json([
            'success' => true,
            'message' => 'Berhasil tambah data',
            'data' => $data,
        ]);
    }

    public function searchPegawai(SearchPegawaiRequest $request)
    {
        $validated = $request->validated();
        $search = $validated['search'] ?? '';
        $satkerId = $validated['satuan_kerja_id'];

        $data = VPegawaiData::select('peg_nip', 'peg_nama', 'jabatan_nama', 'unit_kerja_nama')
            ->aktif()
            ->where('satuan_kerja_id', parseSatuanKerjaId($satkerId))
            ->where(fn ($query) => $query->where('peg_nip', $search)
                ->orWhere('peg_nama', 'ILIKE', "%$search%")
            )
            ->limit(20)
            ->get();

        return response()->json($data);
    }
}
