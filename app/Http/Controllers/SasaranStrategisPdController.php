<?php

namespace App\Http\Controllers;

use App\Exports\SasaranStrategisPDExport;
use App\Http\Requests\SasaranStrategisPd\StoreSasaranStrategisPd;
use App\Http\Requests\SasaranStrategisPd\UpdateSasaranStrategisPd;
use App\Models\KinerjaBayangan;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\Role;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use App\Services\FilterQuery;
use App\Traits\SetdaResourceAccess;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Khusus Sasaran Strategis PD di Sekretariat Daerah,
 * yang bisa olah data adalah akun `setda`, akun `biro` view-only,
 * `satuan_kerja_id` nya juga di set ke `setda`
 */
class SasaranStrategisPdController extends Controller
{
    use SetdaResourceAccess;

    public function index(Request $request, bool $isExport = false)
    {
        $data = SasaranStrategisPd::tahunMulai()->roleSatuanKerja($this->getSatkerSetdaBiro());

        FilterQuery::parseFilter($data, json_decode($request->filter, true));

        $data->with([
            'satuanKerja',
            'sasaranStrategis',
            'indikatorSasaranStrategis',
            'riwayatSkpRejectedLatest' => fn (Builder $query) => $query->tahunKinerja(),
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

        /**
         * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection $data
         */
        $queryFilter = fn (Builder $query) => $query
            ->whereIn('sasaran_strategis_pd.id', $data->pluck('id'))
            ->where(fn (Builder $query2) => $query2
                ->whereNull('capaian')
                ->orWhere('capaian', '<', 100)
            );

        $tidakTercapai = KinerjaProgram::tahunKinerja()
            ->select('sasaran_strategis_pd.id')
            ->join('sasaran_strategis_pd', 'sasaran_strategis_pd_id', 'sasaran_strategis_pd.id')
            ->where($queryFilter)
            ->union(KinerjaKegiatan::tahunKinerja()
                ->select('sasaran_strategis_pd.id')
                ->join('sasaran_strategis_pd', 'sasaran_strategis_pd_id', 'sasaran_strategis_pd.id')
                ->where($queryFilter)
            )
            ->union(KinerjaSubKegiatan::tahunKinerja()
                ->select('sasaran_strategis_pd.id')
                ->join('sasaran_strategis_pd', 'sasaran_strategis_pd_id', 'sasaran_strategis_pd.id')
                ->where($queryFilter)
            )
            ->pluck('id');

        /**
         * @var \Illuminate\Pagination\AbstractPaginator $data
         */
        $data->setCollection($data->getCollection()
            ->transform(function (SasaranStrategisPd $item) use ($tidakTercapai) {
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
    public function create(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH, Role::SETDA]);
        $this->restrictBiro();

        $sasaranStrategisRpjmd = [];

        if (! Role::isSuper() || (Role::isSuper() && $request->satuan_kerja_id)) {
            $satkerId = Role::isSuper() ? $request->satuan_kerja_id : Auth::user()->satuan_kerja_id;
            $sasaranStrategisRpjmd = SasaranStrategisRpjmd::tahunMulai()
                ->where('satuan_kerja_id', $satkerId)
                ->with([
                    'sasaranStrategis',
                    'indikatorSasaranStrategis',
                ])
                ->get();
        }

        $kinerjaBayangan = KinerjaBayangan::tahunMulai()
            ->roleSatuanKerja()
            ->select('id', 'sasaran', 'indikator')
            ->get();

        return response()->json(compact('sasaranStrategisRpjmd', 'kinerjaBayangan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSasaranStrategisPd $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH, Role::SETDA]);
        $this->restrictBiro();

        $data = $request->validated();
        $data['tahun_mulai'] = getTahunMulai();
        SasaranStrategisPd::create($data);

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
    public function show(SasaranStrategisPd $sasaranStrategisPd)
    {
        try {
            $this->authorizeBySatuanKerjaExcSetdaBiro($sasaranStrategisPd->satuan_kerja_id);
        } catch (Exception $e) {
            if (! Role::isViewAll()) {
                throw $e;
            }
        }

        $sasaranStrategisPd->load([
            'satuanKerja',
            'sasaranStrategis',
            'indikatorSasaranStrategis',
        ]);

        return response()->json($sasaranStrategisPd);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, SasaranStrategisPd $sasaranStrategisPd)
    {
        $this->authorizeBySatuanKerja($sasaranStrategisPd->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH, Role::SETDA]);

        $sasaranStrategisRpjmd = SasaranStrategisRpjmd::tahunMulai()
            ->where('satuan_kerja_id', $sasaranStrategisPd->satuan_kerja_id)
            ->with([
                'sasaranStrategis',
                'indikatorSasaranStrategis',
            ])
            ->get();

        $form = $sasaranStrategisPd->load('satuanKerja');

        $kinerjaBayangan = KinerjaBayangan::tahunMulai()
            ->roleSatuanKerja()
            ->select('id', 'sasaran', 'indikator')
            ->get();

        return response()->json(compact('form', 'sasaranStrategisRpjmd', 'kinerjaBayangan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSasaranStrategisPd $request, SasaranStrategisPd $sasaranStrategisPd)
    {
        $this->authorizeBySatuanKerja($sasaranStrategisPd->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH, Role::SETDA]);

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

        $sasaranStrategisPd->update($data);

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
    public function destroy(SasaranStrategisPd $sasaranStrategisPd)
    {
        $this->authorizeBySatuanKerja($sasaranStrategisPd->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH, Role::SETDA]);

        $sasaranStrategisPd->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data',
        ]);
    }

    public function upload(Request $request)
    {
        $this->authorizeByRoles([Role::SUPER, Role::PERANGKAT_DAERAH, Role::SETDA]);
        $this->restrictBiro();

        $request->validate([
            'file' => ['required', 'image', 'max:2048'],
        ]);

        $file = $request->file('file');
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = date('YmdHis').'_'.Str::slug($fileName).'.'.$file->getClientOriginalExtension();

        $path = $request->file('file')->storeAs(SasaranStrategisPd::PATH_LAMPIRAN, $fileName);
        $path = Storage::url($path);

        return response()->json($path);
    }

    public function sync(SasaranStrategisPd $sasaranStrategisPd)
    {
        $this->authorizeBySatuanKerja($sasaranStrategisPd->satuan_kerja_id, [Role::SUPER, Role::PERANGKAT_DAERAH, Role::SETDA]);

        $sk = DB::connection('ekinerja')->table('iki')
            ->join('sasaran_kinerja', 'iki.sasaran_kerja_id', 'sasaran_kinerja.id_old_skp')
            ->whereNull('iki.deleted_at')
            // ->where('validasi', 1) // hanya ambil yang sudah di validasi
            ->where('sakip_type', 'App\Model\Sakip\SasaranStrategisPd')
            ->where('sakip_id', $sasaranStrategisPd->id)
            ->first();

        if (! $sk) {
            abort(404, 'Tidak ada data di TRK');
        } elseif ($sk->validasi != 1) {
            abort(404, 'Data belum divalidasi');
        }

        $realisasiField = 'realisasi_'.((getTahunKinerja() - $sasaranStrategisPd->tahun_mulai) + 1);
        $targetField = 'target_'.((getTahunKinerja() - $sasaranStrategisPd->tahun_mulai) + 1);
        $capaianField = 'capaian_'.((getTahunKinerja() - $sasaranStrategisPd->tahun_mulai) + 1);

        try {
            $capaian = round($sk->realisasi / $sasaranStrategisPd[$targetField] * 100, 2);
        } catch (Exception $e) {
            $capaian = 0;
        }

        $sasaranStrategisPd->update([
            $realisasiField => $sk->realisasi,
            $capaianField => $capaian,
        ]);

        return response()->json([
            'realisasi_field' => $realisasiField,
            'realisasi' => $sk->realisasi,
            'capaian_field' => $capaianField,
            'capaian' => $capaian,
        ]);
    }

    public function export(Request $request)
    {
        $data = $this->index($request, true);

        return Excel::download(new SasaranStrategisPDExport($data), 'Sasaran Strategis Perangkat Daerah.xlsx');
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

        $path = $request->file('file')->storeAs(SasaranStrategisPd::PATH_DEFINISI_OPERASIONAL, $fileName);
        $path = Storage::url($path);

        return response()->json($path);
    }
}
