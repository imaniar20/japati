<?php

namespace App\Http\Controllers;

use App\Models\KinerjaSubKegiatan;
use App\Models\PenyebabKegagalan;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenyebabKegagalanController extends Controller
{
    public function index(int $triwulan, int $kinerjaSubKegiatan, Request $request)
    {
        if ($request->info) {
            return $this->info($kinerjaSubKegiatan);
        }

        $data = PenyebabKegagalan::query()
            ->where('triwulan', $triwulan)
            ->where('kinerja_sub_kegiatan_id', $kinerjaSubKegiatan)
            ->withCount('langkahAksi')
            ->get();

        return response()->json($data);
    }

    private function info(int $kinerjaSubKegiatan)
    {
        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()
            ->roleSatuanKerja()
            ->where('id', $kinerjaSubKegiatan)
            ->firstOrFail(['id', 'kegiatan_id', 'sub_kegiatan_id', 'sasaran', 'indikator', 'target_bulanan', 'realisasi_bulanan']);

        $kinerjaSubKegiatan->load([
            'kegiatan:id,nama',
            'subKegiatan:id,nama',
        ]);

        return response()->json($kinerjaSubKegiatan);
    }

    public function store(int $triwulan, int $kinerjaSubKegiatan, Request $request)
    {
        $validated = $request->validate([
            'penyebab' => ['required', 'string'],
        ]);

        PenyebabKegagalan::create([
            'kinerja_sub_kegiatan_id' => $kinerjaSubKegiatan,
            'triwulan' => $triwulan,
            'penyebab' => $validated['penyebab'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil tambah penyebab kegagalan',
        ]);
    }

    public function destroy(int $triwulan, int $kinerjaSubKegiatan, int $penyebabKegagalan)
    {
        $penyebabKegagalan = PenyebabKegagalan::query()
            ->where('id', $penyebabKegagalan)
            ->where('triwulan', $triwulan)
            ->whereHas('kinerjaSubKegiatan', fn (Builder $query) => $query
                ->tahunKinerja()
                ->roleSatuanKerja()
                ->where('id', $kinerjaSubKegiatan)
            )
            ->firstOrFail();

        try {
            DB::transaction(function () use ($penyebabKegagalan) {
                $penyebabKegagalan->langkahAksi()->delete();
                $penyebabKegagalan->delete();
            });
        } catch (Exception $e) {
            throw $e;
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus penyebab kegagalan',
        ]);
    }

    public function update(int $triwulan, int $kinerjaSubKegiatan, int $penyebabKegagalan, Request $request)
    {
        $validated = $request->validate([
            'penyebab' => ['required', 'string'],
        ]);

        $penyebabKegagalan = PenyebabKegagalan::query()
            ->where('id', $penyebabKegagalan)
            ->where('triwulan', $triwulan)
            ->whereHas('kinerjaSubKegiatan', fn (Builder $query) => $query
                ->tahunKinerja()
                ->roleSatuanKerja()
                ->where('id', $kinerjaSubKegiatan)
            )
            ->firstOrFail();

        $penyebabKegagalan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil update penyebab kegagalan',
        ]);
    }
}
