<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Role;
use App\Models\SubKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubKegiatanDataController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $validated = $request->validate([
            'satuan_kerja_id' => ['nullable', 'integer'],
            'tahun_kinerja' => ['nullable', 'integer'],
            'kegiatan_id' => ['nullable', 'integer'],
        ]);

        $tahunKinerja = $validated['tahun_kinerja'] ?? getTahunKinerja();
        $satkerId = Role::isSuper()
            ? ($validated['satuan_kerja_id'] ?? null)
            : Auth::user()->satuan_kerja_id;

        $data = SubKegiatan::query()
            ->with([
                'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                'kegiatan:id,kode,nama,program_id',
                'kegiatan.program:id,kode,nama',
                'indikator',
            ])
            ->withCount(['indikator', 'kinerjaSubKegiatan', 'kinerjaSubKegiatanKabKota'])
            ->where('tahun_kinerja', $tahunKinerja)
            ->when($satkerId, fn ($query) => $query->whereIn('satuan_kerja_id', getSatuanKerjaIds((int) $satkerId)))
            ->when($validated['kegiatan_id'] ?? null, fn ($query, $kegiatanId) => $query->where('kegiatan_id', $kegiatanId))
            ->orderBy('satuan_kerja_id')
            ->orderBy('kode')
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $validated = $this->validateSubKegiatan($request);

        $subKegiatan = DB::transaction(function () use ($validated) {
            $indikator = $validated['indikator'] ?? [];
            unset($validated['indikator']);

            $subKegiatan = SubKegiatan::query()->create($validated);
            $this->syncIndikator($subKegiatan, $indikator);

            return $subKegiatan;
        });

        $subKegiatan->load([
            'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            'kegiatan:id,kode,nama,program_id',
            'kegiatan.program:id,kode,nama',
            'indikator',
        ]);

        return response()->json($subKegiatan, 201);
    }

    public function show(SubKegiatan $subKegiatan)
    {
        $this->authorizeSubKegiatan($subKegiatan);

        $subKegiatan->load([
            'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            'kegiatan:id,kode,nama,program_id',
            'kegiatan.program:id,kode,nama',
            'indikator',
        ]);

        return response()->json($subKegiatan);
    }

    public function update(Request $request, SubKegiatan $subKegiatan)
    {
        $this->authorizeSubKegiatan($subKegiatan);

        $validated = $this->validateSubKegiatan($request, $subKegiatan);

        if (! Role::isSuper() && (int) $validated['satuan_kerja_id'] !== (int) $subKegiatan->satuan_kerja_id) {
            abort(403, 'Anda tidak memiliki hak akses');
        }

        DB::transaction(function () use ($subKegiatan, $validated) {
            $indikator = $validated['indikator'] ?? [];
            unset($validated['indikator']);

            $subKegiatan->update($validated);
            $this->syncIndikator($subKegiatan, $indikator);
        });

        $subKegiatan->load([
            'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            'kegiatan:id,kode,nama,program_id',
            'kegiatan.program:id,kode,nama',
            'indikator',
        ]);

        return response()->json($subKegiatan);
    }

    public function destroy(SubKegiatan $subKegiatan)
    {
        $this->authorizeSubKegiatan($subKegiatan);

        $usage = [
            'kinerja_sub_kegiatan' => $subKegiatan->kinerjaSubKegiatan()->count(),
            'kinerja_sub_kegiatan_kab_kota' => $subKegiatan->kinerjaSubKegiatanKabKota()->count(),
        ];
        $usage['total'] = array_sum($usage);

        if ($usage['total'] > 0) {
            return response()->json([
                'message' => 'Sub Kegiatan tidak bisa dihapus karena sudah dipakai pada data kinerja.',
                'usage_counts' => $usage,
            ], 400);
        }

        DB::transaction(function () use ($subKegiatan) {
            $subKegiatan->indikator()->delete();
            $subKegiatan->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus data',
        ]);
    }

    private function validateSubKegiatan(Request $request, ?SubKegiatan $subKegiatan = null): array
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:255'],
            'nama' => ['required', 'string'],
            'kegiatan_id' => ['required', 'integer'],
            'satuan_kerja_id' => ['required', 'integer'],
            'tahun_kinerja' => ['required', 'integer', 'min:1900', 'max:2100'],
            'anggaran' => ['required', 'numeric', 'min:0'],
            'indikator' => ['nullable', 'array'],
            'indikator.*.indikator' => ['nullable', 'string'],
            'indikator.*.target' => ['nullable', 'string', 'max:255'],
            'indikator.*.satuan' => ['nullable', 'string', 'max:255'],
        ]);

        if (! Role::isSuper()) {
            $data['satuan_kerja_id'] = Auth::user()->satuan_kerja_id;
        }

        $data['satuan_kerja_id'] = (int) $data['satuan_kerja_id'];
        $data['kode'] = trim($data['kode']);
        $data['nama'] = trim($data['nama']);
        $data['indikator'] = collect($data['indikator'] ?? [])
            ->map(fn ($item) => [
                'indikator' => trim($item['indikator'] ?? ''),
                'target' => trim($item['target'] ?? ''),
                'satuan' => trim($item['satuan'] ?? ''),
            ])
            ->filter(fn ($item) => $item['indikator'] !== '')
            ->values()
            ->all();

        $kegiatanExists = Kegiatan::query()
            ->whereKey($data['kegiatan_id'])
            ->where('satuan_kerja_id', $data['satuan_kerja_id'])
            ->where('tahun_kinerja', $data['tahun_kinerja'])
            ->exists();

        $kodeRule = Rule::unique('sub_kegiatan', 'kode')
            ->where(fn ($query) => $query
                ->where('satuan_kerja_id', $data['satuan_kerja_id'])
                ->where('tahun_kinerja', $data['tahun_kinerja']));

        if ($subKegiatan) {
            $kodeRule->ignore($subKegiatan->id);
        }

        Validator::make($data, [
            'kode' => [$kodeRule],
        ], [
            'kode.unique' => 'Kode sub kegiatan sudah ada untuk OPD dan tahun kinerja yang sama.',
        ])->after(function ($validator) use ($kegiatanExists) {
            if (! $kegiatanExists) {
                $validator->errors()->add('kegiatan_id', 'Kegiatan tidak sesuai dengan OPD dan tahun kinerja yang dipilih.');
            }
        })->validate();

        return $data;
    }

    private function syncIndikator(SubKegiatan $subKegiatan, array $indikator): void
    {
        $subKegiatan->indikator()->delete();

        if (! count($indikator)) {
            return;
        }

        $subKegiatan->indikator()->createMany($indikator);
    }

    private function authorizeSubKegiatan(SubKegiatan $subKegiatan): void
    {
        if (Role::isSuper()) {
            return;
        }

        $this->authorizeBySatuanKerja((int) $subKegiatan->satuan_kerja_id, [Role::PERANGKAT_DAERAH]);
    }

    public function import(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $validated = $request->validate([
            'satuan_kerja_id' => ['nullable', 'integer'],
            'tahun_kinerja' => ['required', 'integer', 'min:1900', 'max:2100'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.kode_kegiatan' => ['required', 'string'],
            'items.*.kode' => ['required', 'string'],
            'items.*.nama' => ['required', 'string'],
            'items.*.anggaran' => ['nullable', 'numeric'],
        ]);

        $satkerId = Role::isSuper()
            ? ($validated['satuan_kerja_id'] ?? Auth::user()->satuan_kerja_id)
            : Auth::user()->satuan_kerja_id;

        if (!$satkerId) {
            return response()->json(['message' => 'Satuan Kerja / OPD harus dipilih'], 422);
        }

        $tahunKinerja = (int) $validated['tahun_kinerja'];

        // Load all kegiatan for this satker & year indexed by kode
        $kegiatanMap = Kegiatan::query()
            ->where('satuan_kerja_id', $satkerId)
            ->where('tahun_kinerja', $tahunKinerja)
            ->get()
            ->keyBy(fn ($k) => trim($k->kode));

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        foreach ($validated['items'] as $index => $item) {
            $kodeKegiatan = trim($item['kode_kegiatan']);
            $kodeSubKegiatan = trim($item['kode']);
            $namaSubKegiatan = trim($item['nama']);
            $anggaran = isset($item['anggaran']) ? (float) $item['anggaran'] : 0;

            if (empty($kodeKegiatan) || empty($kodeSubKegiatan) || empty($namaSubKegiatan)) {
                $skipped++;
                continue;
            }

            if (!isset($kegiatanMap[$kodeKegiatan])) {
                $skipped++;
                if (count($errors) < 5) {
                    $errors[] = "Baris " . ($index + 1) . ": Kode Kegiatan '{$kodeKegiatan}' tidak ditemukan di Master Kegiatan.";
                }
                continue;
            }

            $kegiatanId = $kegiatanMap[$kodeKegiatan]->id;

            $subKegiatan = SubKegiatan::query()
                ->where('satuan_kerja_id', $satkerId)
                ->where('tahun_kinerja', $tahunKinerja)
                ->where('kode', $kodeSubKegiatan)
                ->first();

            if ($subKegiatan) {
                $subKegiatan->update([
                    'kegiatan_id' => $kegiatanId,
                    'nama' => $namaSubKegiatan,
                    'anggaran' => $anggaran,
                ]);
                $updated++;
            } else {
                SubKegiatan::create([
                    'kode' => $kodeSubKegiatan,
                    'nama' => $namaSubKegiatan,
                    'kegiatan_id' => $kegiatanId,
                    'satuan_kerja_id' => $satkerId,
                    'tahun_kinerja' => $tahunKinerja,
                    'anggaran' => $anggaran,
                ]);
                $created++;
            }
        }

        $msg = "Berhasil memproses import sub kegiatan: {$created} data baru dibuat, {$updated} data diperbarui, {$skipped} dilewati.";
        if (!empty($errors)) {
            $msg .= " Catatan: " . implode(" ", $errors);
        }

        return response()->json([
            'success' => true,
            'message' => $msg,
            'created_count' => $created,
            'updated_count' => $updated,
            'skipped_count' => $skipped,
        ]);
    }
}
