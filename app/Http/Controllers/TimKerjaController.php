<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeKerja\StoreTimKerja;
use App\Http\Requests\TimKerja\SearchPegawaiRequest;
use App\Models\Ekinerja\TimKerja;
use App\Models\Ekinerja\VPegawaiData;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\KinerjaSubKegiatanKabKota;
use App\Models\Role;
use App\Models\SKP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimKerjaController extends Controller
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

        $data = TimKerja::query()
            ->roleSatuanKerja($satkerId)
            ->with([
                'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                'strukturOrganisasi:id,unit_kerja_nama,jabatan_nama',
                'ketua:peg_nip,peg_nama,jabatan_nama,unit_kerja_nama',
            ])
            ->orderBy('satuan_kerja_id')
            ->orderBy('nama')
            ->get();

        return response()->json($this->withUsageCounts($data));
    }

    public function store(StoreTimKerja $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $data = $request->validated();
        $data = $this->normalizeData($data);

        // $isExists = TimKerja::where('satuan_kerja_id', $data['satuan_kerja_id'])
        //     ->where('v_struktur_organisasi_id', $data['v_struktur_organisasi_id'])
        //     ->where('nama', trim($data['nama']))
        //     ->exists();

        // if ($isExists) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Nama Tim Kinerja sudah ada',
        //     ]);
        // }

        $data = TimKerja::create($data);
        $data->load([
            'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            'strukturOrganisasi:id,unit_kerja_nama,jabatan_nama',
            'ketua:peg_nip,peg_nama,jabatan_nama,unit_kerja_nama',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil tambah data',
            'data' => $data,
        ]);
    }

    public function show(TimKerja $timKerja)
    {
        $this->authorizeTimKerja($timKerja);

        $timKerja->load([
            'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            'strukturOrganisasi:id,unit_kerja_nama,jabatan_nama',
            'ketua:peg_nip,peg_nama,jabatan_nama,unit_kerja_nama',
        ]);

        return response()->json($timKerja);
    }

    public function update(StoreTimKerja $request, TimKerja $timKerja)
    {
        $this->authorizeTimKerja($timKerja);

        $data = $this->normalizeData($request->validated());

        if (! Role::isSuper() && (int) $data['satuan_kerja_id'] !== (int) $timKerja->satuan_kerja_id) {
            abort(403, 'Anda tidak memiliki hak akses');
        }

        $timKerja->update($data);
        $timKerja->load([
            'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            'strukturOrganisasi:id,unit_kerja_nama,jabatan_nama',
            'ketua:peg_nip,peg_nama,jabatan_nama,unit_kerja_nama',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menyimpan data',
            'data' => $timKerja,
        ]);
    }

    public function destroy(TimKerja $timKerja)
    {
        $this->authorizeTimKerja($timKerja);

        $usage = $this->usageCounts([$timKerja->id])[$timKerja->id] ?? $this->emptyUsageCounts();

        if ($usage['total'] > 0) {
            return response()->json([
                'message' => 'Tim Kerja tidak bisa dihapus karena sudah dipakai pada data kinerja.',
                'usage_counts' => $usage,
            ], 400);
        }

        $timKerja->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus data',
        ]);
    }

    public function searchPegawai(SearchPegawaiRequest $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $validated = $request->validated();
        $search = $validated['search'] ?? '';
        $satkerId = $validated['satuan_kerja_id'];

        if (! Role::isSuper()) {
            $satkerId = Auth::user()->satuan_kerja_id;
        }

        $data = VPegawaiData::select('peg_nip', 'peg_nama', 'jabatan_nama', 'unit_kerja_nama')
            ->aktif()
            ->where('satuan_kerja_id', parseSatuanKerjaId($satkerId))
            ->where(fn ($query) => $query->where('peg_nip', $search)
                ->orWhere('peg_nama', 'ILIKE', "%$search%")
            )
            ->limit(20)
            ->get();

        return response()->json($data);
    }

    private function normalizeData(array $data): array
    {
        if (! Role::isSuper()) {
            $data['satuan_kerja_id'] = Auth::user()->satuan_kerja_id;
        }

        $data['nama'] = trim($data['nama']);
        $data['v_struktur_organisasi_id'] = $data['v_struktur_organisasi_id'] ?? null;

        return $data;
    }

    private function authorizeTimKerja(TimKerja $timKerja): void
    {
        if (Role::isSuper()) {
            return;
        }

        if (! $timKerja->satuan_kerja_id) {
            abort(403, 'Anda tidak memiliki hak akses');
        }

        $this->authorizeBySatuanKerja((int) $timKerja->satuan_kerja_id, [Role::PERANGKAT_DAERAH]);
    }

    private function withUsageCounts($data)
    {
        $counts = $this->usageCounts($data->pluck('id')->all());

        return $data->transform(function (TimKerja $timKerja) use ($counts) {
            $timKerja->usage_counts = $counts[$timKerja->id] ?? $this->emptyUsageCounts();

            return $timKerja;
        });
    }

    private function usageCounts(array $ids): array
    {
        if (! count($ids)) {
            return [];
        }

        $program = $this->countUsage(KinerjaProgram::class, $ids);
        $kegiatan = $this->countUsage(KinerjaKegiatan::class, $ids);
        $subKegiatan = $this->countUsage(KinerjaSubKegiatan::class, $ids);
        $subKegiatanKabKota = $this->countUsage(KinerjaSubKegiatanKabKota::class, $ids);
        $skp = $this->countUsage(SKP::class, $ids);

        $result = [];

        foreach ($ids as $id) {
            $result[$id] = [
                'kinerja_program' => $program[$id] ?? 0,
                'kinerja_kegiatan' => $kegiatan[$id] ?? 0,
                'kinerja_sub_kegiatan' => $subKegiatan[$id] ?? 0,
                'kinerja_sub_kegiatan_kab_kota' => $subKegiatanKabKota[$id] ?? 0,
                'skp' => $skp[$id] ?? 0,
            ];
            $result[$id]['total'] = array_sum($result[$id]);
        }

        return $result;
    }

    private function countUsage(string $model, array $ids): array
    {
        return $model::query()
            ->whereIn('tim_kerja_id', $ids)
            ->selectRaw('tim_kerja_id, COUNT(*) AS total')
            ->groupBy('tim_kerja_id')
            ->pluck('total', 'tim_kerja_id')
            ->map(fn ($value) => (int) $value)
            ->all();
    }

    private function emptyUsageCounts(): array
    {
        return [
            'kinerja_program' => 0,
            'kinerja_kegiatan' => 0,
            'kinerja_sub_kegiatan' => 0,
            'kinerja_sub_kegiatan_kab_kota' => 0,
            'skp' => 0,
            'total' => 0,
        ];
    }
}
