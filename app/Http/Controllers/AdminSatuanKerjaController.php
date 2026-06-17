<?php

namespace App\Http\Controllers;

use App\Models\SatuanKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AdminSatuanKerjaController extends Controller
{
    public function index()
    {
        $data = SatuanKerja::query()
            ->orderBy('satuan_kerja_nama')
            ->orderBy('satuan_kerja_id')
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $this->validateSatuanKerja($request);

        $satuanKerja = SatuanKerja::query()->create($validated);

        return response()->json($satuanKerja, 201);
    }

    public function show(SatuanKerja $satuanKerja)
    {
        return response()->json($satuanKerja);
    }

    public function update(Request $request, SatuanKerja $satuanKerja)
    {
        $validated = $this->validateSatuanKerja($request, $satuanKerja);

        $satuanKerja->update($validated);

        return response()->json($satuanKerja);
    }

    public function destroy(SatuanKerja $satuanKerja)
    {
        $usage = $this->findUsage($satuanKerja);

        if ($usage) {
            return response()->json([
                'message' => "Satuan kerja tidak bisa dihapus karena sudah dipakai pada tabel {$usage}.",
            ], 400);
        }

        $satuanKerja->delete();

        return response()->json();
    }

    private function validateSatuanKerja(Request $request, ?SatuanKerja $satuanKerja = null): array
    {
        $idRule = Rule::unique('satuan_kerja', 'satuan_kerja_id');

        if ($satuanKerja) {
            $idRule->ignore($satuanKerja->satuan_kerja_id, 'satuan_kerja_id');
        }

        $rules = [
            'satuan_kerja_nama' => ['required', 'string', 'max:255'],
            'tahun_id' => ['nullable', 'integer'],
            'kode' => ['nullable', 'string', 'max:255'],
            'satuan_kerja_alamat' => ['nullable', 'string', 'max:255'],
            'satuan_kerja_kel_ds' => ['nullable', 'string', 'max:255'],
            'kecamatan_id' => ['nullable', 'integer'],
            'satuan_kerja_khusus' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'integer', Rule::in([0, 1])],
            'kode_skpd' => ['nullable', 'string', 'max:255'],
            'create_username' => ['nullable', 'string', 'max:255'],
            'update_username' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'kota' => ['nullable', 'string', 'max:255'],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'kelurahan' => ['nullable', 'string', 'max:255'],
            'satuan_kerja_nama_alias' => ['nullable', 'string', 'max:255'],
            'sapk_id' => ['nullable', 'string', 'max:255'],
            'bobot' => ['nullable', 'numeric'],
            'm_kabkot_id' => ['nullable', 'integer'],
            'rumpun_id' => ['nullable', 'integer'],
            'lampiran_no' => ['nullable', 'numeric'],
        ];

        if (! $satuanKerja) {
            $rules['satuan_kerja_id'] = ['required', 'integer', 'min:1', $idRule];
        }

        return $request->validate($rules);
    }

    private function findUsage(SatuanKerja $satuanKerja): ?string
    {
        foreach ($this->usageTables() as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'satuan_kerja_id')) {
                continue;
            }

            if (DB::table($table)->where('satuan_kerja_id', $satuanKerja->satuan_kerja_id)->exists()) {
                return $table;
            }
        }

        return null;
    }

    private function usageTables(): array
    {
        return [
            'users',
            'visi_misi_rpjmd',
            'sasaran_strategis_rpjmd',
            'sasaran_strategis_pd',
            'kinerja_program',
            'kinerja_kegiatan',
            'kinerja_sub_kegiatan',
            'kinerja_langkah_aksi',
            'kinerja_bayangan',
            'kinerja_program_cross',
            'kinerja_kegiatan_cross',
            'kinerja_sub_kegiatan_cross',
            'kinerja_sub_kegiatan_kab_kota',
            'lkip_narasi_pd',
            'link_perjanjian_kinerja',
            'validasi_perencanaan',
            'nilai_jenjang_kinerja',
            'anggaran_capaian_iku',
            'nilai_sakip_pemda',
            'perubahan_jumlah_output',
            'perubahan_jumlah_output_v2',
            'kamus_indikator_validasi_bappeda',
            'pohon_kinerja_raw',
            'pohon_kinerja_raw2',
            'pohon_kinerja',
        ];
    }
}
