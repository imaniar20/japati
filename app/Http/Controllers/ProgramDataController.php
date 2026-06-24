<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProgramDataController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $validated = $request->validate([
            'satuan_kerja_id' => ['nullable', 'integer'],
            'tahun_kinerja' => ['nullable', 'integer'],
        ]);

        $tahunKinerja = $validated['tahun_kinerja'] ?? getTahunKinerja();
        $satkerId = Role::isSuper()
            ? ($validated['satuan_kerja_id'] ?? null)
            : Auth::user()->satuan_kerja_id;

        $data = Program::query()
            ->with('satuanKerja:satuan_kerja_id,satuan_kerja_nama')
            ->withCount(['kegiatan', 'kinerjaProgram', 'kinerjaKegiatan'])
            ->where('tahun_kinerja', $tahunKinerja)
            ->when($satkerId, fn ($query) => $query->whereIn('satuan_kerja_id', getSatuanKerjaIds((int) $satkerId)))
            ->orderBy('satuan_kerja_id')
            ->orderBy('kode')
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $validated = $this->validateProgram($request);

        $program = Program::query()->create($validated);
        $program->load('satuanKerja:satuan_kerja_id,satuan_kerja_nama');

        return response()->json($program, 201);
    }

    public function show(Program $program)
    {
        $this->authorizeProgram($program);

        $program->load('satuanKerja:satuan_kerja_id,satuan_kerja_nama');

        return response()->json($program);
    }

    public function update(Request $request, Program $program)
    {
        $this->authorizeProgram($program);

        $validated = $this->validateProgram($request, $program);

        if (! Role::isSuper() && (int) $validated['satuan_kerja_id'] !== (int) $program->satuan_kerja_id) {
            abort(403, 'Anda tidak memiliki hak akses');
        }

        $program->update($validated);
        $program->load('satuanKerja:satuan_kerja_id,satuan_kerja_nama');

        return response()->json($program);
    }

    public function destroy(Program $program)
    {
        $this->authorizeProgram($program);

        $usage = [
            'kegiatan' => $program->kegiatan()->count(),
            'kinerja_program' => $program->kinerjaProgram()->count(),
            'kinerja_kegiatan' => $program->kinerjaKegiatan()->count(),
        ];
        $usage['total'] = array_sum($usage);

        if ($usage['total'] > 0) {
            return response()->json([
                'message' => 'Program tidak bisa dihapus karena sudah dipakai pada data kegiatan atau kinerja.',
                'usage_counts' => $usage,
            ], 400);
        }

        $program->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus data',
        ]);
    }

    private function validateProgram(Request $request, ?Program $program = null): array
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:255'],
            'nama' => ['required', 'string'],
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

        $kodeRule = Rule::unique('program', 'kode')
            ->where(fn ($query) => $query
                ->where('satuan_kerja_id', $data['satuan_kerja_id'])
                ->where('tahun_kinerja', $data['tahun_kinerja']));

        if ($program) {
            $kodeRule->ignore($program->id);
        }

        Validator::make($data, [
            'kode' => [$kodeRule],
        ], [
            'kode.unique' => 'Kode program sudah ada untuk OPD dan tahun kinerja yang sama.',
        ])->validate();

        return $data;
    }

    private function authorizeProgram(Program $program): void
    {
        if (Role::isSuper()) {
            return;
        }

        $this->authorizeBySatuanKerja((int) $program->satuan_kerja_id, [Role::PERANGKAT_DAERAH]);
    }
}
