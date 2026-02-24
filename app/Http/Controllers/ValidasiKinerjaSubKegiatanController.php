<?php

namespace App\Http\Controllers;

use App\Models\KinerjaSubKegiatan;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ValidasiKinerjaSubKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
            'bulan' => ['required', 'string', Rule::in(array_map(fn (array $month) => $month[0], MONTHS))],
            'search' => ['nullable', 'string'],
        ]);

        $data = KinerjaSubKegiatan::tahunKinerja()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->targetBulanan($validated['bulan'])
            ->select('id', 'target_bulanan', 'realisasi_bulanan', 'eviden_bulanan', 'validasi_bulanan', 'sasaran', 'indikator', 'satuan', 'kinerja_kegiatan_id')
            ->with([
                'kinerjaKegiatan:id,sasaran',
            ])
            ->when($validated['search'] ?? null, fn (Builder $query, string $search) => $query
                ->where(fn (Builder $query) => $query
                    ->where('sasaran', 'ILIKE', "%$search%")
                    ->orWhere('indikator', 'ILIKE', "%$search%")
                    ->orWhereHas('kinerjaKegiatan', fn (Builder $query) => $query
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
            'bulan' => ['required', 'string', Rule::in(array_map(fn (array $month) => $month[0], MONTHS))],
            'status' => ['required', 'boolean'],
            'catatan' => ['nullable', 'string'],
        ]);

        KinerjaSubKegiatan::tahunKinerja()
            ->where('id', $validated['id'])
            ->update([
                'validasi_bulanan->'.$validated['bulan'] => [
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

        $data = KinerjaSubKegiatan::tahunKinerja()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->select('id', 'kegiatan_id', 'sub_kegiatan_id', 'sasaran', 'indikator', 'validasi_pengampu', 'pengampu', 'v_struktur_organisasi_id', 'tim_kerja_id')
            ->with([
                'kegiatan' => fn ($query) => $query->tahunKinerja(),
                'subKegiatan' => fn ($query) => $query->tahunKinerja(),
                'strukturOrganisasi:id,jabatan_nama',
                'timKerja:id,nama,nip_ketua',
                'timKerja.ketua:peg_nip,peg_nama',
            ])
            ->when($validated['search'] ?? null, fn (Builder $query, string $search) => $query
                ->where(fn (Builder $query) => $query
                    ->where('sasaran', 'ILIKE', "%$search%")
                    ->orWhere('indikator', 'ILIKE', "%$search%")
                    ->orWhereHas('subKegiatan', fn (Builder $query) => $query
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

        KinerjaSubKegiatan::tahunKinerja()
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
