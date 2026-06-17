<?php

namespace App\Http\Controllers;

use App\Models\Infografis;
use Illuminate\Http\Request;

class InfografisController extends Controller
{
    public function index(Request $request)
    {
        $tahunKinerja = $request->integer('tahun_kinerja') ?: getTahunKinerja();

        $data = Infografis::query()
            ->where('tahun_kinerja', $tahunKinerja)
            ->whereNotNull('gambar_url')
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        return response()->json($data);
    }
}
