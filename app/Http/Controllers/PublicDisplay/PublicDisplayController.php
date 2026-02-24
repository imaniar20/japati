<?php

namespace App\Http\Controllers\PublicDisplay;

use App\Http\Controllers\Controller;
use App\Models\AnggaranCapaianIku;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\NilaiSakipPemda;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use App\Models\VisiMisiRpjmd;
use App\Services\DiagramSasaran;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PublicDisplayController extends Controller
{
    public function arsitekturKinerja()
    {
        $cacheKey = md5(json_encode([
            __METHOD__,
            request()->all(),
            getTahunKinerja(),
        ]));

        $data = Cache::remember($cacheKey, 60, function () {
            $data = DiagramSasaran::getCompact(satuanKerjaId: null);

            return $data;
        });

        return response()->json($data);
    }

    public function cascadingJson(Request $request)
    {
        $validated = $request->validate([
            'tahun_kinerja' => ['nullable', 'integer'],
        ]);

        // setTahunKinerja($validated['tahun_kinerja'] ?? getTahunKinerja());

        $getKeyTahun = function ($key, $offset = 0) {
            $index = (getTahunKinerja() - getTahunMulai()) + 1 + $offset;

            if ($index < 1) {
                $index = 'baseline';
            }

            return "{$key}_{$index}";
        };

        // convert penamaan kolom capaian per tahun
        $selectColumnByTahun = [
            $getKeyTahun('target').' AS target',
            $getKeyTahun('realisasi').' AS realisasi',
            $getKeyTahun('capaian').' AS capaian',
        ];

        $data = SasaranStrategisRpjmd::tahunMulai()
            ->select(['id', 'satuan_kerja_id', 'sasaran_strategis_id', 'indikator_sasaran_strategis_id', 'satuan', 'tahun_mulai', ...$selectColumnByTahun])
            ->with([
                'satuanKerja:satuan_kerja_id,satuan_kerja_nama',

                'sasaranStrategis:id,nomor,sasaran',
                'indikatorSasaranStrategis:id,nomor,indikator',

                'sasaranStrategisPd' => fn (Builder|SasaranStrategisPd $query) => $query
                    ->tahunMulai()
                    ->select(['id', 'sasaran_strategis_rpjmd_id', 'sasaran_strategis_satker', 'iku', 'satuan', ...$selectColumnByTahun])
                    ->with([
                        'kinerjaProgram' => fn (Builder|KinerjaProgram $query) => $query
                            ->tahunKinerja()
                            ->select('id', 'sasaran_strategis_pd_id', 'sasaran', 'indikator', 'program_id', 'target', 'realisasi', 'capaian', 'satuan')
                            ->with([
                                'program:id,nama',

                                'kinerjaKegiatan' => fn (Builder|KinerjaKegiatan $query) => $query
                                    ->tahunKinerja()
                                    ->select('id', 'kinerja_program_id', 'sasaran', 'indikator', 'kegiatan_id', 'target', 'realisasi', 'capaian', 'satuan')
                                    ->with([
                                        'kegiatan:id,nama',

                                        'kinerjaSubKegiatan' => fn (Builder|KinerjaSubKegiatan $query) => $query
                                            ->tahunKinerja()
                                            ->select('id', 'kinerja_kegiatan_id', 'sasaran', 'indikator', 'indikator_kemendagri', 'sub_kegiatan_id', 'satuan', 'target', 'realisasi', 'capaian')
                                            ->with([
                                                'subKegiatan:id,nama',
                                            ]),
                                    ]),
                            ]),
                    ]),
            ])
            ->get();

        return response()->json($data);
    }

    /**
     * 1. 14 Indek Pembangunan Manusia dari indikator  tujuan
     * 2. 15 Laju pertumbuhan ekonomi dari indikator  tujuan
     * 3. 55 Tingkat pengangguran terbuka dari indikator sasaran strategis
     * 4. 42 Persentase penduduk miskin dari indikator sasaran strategis
     * 5. Gini Ratio ngetik manual
     */
    public function progresKinerjaMakro()
    {
        $numberingMap = [
            14 => 1,
            15 => 2,

            55 => 3,
            42 => 4,
        ];

        $visiMisi = VisiMisiRpjmd::tahunMulai(2024)
            ->select('indikator_tujuan_id', 'tahun_mulai', 'target_1', 'target_2', 'target_3', 'target_4', 'target_5', 'realisasi_1', 'realisasi_2', 'realisasi_3', 'realisasi_4', 'realisasi_5', 'capaian_1', 'capaian_2', 'capaian_3', 'capaian_4', 'capaian_5')
            ->with([
                'indikatorTujuan:id,indikator',
            ])
            ->whereIn('indikator_tujuan_id', [14, 15])
            ->latest()
            ->get()
            ->keyBy('indikator_tujuan_id')
            ->map(fn (VisiMisiRpjmd $item) => [
                'tahun_mulai' => $item->tahun_mulai,
                'indikator' => $item->indikatorTujuan->indikator,
                'indikator_nomor' => $numberingMap[$item->indikator_tujuan_id],
                'target_1' => $item->target_1,
                'target_2' => $item->target_2,
                'target_3' => $item->target_3,
                'target_4' => $item->target_4,
                'target_5' => $item->target_5,
                'realisasi_1' => $item->realisasi_1,
                'realisasi_2' => $item->realisasi_2,
                'realisasi_3' => $item->realisasi_3,
                'realisasi_4' => $item->realisasi_4,
                'realisasi_5' => $item->realisasi_5,
                'capaian_1' => $item->capaian_1,
                'capaian_2' => $item->capaian_2,
                'capaian_3' => $item->capaian_3,
                'capaian_4' => $item->capaian_4,
                'capaian_5' => $item->capaian_5,
            ]);

        $sasaranStrategis = SasaranStrategisRpjmd::tahunMulai(2024)
            ->select('indikator_sasaran_strategis_id', 'tahun_mulai', 'target_1', 'target_2', 'target_3', 'target_4', 'target_5', 'realisasi_1', 'realisasi_2', 'realisasi_3', 'realisasi_4', 'realisasi_5', 'capaian_1', 'capaian_2', 'capaian_3', 'capaian_4', 'capaian_5')
            ->with([
                'indikatorSasaranStrategis:id,indikator',
            ])
            ->whereIn('indikator_sasaran_strategis_id', [55, 42])
            ->latest()
            ->get()
            ->keyBy('indikator_sasaran_strategis_id')
            ->map(fn (SasaranStrategisRpjmd $item) => [
                'tahun_mulai' => $item->tahun_mulai,
                'indikator' => $item->indikatorSasaranStrategis->indikator,
                'indikator_nomor' => $numberingMap[$item->indikator_sasaran_strategis_id],
                'target_1' => $item->target_1,
                'target_2' => $item->target_2,
                'target_3' => $item->target_3,
                'target_4' => $item->target_4,
                'target_5' => $item->target_5,
                'realisasi_1' => $item->realisasi_1,
                'realisasi_2' => $item->realisasi_2,
                'realisasi_3' => $item->realisasi_3,
                'realisasi_4' => $item->realisasi_4,
                'realisasi_5' => $item->realisasi_5,
                'capaian_1' => $item->capaian_1,
                'capaian_2' => $item->capaian_2,
                'capaian_3' => $item->capaian_3,
                'capaian_4' => $item->capaian_4,
                'capaian_5' => $item->capaian_5,
            ]);

        $manualData = collect([
            [
                'tahun_mulai' => 2024,
                'indikator' => 'Gini Ratio',
                'indikator_nomor' => 5,
                'target_1' => '-',
                'target_2' => '-',
                'target_3' => '-',
                'target_4' => '-',
                'target_5' => '-',
                'realisasi_1' => '0,428',
                'realisasi_2' => null,
                'realisasi_3' => null,
                'realisasi_4' => null,
                'realisasi_5' => null,
                'capaian_1' => 0,
                'capaian_2' => 0,
                'capaian_3' => 0,
                'capaian_4' => 0,
                'capaian_5' => 0,
            ],
        ]);

        $data = $visiMisi->merge($sasaranStrategis)->merge($manualData)->sortBy('indikator_nomor')->values();

        return response()->json($data);
    }

    public function progresRataCapaianIku()
    {
        $iku = SasaranStrategisRpjmd::tahunMulai()
            ->select('id', 'indikator_sasaran_strategis_id', 'tahun_mulai', 'target_1', 'target_2', 'target_3', 'target_4', 'target_5', 'realisasi_1', 'realisasi_2', 'realisasi_3', 'realisasi_4', 'realisasi_5', 'capaian_1', 'capaian_2', 'capaian_3', 'capaian_4', 'capaian_5')
            ->with([
                'indikatorSasaranStrategis:id,nomor,indikator',
            ])
            ->latest()
            ->get()
            ->keyBy('indikator_sasaran_strategis_id')
            ->sortBy('indikatorSasaranStrategis.nomor')
            ->values();

        $anggaran = AnggaranCapaianIku::tahunKinerja()->first(['terpakai', 'tidak_terpakai', 'efisiensi']);

        return response()->json([
            'iku' => $iku,
            'anggaran' => $anggaran,
        ]);
    }

    public function progresRataKenaikanRealisasi()
    {
        $data = SasaranStrategisRpjmd::tahunMulai()
            ->select('id', 'indikator_sasaran_strategis_id', 'tahun_mulai', 'realisasi_baseline', 'realisasi_1', 'realisasi_2', 'realisasi_3', 'realisasi_4', 'realisasi_5', 'capaian_baseline', 'capaian_1', 'capaian_2', 'capaian_3', 'capaian_4', 'capaian_5')
            ->with([
                'indikatorSasaranStrategis:id,nomor,indikator',
            ])
            ->latest()
            ->get()
            ->keyBy('indikator_sasaran_strategis_id')
            ->sortBy('indikatorSasaranStrategis.nomor')
            ->values();

        return response()->json($data);
    }

    public function progresNilaiSakipPemda()
    {
        $nilaiRataOpd = DB::select('SELECT * FROM nilai_sakip_dashboard_get_average_nilai_by_tahun()');
        $nilaiRataOpdKomponen = DB::select('SELECT * FROM nilai_sakip_dashboard_get_average_nilai_komponen_by_tahun()');
        $nilaiDistribusiOpd = DB::select('SELECT * FROM nilai_sakip_dashboard_get_nilai_distribution_by_tahun(?)', [getTahunKinerja()]);
        $data = NilaiSakipPemda::all();

        return response()->json([
            'success' => true,
            'data' => $data,
            'nilai_rata_opd' => $nilaiRataOpd,
            'nilai_rata_opd_komponen' => $nilaiRataOpdKomponen,
            'nilai_distribusi_opd' => $nilaiDistribusiOpd,
            'message' => 'Data retrieved successfully',
        ]);
    }
}
