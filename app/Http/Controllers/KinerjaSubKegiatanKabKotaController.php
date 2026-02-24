<?php

namespace App\Http\Controllers;

use App\Http\Requests\KinerjaSubKegiatan\StoreKinerjaSubKegiatan;
use App\Http\Requests\KinerjaSubKegiatan\UpdateKinerjaSubKegiatan;
use App\Models\Ekinerja\TimKerja;
use App\Models\Kegiatan;
use App\Models\KinerjaKabKota;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\KinerjaSubKegiatanKabKota;
use App\Models\Role;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use App\Models\SubKegiatan;
use App\Models\VStrukturOrganisasi;
use App\Services\FilterQuery;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KinerjaSubKegiatanKabKotaController extends Controller
{
    public function index(Request $request, bool $isExport = false)
    {
        $data = KinerjaSubKegiatanKabKota::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($data, json_decode($request->filter, true));

        $data->with([
            'satuanKerja',
            'kegiatan' => fn ($query) => $query->tahunKinerja(),
            'subKegiatan' => fn ($query) => $query->tahunKinerja(),
            'riwayatSkpRejectedLatest' => fn (Builder $query) => $query->tahunKinerja(),
            'strukturOrganisasi' => fn (Builder $query) => $query
                ->selectRaw('id, jabatan_nama')
                ->withoutGlobalScope('active'),
            'timKerja:id,nama,nip_ketua',
            'timKerja.ketua:peg_nip,peg_nama',
            'kinerjaKabKota',
        ])
            ->withCount([
                'kinerjaTidakTercapai' => fn (Builder $query) => $query->tahunKinerja(),
            ])
            ->withExists([
                'skp' => fn (Builder $query) => $query->tahunKinerja(),
            ])
            ->orderBy('updated_at');

        if ($isExport) {
            return $data->get();
        } else {
            $data = $data->paginate(20);
        }

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $kegiatan = [];
        $subKegiatan = [];
        $sasaranStrategisRpjmd = [];
        $kinerjaProgram = [];
        $kinerjaKegiatan = [];
        $sasaranStrategisPd = [];
        $vso = [];
        $timKerja = [];

        if (! Role::isSuper() || (Role::isSuper() && $request->satuan_kerja_id)) {
            $satkerId = Role::isSuper() ? $request->satuan_kerja_id : Auth::user()->satuan_kerja_id;

            $kinerjaKegiatan = KinerjaKegiatan::tahunKinerja()
                ->select('id', 'kegiatan_id', 'indikator', 'sasaran', 'anggaran', 'kinerja_program_id')
                ->with([
                    'kegiatan:id,nama',
                    'kinerjaProgram:id,sasaran_strategis_pd_id',
                    'kinerjaProgram.sasaranStrategisPd:id,sasaran_strategis_rpjmd_id',
                ])
                ->where('satuan_kerja_id', $satkerId)
                ->get();

            $subKegiatan = SubKegiatan::tahunKinerja()->with('indikator')->where('satuan_kerja_id', $satkerId)->get();

            // $level = isBiro($satkerId) ? 4 : 2;

            // $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, $level);

            // if ($satkerId == SATKER_DINKES) {
            //     $vso = $vso->concat(VStrukturOrganisasi::getListUnitKerja($satkerId, 3));
            // }

            $vso = self::getPengampuUnitKerja($satkerId);
            $kinerjaKabKota = KinerjaKabKota::all();

            $timKerja = TimKerja::with('ketua:peg_nip,peg_nama')->where('satuan_kerja_id', $satkerId)->get();
            $kinerjaKabKota = KinerjaKabKota::all();
        }

        return response()->json(compact('subKegiatan', 'kinerjaKegiatan', 'vso', 'timKerja', 'kinerjaKabKota'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKinerjaSubKegiatan $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $data = $request->validated();
        $data['tahun_kinerja'] = getTahunKinerja();

        KinerjaSubKegiatanKabKota::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambah data',
        ]);
    }

    public static function getPengampuUnitKerja(int $satkerId, bool $withInactive = false)
    {
        $level = match (true) {
            isBiro($satkerId) => 4,
            $satkerId == SATKER_BANHUB => 1,
            default => 2,
        };

        // TODO: jangan tampilkan unit kerja lama jika pegawai sudah dipindah
        $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, $level, $withInactive);

        // khusus dinkes tampilkan juga level 3 dan 4
        if ($satkerId == SATKER_DINKES) {
            $vso = $vso->concat(VStrukturOrganisasi::getListUnitKerja($satkerId, 3));
            $vso = $vso->concat(VStrukturOrganisasi::getListUnitKerja($satkerId, 4));
        }

        return $vso;
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(KinerjaSubKegiatan $kinerjaSubKegiatan)
    {
        try {
            $this->authorizeBySatuanKerja($kinerjaSubKegiatan->satuan_kerja_id);
        } catch (Exception $e) {
            if (! Role::isViewAll()) {
                throw $e;
            }
        }

        $kinerjaSubKegiatan->load([
            'satuanKerja',
            'kegiatan' => fn ($query) => $query->tahunKinerja(),
            'subKegiatan' => fn ($query) => $query->tahunKinerja(),
        ]);

        return response()->json($kinerjaSubKegiatan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(KinerjaSubKegiatan $kinerjaSubKegiatan)
    {
        $this->authorizeBySatuanKerja($kinerjaSubKegiatan->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH]);

        $form = $kinerjaSubKegiatan->load(['satuanKerja', 'solusi']);
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
            ->select('id', 'kegiatan_id', 'indikator', 'sasaran', 'anggaran', 'kinerja_program_id')
            ->with([
                'kegiatan:id,nama',
                'kinerjaProgram:id,sasaran_strategis_pd_id',
                'kinerjaProgram.sasaranStrategisPd:id,sasaran_strategis_rpjmd_id',
            ])
            ->where('satuan_kerja_id', $satkerId)
            ->get();

        $kegiatan = Kegiatan::tahunKinerja()->whereIn('id', $kinerjaKegiatan->pluck('kegiatan_id'))->get();

        $subKegiatan = SubKegiatan::tahunKinerja()->with('indikator')->where('satuan_kerja_id', $satkerId)->get();

        // $level = isBiro($satkerId) ? 4 : 2;

        // // TODO: jangan tampilkan unit kerja lama jika pegawai sudah dipindah
        // $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, $level, $satkerId == SATKER_DISDUKCAPIL);

        // if ($satkerId == SATKER_DINKES) {
        //     $vso = $vso->concat(VStrukturOrganisasi::getListUnitKerja($satkerId, 3));
        // }

        $vso = self::getPengampuUnitKerja($satkerId, $satkerId == SATKER_DISDUKCAPIL);

        if ($satkerId == SATKER_DINKES) {
            $vso = $vso->concat(VStrukturOrganisasi::getListUnitKerja($satkerId, 3));
        }

        $timKerja = TimKerja::with('ketua:peg_nip,peg_nama')->where('satuan_kerja_id', $satkerId)->get();

        return response()->json(compact('kegiatan', 'subKegiatan', 'sasaranStrategisRpjmd', 'kinerjaProgram', 'kinerjaKegiatan', 'sasaranStrategisPd', 'vso', 'form', 'timKerja'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateKinerjaSubKegiatan $request, KinerjaSubKegiatan $kinerjaSubKegiatan)
    {
        $this->authorizeBySatuanKerja($kinerjaSubKegiatan->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH]);

        $data = $request->validated();

        // penyebab kegagalan hanya untuk capaian dibawah 100
        if ($data['capaian'] >= 100) {
            $data['penyebab_kegagalan'] = null;
        }

        $kinerjaSubKegiatan->update($data);

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
    public function destroy(KinerjaSubKegiatanKabKota $kinerjaSubKegiatanKabKota)
    {
        $this->authorizeBySatuanKerja($kinerjaSubKegiatanKabKota->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH]);

        $kinerjaSubKegiatanKabKota->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data',
        ]);
    }

    public function upload(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $request->validate([
            'file' => ['required', 'image', 'max:2048'],
        ]);

        $file = $request->file('file');
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = date('YmdHis').'_'.Str::slug($fileName).'.'.$file->getClientOriginalExtension();

        $path = $request->file('file')->storeAs(KinerjaSubKegiatan::PATH_LAMPIRAN, $fileName);
        $path = Storage::url($path);

        return response()->json($path);
    }
}
