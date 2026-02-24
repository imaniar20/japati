<?php

namespace App\Http\Controllers;

use App\Models\SasaranStrategisPd;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ValidasiSasaranStrategisPdController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
            'search' => ['nullable', 'string'],
        ]);

        $data = SasaranStrategisPd::tahunMulai()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->select('id', 'target_1', 'target_2', 'target_3', 'target_4', 'target_5', 'realisasi_1', 'realisasi_2', 'realisasi_3', 'realisasi_4', 'realisasi_5', 'validasi_tahunan', 'sasaran_strategis_satker', 'iku', 'satuan')
            ->when($validated['search'] ?? null, fn (Builder $query, string $search) => $query
                ->where(fn (Builder $query) => $query
                    ->where('sasaran_strategis_satker', 'ILIKE', "%$search%")
                    ->orWhere('iku', 'ILIKE', "%$search%")
                )
            )
            ->orderBy('id')
            ->paginate(20);

        return response()->json($data);
    }

    public function validasi(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'numeric'],
            'tahun' => ['required', 'numeric', 'min:1', 'max:5'],
            'status' => ['required', 'boolean'],
            'catatan' => ['nullable', 'string'],
        ]);

        SasaranStrategisPd::tahunMulai()
            ->where('id', $validated['id'])
            ->update([
                'validasi_tahunan->'.$validated['tahun'] => [
                    'status' => $validated['status'],
                    'catatan' => $validated['catatan'],
                ],
            ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function pengampuIndex(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
            'search' => ['nullable', 'string'],
        ]);

        $data = SasaranStrategisPd::tahunMulai()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->select('id', 'sasaran_strategis_satker', 'iku', 'satuan_kerja_id', 'validasi_pengampu')
            ->when($validated['search'] ?? null, fn (Builder $query, string $search) => $query
                ->where(fn (Builder $query) => $query
                    ->where('sasaran_strategis_satker', 'ILIKE', "%$search%")
                    ->orWhere('iku', 'ILIKE', "%$search%")
                )
            )
            ->with([
                'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            ])
            ->orderBy('id')
            ->paginate(20);

        return response()->json($data);
    }

    public function pengampuValidasi(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'numeric'],
            'status' => ['required', 'boolean'],
            'catatan' => ['nullable', 'string'],
        ]);

        SasaranStrategisPd::tahunMulai()
            ->where('id', $validated['id'])
            ->update([
                'validasi_pengampu' => [
                    'status' => $validated['status'],
                    'catatan' => $validated['catatan'],
                ],
            ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
