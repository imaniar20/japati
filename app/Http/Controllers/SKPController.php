<?php

namespace App\Http\Controllers;

use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\RiwayatValidasiSKP;
use App\Models\SasaranStrategisPd;
use App\Models\SKP;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SKPController extends Controller
{
    public function validateSkp(Request $request)
    {
        $validated = $request->validate([
            'class' => ['required', 'string', Rule::in(['sasaran-strategis-pd', 'kinerja-program', 'kinerja-kegiatan', 'kinerja-sub-kegiatan'])],
            'id' => ['required', 'numeric'],
            'keterangan' => ['nullable', 'string'],
        ]);

        switch ($validated['class']) {
            case 'sasaran-strategis-pd':
                $class = SasaranStrategisPd::tahunMulai();
                break;
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

        if ($modelClass == SasaranStrategisPd::class) {
            $pengampu = null;
            $vsoId = null;
            $timKerjaId = null;
            $sasaran = $model->sasaran_strategis_satker;
            $indikator = $model->iku;
            $target = $model[getKeyTahun('target')];
        } else {
            $pengampu = $model->pengampu;
            $vsoId = $pengampu == 'unit-kerja' ? $model->v_struktur_organisasi_id : null;
            $timKerjaId = $pengampu == 'tim-kerja' ? $model->tim_kerja_id : null;
            $sasaran = $model->sasaran;
            $indikator = $model->indikator;
            $target = $model->target;
        }

        $parentId = null;
        $parentClass = null;

        switch ($validated['class']) {
            case 'sasaran-strategis-pd':
                break;
            case 'kinerja-program':
                $parentClass = SasaranStrategisPd::class;
                $parentId = $model->sasaran_strategis_pd_id;
                break;
            case 'kinerja-kegiatan':
                $parentClass = KinerjaProgram::class;
                $parentId = $model->kinerja_program_id;
                break;
            case 'kinerja-sub-kegiatan':
                $parentClass = KinerjaKegiatan::class;
                $parentId = $model->kinerja_kegiatan_id;
                break;
        }

        if ($parentClass && $parentId) {
            $parentId = SKP::query()
                ->where('model_class', $parentClass)
                ->where('model_id', $parentId)
                ->value('id');
        }

        SKP::query()->updateOrCreate(
            [
                'tahun_kinerja' => getTahunKinerja(),
                'model_class' => $modelClass,
                'model_id' => $model->id,
            ],
            [
                'tahun_mulai' => getTahunMulai(),
                'satuan_kerja_id' => $model->satuan_kerja_id,
                'pengampu' => $pengampu,
                'v_struktur_organisasi_id' => $vsoId,
                'tim_kerja_id' => $timKerjaId,
                'sasaran' => $sasaran,
                'indikator' => $indikator,
                'target' => $target,
                'satuan' => $model->satuan,
                'parent_id' => $parentId,
            ]
        );

        RiwayatValidasiSKP::query()->create([
            'tahun_kinerja' => getTahunKinerja(),
            'model_class' => $modelClass,
            'model_id' => $model->id,
            'tahun_mulai' => getTahunMulai(),
            'satuan_kerja_id' => $model->satuan_kerja_id,
            'pengampu' => $pengampu,
            'v_struktur_organisasi_id' => $vsoId,
            'tim_kerja_id' => $timKerjaId,
            'status' => RiwayatValidasiSKP::STATUS_VALIDATED,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function reject(Request $request)
    {
        $validated = $request->validate([
            'class' => ['required', 'string', Rule::in(['sasaran-strategis-pd', 'kinerja-program', 'kinerja-kegiatan', 'kinerja-sub-kegiatan'])],
            'id' => ['required', 'numeric'],
            'keterangan' => ['required', 'string'],
        ]);

        switch ($validated['class']) {
            case 'sasaran-strategis-pd':
                $class = SasaranStrategisPd::tahunMulai();
                break;
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

        if ($modelClass == SasaranStrategisPd::class) {
            $pengampu = null;
            $vsoId = null;
            $timKerjaId = null;
        } else {
            $pengampu = $model->pengampu;
            $vsoId = $pengampu == 'unit-kerja' ? $model->v_struktur_organisasi_id : null;
            $timKerjaId = $pengampu == 'tim-kerja' ? $model->tim_kerja_id : null;
        }

        RiwayatValidasiSKP::query()->create([
            'tahun_kinerja' => getTahunKinerja(),
            'model_class' => $modelClass,
            'model_id' => $model->id,
            'tahun_mulai' => getTahunMulai(),
            'satuan_kerja_id' => $model->satuan_kerja_id,
            'pengampu' => $pengampu,
            'v_struktur_organisasi_id' => $vsoId,
            'tim_kerja_id' => $timKerjaId,
            'status' => RiwayatValidasiSKP::STATUS_REJECTED,
            'keterangan' => $validated['keterangan'],
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function set(SKP $skp)
    {
        $skp->update([
            'is_skp' => true,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function unset(SKP $skp)
    {
        $skp->update([
            'is_skp' => false,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
