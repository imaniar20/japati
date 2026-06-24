<?php

namespace App\Http\Controllers;

use App\Exports\KinerjaKegiatanExport;
use App\Http\Requests\KinerjaKegiatan\StoreKinerjaKegiatan;
use App\Http\Requests\KinerjaKegiatan\UpdateKinerjaKegiatan;
use App\Models\Ekinerja\TimKerja;
use App\Models\Kegiatan;
use App\Models\KinerjaBayangan;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\Program;
use App\Models\Role;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use App\Models\VStrukturOrganisasi;
use App\Services\FilterQuery;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class KinerjaKegiatanController extends Controller
{
    public function index(Request $request, bool $isExport = false)
    {
        $data = KinerjaKegiatan::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($data, json_decode($request->filter, true));

        $data->with([
            'satuanKerja',
            'program' => fn ($query) => $query->tahunKinerja(),
            'kegiatan' => fn ($query) => $query->tahunKinerja(),
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

        $sasaranStrategisRpjmd = [];
        $sasaranStrategisPd = [];
        $kinerjaProgram = [];
        $program = [];
        $kegiatan = [];
        $vso = [];
        $timKerja = [];

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

            $program = Program::tahunKinerja()->whereIn('id', $kinerjaProgram->pluck('program_id'))->orderBy('kode')->get();
            $kegiatan = Kegiatan::tahunKinerja()->where('satuan_kerja_id', $satkerId)->orderBy('kode')->get();

            // // jika biro maka pakai level 2
            // $level = isBiro($satkerId) ? 3 : 1;

            // $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, $level);
            $vso = self::getPengampuUnitKerja($satkerId);
            $timKerja = TimKerja::with('ketua:peg_nip,peg_nama')->where('satuan_kerja_id', $satkerId)->get();
        }

        return response()->json(compact('program', 'kinerjaProgram', 'kegiatan', 'sasaranStrategisRpjmd', 'sasaranStrategisPd', 'vso', 'timKerja'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKinerjaKegiatan $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH]);

        $data = $request->validated();

        $kinerjaProgram = KinerjaProgram::where('id', $data['kinerja_program_id'])->first();
        $sasaranStrategisPd = SasaranStrategisPd::where('id', $kinerjaProgram->sasaran_strategis_pd_id)->first();
        $data['tahun_kinerja'] = getTahunKinerja();
        $data['sasaran_strategis_rpjmd_id'] = $sasaranStrategisPd->sasaran_strategis_rpjmd_id;
        $data['sasaran_strategis_pd_id'] = $kinerjaProgram->sasaran_strategis_pd_id;
        $data['kinerja_program_id'] = $kinerjaProgram->id;
        $data['sasaran_program'] = $kinerjaProgram->sasaran;
        $data['program_id'] = $kinerjaProgram->program_id;

        KinerjaKegiatan::create($data);

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
    public function show(KinerjaKegiatan $kinerjaKegiatan)
    {
        try {
            $this->authorizeBySatuanKerja($kinerjaKegiatan->satuan_kerja_id);
        } catch (Exception $e) {
            if (! Role::isViewAll()) {
                throw $e;
            }
        }

        $kinerjaKegiatan->load([
            'satuanKerja',
            'program' => fn ($query) => $query->tahunKinerja(),
            'kegiatan' => fn ($query) => $query->tahunKinerja(),
        ])
            ->get();

        return response()->json($kinerjaKegiatan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(KinerjaKegiatan $kinerjaKegiatan)
    {
        $this->authorizeBySatuanKerja($kinerjaKegiatan->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH]);

        $form = $kinerjaKegiatan->load(['satuanKerja', 'solusi']);
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

        $program = Program::tahunKinerja()->whereIn('id', $kinerjaProgram->pluck('program_id'))->orderBy('kode')->get();
        $kegiatan = Kegiatan::tahunKinerja()->where('satuan_kerja_id', $satkerId)->orderBy('kode')->get();

        // // jika biro maka pakai level 2
        // $level = isBiro($satkerId) ? 3 : 1;

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

        return response()->json(compact('program', 'kinerjaProgram', 'kegiatan', 'sasaranStrategisRpjmd', 'sasaranStrategisPd', 'vso', 'form', 'timKerja', 'kinerjaBayangan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateKinerjaKegiatan $request, KinerjaKegiatan $kinerjaKegiatan)
    {
        $this->authorizeBySatuanKerja($kinerjaKegiatan->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH]);

        $data = $request->validated();

        // penyebab kegagalan hanya untuk capaian dibawah 100
        if ($data['capaian'] >= 100) {
            $data['penyebab_kegagalan'] = null;
        }

        $kinerjaProgram = KinerjaProgram::where('id', $data['kinerja_program_id'])->first();
        $sasaranStrategisPd = SasaranStrategisPd::where('id', $kinerjaProgram->sasaran_strategis_pd_id)->first();
        $data['tahun_kinerja'] = getTahunKinerja();
        $data['sasaran_strategis_rpjmd_id'] = $sasaranStrategisPd->sasaran_strategis_rpjmd_id;
        $data['sasaran_strategis_pd_id'] = $kinerjaProgram->sasaran_strategis_pd_id;
        $data['kinerja_program_id'] = $kinerjaProgram->id;
        $data['sasaran_program'] = $kinerjaProgram->sasaran;
        $data['program_id'] = $kinerjaProgram->program_id;

        $kinerjaKegiatan->update($data);

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
    public function destroy(KinerjaKegiatan $kinerjaKegiatan)
    {
        $this->authorizeBySatuanKerja($kinerjaKegiatan->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH]);

        $kinerjaKegiatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data',
        ]);
    }

    public function sync(KinerjaKegiatan $kinerjaKegiatan)
    {
        $this->authorizeBySatuanKerja($kinerjaKegiatan->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH]);

        if (! $kinerjaKegiatan->v_struktur_organisasi_id) {
            abort(404, 'Terdapat data unit kerja yang belum diisikan pada menu Kinerja Kegiatan');
        }

        $sk = DB::connection('ekinerja')->table('iki')
            ->join('sasaran_kinerja', 'iki.sasaran_kerja_id', 'sasaran_kinerja.id_old_skp')
            ->whereNull('iki.deleted_at')
            // ->where('validasi', 1) // hanya ambil yang sudah di validasi
            ->where('sakip_type', 'App\Model\Sakip\KinerjaKegiatan')
            ->where('sakip_id', $kinerjaKegiatan->id)
            ->first();

        if (! $sk) {
            abort(404, 'Tidak ada data di TRK');
        } elseif ($sk->validasi != 1) {
            abort(404, 'Data belum divalidasi');
        }

        try {
            $capaian = round($sk->realisasi / $kinerjaKegiatan->target * 100, 2);
        } catch (Exception $e) {
            $capaian = 0;
        }

        try {
            $realisasiBulanan = json_decode($sk->realisasi_bulanan, true);
        } catch (Exception $e) {
            $realisasiBulanan = $kinerjaKegiatan->realisasi_bulanan;
        }

        $kinerjaKegiatan->update([
            'realisasi' => $sk->realisasi,
            'capaian' => $capaian,
            'realisasi_bulanan' => $realisasiBulanan,
        ]);

        return response()->json([
            'realisasi' => $sk->realisasi,
            'capaian' => $capaian,
            'realisasi_bulanan' => $realisasiBulanan,
        ]);
    }

    public function export(Request $request)
    {
        $data = $this->index($request, true);

        return Excel::download(new KinerjaKegiatanExport($data), 'Kinerja Kegiatan.xlsx');
    }

    public static function getPengampuUnitKerja(int $satkerId, bool $withInactive = false)
    {
        $isBiro = isBiro($satkerId);
        $level = $isBiro ? 2 : 1;

        // TODO: saat ini setwan belum ada pelantikan, jadi pegawai masih di unit kerja lama
        // jadi pengampu masih bisa pilih unit kerja yang sudah inaktif
        // hapus $withInactive saat sudah pelantikan
        $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, $level, $withInactive);

        // khusus banhub, tampilkan juga eselon 3 nya (level 0)
        if ($satkerId == SATKER_BANHUB) {
            $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, 0)->concat($vso);
        }

        // khusus biro, tampilkan juga level 3
        if ($isBiro) {
            $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, 3)->concat($vso);
        }

        // khusus dinkes tampilkan juga level 2 dan 3
        if ($satkerId == SATKER_DINKES) {
            $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, 2)->concat($vso);
            $vso = VStrukturOrganisasi::getListUnitKerja($satkerId, 3)->concat($vso);
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

        $path = $request->file('file')->storeAs(KinerjaKegiatan::PATH_DEFINISI_OPERASIONAL, $fileName);
        $path = Storage::url($path);

        return response()->json($path);
    }
}
