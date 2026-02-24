<?php

namespace App\Http\Controllers\PublicDisplay;

use App\Http\Controllers\Controller;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaLangkahAksi;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use App\Models\Visi;
use App\Services\CompactQuery;
use App\Services\DiagramSasaran;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DisplayMakroController extends Controller
{
    public function rpjmd()
    {
        // setTahunKinerja(request()->tahun_kinerja);

        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
            getTahunKinerja(),
        ]));

        $result = Cache::remember($cacheKey, 60, function () {
            $visi = Visi::tahunMulai()->first();

            $sasaranStrategisRpjmd = SasaranStrategisRpjmd::tahunMulai()
                ->selectRaw('id, sasaran_strategis_id, indikator_sasaran_strategis_id, misi_id, tujuan_id, indikator_tujuan_id, target_visi_misi_rpjmd_id, target_1, target_2, target_3, target_4, target_5, strategi')
                ->when(request()->satuan_kerja_id, fn (Builder $query) => $query
                    ->where('satuan_kerja_id', parseSatuanKerjaId(request()->satuan_kerja_id))
                )
                ->with([
                    'sasaranStrategis:id,sasaran',
                    'indikatorSasaranStrategis:id,indikator',
                    'misi:id,misi',
                    'tujuan:id,tujuan',
                    'indikatorTujuan:id,indikator',
                    'targetVisiMisi',
                ]);

            $sasaranStrategisRpjmd = CompactQuery::compactWithoutPagination($sasaranStrategisRpjmd, 'strategi')
                ->groupBy([
                    'misi_id',
                    'tujuan_id',
                    'indikator_tujuan_id',
                    'sasaran_strategis_id',
                ])
                ->transform(function ($byMisi) {
                    return $byMisi->values()
                        ->transform(function ($byTujuan) {
                            return $byTujuan->values()
                                ->transform(function ($byIndikatorTujuan) {
                                    return $byIndikatorTujuan->values();
                                });
                        });
                })
                ->values();

            return [
                'visi' => $visi,
                'sasaranStrategis' => $sasaranStrategisRpjmd,
            ];
        });

        return response()->json($result);
    }

    public function rkpd()
    {
        // setTahunKinerja(request()->tahun_kinerja);

        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
            getTahunKinerja(),
        ]));

        $kinerjaSubKegiatan = Cache::remember($cacheKey, 60, function () {
            $kinerjaSubKegiatan = KinerjaSubKegiatan::selectRaw('id, sub_kegiatan_id, kegiatan_id, sasaran_strategis_rpjmd_id, kinerja_program_id, anggaran')
                ->tahunKinerja()
                ->with([
                    'subKegiatan:id,nama',
                    'kegiatan:id,nama',
                    'sasaranStrategisRpjmd:id,sasaran_strategis_id,indikator_sasaran_strategis_id,target_1,target_2,target_3,target_4,target_5',
                    'sasaranStrategisRpjmd.sasaranStrategis:id,sasaran',
                    'sasaranStrategisRpjmd.indikatorSasaranStrategis:id,indikator',
                    'kinerjaProgram:id,program_id,anggaran',
                    'kinerjaProgram.program:id,nama',
                ]);

            $kinerjaSubKegiatan = CompactQuery::compactWithoutPagination($kinerjaSubKegiatan, 'sub_kegiatan_id', 'sub_kegiatan_id', 'sub_kegiatan_id')
                ->groupBy([
                    'sasaranStrategisRpjmd.sasaran_strategis_id',
                    'sasaranStrategisRpjmd.indikator_sasaran_strategis_id',
                    'kinerjaProgram.program_id',
                    'kegiatan_id',
                ])
                ->transform(function ($bySasaran) {
                    return $bySasaran->values()
                        ->transform(function ($byIku) {
                            return $byIku->values()
                                ->transform(function ($byProgram) {
                                    return $byProgram->values();
                                });
                        });
                })
                ->values();

            return $kinerjaSubKegiatan;
        });

        return response()->json([
            'data' => $kinerjaSubKegiatan,
        ]);
    }

    public function perjanjianKinerja()
    {
        // setTahunKinerja(request()->tahun_kinerja);

        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
            getTahunKinerja(),
        ]));

        $sasaranStrategisRpjmd = Cache::remember($cacheKey, 60, function () {
            $sasaranStrategisRpjmd = SasaranStrategisRpjmd::selectRaw('id, sasaran_strategis_id, indikator_sasaran_strategis_id, tahun_mulai, target_1, target_2, target_3, target_4, target_5')
                ->tahunMulai()
                ->with([
                    'sasaranStrategis:id,sasaran',
                    'indikatorSasaranStrategis:id,indikator',
                ]);

            $sasaranStrategisRpjmd = CompactQuery::compactWithoutPagination($sasaranStrategisRpjmd, 'indikator_sasaran_strategis_id')
                ->groupBy([
                    'sasaran_strategis_id',
                ])
                ->values();

            return $sasaranStrategisRpjmd;
        });

        return response()->json([
            'data' => $sasaranStrategisRpjmd,
        ]);
    }

    public function capaianKinerjaPemda()
    {
        // setTahunKinerja(request()->tahun_kinerja);

        $sasaranStrategisRpjmd = SasaranStrategisRpjmd::selectRaw('id, sasaran_strategis_id, indikator_sasaran_strategis_id, target_1, target_2, target_3, target_4, target_5, realisasi_1, realisasi_2, realisasi_3, realisasi_4, realisasi_5, capaian_1, capaian_2, capaian_3, capaian_4, capaian_5, capaian_terhadap_target_akhir, rata_nasional, peringkat_nasional, penghargaan, perbandingan_realisasi_tahun_1, perbandingan_realisasi_tahun_2, perbandingan_realisasi_tahun_3, perbandingan_realisasi_tahun_4, perbandingan_realisasi_tahun_5, perbandingan_realisasi_target_1, perbandingan_realisasi_target_2, perbandingan_realisasi_target_3, perbandingan_realisasi_target_4, perbandingan_realisasi_target_5')
            ->tahunMulai()
            ->with([
                'sasaranStrategis:id,sasaran',
                'indikatorSasaranStrategis:id,indikator',
            ]);

        $sasaranStrategisRpjmd = CompactQuery::compactWithoutPagination($sasaranStrategisRpjmd, 'indikator_sasaran_strategis_id')
            ->groupBy([
                'sasaran_strategis_id',
            ])
            ->values();

        return response()->json([
            'data' => $sasaranStrategisRpjmd,
        ]);
    }

    public function capaianKinerjaEfisiensiAnggaran()
    {
        // setTahunKinerja(request()->tahun_kinerja);

        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
            getTahunKinerja(),
        ]));

        $sasaranStrategisRpjmd = Cache::remember($cacheKey, 60, function () {
            $sasaranStrategisRpjmd = SasaranStrategisRpjmd::selectRaw('*')
                ->tahunMulai()
                ->with([
                    'sasaranStrategis',
                    'indikatorSasaranStrategis',
                ]);

            $sasaranStrategisRpjmd = CompactQuery::compactWithoutPagination($sasaranStrategisRpjmd, 'indikator_sasaran_strategis_id')
                ->transform(function ($item) {
                    $program = KinerjaProgram::tahunKinerja()
                        ->select('anggaran', 'realisasi_anggaran')
                        ->where('satuan_kerja_id', $item->satuan_kerja_id)
                        ->whereHas('satkerIku', function ($query) use ($item) {
                            return $query->where('indikator_sasaran_strategis_id', $item->indikator_sasaran_strategis_id);
                        })
                        ->get();

                    $item->target_anggaran_program = $program->sum('anggaran');
                    $item->realisasi_anggaran_program = $program->sum('realisasi_anggaran');

                    return $item;
                })
                ->groupBy([
                    'sasaran_strategis_id',
                ])
                ->values();

            return $sasaranStrategisRpjmd;
        });

        return response()->json([
            'data' => $sasaranStrategisRpjmd,
        ]);
    }

    public function programInovatif(Request $request)
    {
        // setTahunKinerja(request()->tahun_kinerja);

        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
            getTahunKinerja(),
        ]));

        $kinerjaSubKegiatan = Cache::remember($cacheKey, 60, function () {
            $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()
                ->whereNotNull('inovasi_uraian')
                ->with([
                    'satuanKerja',
                    'sasaranStrategisRpjmd.sasaranStrategis',
                    'sasaranStrategisRpjmd.indikatorSasaranStrategis',
                ]);

            $sortBySasaran = $this->sortBySasaranClosure();

            $sortByIku = $this->sortByIkuClosure();

            $kinerjaSubKegiatan = CompactQuery::compact($kinerjaSubKegiatan, true, $sortBySasaran, 'sub_kegiatan_id', $sortByIku);

            return $kinerjaSubKegiatan;
        });

        return response()->json($kinerjaSubKegiatan);
    }

    public function capaianKinerjaKeuangan()
    {
        //  setTahunKinerja(request()->tahun_kinerja);

        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
            getTahunKinerja(),
        ]));

        $kinerjaSubKegiatan = Cache::remember($cacheKey, 60, function () {
            $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()
                ->with([
                    'satuanKerja',
                    'kinerjaKegiatan.kegiatan',
                    'kinerjaProgram.program',
                    'sasaranStrategisRpjmd.sasaranStrategis',
                    'sasaranStrategisRpjmd.indikatorSasaranStrategis',
                    'subKegiatan',
                ]);

            $sortBySasaran = $this->sortBySasaranClosure();

            $sortByIku = $this->sortByIkuClosure();

            $kinerjaSubKegiatan = CompactQuery::compact($kinerjaSubKegiatan, true, $sortBySasaran, 'sub_kegiatan_id', $sortByIku);

            return $kinerjaSubKegiatan;
        });

        return response()->json($kinerjaSubKegiatan);
    }

    public function capaianKinerjaSubKegiatan()
    {
        (request()->tahun_kinerja);

        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
            getTahunKinerja(),
        ]));

        $kinerjaSubKegiatan = Cache::remember($cacheKey, 60, function () {
            $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()
                ->with([
                    'satuanKerja',
                    'kinerjaKegiatan.kegiatan',
                    'kinerjaProgram.program',
                    'sasaranStrategisRpjmd.sasaranStrategis',
                    'sasaranStrategisRpjmd.indikatorSasaranStrategis',
                    'subKegiatan',
                ]);

            $sortBySasaran = $this->sortBySasaranClosure();

            $sortByIku = $this->sortByIkuClosure();

            $kinerjaSubKegiatan = CompactQuery::compact($kinerjaSubKegiatan, true, $sortBySasaran, 'sub_kegiatan_id', $sortByIku);

            return $kinerjaSubKegiatan;
        });

        return response()->json($kinerjaSubKegiatan);
    }

    public function rencanaAksi()
    {
        // setTahunKinerja(request()->tahun_kinerja);

        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
            getTahunKinerja(),
        ]));

        $kinerjaLangkahAksi = Cache::remember($cacheKey, 60, function () {
            $kinerjaLangkahAksi = KinerjaLangkahAksi::selectRaw('id, sasaran_strategis_rpjmd_id, sasaran_strategis_pd_id, kinerja_program_id, kinerja_kegiatan_id, kinerja_sub_kegiatan_id, langkah_aksi, indikator, target_bulanan, target')
                ->tahunKinerja()
                ->with([
                    'sasaranStrategisRpjmd:id,sasaran_strategis_id,indikator_sasaran_strategis_id,target_1,target_2,target_3,target_4,target_5',
                    'sasaranStrategisRpjmd.sasaranStrategis:id,sasaran',
                    'sasaranStrategisRpjmd.indikatorSasaranStrategis:id,indikator',

                    'sasaranStrategisPd:id,sasaran_strategis_satker,iku',

                    'kinerjaProgram:id,program_id',
                    'kinerjaProgram.program:id,nama',

                    'kinerjaKegiatan:id,kegiatan_id',
                    'kinerjaKegiatan.kegiatan:id,nama',

                    'kinerjaSubKegiatan:id,sub_kegiatan_id',
                    'kinerjaSubKegiatan.subKegiatan:id,nama',
                ]);

            $sortBySasaran = $this->sortBySasaranClosure();
            $sortByIku = $this->sortByIkuClosure();

            $kinerjaLangkahAksi = CompactQuery::compactWithoutPagination($kinerjaLangkahAksi, $sortBySasaran, 'langkah_aksi', $sortByIku)
                ->groupby([
                    'sasaranStrategisRpjmd.sasaran_strategis_id',
                    'sasaranStrategisRpjmd.indikator_sasaran_strategis_id',
                    'sasaranStrategisPd.sasaran_strategis_satker',
                    'sasaranStrategisPd.iku',
                    'kinerjaProgram.program_id',
                    'kinerjaKegiatan.kegiatan_id',
                    'kinerjaSubKegiatan.sub_kegiatan_id',
                ])
                ->transform(function ($bySasaran) {
                    return $bySasaran->values()
                        ->transform(function ($byIku) {
                            return $byIku->values()
                                ->transform(function ($bySatkerSasaran) {
                                    return $bySatkerSasaran->values()
                                        ->transform(function ($bySatkerIku) {
                                            return $bySatkerIku->values()
                                                ->transform(function ($byProgram) {
                                                    return $byProgram->values()
                                                        ->transform(function ($byKegiatan) {
                                                            return $byKegiatan->values()
                                                                ->transform(function ($bySubKegiatan) {
                                                                    return $bySubKegiatan->values();
                                                                });
                                                        });
                                                });
                                        });
                                });
                        });
                })
                ->values()
                ->paginate(1);

            return $kinerjaLangkahAksi;
        });

        return response()->json($kinerjaLangkahAksi);
    }

    public function definisiOperasional(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['nullable', 'numeric'],
            'sasaran_strategis_rpjmd_id' => ['required', 'numeric'],
            'programId' => ['nullable', 'numeric'],
            'kegiatanId' => ['nullable', 'numeric'],
            'subKegiatanId' => ['nullable', 'numeric'],
        ]);
        // return response()->json($request->all());
        if (isset($validated['sasaran_strategis_rpjmd_id'])) {
            $data = SasaranStrategisPd::whereId($validated['sasaran_strategis_rpjmd_id'])->paginate(20);
        }

        if (isset($validated['programId'])) {
            $data = KinerjaProgram::whereId($validated['programId'])->paginate(20);
        }
        if (isset($validated['kegiatanId'])) {
            $data = KinerjaKegiatan::whereId($validated['kegiatanId'])->paginate(20);
        }

        if (isset($validated['subKegiatanId'])) {
            $data = KinerjaSubKegiatan::whereId($validated['subKegiatanId'])->paginate(20);
        }

        return response()->json($data);
    }

    public function capaianKinerjaAktivitas()
    {
        (request()->tahun_kinerja);

        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
            getTahunKinerja(),
        ]));

        $kinerjaLangkahAksi = Cache::remember($cacheKey, 60, function () {
            $kinerjaLangkahAksi = KinerjaLangkahAksi::tahunKinerja()
                ->with([
                    'satuanKerja',
                    'sasaranStrategisRpjmd.sasaranStrategis',
                    'sasaranStrategisRpjmd.indikatorSasaranStrategis',
                    'sasaranStrategisPd',
                    'kinerjaProgram.program',
                    'kinerjaKegiatan.kegiatan',
                    'kinerjaSubKegiatan.subKegiatan',
                ]);

            $sortBySasaran = $this->sortBySasaranClosure();

            $sortByIku = $this->sortByIkuClosure();

            $kinerjaLangkahAksi = CompactQuery::compact($kinerjaLangkahAksi, true, $sortBySasaran, 'langkah_aksi', $sortByIku);

            return $kinerjaLangkahAksi;
        });

        return response()->json($kinerjaLangkahAksi);
    }

    public function capaianKinerjaKegiatan(Request $request)
    {
        // setTahunKinerja(request()->tahun_kinerja);

        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
            getTahunKinerja(),
        ]));

        $kinerjaKegiatan = Cache::remember($cacheKey, 60, function () {
            $kinerjaKegiatan = KinerjaKegiatan::tahunKinerja()
                ->with([
                    'satuanKerja',
                    'sasaranStrategisRpjmd.sasaranStrategis',
                    'sasaranStrategisRpjmd.indikatorSasaranStrategis',
                    'kinerjaProgram.program',
                    'kegiatan',
                ]);

            $sortBySasaran = $this->sortBySasaranClosure();

            $sortByIku = $this->sortByIkuClosure();

            $kinerjaKegiatan = CompactQuery::compact($kinerjaKegiatan, true, $sortBySasaran, 'kegiatan_id', $sortByIku);

            return $kinerjaKegiatan;
        });

        return response()->json($kinerjaKegiatan);
    }

    public function cascading()
    {
        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
            getTahunKinerja(),
        ]));

        $sasaranStrategisRpjmd = Cache::remember($cacheKey, 60, function () {
            $sasaranStrategisRpjmd = DiagramSasaran::getCompact(null, 2);

            return $sasaranStrategisRpjmd;
        });

        return response()->json($sasaranStrategisRpjmd);
    }

    private function sortBySasaranClosure()
    {
        return function ($item) {
            return $item->sasaranStrategisRpjmd ? 1 : PHP_INT_MAX;
        };
    }

    private function sortByIkuClosure()
    {
        return function ($item) {
            return $item->sasaranStrategisRpjmd ? $item->sasaranStrategisRpjmd->indikator_sasaran_strategis_id : PHP_INT_MAX;
        };
    }
}
