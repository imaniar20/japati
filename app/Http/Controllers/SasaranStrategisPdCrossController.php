<?php

namespace App\Http\Controllers;

use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisPdCross;
use App\Services\FilterQuery;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SasaranStrategisPdCrossController extends Controller
{
    public function index(int $sasaranStrategisRpjmdId, Request $request)
    {
        $filter = json_decode($request->filter, true);

        $data = SasaranStrategisPd::tahunMulai();

        FilterQuery::parseFilter($data, $filter);

        $data = $data
            ->select('id', 'sasaran_strategis_satker', 'iku')
            ->withExists([
                'sasaranStrategisPdCross' => fn (Builder $query) => $query->where('sasaran_strategis_rpjmd_id', $sasaranStrategisRpjmdId),
            ])
            ->when(isset($filter['search']), fn (Builder $query) => $query
                ->where(fn (Builder $query) => $query
                    ->where('sasaran_strategis_satker', 'ILIKE', "%{$filter['search']}%")
                    ->orWhere('iku', 'ILIKE', "%{$filter['search']}%")
                ))
            ->paginate(20);

        return response()->json($data);
    }

    public function set(int $sasaranStrategisRpjmdId, Request $request)
    {
        $validated = $request->validate([
            'set' => ['required', 'boolean'],
            'sasaran_strategis_pd_id' => ['required', 'integer'],
        ]);

        if ($validated['set']) {
            SasaranStrategisPdCross::query()->create([
                'sasaran_strategis_rpjmd_id' => $sasaranStrategisRpjmdId,
                'sasaran_strategis_pd_id' => $validated['sasaran_strategis_pd_id'],
            ]);
        } else {
            SasaranStrategisPdCross::query()
                ->where('sasaran_strategis_rpjmd_id', $sasaranStrategisRpjmdId)
                ->where('sasaran_strategis_pd_id', $validated['sasaran_strategis_pd_id'])
                ->delete();
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
