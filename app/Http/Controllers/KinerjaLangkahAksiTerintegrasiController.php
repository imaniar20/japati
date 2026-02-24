<?php

namespace App\Http\Controllers;

use App\Models\Ekinerja\IkiBulanan;
use App\Models\KinerjaSubKegiatan;
use App\Models\SKP;
use App\Services\FilterQuery;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class KinerjaLangkahAksiTerintegrasiController extends Controller
{
    public function index(Request $request)
    {
        $filter = json_decode($request->filter, true);

        $skpIds = SKP::tahunKinerja()
            ->roleSatuanKerja()
            ->where('model_class', KinerjaSubKegiatan::class)
            ->when($filter['sasaran_sub_kegiatan'] ?? null, fn (Builder $query, string $sasaran) => $query->where('sasaran', $sasaran))
            ->where('is_skp', true);

        FilterQuery::parseFilter($skpIds, $filter);

        $skpIds = $skpIds->pluck('id');

        $data = IkiBulanan::query()
            ->selectRaw("sasaran_kinerja_id, json_agg(json_build_object('indikator_langkah_aksi', indikator_bulanan, 'bulan', bulan, 'target', target, 'satuan', satuan_target, 'realisasi', realisasi)) AS data")
            ->with([
                'sasaranKinerja:id,sakip_id,sakip_type',

                'sasaranKinerja.skp:id,sasaran,parent_id,model_class,model_id,satuan_kerja_id', // SKP KinerjaSubKegiatan
                'sasaranKinerja.skp.skp:id,sub_kegiatan_id', // KinerjaSubKegiatan
                'sasaranKinerja.skp.skp.subKegiatan:id,nama', // SubKegiatan

                'sasaranKinerja.skp.parent:id,sasaran,indikator,parent_id,model_class,model_id', // SKP KinerjaKegiatan
                'sasaranKinerja.skp.parent.skp:id,kegiatan_id', // KinerjaKegiatan
                'sasaranKinerja.skp.parent.skp.kegiatan:id,nama', // Kegiatan

                'sasaranKinerja.skp.parent.parent:id,sasaran,indikator,parent_id,model_class,model_id', // SKP KinerjaProgram
                'sasaranKinerja.skp.parent.parent.skp:id,program_id', // KinerjaProgram
                'sasaranKinerja.skp.parent.parent.skp.program:id,nama', // Program

                'sasaranKinerja.skp.satuanKerja:satuan_kerja_id,satuan_kerja_nama', // SatuanKerja
            ])
            ->whereHas('sasaranKinerja', fn (Builder $query) => $query
                ->whereIn('sakip_id', $skpIds)
                ->where('sakip_type', 'App\Model\Sakip\KinerjaSubKegiatan')
            )
            ->groupBy('sasaran_kinerja_id')
            ->paginate(20);

        /**
         * @var \Illuminate\Pagination\AbstractPaginator $data
         */
        $data->setCollection($data->getCollection()->transform(fn (IkiBulanan $item) => [
            'satuan_kerja' => $item->sasaranKinerja?->skp?->satuanKerja->satuan_kerja_nama,
            'data' => collect(json_decode($item->data, true))->sortBy('bulan')->values(),
            'sasaran_sub_kegiatan' => $item->sasaranKinerja?->skp?->sasaran,
            'sub_kegiatan' => $item->sasaranKinerja?->skp?->skp?->subKegiatan?->nama,
            'kegiatan' => $item->sasaranKinerja?->skp?->parent?->skp?->kegiatan?->nama,
            'program' => $item->sasaranKinerja?->skp?->parent?->parent?->skp?->program?->nama,
        ]));

        return response()->json($data);
    }
}
