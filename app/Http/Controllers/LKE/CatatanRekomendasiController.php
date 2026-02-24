<?php

namespace App\Http\Controllers\LKE;

use App\Http\Controllers\Controller;
use App\Models\LKE\CatatanRekomendasi;
use App\Models\LKE\Eviden;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatatanRekomendasiController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        $data = CatatanRekomendasi::query()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->tahunKinerja()
            ->first();

        // ambil dari eviden 4.3.1
        $rencanaAksi = Eviden::tahunKinerja(2024) // TODO: kenapa di hardcode? YNTKTS
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->whereHas('kriteria', fn (Builder $query) => $query->where('nomor_full', '4.3.1'))
            ->value('eviden');

        return response()->json([
            'data' => $data,
            'rencana_aksi' => $rencanaAksi,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
            'rekomendasi' => ['present', 'array'],
        ]);

        $catatanRekomendasi = CatatanRekomendasi::query()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->tahunKinerja()
            ->where('user_id', Auth::user()->id)
            ->first();

        if ($catatanRekomendasi) {
            $catatanRekomendasi->update([
                'rekomendasi' => $validated['rekomendasi'],
            ]);
        } else {
            CatatanRekomendasi::query()->create([
                'satuan_kerja_id' => $validated['satuan_kerja_id'],
                'tahun_kinerja' => getTahunKinerja(),
                'user_id' => Auth::user()->id,
                'catatan' => [],
                'rekomendasi' => $validated['rekomendasi'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambah data',
        ]);
    }
}
