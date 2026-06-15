<?php

namespace App\Http\Controllers;

use App\Models\Ekinerja\TimKerja;
use App\Models\IndikatorSasaranStrategis;
use App\Models\IndikatorTujuan;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaLangkahAksi;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\KinerjaSubKegiatanKabKota;
use App\Models\Misi;
use App\Models\PohonKinerja;
use App\Models\SasaranStrategis;
use App\Models\SatuanKerja;
use App\Models\SubKegiatan;
use App\Models\Tujuan;
use App\Models\Visi;
use App\Models\VStrukturOrganisasi;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OptionController extends Controller
{
    public function visi(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['nullable', 'array'],
            'ids.*' => ['integer'],
        ]);

        $ids = $validated['ids'] ?? null;

        $visi = Visi::tahunMulai()
            ->orderBy('id')
            ->when($ids, fn ($query) => $query->whereIn('id', $ids))
            ->get();

        return response()->json($visi);
    }

    public function misi(Request $request)
    {
        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
        ]));

        $validated = $request->validate([
            'ids' => ['nullable', 'array'],
            'ids.*' => ['integer'],
            'visi_id' => ['nullable', 'integer'],
        ]);

        $ids = $validated['ids'] ?? null;
        $visiId = $validated['visi_id'] ?? null;

        $misi = Misi::tahunMulai()
            ->orderBy('nomor')
            ->when($visiId, fn ($query) => $query->where('visi_id', $visiId))
            ->when($ids, fn ($query) => $query->whereIn('id', $ids))
            ->get();

        return response()->json($misi);
    }

    public function tujuan(Request $request)
    {
        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
        ]));

        $validated = $request->validate([
            'ids' => ['nullable', 'array'],
            'ids.*' => ['integer'],
            'misi_id' => ['nullable', 'integer'],
        ]);

        $ids = $validated['ids'] ?? null;
        $misiId = $validated['misi_id'] ?? null;

        $tujuan = Tujuan::tahunMulai()
            ->orderBy('nomor')
            ->when($misiId, fn ($query) => $query->where('misi_id', $misiId))
            ->when($ids, fn ($query) => $query->whereIn('id', $ids))
            ->get();

        return response()->json($tujuan);
    }

    public function indikatorTujuan(Request $request)
    {
        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
        ]));

        $validated = $request->validate([
            'ids' => ['nullable', 'array'],
            'ids.*' => ['integer'],
            'tujuan_id' => ['nullable', 'integer'],
        ]);

        $ids = $validated['ids'] ?? null;
        $tujuanId = $validated['tujuan_id'] ?? null;

        $indikator = IndikatorTujuan::tahunMulai()
            ->orderBy('nomor')
            ->when($tujuanId, fn ($query) => $query->where('tujuan_id', $tujuanId))
            ->when($ids, fn ($query) => $query->whereIn('id', $ids))
            ->get();

        return response()->json($indikator);
    }

    public function satuanKerja(Request $request)
    {
        $hasSatkerId = $request->satuan_kerja_id && $request->satuan_kerja_id != 'null';
        $satkerIds = $request->get('satuan_kerja_ids', []);

        $satker = SatuanKerja::query()
            ->select('satuan_kerja_id', 'satuan_kerja_nama')
            ->when($hasSatkerId, function ($query) use ($request) {
                $query->whereIn('satuan_kerja_id', getSatuanKerjaIds($request->satuan_kerja_id));
            })
            ->when(count($satkerIds), fn (Builder $query) => $query->whereIn('satuan_kerja_id', $satkerIds)) // TODO, mapping dengan getSatuanKerjaIds()
            ->get();

        if ($request->with_default_option == 'true') {
            $satker = $satker->prepend([
                'satuan_kerja_id' => null,
                'satuan_kerja_nama' => 'Semua Satuan Kerja',
            ]);
        }

        return response()->json($satker);
    }

    public function sasaranPohonKinerja(Request $request)
    {
        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
        ]));

        $sasaran = PohonKinerja::whereNull('parent_id')
            ->get();

        return response()->json($sasaran);
    }

    public function sasaranStrategis(Request $request)
    {
        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
        ]));

        $validated = $request->validate([
            'ids' => ['nullable', 'array'],
            'ids.*' => ['integer'],
        ]);

        $ids = $validated['ids'] ?? null;

        $sasaran = SasaranStrategis::tahunMulai()
            ->orderBy('nomor')
            ->when($ids, fn ($query) => $query->whereIn('id', $ids))
            ->get();

        return response()->json($sasaran);
    }

    public function indikatorSasaranStrategis(Request $request)
    {
        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
        ]));

        $validated = $request->validate([
            'ids' => ['nullable', 'array'],
            'ids.*' => ['integer'],
        ]);

        $ids = $validated['ids'] ?? null;

        $indikator = IndikatorSasaranStrategis::tahunMulai()
            ->orderBy('nomor')
            ->when($ids, fn ($query) => $query->whereIn('id', $ids))
            ->get();

        return response()->json($indikator);
    }

    public function sasaranKinerja($model)
    {
        $validator = Validator::make([
            'model' => $model,
        ], [
            'model' => ['required', 'in:kinerja-program,kinerja-kegiatan,kinerja-sub-kegiatan,kinerja-langkah-aksi,kinerja-sub-kegiatan-kab-kota'],
        ]);

        $validator->validate();

        $data = $this->getSasaranOrIndikatorKinerja($model, 'sasaran');

        return response()->json($data);
    }

    public function indikatorKinerja($model)
    {
        $validator = Validator::make([
            'model' => $model,
        ], [
            'model' => ['required', 'in:kinerja-program,kinerja-kegiatan,kinerja-sub-kegiatan,kinerja-langkah-aksi,kinerja-sub-kegiatan-kab-kota'],
        ]);

        $validator->validate();

        $data = $this->getSasaranOrIndikatorKinerja($model, 'indikator');

        return response()->json($data);
    }

    private function getSasaranOrIndikatorKinerja(string $model, string $column): Collection
    {
        switch ($model) {
            case 'kinerja-program':
                $model = KinerjaProgram::class;
                break;
            case 'kinerja-kegiatan':
                $model = KinerjaKegiatan::class;
                break;
            case 'kinerja-sub-kegiatan':
                $model = KinerjaSubKegiatan::class;
                break;
            case 'kinerja-sub-kegiatan-kab-kota':
                $model = KinerjaSubKegiatanKabKota::class;
                break;
            case 'kinerja-langkah-aksi':
                $model = KinerjaLangkahAksi::class;
                break;
        }

        $data = $model::tahunKinerja()
            ->roleSatuanKerja(request('satuan_kerja_id'))// TODO: pakai parameter jangan ambil dari request langsung
            ->selectRaw("DISTINCT {$column}")
            ->whereNotNull($column)
            ->orderBy($column)
            ->pluck($column);

        return $data;
    }

    public function unitKerja($satuanKerjaId)
    {
        $data = VStrukturOrganisasi::select('id', 'unit_kerja_nama', 'level')
            ->when(isBiro($satuanKerjaId),
                fn (Builder $query) => $query->where('lv2_unit_kerja_id', $satuanKerjaId),
                fn (Builder $query) => $query->whereIn('satuan_kerja_id', getSatuanKerjaIds($satuanKerjaId)),
            )
            ->where('level', '>', 0)
            ->orderBy('id')
            ->get()
            ->transform(function ($item) {
                $indent = function ($vso) {
                    if (! $vso->level) {
                        return $vso->unit_kerja_nama;
                    }

                    $dash = '';

                    for ($i = 1; $i <= $vso->level; $i++) {
                        $dash .= '--';
                    }

                    return $dash.$vso->unit_kerja_nama;
                };

                return [
                    'id' => $item->id,
                    'unit_kerja_nama' => $indent($item),
                ];
            });

        return response()->json($data);
    }

    public function subKegiatan($satuanKerjaId)
    {
        $data = SubKegiatan::tahunKinerja()
            ->select('id', 'nama')
            ->whereHas('kinerjaSubKegiatan', fn (Builder $query) => $query->tahunKinerja()
                ->whereIn('satuan_kerja_id', getSatuanKerjaIds($satuanKerjaId))
            )
            ->get();

        return response()->json($data);
    }

    public function pengampuTimKerja(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        $data = TimKerja::query()
            ->select('id', 'nama', 'nip_ketua')
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->with('ketua:peg_nip,peg_nama')
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    public function pengampuUnitKerja(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
            'type' => ['required', 'string', Rule::in(['kinerja-program', 'kinerja-kegiatan', 'kinerja-sub-kegiatan'])],
        ]);

        $satkerId = $validated['satuan_kerja_id'];

        $data = match ($validated['type']) {
            'kinerja-program' => KinerjaProgramController::getPengampuUnitKerja($satkerId),
            'kinerja-kegiatan' => KinerjaKegiatanController::getPengampuUnitKerja($satkerId),
            'kinerja-sub-kegiatan' => KinerjaSubKegiatanController::getPengampuUnitKerja($satkerId),
        };

        return response()->json($data);
    }
}
