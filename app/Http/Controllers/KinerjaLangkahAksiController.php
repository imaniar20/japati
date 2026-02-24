<?php

namespace App\Http\Controllers;

use App\Exports\KinerjaLangkahAksiExport;
use App\Http\Requests\KinerjaLangkahAksi\StoreKinerjaLangkahAksi;
use App\Http\Requests\KinerjaLangkahAksi\UpdateKinerjaLangkahAksi;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaLangkahAksi;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\PenyebabKegagalan;
use App\Models\Role;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use App\Models\SubKegiatan;
use App\Services\FilterQuery;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class KinerjaLangkahAksiController extends Controller
{
    public function index(Request $request, bool $isExport = false)
    {
        $data = KinerjaLangkahAksi::tahunKinerja()->roleSatuanKerja();
        $penyebabKegagalan = $request->penyebab_kegagalan_id;
        $filter = json_decode($request->filter, true);
        FilterQuery::parseFilter($data, $filter);

        $data->with([
            'satuanKerja',
            'subKegiatan' => fn ($query) => $query->tahunKinerja(),
        ])
            ->withCount([
                'kinerjaTidakTercapai' => fn (Builder $query) => $query->tahunKinerja(),
            ])
            ->when(isset($filter['sub_kegiatan_id']), fn (Builder $query) => $query->where('sub_kegiatan_id', $filter['sub_kegiatan_id']))
            ->when($penyebabKegagalan, fn (Builder $query) => $query->where('penyebab_kegagalan_id', $penyebabKegagalan))
            ->orderBy('updated_at');

        if ($isExport) {
            return $data->get();
        } else {
            $data = $data->paginate(20);
        }

        if ($penyebabKegagalan) {
            $penyebabKegagalan = PenyebabKegagalan::query()->findOrFail($penyebabKegagalan);
        }

        return response()->json(compact('data', 'penyebabKegagalan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $subKegiatan = [];
        $sasaranStrategisRpjmd = [];
        $sasaranStrategisPd = [];
        $kinerjaProgram = [];
        $kinerjaKegiatan = [];
        $kinerjaSubKegiatan = [];
        $penyebabKegagalan = $request->penyebab_kegagalan_id;

        if (! Role::isSuper() || (Role::isSuper() && $request->satuan_kerja_id)) {
            $satkerId = Role::isSuper() ? $request->satuan_kerja_id : Auth::user()->satuan_kerja_id;

            $sasaranStrategisRpjmd = SasaranStrategisRpjmd::tahunMulai()
                ->where('satuan_kerja_id', parseSatuanKerjaId($satkerId))
                ->with([
                    'sasaranStrategis',
                    'indikatorSasaranStrategis',
                ])
                ->get();

            $sasaranStrategisPd = SasaranStrategisPd::tahunMulai()->where('satuan_kerja_id', parseSatuanKerjaId($satkerId))->get();

            $kinerjaProgram = KinerjaProgram::tahunKinerja()
                ->where('satuan_kerja_id', $satkerId)
                ->with([
                    'program',
                ])
                ->get();

            $kinerjaKegiatan = KinerjaKegiatan::tahunKinerja()
                ->with([
                    'kegiatan',
                ])
                ->where('satuan_kerja_id', $satkerId)
                ->get();

            $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()
                ->where('satuan_kerja_id', $satkerId)
                ->with([
                    'subKegiatan',
                ])
                ->get();

            $subKegiatan = SubKegiatan::tahunKinerja()->whereIn('id', $kinerjaSubKegiatan->pluck('sub_kegiatan_id'))->get();

            if ($penyebabKegagalan) {
                $penyebabKegagalan = PenyebabKegagalan::query()->findOrFail($penyebabKegagalan);
                $penyebabKegagalan->load('kinerjaSubKegiatan:id,sasaran_strategis_rpjmd_id,sasaran_strategis_pd_id,kinerja_program_id,kinerja_kegiatan_id,sub_kegiatan_id');
            }
        }

        return response()->json(compact('subKegiatan', 'sasaranStrategisRpjmd', 'sasaranStrategisPd', 'kinerjaProgram', 'kinerjaKegiatan', 'kinerjaSubKegiatan', 'penyebabKegagalan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKinerjaLangkahAksi $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $data = $request->validated();
        $data['tahun_kinerja'] = getTahunKinerja();

        KinerjaLangkahAksi::create($data);

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
    public function show(KinerjaLangkahAksi $kinerjaLangkahAksi)
    {
        try {
            $this->authorizeBySatuanKerja($kinerjaLangkahAksi->satuan_kerja_id);
        } catch (Exception $e) {
            if (! Role::isViewAll()) {
                throw $e;
            }
        }

        $kinerjaLangkahAksi->load([
            'satuanKerja',
            'subKegiatan' => fn ($query) => $query->tahunKinerja(),
        ]);

        return response()->json($kinerjaLangkahAksi);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(KinerjaLangkahAksi $kinerjaLangkahAksi)
    {
        $this->authorizeBySatuanKerja($kinerjaLangkahAksi->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH]);

        $form = $kinerjaLangkahAksi->load(['satuanKerja', 'solusi']);
        $satkerId = $form->satuan_kerja_id;

        $sasaranStrategisRpjmd = SasaranStrategisRpjmd::tahunMulai()
            ->where('satuan_kerja_id', parseSatuanKerjaId($satkerId))
            ->with([
                'sasaranStrategis',
                'indikatorSasaranStrategis',
            ])
            ->get();

        $sasaranStrategisPd = SasaranStrategisPd::tahunMulai()->where('satuan_kerja_id', parseSatuanKerjaId($satkerId))->get();

        $kinerjaProgram = KinerjaProgram::tahunKinerja()
            ->where('satuan_kerja_id', $satkerId)
            ->with([
                'program',
            ])
            ->get();

        $kinerjaKegiatan = KinerjaKegiatan::tahunKinerja()
            ->with([
                'kegiatan',
            ])
            ->where('satuan_kerja_id', $satkerId)
            ->get();

        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()
            ->where('satuan_kerja_id', $satkerId)
            ->with([
                'subKegiatan',
            ])
            ->get();

        $subKegiatan = SubKegiatan::tahunKinerja()->whereIn('id', $kinerjaSubKegiatan->pluck('sub_kegiatan_id'))->get();

        return response()->json(compact('subKegiatan', 'sasaranStrategisRpjmd', 'sasaranStrategisPd', 'kinerjaProgram', 'kinerjaKegiatan', 'kinerjaSubKegiatan', 'form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateKinerjaLangkahAksi $request, KinerjaLangkahAksi $kinerjaLangkahAksi)
    {
        $this->authorizeBySatuanKerja($kinerjaLangkahAksi->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH]);

        $data = $request->validated();

        // penyebab kegagalan hanya untuk capaian dibawah 100
        if ($data['capaian'] >= 100) {
            $data['penyebab_kegagalan'] = null;
        }

        $kinerjaLangkahAksi->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambah data',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(KinerjaLangkahAksi $kinerjaLangkahAksi)
    {
        $this->authorizeBySatuanKerja($kinerjaLangkahAksi->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH]);

        $kinerjaLangkahAksi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data',
        ]);
    }

    public function export(Request $request)
    {
        $data = $this->index($request, true);

        return Excel::download(new KinerjaLangkahAksiExport($data), 'Kinerja Langkah Aksi.xlsx');
    }
}
