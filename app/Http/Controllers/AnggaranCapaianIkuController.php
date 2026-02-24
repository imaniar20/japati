<?php

namespace App\Http\Controllers;

use App\Models\AnggaranCapaianIku;
use Illuminate\Http\Request;

class AnggaranCapaianIkuController extends Controller
{
    public function index()
    {
        $data = AnggaranCapaianIku::query()
            ->orderByDesc('tahun_kinerja')
            ->paginate();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_kinerja' => ['required', 'numeric'],
            'terpakai' => ['required', 'numeric'],
            'tidak_terpakai' => ['required', 'numeric'],
            'efisiensi' => ['required', 'numeric'],
        ]);

        $isExists = AnggaranCapaianIku::query()
            ->where('tahun_kinerja', $validated['tahun_kinerja'])
            ->exists();

        if ($isExists) {
            return response()->json(['message' => "Data tahun {$validated['tahun_kinerja']} sudah ada"], 400);
        }

        AnggaranCapaianIku::query()->create($validated);

        return response()->json();
    }

    public function show(AnggaranCapaianIku $anggaranCapaianIku)
    {
        return response()->json($anggaranCapaianIku);
    }

    public function update(Request $request, AnggaranCapaianIku $anggaranCapaianIku)
    {
        $validated = $request->validate([
            'tahun_kinerja' => ['required', 'numeric'],
            'terpakai' => ['required', 'numeric'],
            'tidak_terpakai' => ['required', 'numeric'],
            'efisiensi' => ['required', 'numeric'],
        ]);

        $isExists = AnggaranCapaianIku::query()
            ->where('tahun_kinerja', $validated['tahun_kinerja'])
            ->where('id', '<>', $anggaranCapaianIku->id)
            ->exists();

        if ($isExists) {
            return response()->json(['message' => "Data tahun {$validated['tahun_kinerja']} sudah ada"], 400);
        }

        $anggaranCapaianIku->update($validated);

        return response()->json();
    }

    public function destroy(AnggaranCapaianIku $anggaranCapaianIku)
    {
        $anggaranCapaianIku->delete();

        return response()->json();
    }
}
