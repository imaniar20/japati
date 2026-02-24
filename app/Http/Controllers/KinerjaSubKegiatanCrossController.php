<?php

namespace App\Http\Controllers;

use App\Models\KinerjaSubKegiatan;
use App\Models\KinerjaSubKegiatanCross;
use App\Services\FilterQuery;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class KinerjaSubKegiatanCrossController extends Controller
{
    public function index(int $kinerjaKegiatanId, Request $request)
    {
        $data = KinerjaSubKegiatan::tahunKinerja();

        FilterQuery::parseFilter($data, json_decode($request->filter, true));

        $data = $data
            ->select('id', 'sasaran', 'indikator', 'pengampu', 'v_struktur_organisasi_id', 'tim_kerja_id', 'sub_kegiatan_id')
            ->with([
                'strukturOrganisasi' => fn (Builder $query) => $query
                    ->selectRaw('id, jabatan_nama')
                    ->withoutGlobalScope('active'),
                'timKerja:id,nama,nip_ketua',
                'timKerja.ketua:peg_nip,peg_nama',
                'subKegiatan:id,nama',
            ])
            ->withExists([
                'kinerjaSubKegiatanCross' => fn (Builder $query) => $query->where('kinerja_kegiatan_id', $kinerjaKegiatanId),
            ])
            ->paginate(20);

        return response()->json($data);
    }

    public function set(int $kinerjaKegiatanId, Request $request)
    {
        $validated = $request->validate([
            'set' => ['required', 'boolean'],
            'kinerja_sub_kegiatan_id' => ['required', 'integer'],
        ]);

        if ($validated['set']) {
            KinerjaSubKegiatanCross::query()->create([
                'kinerja_kegiatan_id' => $kinerjaKegiatanId,
                'kinerja_sub_kegiatan_id' => $validated['kinerja_sub_kegiatan_id'],
            ]);
        } else {
            KinerjaSubKegiatanCross::query()
                ->where('kinerja_kegiatan_id', $kinerjaKegiatanId)
                ->where('kinerja_sub_kegiatan_id', $validated['kinerja_sub_kegiatan_id'])
                ->delete();
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
