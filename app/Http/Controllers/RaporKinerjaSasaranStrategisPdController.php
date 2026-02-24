<?php

namespace App\Http\Controllers;

use App\Models\SasaranStrategisPd;
use Illuminate\Http\Request;

class RaporKinerjaSasaranStrategisPdController extends Controller
{
    public function kinerja(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
            'tahun_kinerja' => ['nullable', 'numeric'],
        ]);

        // setTahunKinerja($validated['tahun_kinerja'] ?? getTahunKinerja());

        // original function from client/plugins/helpers.js::getKeyTahun()
        $getKeyTahun = function ($key, $offset = 0) {
            $index = (getTahunKinerja() - getTahunMulai()) + 1 + $offset;

            if ($index < 1) {
                $index = 'baseline';
            }

            return "{$key}_{$index}_triwulan";
        };

        // convert penamaan kolom
        $selectColumnByTahun = [
            $getKeyTahun('target').' AS target_triwulan',
            $getKeyTahun('realisasi').' AS realisasi_triwulan',
        ];

        $data = SasaranStrategisPd::tahunMulai()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->select(['sasaran_strategis_satker', 'iku', ...$selectColumnByTahun])
            ->where('is_rapor_kinerja', true)
            ->get()
            ->map(function ($sasaran) {
                $sasaran->target_triwulan = json_decode($sasaran->target_triwulan, true);
                $sasaran->realisasi_triwulan = json_decode($sasaran->realisasi_triwulan, true);

                return $sasaran;
            });

        return response()->json($data);
    }
}
