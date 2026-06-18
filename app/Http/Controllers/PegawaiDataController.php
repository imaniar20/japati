<?php

namespace App\Http\Controllers;

use App\Models\Ekinerja\PegawaiData;
use App\Models\Ekinerja\TimKerja;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PegawaiDataController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $validated = $request->validate([
            'satuan_kerja_id' => ['nullable', 'integer'],
        ]);

        $satkerId = Role::isSuper()
            ? ($validated['satuan_kerja_id'] ?? null)
            : Auth::user()->satuan_kerja_id;

        $data = PegawaiData::query()
            ->when($satkerId, fn ($query) => $query->whereIn('id_satuan_kerja', getSatuanKerjaIds($satkerId)))
            ->with('satuanKerja:satuan_kerja_id,satuan_kerja_nama')
            ->withCount('timKerja')
            ->orderBy('id_satuan_kerja')
            ->orderBy('peg_nama')
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $validated = $this->validatePegawai($request);
        $validated = $this->normalizeData($validated);

        $pegawai = PegawaiData::query()->create($validated);
        $pegawai->load('satuanKerja:satuan_kerja_id,satuan_kerja_nama');

        return response()->json($pegawai, 201);
    }

    public function show(PegawaiData $pegawaiData)
    {
        $this->authorizePegawai($pegawaiData);

        $pegawaiData->load('satuanKerja:satuan_kerja_id,satuan_kerja_nama');

        return response()->json($pegawaiData);
    }

    public function update(Request $request, PegawaiData $pegawaiData)
    {
        $this->authorizePegawai($pegawaiData);

        $validated = $this->validatePegawai($request, $pegawaiData);
        $validated = $this->normalizeData($validated);

        if (! Role::isSuper() && (int) $validated['id_satuan_kerja'] !== (int) $pegawaiData->id_satuan_kerja) {
            abort(403, 'Anda tidak memiliki hak akses');
        }

        $pegawaiData->update($validated);
        $pegawaiData->load('satuanKerja:satuan_kerja_id,satuan_kerja_nama');

        return response()->json($pegawaiData);
    }

    public function destroy(PegawaiData $pegawaiData)
    {
        $this->authorizePegawai($pegawaiData);

        if ($pegawaiData->timKerja()->exists()) {
            return response()->json([
                'message' => 'Pegawai tidak bisa dihapus karena sudah dipakai sebagai pengampu Tim Kerja.',
            ], 400);
        }

        $pegawaiData->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus data',
        ]);
    }

    private function validatePegawai(Request $request, ?PegawaiData $pegawai = null): array
    {
        $nipRule = Rule::unique('ekinerja.pegawai_data', 'peg_nip');

        if ($pegawai) {
            $nipRule->ignore($pegawai->id);
        }

        return $request->validate([
            'peg_nip' => ['required', 'string', 'max:255', $nipRule],
            'id_satuan_kerja' => ['required', 'integer'],
            'peg_nama' => ['required', 'string', 'max:255'],
            'jabatan_nama' => ['nullable', 'string', 'max:255'],
            'unit_kerja_nama' => ['nullable', 'string', 'max:255'],
            'peg_status' => ['required', 'string', Rule::in(['1', '0'])],
        ]);
    }

    private function normalizeData(array $data): array
    {
        if (! Role::isSuper()) {
            $data['id_satuan_kerja'] = Auth::user()->satuan_kerja_id;
        }

        $data['peg_nip'] = trim($data['peg_nip']);
        $data['peg_nama'] = trim($data['peg_nama']);

        return $data;
    }

    private function authorizePegawai(PegawaiData $pegawai): void
    {
        if (Role::isSuper()) {
            return;
        }

        $this->authorizeBySatuanKerja((int) $pegawai->id_satuan_kerja, [Role::PERANGKAT_DAERAH]);
    }
}
