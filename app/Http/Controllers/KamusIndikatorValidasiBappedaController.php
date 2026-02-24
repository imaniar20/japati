<?php

namespace App\Http\Controllers;

use App\Models\KamusIndikatorValidasiBappeda;
use App\Models\SatuanKerja;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

class KamusIndikatorValidasiBappedaController extends Controller
{
    public function index()
    {
        $data = SatuanKerja::query()
            ->select('satuan_kerja.satuan_kerja_id', 'satuan_kerja_nama', 'is_validasi')
            ->leftJoin('kamus_indikator_validasi_bappeda', fn (JoinClause $query) => $query
                ->on('kamus_indikator_validasi_bappeda.satuan_kerja_id', 'satuan_kerja.satuan_kerja_id')
                ->where('tahun_kinerja', getTahunKinerja())
            )
            ->orderBy('satuan_kerja.satuan_kerja_id')
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
            'status' => ['required', 'boolean'],
        ]);

        KamusIndikatorValidasiBappeda::query()->updateOrCreate(
            [
                'tahun_kinerja' => getTahunKinerja(),
                'satuan_kerja_id' => $validated['satuan_kerja_id'],
            ],
            [
                'is_validasi' => $validated['status'],
            ]
        );

        return response()->json([
            'success' => true,
        ]);
    }
}
