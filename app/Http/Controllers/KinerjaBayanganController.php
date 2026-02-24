<?php

namespace App\Http\Controllers;

use App\Exports\KinerjaBayanganExport;
use App\Http\Requests\KinerjaBayangan\StoreKinerjaBayangan;
use App\Http\Requests\KinerjaBayangan\UpdateKinerjaBayangan;
use App\Models\KinerjaBayangan;
use App\Models\Role;
use App\Services\FilterQuery;
use App\Traits\SetdaResourceAccess;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KinerjaBayanganController extends Controller
{
    use SetdaResourceAccess;

    public function index(Request $request, bool $isExport = false)
    {
        $data = KinerjaBayangan::tahunMulai()->roleSatuanKerja($this->getSatkerSetdaBiro());

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

    public function store(StoreKinerjaBayangan $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);
        $this->restrictBiro();

        $data = $request->validated();

        KinerjaBayangan::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambah data',
        ]);
    }

    public function destroy(KinerjaBayangan $kinerjaBayangan)
    {
        $this->authorizeBySatuanKerja($kinerjaBayangan->satuan_kerja_id, [Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);

        $kinerjaBayangan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data',
        ]);
    }

    public function edit(KinerjaBayangan $kinerjaBayangan)
    {
        $this->authorizeBySatuanKerja($kinerjaBayangan->satuan_kerja_id, [Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);

        $form = $kinerjaBayangan;

        return response()->json(compact('form'));
    }

    public function update(KinerjaBayangan $kinerjaBayangan, UpdateKinerjaBayangan $request)
    {
        $this->authorizeBySatuanKerja($kinerjaBayangan->satuan_kerja_id, [Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);

        $data = $request->validated();

        $kinerjaBayangan->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menyimpan data',
        ]);
    }

    public function show(KinerjaBayangan $kinerjaBayangan)
    {
        $this->authorizeBySatuanKerjaExcSetdaBiro($kinerjaBayangan->satuan_kerja_id);

        $kinerjaBayangan->load([
            'satuanKerja',
        ]);

        return response()->json($kinerjaBayangan);
    }

    public function export(Request $request)
    {
        $data = $this->index($request, true);

        return Excel::download(new KinerjaBayanganExport($data), 'Kinerja Cross-Cutting.xlsx');
    }
}
