<?php

namespace App\Http\Controllers\PublicDisplay;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LKE\EvidenController as LKEEvidenController;
use App\Models\SatuanKerja;
use Illuminate\Http\Request;

class EvidenController extends Controller
{
    public function hasilSelfAssessment(Request $request)
    {
        $filter = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        $result = LKEEvidenController::getHasilSelfAssessment($filter['satuan_kerja_id']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'bobotTotal' => $result['bobotTotal'],
            'skorTotal' => $result['skorTotal'],
            'skorTotal2' => $result['skorTotal2'],
            'predikat' => $result['predikat'],
            'predikat2' => $result['predikat2'],
        ]);
    }

    public function hasilAkhir(Request $request)
    {
        $filter = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        $satkerId = $filter['satuan_kerja_id'];

        $result = LKEEvidenController::getHasilAkhir($satkerId);
        $satker = SatuanKerja::query()
            ->select('satuan_kerja_id', 'satuan_kerja_nama')
            ->where('satuan_kerja_id', $satkerId)
            ->first();

        $result['satuanKerja'] = $satker;

        return response()->json($result);
    }
}
