<?php

namespace App\Http\Controllers;

use App\Models\KinerjaSubKegiatan;
use App\Models\Role;
use App\Models\SatuanKerja;
use App\Models\ValidasiPerencanaan;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiPerencanaanController extends Controller
{
    const USER_TAHAP1_MAP = [
        'validator_perencanaan_ani_sri_mulyani' => [1003, 1022, 1043, 1045],
        'validator_perencanaan_dewi_nurhayati' => [1010, 1012, 1034],
        'validator_perencanaan_dwi_astuti_ruhayati' => [1008, 1011, 1013],
        'validator_perencanaan_firman_firdaus_sendjaya' => [1005, 1006, 1030, 1035, 1042],
        'validator_perencanaan_heni_fajria_rifati' => [1007, 1015],
        'validator_perencanaan_hermi_harini' => [1023, 1026, 1040, 100101010000, 100101020000, 100101030000, 100102030000, 100103010000, 100103020000, 100103030000],
        'validator_perencanaan_latief' => [1002, 1024, 1027, 1031],
        'validator_perencanaan_nenden_suwardini' => [1016, 100102020000],
        'validator_perencanaan_reni_marlina' => [1009, 1018, 100102010000],
        'validator_perencanaan_reny_welyindra_k' => [1004, 1021, 1025, 1041, 1080],
        'validator_perencanaan_sri_endang_wijayanti' => [1017, 1020],
        'validator_perencanaan_sutrisno' => [1014, 1019, 1046],
    ];

    public function status()
    {
        $data = ValidasiPerencanaan::tahunKinerja()
            ->roleSatuanKerja()
            ->first();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function submit()
    {
        $validasi = ValidasiPerencanaan::tahunKinerja()
            ->roleSatuanKerja()
            ->first();

        if ($validasi) {
            $validasi->update([
                'status' => null,
                'catatan' => null,
            ]);
        } else {
            ValidasiPerencanaan::query()->create([
                'satuan_kerja_id' => Auth::user()->satuan_kerja_id,
                'tahun_kinerja' => getTahunKinerja(),
                'tahap' => 1,
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function opd()
    {
        $satker = SatuanKerja::query()
            ->select('satuan_kerja_id', 'satuan_kerja_nama')
            ->when(Role::isValidatorPerencanaan1(), fn (Builder $query) => $query->whereIn('satuan_kerja_id', self::USER_TAHAP1_MAP[Auth::user()->username]))
            ->get();

        $data = ValidasiPerencanaan::tahunKinerja()
            ->when(Role::isValidatorPerencanaan1(), fn (Builder $query) => $query->whereIn('satuan_kerja_id', self::USER_TAHAP1_MAP[Auth::user()->username]))
            ->get()
            ->keyBy('satuan_kerja_id');

        $satker->transform(function (SatuanKerja $item) use ($data) {
            $item->validasi_perencanaan = $data[$item->satuan_kerja_id] ?? null;

            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $satker,
        ]);
    }

    public function statusValidasiOpd(int $satkerId)
    {
        $data = ValidasiPerencanaan::tahunKinerja()
            ->roleSatuanKerja($satkerId)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function data(int $satkerId)
    {
        if (Role::isValidatorPerencanaan1() && ! in_array($satkerId, self::USER_TAHAP1_MAP[Auth::user()->username])) {
            abort(403, 'Anda tidak memiliki hak akses untuk satuan kerja ini');
        }

        $data = KinerjaSubKegiatan::tahunKinerja()
            ->roleSatuanKerja($satkerId)
            ->select('id', 'sasaran', 'indikator', 'sub_kegiatan_id', 'sasaran_strategis_pd_id', 'kinerja_program_id', 'kinerja_kegiatan_id', 'anggaran', 'pengampu', 'v_struktur_organisasi_id', 'tim_kerja_id')
            ->with([
                'subKegiatan:id,nama',
                'strukturOrganisasi:id,jabatan_nama',
                'timKerja:id,nama,nip_ketua',
                'timKerja.ketua:peg_nip,peg_nama',

                'kinerjaKegiatan:id,kinerja_program_id,sasaran,indikator,kegiatan_id,anggaran,pengampu,v_struktur_organisasi_id,tim_kerja_id',
                'kinerjaKegiatan.kegiatan:id,nama',
                'kinerjaKegiatan.strukturOrganisasi:id,jabatan_id',
                'kinerjaKegiatan.timKerja:id,nama,nip_ketua',
                'kinerjaKegiatan.timKerja.ketua:peg_nip,peg_nama',

                'kinerjaKegiatan.kinerjaProgram:id,sasaran_strategis_pd_id,sasaran,indikator,program_id,anggaran,pengampu,v_struktur_organisasi_id,tim_kerja_id',
                'kinerjaKegiatan.kinerjaProgram.program:id,nama',
                'kinerjaKegiatan.kinerjaProgram.strukturOrganisasi:id,jabatan_id',
                'kinerjaKegiatan.kinerjaProgram.timKerja:id,nama,nip_ketua',
                'kinerjaKegiatan.kinerjaProgram.timKerja.ketua:peg_nip,peg_nama',

                'kinerjaKegiatan.kinerjaProgram.sasaranStrategisPd:id,sasaran_strategis_rpjmd_id,sasaran_strategis_satker,iku',

                'kinerjaKegiatan.kinerjaProgram.sasaranStrategisPd.sasaranStrategisRpjmd:id,sasaran_strategis_id,indikator_sasaran_strategis_id',
                'kinerjaKegiatan.kinerjaProgram.sasaranStrategisPd.sasaranStrategisRpjmd.sasaranStrategis:id,sasaran',
                'kinerjaKegiatan.kinerjaProgram.sasaranStrategisPd.sasaranStrategisRpjmd.indikatorSasaranStrategis:id,indikator',
            ])
            ->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function validasi(int $satkerId, Request $request)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
            'catatan' => ['nullable', 'string'],
        ]);

        if (Role::isValidatorPerencanaan1() && ! in_array($satkerId, self::USER_TAHAP1_MAP[Auth::user()->username])) {
            abort(403);
        }

        $data = [
            'status' => $validated['status'],
            'catatan' => $validated['catatan'],
        ];

        if ($data['status']) {
            // tahap naik 1
            if (Role::isValidatorPerencanaan1()) {
                $data['tahap'] = 2;
            } elseif (Role::isValidatorPerencanaan2()) {
                $data['tahap'] = 3;
            }

            // jika bukan tahap 3 maka reset status
            if (! Role::isValidatorPerencanaan3()) {
                $data['status'] = null;
            }

            // catatan set null
            $data['catatan'] = null;
        }

        ValidasiPerencanaan::tahunKinerja()
            ->roleSatuanKerja($satkerId)
            ->update($data);

        return response()->json([
            'success' => true,
        ]);
    }
}
