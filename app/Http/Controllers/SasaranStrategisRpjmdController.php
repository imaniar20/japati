<?php

namespace App\Http\Controllers;

use App\Exports\SasaranStrategisRpjmdExport;
use App\Http\Requests\SasaranStrategisRpjmd\StoreSasaranStrategisRpjmd;
use App\Http\Requests\SasaranStrategisRpjmd\UpdateSasaranStrategisRpjmd;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\Role;
use App\Models\SasaranStrategisRpjmd;
use App\Models\VisiMisiRpjmd;
use App\Services\FilterQuery;
use App\Traits\SetdaResourceAccess;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Khusus Kinerja Makro di Sekretariat Daerah,
 * yang bisa olah data adalah akun `setda`, akun `biro` view-only,
 * `satuan_kerja_id` nya juga di set ke `setda`
 */
class SasaranStrategisRpjmdController extends Controller
{
    use SetdaResourceAccess;

    public function index(Request $request, bool $isExport = false)
    {
        $data = SasaranStrategisRpjmd::tahunMulai()->roleSatuanKerja($this->getSatkerSetdaBiro());

        FilterQuery::parseFilter($data, json_decode($request->filter, true));

        $data->with([
            'satuanKerja',
            'misi',
            'tujuan',
            'indikatorTujuan',
            'sasaranStrategis',
            'indikatorSasaranStrategis',
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
            ->whereIn('sasaran_strategis_rpjmd.id', $data->pluck('id'))
            ->where(fn (Builder $query2) => $query2
                ->whereNull('capaian')
                ->orWhere('capaian', '<', 100)
            );

        $tidakTercapai = KinerjaProgram::tahunKinerja()
            ->select('sasaran_strategis_rpjmd.id')
            ->join('sasaran_strategis_pd', 'sasaran_strategis_pd_id', 'sasaran_strategis_pd.id')
            ->join('sasaran_strategis_rpjmd', 'sasaran_strategis_pd.sasaran_strategis_rpjmd_id', 'sasaran_strategis_rpjmd.id')
            ->where($queryFilter)
            ->union(KinerjaKegiatan::tahunKinerja()
                ->select('sasaran_strategis_rpjmd.id')
                ->join('sasaran_strategis_pd', 'sasaran_strategis_pd_id', 'sasaran_strategis_pd.id')
                ->join('sasaran_strategis_rpjmd', 'sasaran_strategis_pd.sasaran_strategis_rpjmd_id', 'sasaran_strategis_rpjmd.id')
                ->where($queryFilter)
            )
            ->union(KinerjaSubKegiatan::tahunKinerja()
                ->select('sasaran_strategis_rpjmd.id')
                ->join('sasaran_strategis_pd', 'sasaran_strategis_pd_id', 'sasaran_strategis_pd.id')
                ->join('sasaran_strategis_rpjmd', 'sasaran_strategis_pd.sasaran_strategis_rpjmd_id', 'sasaran_strategis_rpjmd.id')
                ->where($queryFilter)
            )
            ->pluck('id');

        /**
         * @var \Illuminate\Pagination\AbstractPaginator $data
         */
        $data->setCollection($data->getCollection()
            ->transform(function (SasaranStrategisRpjmd $item) use ($tidakTercapai) {
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

        $visiMisiRpjmd = VisiMisiRpjmd::tahunMulai()
            ->select('id', 'misi_id', 'tujuan_id', 'indikator_tujuan_id', 'tahun_mulai', 'target_1', 'target_2', 'target_3', 'target_4', 'target_5')
            ->when(! Role::isSuper(), function ($query) {
                $query->where('satuan_kerja_id', Auth::user()->satuan_kerja_id);
            })
            ->get();

        return response()->json(compact('visiMisiRpjmd'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSasaranStrategisRpjmd $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);
        $this->restrictBiro();

        $data = $request->validated();
        $data['tahun_mulai'] = getTahunMulai();

        SasaranStrategisRpjmd::create($data);

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
    public function show(SasaranStrategisRpjmd $sasaranStrategisRpjmd)
    {
        try {
            $this->authorizeBySatuanKerjaExcSetdaBiro($sasaranStrategisRpjmd->satuan_kerja_id);
        } catch (Exception $e) {
            if (! Role::isViewAll()) {
                throw $e;
            }
        }

        $sasaranStrategisRpjmd->load([
            'satuanKerja',
            'sasaranStrategis',
            'indikatorSasaranStrategis',
        ]);

        return response()->json($sasaranStrategisRpjmd);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(SasaranStrategisRpjmd $sasaranStrategisRpjmd)
    {
        $this->authorizeBySatuanKerja($sasaranStrategisRpjmd->satuan_kerja_id, [Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);

        $form = $sasaranStrategisRpjmd;

        $visiMisiRpjmd = VisiMisiRpjmd::tahunMulai()
            ->select('id', 'misi_id', 'tujuan_id', 'indikator_tujuan_id', 'tahun_mulai', 'target_1', 'target_2', 'target_3', 'target_4', 'target_5')
            ->when(! Role::isSuper(), function ($query) {
                $query->where('satuan_kerja_id', Auth::user()->satuan_kerja_id);
            })
            ->get();

        return response()->json(compact('form', 'visiMisiRpjmd'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSasaranStrategisRpjmd $request, SasaranStrategisRpjmd $sasaranStrategisRpjmd)
    {
        $this->authorizeBySatuanKerja($sasaranStrategisRpjmd->satuan_kerja_id, [Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);

        $data = $request->validated();

        $columns = ['baseline'];

        for ($i = 1; $i <= 5; $i++) {
            $columns[] = $i;
        }

        // penyebab kegagalan hanya untuk capaian dibawah 100
        foreach ($columns as $column) {
            if ($data["capaian_{$column}"] >= 100) {
                $data["penyebab_kegagalan_{$column}"] = null;
            }
        }

        $sasaranStrategisRpjmd->update($data);

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
    public function destroy(SasaranStrategisRpjmd $sasaranStrategisRpjmd)
    {
        $this->authorizeBySatuanKerja($sasaranStrategisRpjmd->satuan_kerja_id, [Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);

        $sasaranStrategisRpjmd->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data',
        ]);
    }

    public function export(Request $request)
    {
        $data = $this->index($request, true);

        return Excel::download(new SasaranStrategisRpjmdExport($data), 'Sasaran Strategis RPJMD & IKU Bupati.xlsx');
    }
}
