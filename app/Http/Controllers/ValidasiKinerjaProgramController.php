<?php

namespace App\Http\Controllers;

use App\Models\KinerjaProgram;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ValidasiKinerjaProgramController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
            'search' => ['nullable', 'string'],
        ]);

        $data = KinerjaProgram::tahunKinerja()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->select('id', 'target', 'realisasi', 'validasi_cascading', 'sasaran', 'indikator', 'satuan', 'sasaran_strategis_pd_id')
            ->with([
                'sasaranStrategisPd:id,sasaran_strategis_satker',
            ])
            ->when($validated['search'] ?? null, fn (Builder $query, string $search) => $query
                ->where(fn (Builder $query) => $query
                    ->where('sasaran', 'ILIKE', "%$search%")
                    ->orWhere('indikator', 'ILIKE', "%$search%")
                    ->orWhereHas('sasaranStrategisPd', fn (Builder $query) => $query
                        ->where('sasaran_strategis_satker', 'ILIKE', "%$search%")
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

        KinerjaProgram::tahunKinerja()
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

        $data = KinerjaProgram::tahunKinerja()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->select('id', 'program_id', 'sasaran', 'indikator', 'validasi_pengampu', 'pengampu', 'v_struktur_organisasi_id', 'tim_kerja_id')
            ->with([
                'program' => fn ($query) => $query->tahunKinerja(),
                'strukturOrganisasi:id,jabatan_nama',
                'timKerja:id,nama,nip_ketua',
                'timKerja.ketua:peg_nip,peg_nama',
            ])
            ->when($validated['search'] ?? null, fn (Builder $query, string $search) => $query
                ->where(fn (Builder $query) => $query
                    ->where('sasaran', 'ILIKE', "%$search%")
                    ->orWhere('indikator', 'ILIKE', "%$search%")
                    ->orWhereHas('program', fn (Builder $query) => $query
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

        KinerjaProgram::tahunKinerja()
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
