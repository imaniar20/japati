<?php

namespace App\Http\Controllers;

use App\Exports\KinerjaProgramExport;
use App\Http\Requests\KinerjaProgram\StoreKinerjaProgram;
use App\Http\Requests\KinerjaProgram\UpdateKinerjaProgram;
use App\Models\Ekinerja\TimKerja;
use App\Models\KinerjaBayangan;
use App\Models\KinerjaProgram;
use App\Models\Program;
use App\Models\Role;
use App\Models\SasaranStrategisPd;
use App\Models\VStrukturOrganisasi;
use App\Services\FilterQuery;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class KinerjaProgramController extends Controller
{
    public function index(Request $request, bool $isExport = false)
    {
        $data = KinerjaProgram::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($data, json_decode($request->filter, true));

        $data->with([
            'satuanKerja',
            'sasaranStrategisPd',
            'satkerIku',
            'program' => fn ($query) => $query->tahunKinerja(),
            'riwayatSkpRejectedLatest' => fn (Builder $query) => $query->tahunKinerja(),
            'strukturOrganisasi' => fn (Builder $query) => $query
                ->selectRaw('id, jabatan_nama')
                ->withoutGlobalScope('active'),
            'timKerja:id,nama,nip_ketua',
            'timKerja.ketua:peg_nip,peg_nama',
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

        $sasaranStrategisPd = [];
        $program = [];
        $vso = [];
        $timKerja = [];

        if (! Role::isSuper() || (Role::isSuper() && $request->satuan_kerja_id)) {
            $satkerId = Role::isSuper() ? $request->satuan_kerja_id : Auth::user()->satuan_kerja_id;

            $sasaranStrategisPd = SasaranStrategisPd::tahunMulai()
                ->select('id', 'sasaran_strategis_satker', 'iku', 'satuan')
                ->where('satuan_kerja_id', parseSatuanKerjaId($satkerId))
                ->get();

            $program = Program::tahunKinerja()->where('satuan_kerja_id', $satkerId)->get();

            // // jika biro maka pakai level 2
            // $level = isBiro($satkerId) ? 2 : 1;

            // $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, $level);
            $vso = self::getPengampuUnitKerja($satkerId);

            $timKerja = TimKerja::with('ketua:peg_nip,peg_nama')->where('satuan_kerja_id', $satkerId)->get();
        }

        return response()->json(compact('program', 'sasaranStrategisPd', 'vso', 'timKerja'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKinerjaProgram $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $data = $request->validated();
        $data['tahun_kinerja'] = getTahunKinerja();

        KinerjaProgram::create($data);

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
    public function show(KinerjaProgram $kinerjaProgram)
    {
        try {
            $this->authorizeBySatuanKerja($kinerjaProgram->satuan_kerja_id);
        } catch (Exception $e) {
            if (! Role::isViewAll()) {
                throw $e;
            }
        }

        $kinerjaProgram->load([
            'satuanKerja',
            'sasaranStrategisPd',
            'satkerIku',
            'program' => fn ($query) => $query->tahunKinerja(),
        ]);

        return response()->json($kinerjaProgram);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, KinerjaProgram $kinerjaProgram)
    {
        $this->authorizeBySatuanKerja($kinerjaProgram->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH]);

        $form = $kinerjaProgram->load(['satuanKerja', 'solusi']);
        $satkerId = $form->satuan_kerja_id;

        $sasaranStrategisPd = SasaranStrategisPd::tahunMulai()
            ->select('id', 'sasaran_strategis_satker', 'iku', 'satuan')
            ->where('satuan_kerja_id', parseSatuanKerjaId($satkerId))
            ->get();

        $program = Program::tahunKinerja()->where('satuan_kerja_id', $satkerId)->get();

        // // jika biro maka pakai level 2
        // $level = isBiro($satkerId) ? 2 : 1;

        // // TODO: saat ini setwan belum ada pelantikan, jadi pegawai masih di unit kerja lama
        // // jadi pengampu masih bisa pilih unit kerja yang sudah inaktif
        // // hapus $withInactive saat sudah pelantikan
        // $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, $level, $satkerId == SATKER_SETWAN);

        // // khusus banhub, tampilkan juga eselon 3 nya (level 0)
        // if ($satkerId == SATKER_BANHUB) {
        //     $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, 0)->concat($vso);
        // }

        $vso = self::getPengampuUnitKerja($satkerId, $satkerId == SATKER_SETWAN);

        $timKerja = TimKerja::with('ketua:peg_nip,peg_nama')->where('satuan_kerja_id', $satkerId)->get();

        $kinerjaBayangan = KinerjaBayangan::tahunMulai()
            ->roleSatuanKerja(parseSatuanKerjaId($satkerId))
            ->select('id', 'sasaran', 'indikator')
            ->get();

        return response()->json(compact('program', 'sasaranStrategisPd', 'vso', 'form', 'kinerjaBayangan', 'timKerja'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateKinerjaProgram $request, KinerjaProgram $kinerjaProgram)
    {
        $this->authorizeBySatuanKerja($kinerjaProgram->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH]);

        $data = $request->validated();

        // penyebab kegagalan hanya untuk capaian dibawah 100
        if ($data['capaian'] >= 100) {
            $data['penyebab_kegagalan'] = null;
        }

        $kinerjaProgram->update($data);

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
    public function destroy(KinerjaProgram $kinerjaProgram)
    {
        $this->authorizeBySatuanKerja($kinerjaProgram->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH]);

        $kinerjaProgram->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data',
        ]);
    }

    public function export(Request $request)
    {
        $data = $this->index($request, true);

        return Excel::download(new KinerjaProgramExport($data), 'Kinerja Program.xlsx');
    }

    public static function getPengampuUnitKerja(int $satkerId, bool $withInactive = false)
    {
        // jika biro maka pakai level 2
        $level = isBiro($satkerId) ? 2 : 1;

        // TODO: saat ini setwan belum ada pelantikan, jadi pegawai masih di unit kerja lama
        // jadi pengampu masih bisa pilih unit kerja yang sudah inaktif
        // hapus $withInactive saat sudah pelantikan
        $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, $level, $withInactive);

        // khusus banhub, tampilkan juga eselon 3 nya (level 0)
        if ($satkerId == SATKER_BANHUB) {
            $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, 0)->concat($vso);
        }

        return $vso;
    }

    public function uploadDefinisiOperasional(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $request->validate([
            'file' => ['required', 'mimes:jpg,png', 'max:2048'],
        ]);

        $file = $request->file('file');
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = date('YmdHis').'_'.'_'.Str::slug($fileName).'.'.$file->getClientOriginalExtension();

        $path = $request->file('file')->storeAs(KinerjaProgram::PATH_DEFINISI_OPERASIONAL, $fileName);
        $path = Storage::url($path);

        return response()->json($path);
    }
}
