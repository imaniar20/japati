<?php

namespace App\Http\Controllers;

use App\Models\PerubahanJumlahOutputV2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerubahanJumlahOutputV2Controller extends Controller
{
    public function index()
    {
        $data = PerubahanJumlahOutputV2::tahunKinerja()
            ->roleSatuanKerja()
            ->first();

        if (! $data) {
            $data = new PerubahanJumlahOutputV2;
            $data->tahun_kinerja = getTahunKinerja();
            $data->satuan_kerja_id = Auth::user()->satuan_kerja_id;
            $data->tw1 = 0;
            $data->tw2 = 0;
            $data->tw3 = 0;
            $data->tw4 = 0;

        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tw1' => ['required', 'numeric', 'min:0'],
            'tw2' => ['required', 'numeric', 'min:0'],
            'tw3' => ['required', 'numeric', 'min:0'],
            'tw4' => ['required', 'numeric', 'min:0'],
        ]);

        PerubahanJumlahOutputV2::query()->updateOrCreate(
            [
                'tahun_kinerja' => getTahunKinerja(),
                'satuan_kerja_id' => Auth::user()->satuan_kerja_id,
            ],
            [
                'tw1' => $validated['tw1'],
                'tw2' => $validated['tw2'],
                'tw3' => $validated['tw3'],
                'tw4' => $validated['tw4'],
            ]
        );

        return response()->json([
            'success' => true,
        ]);
    }
}
