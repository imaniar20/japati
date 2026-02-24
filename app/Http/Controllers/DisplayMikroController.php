<?php

namespace App\Http\Controllers;

use App\Exports\DisplayMikro\CapaianKinerjaEfisiensiAnggaranExport;
use App\Exports\DisplayMikro\CapaianKinerjaKegiatanExport;
use App\Exports\DisplayMikro\CapaianKinerjaKeuanganExport;
use App\Exports\DisplayMikro\CapaianKinerjaLangkahAksiExport;
use App\Exports\DisplayMikro\CapaianKinerjaPdExport;
use App\Exports\DisplayMikro\CapaianKinerjaSubKegiatanExport;
use App\Exports\DisplayMikro\CascadingExport;
use App\Exports\DisplayMikro\PerjanjianKinerjaExport;
use App\Exports\DisplayMikro\ProgramInovatifExport;
use App\Exports\DisplayMikro\RencanaAksiPdExport;
use App\Exports\DisplayMikro\RenstraExport;
use App\Exports\DisplayMikro\RktExport;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaLangkahAksi;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\Role;
use App\Models\SasaranStrategisPd;
use App\Services\DiagramSasaran;
use App\Services\FilterQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DisplayMikroController extends Controller
{
    public function renstra(Request $request, bool $isExport = false)
    {
        $sasaranStrategisPd = SasaranStrategisPd::tahunMulai()->roleSatuanKerja();

        FilterQuery::parseFilter($sasaranStrategisPd, json_decode($request->filter, true));

        $sasaranStrategisPd->with([
            'satuanKerja',
        ])
            ->select('id', 'sasaran_strategis_satker', 'iku', 'target_1', 'target_2', 'target_3', 'target_4', 'target_5', 'satuan_kerja_id');

        if ($isExport) {
            return $sasaranStrategisPd->get();
        } else {
            $sasaranStrategisPd = $sasaranStrategisPd->paginate(20);
        }

        return response()->json($sasaranStrategisPd);
    }

    public function rkt(Request $request, bool $isExport = false)
    {
        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaSubKegiatan, json_decode($request->filter, true));

        $kinerjaSubKegiatan->select('id', 'sub_kegiatan_id', 'sasaran_strategis_pd_id', 'kinerja_program_id', 'kinerja_kegiatan_id', 'satuan_kerja_id')
            ->with([
                'satuanKerja',
                'subKegiatan',
                'sasaranStrategisPd',
                'kinerjaProgram.program',
                'kinerjaKegiatan.kegiatan',
            ]);

        if ($isExport) {
            return $kinerjaSubKegiatan->get();
        } else {
            $kinerjaSubKegiatan = $kinerjaSubKegiatan->paginate(20);
        }

        return response()->json($kinerjaSubKegiatan);
    }

    public function perjanjianKinerja(Request $request, bool $isExport = false)
    {
        $sasaranStrategisPd = SasaranStrategisPd::tahunMulai()->roleSatuanKerja();

        FilterQuery::parseFilter($sasaranStrategisPd, json_decode($request->filter, true));

        $sasaranStrategisPd->with([
            'satuanKerja',
        ])
            ->select('id', 'sasaran_strategis_satker', 'iku', 'tahun_mulai', 'target_1', 'target_2', 'target_3', 'target_4', 'target_5', 'satuan_kerja_id');

        if ($isExport) {
            return $sasaranStrategisPd->get();
        } else {
            $sasaranStrategisPd = $sasaranStrategisPd->paginate(20);
        }

        return response()->json($sasaranStrategisPd);
    }

    public function rencanaAksi(Request $request, bool $isExport = false)
    {
        $kinerjaLangkahAksi = KinerjaLangkahAksi::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaLangkahAksi, json_decode($request->filter, true));

        $kinerjaLangkahAksi->with([
            'satuanKerja',
            'sasaranStrategisRpjmd.sasaranStrategis',
            'sasaranStrategisRpjmd.indikatorSasaranStrategis',
            'sasaranStrategisPd',
            'kinerjaProgram.program',
            'kinerjaKegiatan.kegiatan',
            'kinerjaSubKegiatan.subKegiatan',
        ]);

        if ($isExport) {
            return $kinerjaLangkahAksi->get();
        } else {
            $kinerjaLangkahAksi = $kinerjaLangkahAksi->paginate(20);
        }

        return response()->json($kinerjaLangkahAksi);
    }

    public function capaianKinerjaPD(Request $request, bool $isExport = false)
    {
        $sasaranStrategisPd = SasaranStrategisPd::tahunMulai()->roleSatuanKerja();

        FilterQuery::parseFilter($sasaranStrategisPd, json_decode($request->filter, true));

        $sasaranStrategisPd->with([
            'satuanKerja',
        ]);

        if ($isExport) {
            return $sasaranStrategisPd->get();
        } else {
            $sasaranStrategisPd = $sasaranStrategisPd->paginate(20);
        }

        return response()->json($sasaranStrategisPd);
    }

    public function capaianKinerjaEfisiensiAnggaran(Request $request, bool $isExport = false)
    {
        $kinerjaProgram = KinerjaProgram::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaProgram, json_decode($request->filter, true));

        $kinerjaProgram->with([
            'satuanKerja',
            'program',
            'sasaranStrategisPd',
        ]);

        if ($isExport) {
            return $kinerjaProgram->get();
        } else {
            $kinerjaProgram = $kinerjaProgram->paginate(20);
        }

        return response()->json($kinerjaProgram);
    }

    public function capaianKinerjaKeuangan(Request $request, bool $isExport = false)
    {
        $kinerjaLangkahAksi = KinerjaLangkahAksi::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaLangkahAksi, json_decode($request->filter, true));

        $kinerjaLangkahAksi->with([
            'satuanKerja',
            'sasaranStrategisPd',
            'kinerjaProgram.program',
            'kinerjaKegiatan.kegiatan',
            'kinerjaSubKegiatan.subKegiatan',
        ]);

        if ($isExport) {
            return $kinerjaLangkahAksi->get();
        } else {
            $kinerjaLangkahAksi = $kinerjaLangkahAksi->paginate(20);
        }

        return response()->json($kinerjaLangkahAksi);
    }

    public function programInovatif(Request $request, bool $isExport = false)
    {
        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaSubKegiatan, json_decode($request->filter, true));

        $kinerjaSubKegiatan->whereNotNull('inovasi_uraian')
            ->with([
                'satuanKerja',
                'sasaranStrategisPd',
            ]);

        if ($isExport) {
            return $kinerjaSubKegiatan->get();
        } else {
            $kinerjaSubKegiatan = $kinerjaSubKegiatan->paginate(20);
        }

        return response()->json($kinerjaSubKegiatan);
    }

    public function capaianKinerjaProgram(Request $request)
    {
        $data = KinerjaProgram::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($data, json_decode($request->filter, true));

        $data = $data
            ->with([
                'satuanKerja',
                'sasaranStrategisPd',
                'program',
            ])
            ->paginate(20);

        return response()->json($data);
    }

    public function capaianKinerjaKegiatan(Request $request, bool $isExport = false)
    {
        $kinerjaKegiatan = KinerjaKegiatan::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaKegiatan, json_decode($request->filter, true));

        $kinerjaKegiatan->with([
            'satuanKerja',
            'sasaranStrategisPd',
            'kinerjaProgram.program',
            'kegiatan',
        ]);

        if ($isExport) {
            return $kinerjaKegiatan->get();
        } else {
            $kinerjaKegiatan = $kinerjaKegiatan->paginate(20);
        }

        return response()->json($kinerjaKegiatan);
    }

    public function capaianKinerjaSubKegiatan(Request $request, bool $isExport = false)
    {
        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaSubKegiatan, json_decode($request->filter, true));

        $kinerjaSubKegiatan->with([
            'satuanKerja',
            'sasaranStrategisPd',
            'kinerjaProgram.program',
            'kinerjaKegiatan.kegiatan',
            'subKegiatan',
        ]);

        if ($isExport) {
            return $kinerjaSubKegiatan->get();
        } else {
            $kinerjaSubKegiatan = $kinerjaSubKegiatan->paginate(20);
        }

        return response()->json($kinerjaSubKegiatan);
    }

    public function capaianKinerjaLangkahAksi(Request $request, bool $isExport = false)
    {
        $kinerjaLangkahAksi = KinerjaLangkahAksi::tahunKinerja()->roleSatuanKerja();

        FilterQuery::parseFilter($kinerjaLangkahAksi, json_decode($request->filter, true));

        $kinerjaLangkahAksi->with([
            'satuanKerja',
            'sasaranStrategisPd',
            'kinerjaProgram.program',
            'kinerjaKegiatan.kegiatan',
            'kinerjaSubKegiatan.subKegiatan',
        ]);

        if ($isExport) {
            return $kinerjaLangkahAksi->get();
        } else {
            $kinerjaLangkahAksi = $kinerjaLangkahAksi->paginate(20);
        }

        return response()->json($kinerjaLangkahAksi);
    }

    public function cascading(Request $request)
    {
        $filter = $request->validate([
            'satuan_kerja_id' => ['nullable'],
        ]);

        $satuanKerjaId = (Role::isSuper() || Role::isSetda()) ? $filter['satuan_kerja_id'] ?? null : Auth::user()->satuan_kerja_id;

        if (! $satuanKerjaId && ! Role::isPemda()) {
            return response()->json([]);
        }

        if (Role::isPemda()) {
            $data = DiagramSasaran::getCompact($satuanKerjaId);
        } else {
            $data = DiagramSasaran::get($satuanKerjaId);
        }

        return response()->json($data);
    }

    public function renstraExport(Request $request)
    {
        $data = $this->renstra($request, true);

        return Excel::download(new RenstraExport($data), 'Renstra.xlsx');
    }

    public function rktExport(Request $request)
    {
        $data = $this->rkt($request, true);

        return Excel::download(new RktExport($data), 'Rkt.xlsx');
    }

    public function perjanjianKinerjaExport(Request $request)
    {
        $data = $this->perjanjianKinerja($request, true);

        return Excel::download(new PerjanjianKinerjaExport($data), 'Perjanjian Kinerja.xlsx');
    }

    public function rencanaAksiExport(Request $request)
    {
        $data = $this->rencanaAksi($request, true);

        return Excel::download(new RencanaAksiPdExport($data), 'Rencana Aksi Perangkat Daerah.xlsx');
    }

    public function capaianKinerjaPdExport(Request $request)
    {
        $data = $this->capaianKinerjaPD($request, true);

        return Excel::download(new CapaianKinerjaPdExport($data), 'Capaian Kinerja Perangkat Daerah.xlsx');
    }

    public function capaianKinerjaEfisiensiAnggaranExport(Request $request)
    {
        $data = $this->capaianKinerjaEfisiensiAnggaran($request, true);

        return Excel::download(new CapaianKinerjaEfisiensiAnggaranExport($data), 'Capaian Kinerja Efisiensi Anggaran.xlsx');
    }

    public function capaianKinerjaKeuanganExport(Request $request)
    {
        $data = $this->capaianKinerjaKeuangan($request, true);

        return Excel::download(new CapaianKinerjaKeuanganExport($data), 'Capaian Kinerja dan Keuangan Perangkat Daerah.xlsx');
    }

    public function programInovatifExport(Request $request)
    {
        $data = $this->programInovatif($request, true);

        return Excel::download(new ProgramInovatifExport($data), 'Program Inovatif.xlsx');
    }

    public function capaianKinerjaKegiatanExport(Request $request)
    {
        $data = $this->capaianKinerjaKegiatan($request, true);

        return Excel::download(new CapaianKinerjaKegiatanExport($data), 'Capaian Kinerja Kegiatan.xlsx');
    }

    public function capaianKinerjaSubKegiatanExport(Request $request)
    {
        $data = $this->capaianKinerjaSubKegiatan($request, true);

        return Excel::download(new CapaianKinerjaSubKegiatanExport($data), 'Capaian Kinerja Sub Kegiatan.xlsx');
    }

    public function capaianKinerjaLangkahAksiExport(Request $request)
    {
        $data = $this->capaianKinerjaLangkahAksi($request, true);

        return Excel::download(new CapaianKinerjaLangkahAksiExport($data), 'Capaian Kinerja Langkah Aksi.xlsx');
    }

    public function cascadingExport(Request $request)
    {
        $filter = $request->validate([
            'satuan_kerja_id' => ['nullable'],
        ]);

        $satuanKerjaId = (Role::isSuper() || Role::isSetda()) ? $filter['satuan_kerja_id'] ?? null : Auth::user()->satuan_kerja_id;

        if (! $satuanKerjaId && ! Role::isPemda()) {
            return response()->json([]);
        }

        $data = DiagramSasaran::getExport($satuanKerjaId);

        return Excel::download(new CascadingExport($data), 'Cascading.xlsx');
    }
}
