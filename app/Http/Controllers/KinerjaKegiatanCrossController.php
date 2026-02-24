<?php

namespace App\Http\Controllers;

use App\Models\KinerjaKegiatan;
use App\Models\KinerjaKegiatanCross;
use App\Services\FilterQuery;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class KinerjaKegiatanCrossController extends Controller
{
    public function index(int $kinerjaProgramId, Request $request)
    {
        $data = KinerjaKegiatan::tahunKinerja();

        FilterQuery::parseFilter($data, json_decode($request->filter, true));

        $data = $data
            ->select('id', 'sasaran', 'indikator', 'pengampu', 'v_struktur_organisasi_id', 'tim_kerja_id', 'kegiatan_id')
            ->with([
                'strukturOrganisasi' => fn (Builder $query) => $query
                    ->selectRaw('id, jabatan_nama')
                    ->withoutGlobalScope('active'),
                'timKerja:id,nama,nip_ketua',
                'timKerja.ketua:peg_nip,peg_nama',
                'kegiatan:id,nama',
            ])
            ->withExists([
                'kinerjaKegiatanCross' => fn (Builder $query) => $query->where('kinerja_program_id', $kinerjaProgramId),
            ])
            ->paginate(20);

        return response()->json($data);
    }

    public function set(int $kinerjaProgramId, Request $request)
    {
        $validated = $request->validate([
            'set' => ['required', 'boolean'],
            'kinerja_kegiatan_id' => ['required', 'integer'],
        ]);

        if ($validated['set']) {
            KinerjaKegiatanCross::query()->create([
                'kinerja_program_id' => $kinerjaProgramId,
                'kinerja_kegiatan_id' => $validated['kinerja_kegiatan_id'],
            ]);
        } else {
            KinerjaKegiatanCross::query()
                ->where('kinerja_program_id', $kinerjaProgramId)
                ->where('kinerja_kegiatan_id', $validated['kinerja_kegiatan_id'])
                ->delete();
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
