<?php

namespace App\Http\Controllers;

use App\Models\KinerjaProgram;
use App\Models\KinerjaProgramCross;
use App\Services\FilterQuery;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class KinerjaProgramCrossController extends Controller
{
    public function index(int $sasaranStrategisPdId, Request $request)
    {
        $data = KinerjaProgram::tahunKinerja();

        FilterQuery::parseFilter($data, json_decode($request->filter, true));

        $data = $data
            ->select('id', 'sasaran', 'indikator', 'pengampu', 'v_struktur_organisasi_id', 'tim_kerja_id', 'program_id')
            ->with([
                'strukturOrganisasi' => fn (Builder $query) => $query
                    ->selectRaw('id, jabatan_nama')
                    ->withoutGlobalScope('active'),
                'timKerja:id,nama,nip_ketua',
                'timKerja.ketua:peg_nip,peg_nama',
                'program:id,nama',
            ])
            ->withExists([
                'kinerjaProgramCross' => fn (Builder $query) => $query->where('sasaran_strategis_pd_id', $sasaranStrategisPdId),
            ])
            ->paginate(20);

        return response()->json($data);
    }

    public function set(int $sasaranStrategisPdId, Request $request)
    {
        $validated = $request->validate([
            'set' => ['required', 'boolean'],
            'kinerja_program_id' => ['required', 'integer'],
        ]);

        if ($validated['set']) {
            KinerjaProgramCross::query()->create([
                'sasaran_strategis_pd_id' => $sasaranStrategisPdId,
                'kinerja_program_id' => $validated['kinerja_program_id'],
            ]);
        } else {
            KinerjaProgramCross::query()
                ->where('sasaran_strategis_pd_id', $sasaranStrategisPdId)
                ->where('kinerja_program_id', $validated['kinerja_program_id'])
                ->delete();
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
