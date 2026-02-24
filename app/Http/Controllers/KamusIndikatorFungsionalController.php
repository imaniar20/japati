<?php

namespace App\Http\Controllers;

use App\Models\KamusIndikatorFungsional;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\Role;
use App\Models\Simpeg\Jabatan;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KamusIndikatorFungsionalController extends Controller
{
    public function index(Request $request)
    {
        if (Role::isSuper()) {
            $satkerId = $request->get('satuan_kerja_id', 1030);
        } elseif (Role::isSetda()) {
            $satkerId = $request->get('satuan_kerja_id', 100103010000);
        } else {
            $satkerId = Auth::user()->satuan_kerja_id;
        }

        $kinerjaProgram = KinerjaProgram::tahunKinerja()
            ->roleSatuanKerja($satkerId)
            ->select('id', 'indikator')
            ->with([
                'kamusIndikatorFungsional' => fn (Builder $query) => $query->tahunKinerja(),
            ])
            ->get()
            ->transform(fn (KinerjaProgram $item) => [
                'id' => $item->id,
                'tipe' => 'Indikator Program',
                'class' => 'kinerja-program',
                'indikator' => $item->indikator,
                'kamus_indikator_fungsional' => $item->kamusIndikatorFungsional->first(),
            ]);

        $kinerjaKegiatan = KinerjaKegiatan::tahunKinerja()
            ->roleSatuanKerja($satkerId)
            ->select('id', 'indikator')
            ->with([
                'kamusIndikatorFungsional' => fn (Builder $query) => $query->tahunKinerja(),
            ])
            ->get()
            ->transform(fn (KinerjaKegiatan $item) => [
                'id' => $item->id,
                'tipe' => 'Indikator Kegiatan',
                'class' => 'kinerja-kegiatan',
                'indikator' => $item->indikator,
                'kamus_indikator_fungsional' => $item->kamusIndikatorFungsional->first(),
            ]);

        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()
            ->roleSatuanKerja($satkerId)
            ->select('id', 'indikator', 'indikator_kemendagri')
            ->with([
                'kamusIndikatorFungsional' => fn (Builder $query) => $query->tahunKinerja(),
            ])
            ->get()
            ->transform(fn (KinerjaSubKegiatan $item) => [
                'id' => $item->id,
                'tipe' => 'Indikator Sub Kegiatan',
                'class' => 'kinerja-sub-kegiatan',
                'indikator' => $item->indikator,
                'indikator_kemendagri' => $item->indikator_kemendagri,
                'kamus_indikator_fungsional' => $item->kamusIndikatorFungsional->first(),
            ]);

        $data = [
            ...$kinerjaProgram,
            ...$kinerjaKegiatan,
            ...$kinerjaSubKegiatan,
        ];

        $jabatanList = Jabatan::query()
            ->selectRaw("jabatan_id, CONCAT(jf_nama, ' - ', unit_kerja_nama) jf_nama")
            ->join('m_spg_referensi_jf', 'm_spg_referensi_jf.jf_id', 'm_spg_jabatan.jf_id')
            ->join('m_spg_unit_kerja', 'm_spg_unit_kerja.unit_kerja_id', 'm_spg_jabatan.unit_kerja_id')
            ->whereNotNull('m_spg_jabatan.jf_id')
            ->where('m_spg_jabatan.satuan_kerja_id', $satkerId)
            ->orderBy('jf_nama')
            ->get();

        return response()->json([
            'data' => $data,
            'jabatanList' => $jabatanList,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class' => ['required', Rule::in(['kinerja-program', 'kinerja-kegiatan', 'kinerja-sub-kegiatan'])],
            'id' => ['required', 'numeric'],
            'data' => ['required', 'array'],
            'data.pengampu' => ['nullable', 'string'],
            'data.jabatan_id' => ['nullable', 'numeric'],
            'data.keterangan' => ['nullable', 'string'],
        ]);

        switch ($validated['class']) {
            case 'kinerja-program':
                $class = KinerjaProgram::tahunKinerja();
                break;
            case 'kinerja-kegiatan':
                $class = KinerjaKegiatan::tahunKinerja();
                break;
            case 'kinerja-sub-kegiatan':
                $class = KinerjaSubKegiatan::tahunKinerja();
                break;
        }

        $model = $class->where('id', $validated['id'])->firstOrFail();
        $modelClass = get_class($model);
        $update = [
            'tahun_mulai' => getTahunMulai(),
            'satuan_kerja_id' => $model->satuan_kerja_id,
        ];

        if ($request->has('data.pengampu')) {
            $update['pengampu'] = $validated['data']['pengampu'];
        }
        if ($request->has('data.jabatan_id')) {
            $update['jabatan_id'] = $validated['data']['jabatan_id'];
        }
        if ($request->has('data.keterangan')) {
            $update['keterangan'] = $validated['data']['keterangan'];
        }

        KamusIndikatorFungsional::query()->updateOrCreate(
            [
                'tahun_kinerja' => $model->tahun_kinerja,
                'model_class' => $modelClass,
                'model_id' => $model->id,
            ],
            $update
        );

        return response()->json([
            'success' => true,
        ]);
    }
}
