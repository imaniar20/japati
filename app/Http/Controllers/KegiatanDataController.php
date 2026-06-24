<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Program;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KegiatanDataController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $validated = $request->validate([
            'satuan_kerja_id' => ['nullable', 'integer'],
            'tahun_kinerja' => ['nullable', 'integer'],
            'program_id' => ['nullable', 'integer'],
        ]);

        $tahunKinerja = $validated['tahun_kinerja'] ?? getTahunKinerja();
        $satkerId = Role::isSuper()
            ? ($validated['satuan_kerja_id'] ?? null)
            : Auth::user()->satuan_kerja_id;

        $data = Kegiatan::query()
            ->with([
                'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                'program:id,kode,nama',
            ])
            ->withCount(['subKegiatan', 'kinerjaKegiatan'])
            ->where('tahun_kinerja', $tahunKinerja)
            ->when($satkerId, fn ($query) => $query->whereIn('satuan_kerja_id', getSatuanKerjaIds((int) $satkerId)))
            ->when($validated['program_id'] ?? null, fn ($query, $programId) => $query->where('program_id', $programId))
            ->orderBy('satuan_kerja_id')
            ->orderBy('kode')
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $validated = $this->validateKegiatan($request);

        $kegiatan = Kegiatan::query()->create($validated);
        $kegiatan->load([
            'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            'program:id,kode,nama',
        ]);

        return response()->json($kegiatan, 201);
    }

    public function show(Kegiatan $kegiatan)
    {
        $this->authorizeKegiatan($kegiatan);

        $kegiatan->load([
            'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            'program:id,kode,nama',
        ]);

        return response()->json($kegiatan);
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $this->authorizeKegiatan($kegiatan);

        $validated = $this->validateKegiatan($request, $kegiatan);

        if (! Role::isSuper() && (int) $validated['satuan_kerja_id'] !== (int) $kegiatan->satuan_kerja_id) {
            abort(403, 'Anda tidak memiliki hak akses');
        }

        $kegiatan->update($validated);
        $kegiatan->load([
            'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            'program:id,kode,nama',
        ]);

        return response()->json($kegiatan);
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $this->authorizeKegiatan($kegiatan);

        $usage = [
            'sub_kegiatan' => $kegiatan->subKegiatan()->count(),
            'kinerja_kegiatan' => $kegiatan->kinerjaKegiatan()->count(),
        ];
        $usage['total'] = array_sum($usage);

        if ($usage['total'] > 0) {
            return response()->json([
                'message' => 'Kegiatan tidak bisa dihapus karena sudah dipakai pada sub kegiatan atau data kinerja.',
                'usage_counts' => $usage,
            ], 400);
        }

        $kegiatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus data',
        ]);
    }

    private function validateKegiatan(Request $request, ?Kegiatan $kegiatan = null): array
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:255'],
            'nama' => ['required', 'string'],
            'program_id' => ['required', 'integer'],
            'satuan_kerja_id' => ['required', 'integer'],
            'tahun_kinerja' => ['required', 'integer', 'min:1900', 'max:2100'],
            'anggaran' => ['required', 'numeric', 'min:0'],
        ]);

        if (! Role::isSuper()) {
            $data['satuan_kerja_id'] = Auth::user()->satuan_kerja_id;
        }

        $data['satuan_kerja_id'] = (int) $data['satuan_kerja_id'];
        $data['kode'] = trim($data['kode']);
        $data['nama'] = trim($data['nama']);

        $programExists = Program::query()
            ->whereKey($data['program_id'])
            ->where('satuan_kerja_id', $data['satuan_kerja_id'])
            ->where('tahun_kinerja', $data['tahun_kinerja'])
            ->exists();

        $kodeRule = Rule::unique('kegiatan', 'kode')
            ->where(fn ($query) => $query
                ->where('satuan_kerja_id', $data['satuan_kerja_id'])
                ->where('tahun_kinerja', $data['tahun_kinerja']));

        if ($kegiatan) {
            $kodeRule->ignore($kegiatan->id);
        }

        Validator::make($data, [
            'kode' => [$kodeRule],
        ], [
            'kode.unique' => 'Kode kegiatan sudah ada untuk OPD dan tahun kinerja yang sama.',
        ])->after(function ($validator) use ($programExists) {
            if (! $programExists) {
                $validator->errors()->add('program_id', 'Program tidak sesuai dengan OPD dan tahun kinerja yang dipilih.');
            }
        })->validate();

        return $data;
    }

    private function authorizeKegiatan(Kegiatan $kegiatan): void
    {
        if (Role::isSuper()) {
            return;
        }

        $this->authorizeBySatuanKerja((int) $kegiatan->satuan_kerja_id, [Role::PERANGKAT_DAERAH]);
    }
}
