<?php

namespace App\Http\Controllers;

use App\Models\KinerjaKegiatan;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ValidasiKinerjaKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
            'search' => ['nullable', 'string'],
        ]);

        $data = KinerjaKegiatan::tahunKinerja()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->select('id', 'target', 'realisasi', 'validasi_cascading', 'sasaran', 'indikator', 'satuan', 'kinerja_program_id')
            ->with([
                'kinerjaProgram:id,sasaran',
            ])
            ->when($validated['search'] ?? null, fn (Builder $query, string $search) => $query
                ->where(fn (Builder $query) => $query
                    ->where('sasaran', 'ILIKE', "%$search%")
                    ->orWhere('indikator', 'ILIKE', "%$search%")
                    ->orWhereHas('kinerjaProgram', fn (Builder $query) => $query
                        ->where('sasaran', 'ILIKE', "%$search%")
                    )
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
            'status' => ['required', 'boolean'],
            'catatan' => ['nullable', 'string'],
        ]);

        KinerjaKegiatan::tahunKinerja()
            ->where('id', $validated['id'])
            ->update([
                'validasi_cascading' => [
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

        $data = KinerjaKegiatan::tahunKinerja()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->select('id', 'kegiatan_id', 'sasaran', 'indikator', 'validasi_pengampu', 'pengampu', 'v_struktur_organisasi_id', 'tim_kerja_id')
            ->with([
                'kegiatan' => fn ($query) => $query->tahunKinerja(),
                'strukturOrganisasi:id,jabatan_nama',
                'timKerja:id,nama,nip_ketua',
                'timKerja.ketua:peg_nip,peg_nama',
            ])
            ->when($validated['search'] ?? null, fn (Builder $query, string $search) => $query
                ->where(fn (Builder $query) => $query
                    ->where('sasaran', 'ILIKE', "%$search%")
                    ->orWhere('indikator', 'ILIKE', "%$search%")
                    ->orWhereHas('kegiatan', fn (Builder $query) => $query
                        ->where('nama', 'ILIKE', "%$search%")
                    )
                )
            )
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

        KinerjaKegiatan::tahunKinerja()
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
