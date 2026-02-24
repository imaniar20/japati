<?php

namespace App\Http\Controllers;

use App\Exports\VisiMisiRpjmdExport;
use App\Http\Requests\VisiMisiRpjmd\StoreVisiMisiRpjmd;
use App\Http\Requests\VisiMisiRpjmd\UpdateVisiMisiRpjmd;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\Role;
use App\Models\Visi;
use App\Models\VisiMisiRpjmd;
use App\Services\FilterQuery;
use App\Traits\SetdaResourceAccess;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Khusus Kinerja Makro di Sekretariat Daerah,
 * yang bisa olah data adalah akun `setda`, akun `biro` view-only,
 * `satuan_kerja_id` nya juga di set ke `setda`
 */
class VisiMisiRpjmdController extends Controller
{
    use SetdaResourceAccess;

    public function index(Request $request, bool $isExport = false)
    {
        $data = VisiMisiRpjmd::tahunMulai()->roleSatuanKerja($this->getSatkerSetdaBiro());

        FilterQuery::parseFilter($data, json_decode($request->filter, true));

        $data->with([
            'satuanKerja',
            'visi',
            'misi',
            'tujuan',
            'indikatorTujuan',
        ])
            ->withCount([
                'kinerjaTidakTercapai' => fn (Builder $query) => $query->tahunKinerja(),
            ])
            ->orderBy('updated_at');

        if ($isExport) {
            return $data->get();
        } else {
            $data = $data->paginate(20);
        }

        /**
         * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection $data
         */
        $queryFilter = fn (Builder $query) => $query
            ->where('sasaran_strategis_rpjmd.tahun_mulai', getTahunMulai())
            ->whereIn('target_visi_misi_rpjmd_id', $data->pluck('id'))
            ->where(fn (Builder $query2) => $query2
                ->whereNull('capaian')
                ->orWhere('capaian', '<', 100)
            );

        $tidakTercapai = KinerjaProgram::tahunKinerja()
            ->select('target_visi_misi_rpjmd_id')
            ->join('sasaran_strategis_pd', 'sasaran_strategis_pd_id', 'sasaran_strategis_pd.id')
            ->join('sasaran_strategis_rpjmd', 'sasaran_strategis_pd.sasaran_strategis_rpjmd_id', 'sasaran_strategis_rpjmd.id')
            ->where($queryFilter)
            ->union(KinerjaKegiatan::tahunKinerja()
                ->select('target_visi_misi_rpjmd_id')
                ->join('sasaran_strategis_pd', 'sasaran_strategis_pd_id', 'sasaran_strategis_pd.id')
                ->join('sasaran_strategis_rpjmd', 'sasaran_strategis_pd.sasaran_strategis_rpjmd_id', 'sasaran_strategis_rpjmd.id')
                ->where($queryFilter)
            )
            ->union(KinerjaSubKegiatan::tahunKinerja()
                ->select('target_visi_misi_rpjmd_id')
                ->join('sasaran_strategis_pd', 'sasaran_strategis_pd_id', 'sasaran_strategis_pd.id')
                ->join('sasaran_strategis_rpjmd', 'sasaran_strategis_pd.sasaran_strategis_rpjmd_id', 'sasaran_strategis_rpjmd.id')
                ->where($queryFilter)
            )
            ->pluck('target_visi_misi_rpjmd_id');

        /**
         * @var \Illuminate\Pagination\AbstractPaginator $data
         */
        $data->setCollection($data->getCollection()
            ->transform(function (VisiMisiRpjmd $item) use ($tidakTercapai) {
                $item->tercapai = ! $tidakTercapai->contains($item->id);

                return $item;
            })
        );

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorizeByRoles([Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);
        $this->restrictBiro();
        // return response()->json([getTahunMulai(), getTahunKinerja(), config("database.connections.pgsql")]);
        $visi = Visi::tahunMulai()->first();

        return response()->json(compact('visi'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVisiMisiRpjmd $request)
    {
        $this->authorizeBySatuanKerja($request->satuan_kerja_id, [Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);
        $this->restrictBiro();

        $data = $request->validated();
        $data['tahun_mulai'] = getTahunMulai();

        VisiMisiRpjmd::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambah data',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(VisiMisiRpjmd $visiMisiRpjmd)
    {
        try {
            $this->authorizeBySatuanKerjaExcSetdaBiro($visiMisiRpjmd->satuan_kerja_id);
        } catch (Exception $e) {
            if (! Role::isViewAll()) {
                throw $e;
            }
        }

        $visiMisiRpjmd->load([
            'satuanKerja',
            'visi',
            'misi',
            'tujuan',
            'indikatorTujuan',
        ]);

        return response()->json($visiMisiRpjmd);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(VisiMisiRpjmd $visiMisiRpjmd)
    {
        $this->authorizeBySatuanKerja($visiMisiRpjmd->satuan_kerja_id, [Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);

        $visi = Visi::tahunMulai()->first();
        $form = $visiMisiRpjmd;

        return response()->json(compact('visi', 'form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVisiMisiRpjmd $request, VisiMisiRpjmd $visiMisiRpjmd)
    {
        $this->authorizeBySatuanKerja($visiMisiRpjmd->satuan_kerja_id, [Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);

        $data = $request->validated();

        $visiMisiRpjmd->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menyimpan data',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(VisiMisiRpjmd $visiMisiRpjmd)
    {
        $this->authorizeBySatuanKerja($visiMisiRpjmd->satuan_kerja_id, [Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);

        $visiMisiRpjmd->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data',
        ]);
    }

    public function export(Request $request)
    {
        $data = $this->index($request, true);

        return Excel::download(new VisiMisiRpjmdExport($data), 'Visi, Misi & Tujuan RPJMD.xlsx');
    }
}
