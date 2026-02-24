<?php

namespace App\Http\Controllers;

use App\Exports\RankRaporKinerjaExport;
use App\Models\Ekinerja\IkiBulanan;
use App\Models\KinerjaLangkahAksi;
use App\Models\KinerjaSubKegiatan;
use App\Models\PenyebabKegagalan;
use App\Models\PerubahanJumlahOutputV2;
use App\Models\Role;
use App\Models\SatuanKerja;
use App\Models\SKP;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class RaporKinerjaController extends Controller
{
    public function diagram(int $triwulan, Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
            'tahun_kinerja' => ['nullable', 'numeric'],
            'is_external' => ['nullable', 'boolean'],
        ]);

        $tahunKinerja = $validated['tahun_kinerja'] ?? getTahunKinerja();

        $satuanKerja = SatuanKerja::find($validated['satuan_kerja_id']);

        $isExternal = $validated['is_external'] ?? null;

        if (! $satuanKerja) {
            return response()->json([
                'success' => false,
                'message' => 'Satuan kerja tidak ditemukan',
            ], 400);
        }

        $rank = $this->calculateRank($triwulan, $satuanKerja->satuan_kerja_id, $tahunKinerja, $isExternal);

        $dataTahun = KinerjaSubKegiatan::tahunKinerja($tahunKinerja)
            ->roleSatuanKerja($satuanKerja->satuan_kerja_id)
            // ->targetBulananTriwulan($triwulan)
            ->select('id', 'target_bulanan', 'realisasi_bulanan', 'anggaran_bulanan', 'realisasi_anggaran_bulanan')
            ->withCount([
                'penyebabKegagalan' => fn (Builder $query) => $query->where('triwulan', $triwulan),
            ])
            ->when(! is_null($isExternal), fn (Builder $query) => $query->where('is_external', $isExternal))
            ->get();

        $triwulanList = TRIWULAN_BULAN[$triwulan];

        $data = $dataTahun->filter(function (KinerjaSubKegiatan $item) use ($triwulanList) {
            $isKinerjaTriwulan = false;

            foreach ($triwulanList as $bulan) {
                $isKinerjaTriwulan = $isKinerjaTriwulan || (float) $item->target_bulanan[$bulan] > 0;
            }

            return $isKinerjaTriwulan;
        })
            ->values();

        $tercapai = $data->filter(function ($item) use ($triwulanList) {
            $target = 0;
            $realisasi = 0;

            foreach ($triwulanList as $bulan) {
                $target += (float) $item->target_bulanan[$bulan];
                $realisasi += (float) $item->realisasi_bulanan[$bulan];
            }

            return $realisasi >= $target;
        })
            ->values();
        $tidakTercapai = $data->whereNotIn('id', $tercapai->pluck('id'))->values();

        $total = $data->count();
        $jumlahTercapai = $tercapai->count();
        $jumlahTidakTercapai = $total - $jumlahTercapai;
        $jumlahPenyebabTidakTercapai = $tidakTercapai->sum('penyebab_kegagalan_count');
        $capaian = $total ? round($jumlahTercapai / $total * 100, 2) : 0;
        $totalAnggaran = $dataTahun->sum(function ($item) use ($triwulanList) {
            $total = 0;

            foreach ($triwulanList as $bulan) {
                $total += (float) $item->anggaran_bulanan[$bulan];
            }

            return $total;
        });
        $anggaranTerserap = $dataTahun->sum(function ($item) use ($triwulanList) {
            $total = 0;

            foreach ($triwulanList as $bulan) {
                $total += (float) $item->realisasi_anggaran_bulanan[$bulan];
            }

            return $total;
        });
        $anggaranTidakTerserap = ($anggaranTidakTerserap = $totalAnggaran - $anggaranTerserap) > 0 ? $anggaranTidakTerserap : 0;
        $efisiensiAnggaran = $jumlahTidakTercapai ? '-' : round($totalAnggaran ? ($anggaranTidakTerserap / $totalAnggaran * 100) : 0, 2);

        $anggaranTidakTercapai = $tidakTercapai->sum(function ($item) use ($triwulanList) {
            $total = 0;

            foreach ($triwulanList as $bulan) {
                $total += (float) $item->anggaran_bulanan[$bulan];
            }

            return $total;
        });

        $langkahAksiTidakTercapai = KinerjaLangkahAksi::tahunKinerja($tahunKinerja)
            ->roleSatuanKerja($satuanKerja->satuan_kerja_id)
            ->whereIn('kinerja_sub_kegiatan_id', $tidakTercapai->pluck('id'))
            ->select('id', 'penyebab_kegagalan_id', 'anggaran')
            ->get();

        $jumlahLangkahAksiTidakTercapaiAwal = $langkahAksiTidakTercapai->whereNull('penyebab_kegagalan_id')->count();
        $jumlahLangkahAksiTidakTercapaiAkhir = $langkahAksiTidakTercapai->count();
        $usulanAnggaran = $langkahAksiTidakTercapai->whereNotNull('penyebab_kegagalan_id')->sum('anggaran');

        return response()->json([
            'jumlah' => $total,
            'tercapai' => $jumlahTercapai,
            'tidak_tercapai' => $jumlahTidakTercapai,
            'penyebab_tidak_tercapai' => $jumlahPenyebabTidakTercapai,
            'capaian' => $capaian,
            'anggaran' => $totalAnggaran,
            'anggaran_terserap' => $anggaranTerserap,
            'anggaran_tidak_terserap' => $anggaranTidakTerserap,
            'anggaran_tidak_tercapai' => $anggaranTidakTercapai,
            'usulan_anggaran' => $usulanAnggaran,
            'efisiensi_anggaran' => $efisiensiAnggaran,
            'satuan_kerja_nama' => $satuanKerja->satuan_kerja_nama,
            'langkah_aksi_tidak_tercapai_awal' => $jumlahLangkahAksiTidakTercapaiAwal,
            'langkah_aksi_tidak_tercapai_akhir' => $jumlahLangkahAksiTidakTercapaiAkhir,
            'rank' => $rank,
            'tahun_kinerja' => $tahunKinerja,
        ]);
    }

    /**
     * Rank dinas yang paling jelek adalah yang tidak input output (capaian kinerjanya 0)
     * Kalau ada lebih dari 1 dinas yang tidak input output (capaian kinerjanya 0), maka dinas yang jumlah sasaran sub kegiatannya lebih banyak dia yang rank lebih bagus
     * Setelah itu baru dinas yang tidak input anggaran (rules yg sama kaya sekarang)
     */
    private function calculateRank(int $triwulan, string $satkerId, ?int $tahunKinerja = null, ?bool $isExternal = null): array
    {
        $cacheKey = md5(json_encode([
            __METHOD__,
            $triwulan,
            $satkerId,
            getTahunKinerja(),
            $tahunKinerja,
            $isExternal,
        ]));

        /**
         * cache 10 minutes
         *
         * @var \Illuminate\Support\Collection
         */
        $list = Cache::remember($cacheKey, 60 * 10, function () use ($triwulan, $tahunKinerja, $isExternal) {
            $triwulanList = TRIWULAN_BULAN[$triwulan];

            $jumlahSasaranSubKegiatan = KinerjaSubKegiatan::tahunKinerja($tahunKinerja)
                ->targetBulananTriwulan($triwulan)
                ->selectRaw('satuan_kerja_id, COUNT(*) total')
                ->when(! is_null($isExternal), fn (Builder $query) => $query->where('is_external', $isExternal))
                ->groupBy('satuan_kerja_id')
                ->pluck('total', 'satuan_kerja_id');
            $perubahanJumlahOutputList = PerubahanJumlahOutputV2::query()
                ->where('tahun_kinerja', $tahunKinerja ?? getTahunKinerja())
                ->get()
                ->keyBy('satuan_kerja_id');

            return KinerjaSubKegiatan::tahunKinerja($tahunKinerja)
                ->targetBulananTriwulan($triwulan)
                ->select('id', 'target_bulanan', 'realisasi_bulanan', 'anggaran_bulanan', 'realisasi_anggaran_bulanan', 'satuan_kerja_id')
                ->when(! is_null($isExternal), fn (Builder $query) => $query->where('is_external', $isExternal))
                ->get()
                ->groupBy('satuan_kerja_id')
                ->transform(function (Collection $items, int $satkerId) use ($triwulan, $triwulanList, $jumlahSasaranSubKegiatan, $perubahanJumlahOutputList) {
                    $tercapai = $items->filter(function ($items) use ($triwulanList) {
                        $target = 0;
                        $realisasi = 0;

                        foreach ($triwulanList as $bulan) {
                            $target += (float) $items->target_bulanan[$bulan];
                            $realisasi += (float) $items->realisasi_bulanan[$bulan];
                        }

                        return $realisasi >= $target;
                    })
                        ->count();

                    $capaian = $items->count() ? round($tercapai / $items->count() * 100, 2) : 0;

                    $jumlahTidakTercapai = $items->count() - $tercapai;
                    $totalAnggaran = $items->sum(function ($items) use ($triwulanList) {
                        $total = 0;

                        foreach ($triwulanList as $bulan) {
                            $total += (float) $items->anggaran_bulanan[$bulan];
                        }

                        return $total;
                    });
                    $anggaranTerserap = $items->sum(function ($items) use ($triwulanList) {
                        $total = 0;

                        foreach ($triwulanList as $bulan) {
                            $total += (float) $items->realisasi_anggaran_bulanan[$bulan];
                        }

                        return $total;
                    });
                    $anggaranTidakTerserap = ($anggaranTidakTerserap = $totalAnggaran - $anggaranTerserap) > 0 ? $anggaranTidakTerserap : 0;
                    $efisiensiAnggaran = $jumlahTidakTercapai ? '-' : round($totalAnggaran ? ($anggaranTidakTerserap / $totalAnggaran * 100) : 0, 2);

                    $flagAnggaran = $totalAnggaran <= 0 ? 0 : 1;
                    $flagCapaian = $capaian <= 0 ? 0 : 1;

                    $perubahanJumlahOutput = $perubahanJumlahOutputList[$satkerId] ?? null;

                    return [
                        'capaian' => $capaian,
                        'flag_capaian' => $flagCapaian,
                        'efisiensi_anggaran' => $efisiensiAnggaran > 10 ? -$efisiensiAnggaran : $efisiensiAnggaran, // jika > 10 maka dibuat negatif
                        'satuan_kerja_id' => $satkerId,
                        'flag_anggaran' => $flagAnggaran,
                        'jumlah_sasaran_sub_kegiatan' => ! $flagCapaian ? ($jumlahSasaranSubKegiatan[$satkerId] ?? 0) : PHP_INT_MAX, // hanya jika flag_capaian = 0
                        'perubahan_jumlah_output' => $perubahanJumlahOutput["tw{$triwulan}"] ?? 0,
                    ];
                })
                ->sortBy([
                    ['flag_capaian', 'desc'],
                    ['jumlah_sasaran_sub_kegiatan', 'desc'],
                    ['flag_anggaran', 'desc'],
                    ['capaian', 'desc'],
                    ['perubahan_jumlah_output', 'desc'],
                    ['efisiensi_anggaran', 'desc'],
                ])
                ->values();
        });

        /**
         * @var int $rank
         */
        $rank = $list->where('satuan_kerja_id', $satkerId)->keys()->first();

        return [
            'rank' => $rank + 1,
            'total' => $list->count(),
        ];
    }

    public function data(int $triwulan, Request $request)
    {
        $filter = $request->validate([
            'capaian' => ['nullable', 'in:tercapai,tidak-tercapai'],
            'is_external' => ['nullable', 'boolean'],
        ]);

        if (Role::isSuper() || Role::isviewRaporKinerja()) {
            $satkerId = $request->satuan_kerja_id;
        } else {
            $satkerId = Auth::user()->satuan_kerja_id;
        }

        if (! $satkerId) {
            return response()->json([]);
        }

        $isExternal = $filter['is_external'] ?? null;

        $data = KinerjaSubKegiatan::tahunKinerja()
            ->roleSatuanKerja($satkerId)
            ->targetBulananTriwulan($triwulan)
            ->select('id', 'target_bulanan', 'realisasi_bulanan', 'kegiatan_id', 'sub_kegiatan_id', 'sasaran', 'indikator')
            ->with([
                'kegiatan' => fn ($query) => $query->tahunKinerja(),
                'subKegiatan' => fn ($query) => $query->tahunKinerja(),
            ])
            ->withCount([
                'penyebabKegagalan' => fn (Builder $query) => $query->where('triwulan', $triwulan),
            ])
            ->when(! is_null($isExternal), fn (Builder $query) => $query->where('is_external', $isExternal))
            ->get();

        $triwulanList = TRIWULAN_BULAN[$triwulan];

        if (isset($filter['capaian'])) {
            $data = $data->filter(function ($item) use ($triwulanList, $filter) {
                $target = 0;
                $realisasi = 0;

                foreach ($triwulanList as $bulan) {
                    $target += (float) $item->target_bulanan[$bulan];
                    $realisasi += (float) $item->realisasi_bulanan[$bulan];
                }

                return $filter['capaian'] == 'tercapai' ? $realisasi >= $target : $realisasi < $target;
            })
                ->values();
        }

        $data->transform(function ($item) use ($triwulanList) {
            $target = 0;
            $realisasi = 0;

            foreach ($triwulanList as $bulan) {
                $target += (float) $item->target_bulanan[$bulan];
                $realisasi += (float) $item->realisasi_bulanan[$bulan];
            }

            $item->is_tercapai = $realisasi >= $target;
            $item->_rowVariant = ! $item->is_tercapai ? 'danger' : '';

            return $item;
        });

        return response()->json($data);
    }

    public function report(Request $request, int $triwulan, bool $isExport = false)
    {
        $filter = $request->validate([
            'is_external' => ['nullable', 'boolean'],
        ]);

        $isExternal = $filter['is_external'] ?? null;

        $triwulanList = TRIWULAN_BULAN[$triwulan];
        $satkerList = SatuanKerja::query()->pluck('satuan_kerja_nama', 'satuan_kerja_id');
        $jumlahSasaranSubKegiatan = KinerjaSubKegiatan::tahunKinerja()
            ->targetBulananTriwulan($triwulan)
            ->selectRaw('satuan_kerja_id, COUNT(*) total')
            ->when(! is_null($isExternal), fn (Builder $query) => $query->where('is_external', $isExternal))
            ->groupBy('satuan_kerja_id')
            ->pluck('total', 'satuan_kerja_id');
        $perubahanJumlahOutputList = PerubahanJumlahOutputV2::query()
            ->where('tahun_kinerja', getTahunKinerja())
            ->get()
            ->keyBy('satuan_kerja_id');

        // original code from App\Http\Controllers\RaporKinerjaController::calculateRank
        $data = KinerjaSubKegiatan::tahunKinerja()
            ->targetBulananTriwulan($triwulan)
            ->select('id', 'target_bulanan', 'realisasi_bulanan', 'anggaran_bulanan', 'realisasi_anggaran_bulanan', 'satuan_kerja_id')
            ->when(! is_null($isExternal), fn (Builder $query) => $query->where('is_external', $isExternal))
            ->get()
            ->groupBy('satuan_kerja_id')
            ->transform(function (Collection $items, int $satkerId) use ($triwulan, $triwulanList, $jumlahSasaranSubKegiatan, $perubahanJumlahOutputList) {
                $tercapai = $items->filter(function ($items) use ($triwulanList) {
                    $target = 0;
                    $realisasi = 0;

                    foreach ($triwulanList as $bulan) {
                        $target += (float) $items->target_bulanan[$bulan];
                        $realisasi += (float) $items->realisasi_bulanan[$bulan];
                    }

                    return $realisasi >= $target;
                })
                    ->count();

                $capaian = $items->count() ? round($tercapai / $items->count() * 100, 2) : 0;

                $jumlahTidakTercapai = $items->count() - $tercapai;
                $totalAnggaran = $items->sum(function ($items) use ($triwulanList) {
                    $total = 0;

                    foreach ($triwulanList as $bulan) {
                        $total += (float) $items->anggaran_bulanan[$bulan];
                    }

                    return $total;
                });
                $anggaranTerserap = $items->sum(function ($items) use ($triwulanList) {
                    $total = 0;

                    foreach ($triwulanList as $bulan) {
                        $total += (float) $items->realisasi_anggaran_bulanan[$bulan];
                    }

                    return $total;
                });
                $anggaranTidakTerserap = ($anggaranTidakTerserap = $totalAnggaran - $anggaranTerserap) > 0 ? $anggaranTidakTerserap : 0;
                $efisiensiAnggaran = $jumlahTidakTercapai ? '-' : round($totalAnggaran ? ($anggaranTidakTerserap / $totalAnggaran * 100) : 0, 2);

                $flagAnggaran = $totalAnggaran <= 0 ? 0 : 1;
                $flagCapaian = $capaian <= 0 ? 0 : 1;

                $perubahanJumlahOutput = $perubahanJumlahOutputList[$satkerId] ?? null;

                return [
                    'capaian' => $capaian,
                    'flag_capaian' => $flagCapaian,
                    'efisiensi_anggaran' => $efisiensiAnggaran > 10 ? -$efisiensiAnggaran : $efisiensiAnggaran, // jika > 10 maka dibuat negatif
                    'satuan_kerja_id' => $satkerId,
                    'flag_anggaran' => $flagAnggaran,
                    'jumlah_sasaran_sub_kegiatan' => ! $flagCapaian ? ($jumlahSasaranSubKegiatan[$satkerId] ?? 0) : PHP_INT_MAX, // hanya jika flag_capaian = 0
                    'perubahan_jumlah_output' => $perubahanJumlahOutput["tw{$triwulan}"] ?? 0,
                ];
            })
            ->sortBy([
                ['flag_capaian', 'desc'],
                ['jumlah_sasaran_sub_kegiatan', 'desc'],
                ['flag_anggaran', 'desc'],
                ['capaian', 'desc'],
                ['perubahan_jumlah_output', 'desc'],
                ['efisiensi_anggaran', 'desc'],
            ])
            ->values()
            ->transform(function ($item, $rank) use ($triwulan, $satkerList, $perubahanJumlahOutputList, $isExternal) {
                // original code from App\Http\Controllers\RaporKinerjaController::diagram
                $data = KinerjaSubKegiatan::tahunKinerja()
                    ->roleSatuanKerja($item['satuan_kerja_id'])
                    ->targetBulananTriwulan($triwulan)
                    ->select('id', 'target_bulanan', 'realisasi_bulanan', 'anggaran_bulanan', 'realisasi_anggaran_bulanan')
                    ->withCount([
                        'penyebabKegagalan' => fn (Builder $query) => $query->where('triwulan', $triwulan),
                    ])
                    ->when(! is_null($isExternal), fn (Builder $query) => $query->where('is_external', $isExternal))
                    ->get();

                $triwulanList = TRIWULAN_BULAN[$triwulan];

                $tercapai = $data->filter(function ($item) use ($triwulanList) {
                    $target = 0;
                    $realisasi = 0;

                    foreach ($triwulanList as $bulan) {
                        $target += (float) $item->target_bulanan[$bulan];
                        $realisasi += (float) $item->realisasi_bulanan[$bulan];
                    }

                    return $realisasi >= $target;
                })
                    ->values();
                $tidakTercapai = $data->whereNotIn('id', $tercapai->pluck('id'))->values();

                $total = $data->count();
                $jumlahTercapai = $tercapai->count();
                $jumlahTidakTercapai = $total - $jumlahTercapai;
                $jumlahPenyebabTidakTercapai = $tidakTercapai->sum('penyebab_kegagalan_count');
                $capaian = $total ? round($jumlahTercapai / $total * 100, 2) : 0;
                $totalAnggaran = $data->sum(function ($item) use ($triwulanList) {
                    $total = 0;

                    foreach ($triwulanList as $bulan) {
                        $total += (float) $item->anggaran_bulanan[$bulan];
                    }

                    return $total;
                });
                $anggaranTerserap = $data->sum(function ($item) use ($triwulanList) {
                    $total = 0;

                    foreach ($triwulanList as $bulan) {
                        $total += (float) $item->realisasi_anggaran_bulanan[$bulan];
                    }

                    return $total;
                });
                $anggaranTidakTerserap = ($anggaranTidakTerserap = $totalAnggaran - $anggaranTerserap) > 0 ? $anggaranTidakTerserap : 0;
                $efisiensiAnggaran = $jumlahTidakTercapai ? '-' : round($totalAnggaran ? ($anggaranTidakTerserap / $totalAnggaran * 100) : 0, 2);

                $anggaranTidakTercapai = $tidakTercapai->sum(function ($item) use ($triwulanList) {
                    $total = 0;

                    foreach ($triwulanList as $bulan) {
                        $total += (float) $item->anggaran_bulanan[$bulan];
                    }

                    return $total;
                });

                $langkahAksiTidakTercapai = KinerjaLangkahAksi::tahunKinerja()
                    ->roleSatuanKerja($item['satuan_kerja_id'])
                    ->whereIn('kinerja_sub_kegiatan_id', $tidakTercapai->pluck('id'))
                    ->select('id', 'penyebab_kegagalan_id', 'anggaran')
                    ->get();

                $jumlahLangkahAksiTidakTercapaiAwal = $langkahAksiTidakTercapai->whereNull('penyebab_kegagalan_id')->count();
                $jumlahLangkahAksiTidakTercapaiAkhir = $langkahAksiTidakTercapai->count();
                $usulanAnggaran = $langkahAksiTidakTercapai->whereNotNull('penyebab_kegagalan_id')->sum('anggaran');
                $perubahanJumlahOutput = $perubahanJumlahOutputList[$item['satuan_kerja_id']] ?? null;

                return (object) [
                    'jumlah' => $total,
                    'tercapai' => $jumlahTercapai,
                    'tidak_tercapai' => $jumlahTidakTercapai,
                    'penyebab_tidak_tercapai' => $jumlahPenyebabTidakTercapai,
                    'capaian' => $capaian,
                    'anggaran' => $totalAnggaran,
                    'anggaran_terserap' => $anggaranTerserap,
                    'anggaran_tidak_terserap' => $anggaranTidakTerserap,
                    'anggaran_tidak_tercapai' => $anggaranTidakTercapai,
                    'usulan_anggaran' => $usulanAnggaran,
                    'efisiensi_anggaran' => $efisiensiAnggaran,
                    'satuan_kerja_nama' => $satkerList[$item['satuan_kerja_id']],
                    'satuan_kerja_id' => $item['satuan_kerja_id'],
                    'langkah_aksi_tidak_tercapai_awal' => $jumlahLangkahAksiTidakTercapaiAwal,
                    'langkah_aksi_tidak_tercapai_akhir' => $jumlahLangkahAksiTidakTercapaiAkhir,
                    'rank' => $rank + 1,
                    'perubahanJumlahOutput' => $perubahanJumlahOutput["tw{$triwulan}"] ?? 0,
                ];
            });

        if ($isExport) {
            return $data;
        }

        return response()->json($data);
    }

    public function reportExport(int $triwulan, Request $request)
    {
        $data = $this->report($request, $triwulan, true);

        return Excel::download(new RankRaporKinerjaExport($data), 'Rank Rapor Kinerja Triwulan'.$triwulan.'.xlsx');
    }

    public function langkahAksiPerbaikan(int $triwulan, Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        $satuanKerja = SatuanKerja::find($validated['satuan_kerja_id']);

        $data = PenyebabKegagalan::query()
            ->select('id', 'kinerja_sub_kegiatan_id', 'penyebab')
            ->whereHas('kinerjaSubKegiatan', fn (Builder|KinerjaSubKegiatan $query) => $query
                ->roleSatuanKerja($satuanKerja->satuan_kerja_id)
                ->tahunKinerja()
            )
            ->where('triwulan', $triwulan)
            ->with([
                'kinerjaSubKegiatan:id,sub_kegiatan_id,sasaran,indikator',
                'kinerjaSubKegiatan.subKegiatan:id,nama',
                'langkahAksi:penyebab_kegagalan_id,langkah_aksi',
                'kinerjaSubKegiatan.kinerjaLangkahAksi',
            ])
            ->get();

        $skpIds = SKP::tahunKinerja()
            ->roleSatuanKerja($satuanKerja->satuan_kerja_id)
            ->where('is_skp', true)
            ->where('model_class', KinerjaSubKegiatan::class)
            ->whereIn('model_id', $data->pluck('kinerja_sub_kegiatan_id'))
            ->pluck('id');

        $ikiList = IkiBulanan::query()
            ->selectRaw('sasaran_kinerja_id, indikator_bulanan AS indikator_langkah_aksi')
            ->with([
                'sasaranKinerja:id,sakip_id',
                'sasaranKinerja.skp:id,model_class,model_id',
            ])
            ->whereHas('sasaranKinerja', fn (Builder $query) => $query
                ->whereIn('sakip_id', $skpIds)
                ->where('sakip_type', 'App\Model\Sakip\KinerjaSubKegiatan')
            )
            ->whereIn('bulan', range(1, 3 * $triwulan))
            ->get()
            ->groupBy('sasaranKinerja.skp.model_id');

        $data = $data->groupBy('kinerja_sub_kegiatan_id')
            ->map(function (Collection $items) use ($ikiList) {
                $first = $items->first();

                return [
                    'sub_kegiatan' => $first->kinerjaSubKegiatan->subKegiatan->nama,
                    'sasaran' => $first->kinerjaSubKegiatan->sasaran,
                    'indikator' => $first->kinerjaSubKegiatan->indikator,
                    'langkah_aksi' => $first->kinerjaSubKegiatan->kinerjaLangkahAksi,
                    'langkah_aksi_terintegrasi' => ($ikiList[$first->kinerja_sub_kegiatan_id] ?? collect())->pluck('indikator_langkah_aksi'),
                    'penyebab' => $items->map(function ($item) {
                        unset($item->kinerjaSubKegiatan, $item->kinerja_sub_kegiatan_id, $item->id);

                        return $item;
                    }),
                ];
            })
            ->values();

        return response()->json($data);
    }
}
