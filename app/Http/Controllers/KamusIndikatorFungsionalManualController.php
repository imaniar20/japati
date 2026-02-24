<?php

namespace App\Http\Controllers;

use App\Models\KamusIndikatorFungsionalManual;
use App\Models\KamusIndikatorFungsionalPengampu;
use App\Models\KamusIndikatorFungsionalPengampuJF;
use App\Models\KamusIndikatorFungsionalSasaran;
use App\Models\Role;
use App\Models\Simpeg\Jabatan;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class KamusIndikatorFungsionalManualController extends Controller
{
    public function index(Request $request)
    {
        if (Role::isSuper()) {
            $satkerId = $request->get('satuan_kerja_id', 1030);
        } elseif (Role::isSetda()) {
            $satkerId = $request->get('satuan_kerja_id', 100103010000);
        } else {
            $satkerId = null;
        }

        $data = KamusIndikatorFungsionalManual::tahunKinerja()
            ->roleSatuanKerja($satkerId)
            ->with([
                'sasaran.pengampu.jf.jabatan:jabatan_id,jf_id',
                'sasaran.pengampu.jf.jabatan.jf:jf_nama,jf_id',
            ])
            ->get();

        return response()->json($data);
    }

    public function options(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        $pengampu = KamusIndikatorFungsionalPengampu::tahunKinerja()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->with([
                'sasaran' => fn (Builder $query) => $query->tahunKinerja()->roleSatuanKerja($validated['satuan_kerja_id']),
                'jf' => fn (Builder $query) => $query->tahunKinerja()->roleSatuanKerja($validated['satuan_kerja_id']),
            ])
            ->get();

        $jabatan = Jabatan::query()
            ->selectRaw("jabatan_id, CONCAT(jf_nama, ' - ', unit_kerja_nama) jf_nama")
            ->join('m_spg_referensi_jf', 'm_spg_referensi_jf.jf_id', 'm_spg_jabatan.jf_id')
            ->join('m_spg_unit_kerja', 'm_spg_unit_kerja.unit_kerja_id', 'm_spg_jabatan.unit_kerja_id')
            ->whereNotNull('m_spg_jabatan.jf_id')
            ->where('m_spg_jabatan.satuan_kerja_id', $validated['satuan_kerja_id'])
            ->orderBy('jf_nama')
            ->get();

        return response()->json([
            'pengampu' => $pengampu,
            'jabatan' => $jabatan,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['nullable', 'numeric'],
            'satuan_kerja_id' => ['required', 'numeric'],
            'jenis_indikator' => ['required', 'string'],
            'indikator' => ['required', 'string'],
            'pengampu_id' => ['required_if:is_create_pengampu,false', 'numeric', 'nullable'],
            'is_create_pengampu' => ['required', 'boolean'],
            'created_pengampu' => ['required_if:is_create_pengampu,true', 'string', 'nullable'],
            'sasaran_id' => ['required_if:is_create_sasaran,false', 'numeric', 'nullable'],
            'is_create_sasaran' => ['required', 'boolean'],
            'sasaran_created' => ['required_if:is_create_sasaran,true', 'string', 'nullable'],
            'jf' => ['required', 'array'],
            'jf.*' => ['required', 'numeric'],
            'is_edit_jf' => ['required', 'boolean'],
            'keterangan' => ['nullable', 'string'],
        ]);

        if ($validated['is_create_pengampu']) {
            $pengampu = KamusIndikatorFungsionalPengampu::query()->create([
                'tahun_kinerja' => getTahunKinerja(),
                'satuan_kerja_id' => $validated['satuan_kerja_id'],
                'pengampu' => $validated['created_pengampu'],
            ]);

            $validated['pengampu_id'] = $pengampu->id;
        }

        if ($validated['is_create_sasaran']) {
            $sasaran = KamusIndikatorFungsionalSasaran::query()->create([
                'tahun_kinerja' => getTahunKinerja(),
                'satuan_kerja_id' => $validated['satuan_kerja_id'],
                'pengampu_id' => $validated['pengampu_id'],
                'sasaran' => $validated['sasaran_created'],
            ]);

            $validated['sasaran_id'] = $sasaran->id;
        }

        if ($validated['is_edit_jf']) {
            /**
             * @var \Illuminate\Support\Collection
             */
            $existing = KamusIndikatorFungsionalPengampuJF::tahunKinerja()
                ->roleSatuanKerja($validated['satuan_kerja_id'])
                ->where('pengampu_id', $validated['pengampu_id'])
                ->get();

            $deleteids = $existing->whereNotIn('jabatan_id', $validated['jf'])->pluck('id');
            $insertJabatanIds = array_filter($validated['jf'], fn ($jabatanId) => ! in_array($jabatanId, $existing->pluck('jabatan_id')->toArray()));

            KamusIndikatorFungsionalPengampuJF::query()
                ->whereIn('id', $deleteids)
                ->delete();

            foreach ($insertJabatanIds as $jabatanId) {
                KamusIndikatorFungsionalPengampuJF::query()->create([
                    'tahun_kinerja' => getTahunKinerja(),
                    'satuan_kerja_id' => $validated['satuan_kerja_id'],
                    'pengampu_id' => $validated['pengampu_id'],
                    'jabatan_id' => $jabatanId,
                ]);
            }
        }

        if (isset($validated['id'])) {
            return $this->update($validated);
        }

        KamusIndikatorFungsionalManual::query()->create([
            'tahun_kinerja' => getTahunKinerja(),
            'satuan_kerja_id' => $validated['satuan_kerja_id'],
            'jenis_indikator' => $validated['jenis_indikator'],
            'sasaran_id' => $validated['sasaran_id'],
            'indikator' => $validated['indikator'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    private function update(array $data)
    {
        KamusIndikatorFungsionalManual::tahunKinerja()
            ->roleSatuanKerja($data['satuan_kerja_id'])
            ->where('id', $data['id'])
            ->update([
                'jenis_indikator' => $data['jenis_indikator'],
                'sasaran_id' => $data['sasaran_id'],
                'indikator' => $data['indikator'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function destroy(int $id)
    {
        KamusIndikatorFungsionalManual::tahunKinerja()
            ->where('id', $id)
            ->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
