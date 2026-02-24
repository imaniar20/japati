<?php

namespace App\Http\Controllers\LKE;

use App\Http\Controllers\Controller;
use App\Http\Requests\LKE\StoreRekomendasi;
use App\Http\Requests\LKE\UpdateRekomendasi;
use App\Models\LKE\Rekomendasi;
use App\Models\Role;
use App\Services\FilterQuery;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    public function index(Request $request, bool $isExport = false)
    {
        $data = Rekomendasi::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($data, json_decode($request->filter, true));

        $data->with([
            'satuanKerja',
        ])
            ->orderBy('updated_at');

        if ($isExport) {
            return $data->get();
        } else {
            $data = $data->paginate(20);
        }

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRekomendasi $request)
    {
        $this->authorizeByRoles([Role::VALIDATOR_LKE, Role::VALIDATOR_LKE_PLENO, Role::SUPER, Role::PERANGKAT_DAERAH]);

        $data = $request->validated();
        $data['tahun_kinerja'] = getTahunKinerja();

        Rekomendasi::create($data);

        return response()->json([
            'data' => $data,
            'success' => true,
            'message' => 'Berhasil menambah data',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorizeByRoles([Role::VALIDATOR_LKE, Role::VALIDATOR_LKE_PLENO, Role::SUPER, Role::PERANGKAT_DAERAH]);
        $form = Rekomendasi::where('id', $id)->firstOrFail();
        // $form = $rekomendasi->load(['satuanKerja']);

        return response()->json(compact('form'));
    }

    public function edit(Rekomendasi $rekomendasi)
    {
        $this->authorizeByRoles([Role::VALIDATOR_LKE, Role::VALIDATOR_LKE_PLENO, Role::SUPER, Role::PERANGKAT_DAERAH]);

        $form = $rekomendasi->load(['satuanKerja']);

        return response()->json(compact('form', 'rekomendasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRekomendasi $request, Rekomendasi $rekomendasi)
    {
        $this->authorizeByRoles([Role::VALIDATOR_LKE, Role::VALIDATOR_LKE_PLENO, Role::SUPER, Role::PERANGKAT_DAERAH]);

        $data = $request->validated();
        $rekomendasi->update($data);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Berhasil mengubah data',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekomendasi $rekomendasi)
    {
        $this->authorizeByRoles([Role::VALIDATOR_LKE, Role::VALIDATOR_LKE_PLENO, Role::SUPER]);

        $rekomendasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data',
        ]);
    }
}
