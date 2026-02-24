<?php

namespace App\Http\Controllers;

use App\Models\NilaiSakipPemda;
use Illuminate\Http\Request;

class NilaiSakipPemdaController extends Controller
{
    public function index()
    {
        $data = NilaiSakipPemda::query()
            ->orderByDesc('tahun_kinerja')
            ->paginate();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_kinerja' => ['required', 'numeric'],
            'nilai' => ['required', 'numeric'],
            'efisiensi' => ['required', 'numeric'],
        ]);

        $isExists = NilaiSakipPemda::query()
            ->where('tahun_kinerja', $validated['tahun_kinerja'])
            ->exists();

        if ($isExists) {
            return response()->json(['message' => "Data tahun {$validated['tahun_kinerja']} sudah ada"], 400);
        }

        NilaiSakipPemda::query()->create($validated);

        return response()->json();
    }

    public function show(NilaiSakipPemda $nilaiSakip)
    {
        return response()->json($nilaiSakip);
    }

    public function update(Request $request, NilaiSakipPemda $nilaiSakip)
    {
        $validated = $request->validate([
            'tahun_kinerja' => ['required', 'numeric'],
            'nilai' => ['required', 'numeric'],
            'efisiensi' => ['required', 'numeric'],
        ]);

        $isExists = NilaiSakipPemda::query()
            ->where('tahun_kinerja', $validated['tahun_kinerja'])
            ->where('id', '<>', $nilaiSakip->id)
            ->exists();

        if ($isExists) {
            return response()->json(['message' => "Data tahun {$validated['tahun_kinerja']} sudah ada"], 400);
        }

        $nilaiSakip->update($validated);

        return response()->json();
    }

    public function destroy(NilaiSakipPemda $nilaiSakip)
    {
        $nilaiSakip->delete();

        return response()->json();
    }
}
