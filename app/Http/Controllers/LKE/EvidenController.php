<?php

namespace App\Http\Controllers\LKE;

use App\Http\Controllers\Controller;
use App\Models\LKE\Eviden;
use App\Models\LKE\Komponen;
use App\Models\LKE\Kriteria;
use App\Models\LKE\Penilaian;
use App\Models\LKE\PenilaianKomponen;
use App\Models\LKE\RiwayatEviden;
use App\Models\LKE\SubKomponen;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EvidenController extends Controller
{
    public function index()
    {
        $data = Komponen::tahunKinerja()
            ->with([
                'subKomponen' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria.eviden' => fn (Builder $query) => $query->roleSatuanKerja()->tahunKinerja()->orderBy('id'),
                'subKomponen.kriteria.eviden.parameter' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria.eviden.riwayat' => fn (Builder $query) => $query
                    ->whereHas('penilaian', fn (Builder $query) => $query
                        ->whereIn('status', [Penilaian::STATUS_IN_ASSESSMENT, Penilaian::STATUS_DONE]))
                    ->orderBy('id'),
                'subKomponen.kriteria.eviden.riwayat.parameter' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria.eviden.riwayat.parameterNilai' => fn (Builder $query) => $query->orderBy('id'),
                'subKomponen.kriteria.parameter' => fn (Builder $query) => $query->orderBy('nomor'),
            ])
            ->orderBy('nomor')
            ->get();

        $statusPenilaian = Penilaian::query()
            ->roleSatuanKerja()
            ->tahunKinerja()
            ->whereIn('status', [Penilaian::STATUS_IN_ASSESSMENT, Penilaian::STATUS_DONE])
            ->orderBy('id')
            ->value('status');

        if ($statusPenilaian == Penilaian::STATUS_DONE) {
            $data->transform(function (Komponen $komponen) {
                $totalSkorKomponen = 0;

                $komponen->subKomponen->transform(function (SubKomponen $subKomponen) use (&$totalSkorKomponen) {
                    $totalSkorSubKomponen = 0;

                    $subKomponen->kriteria->transform(function (Kriteria $kriteria) use (&$totalSkorSubKomponen) {
                        $getSkor = fn (Collection $riwayat) => $riwayat[$riwayat->count() - 1]->parameterNilai->skor;
                        $skorAkhir = $getSkor($kriteria->eviden->riwayat) * $kriteria->bobot;
                        $totalSkorSubKomponen += $skorAkhir;

                        $kriteria->skor_akhir = $skorAkhir;

                        return $kriteria;
                    });

                    $totalSkorKomponen += round($totalSkorSubKomponen, 2);
                    $subKomponen->total_skor = round($totalSkorSubKomponen, 2);

                    return $subKomponen;
                });

                $komponen->total_skor = round($totalSkorKomponen, 2);

                return $komponen;
            });
        }

        return response()->json(compact('data', 'statusPenilaian'));
    }

    public function index2()
    {
        $data = Komponen::tahunKinerja()
            ->with([
                'subKomponen' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria.eviden' => fn (Builder $query) => $query->roleSatuanKerja()->tahunKinerja()->orderBy('id'),
                'subKomponen.kriteria.eviden.parameter' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria.eviden.riwayat' => fn (Builder $query) => $query
                    ->whereHas('penilaian', fn (Builder $query) => $query
                        ->whereIn('status', [Penilaian::STATUS_DONE_2]))
                    ->orderBy('id'),
                'subKomponen.kriteria.eviden.riwayatPenilaianSebelumnya',
                'subKomponen.kriteria.eviden.riwayat.parameterNilai' => fn (Builder $query) => $query->orderBy('id'),
                'subKomponen.kriteria.parameter' => fn (Builder $query) => $query->orderBy('nomor'),
            ])
            ->orderBy('nomor')
            ->get();

        $isDone1 = Penilaian::query()
            ->roleSatuanKerja()
            ->tahunKinerja()
            ->whereIn('status', [Penilaian::STATUS_DONE])
            ->exists();

        if (! $isDone1) {
            return response()->json([
                'data' => [],
                'statusPenilaian' => null,
            ]);
        }

        $statusPenilaian = Penilaian::query()
            ->roleSatuanKerja()
            ->tahunKinerja()
            ->whereIn('status', [Penilaian::STATUS_IN_ASSESSMENT_2, Penilaian::STATUS_DONE_2])
            ->orderBy('id')
            ->value('status');

        if ($statusPenilaian == Penilaian::STATUS_DONE_2) {
            $data->transform(function (Komponen $komponen) {
                $totalSkorKomponen = 0;

                $komponen->subKomponen->transform(function (SubKomponen $subKomponen) use (&$totalSkorKomponen) {
                    $totalSkorSubKomponen = 0;

                    $subKomponen->kriteria->transform(function (Kriteria $kriteria) use (&$totalSkorSubKomponen) {
                        $getSkor = fn (Collection $riwayat) => $riwayat[$riwayat->count() - 1]->parameterNilai->skor;
                        $skorAkhir = $getSkor($kriteria->eviden->riwayat) * $kriteria->bobot;
                        $totalSkorSubKomponen += $skorAkhir;

                        $kriteria->skor_akhir = $skorAkhir;

                        return $kriteria;
                    });

                    $totalSkorKomponen += round($totalSkorSubKomponen, 2);
                    $subKomponen->total_skor = round($totalSkorSubKomponen, 2);

                    return $subKomponen;
                });

                $komponen->total_skor = round($totalSkorKomponen, 2);

                return $komponen;
            });
        }

        return response()->json(compact('data', 'statusPenilaian'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'data' => ['required', 'array'],
            'data.*.kriteria_id' => ['required', 'numeric'],
            'data.*.parameter_id' => ['numeric', 'nullable'],
            'data.*.eviden' => ['array'],
            'date.*.eviden.*' => ['string', 'nullable'],
        ]);

        try {
            $autoKriteria = Kriteria::query()
                ->whereIn('id', collect($validated['data'])->pluck('kriteria_id'))
                ->where('is_auto', true)
                ->pluck('id');

            DB::transaction(function () use ($validated, $autoKriteria) {
                foreach ($validated['data'] as $data) {
                    $update = [
                        'parameter_id' => $data['parameter_id'],
                    ];

                    // kriteria yang auto tidak bisa ubah eviden
                    if (! $autoKriteria->contains($data['kriteria_id'])) {
                        $update['eviden'] = $data['eviden'];
                    }

                    Eviden::query()->updateOrCreate([
                        'tahun_kinerja' => getTahunKinerja(),
                        'satuan_kerja_id' => Auth::user()->satuan_kerja_id,
                        'kriteria_id' => $data['kriteria_id'],
                    ], $update);
                }
            });
        } catch (Exception $e) {
            throw $e;
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil simpan data',
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $file = $request->file('file');
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = date('YmdHis').'_'.Str::slug($fileName).'.'.$file->getClientOriginalExtension();

        $path = $request->file('file')->storeAs(Eviden::PATH_EVIDEN.'/'.getTahunKinerja().'/'.Auth::user()->satuan_kerja_id, $fileName);
        $path = Storage::url($path);

        return response()->json($path);
    }

    public function submitPenilaian()
    {
        $user = Auth::user();
        $satkerId = $user->satuan_kerja_id;

        $statusPenilaian = Penilaian::query()
            ->roleSatuanKerja()
            ->tahunKinerja()
            ->orderBy('id')
            ->value('status');

        if ($statusPenilaian == Penilaian::STATUS_DONE) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa submit penilaian karena penilaian sudah selesai',
            ]);
        }

        if ($statusPenilaian == Penilaian::STATUS_IN_ASSESSMENT) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa submit penilaian karena sedang dalam tahap penilaian',
            ]);
        }

        $evidenList = Eviden::query()
            ->roleSatuanKerja()
            ->tahunKinerja()
            ->get();

        $isComplete = true;
        $uncompleteKriteriaId = null;

        foreach ($evidenList as $eviden) {
            if (! $eviden->parameter_id) {
                $isComplete = false;
                $uncompleteKriteriaId = $eviden->kriteria_id;
                break;
            }

            if (! $eviden->eviden || ! count($eviden->eviden)) {
                $isComplete = false;
                $uncompleteKriteriaId = $eviden->kriteria_id;
                break;
            }

            foreach ($eviden->eviden as $item) {
                if (! $item) {
                    $isComplete = false;
                    $uncompleteKriteriaId = $eviden->kriteria_id;
                    break;
                }
            }
        }

        if (! $isComplete) {
            $kriteria = Kriteria::query()->where('id', $uncompleteKriteriaId)->value('nama');

            return response()->json([
                'success' => false,
                'message' => "Tidak bisa submit penilaian karena terdapat jawaban atau eviden yang belum diisi [{$kriteria}]",
            ]);
        }

        try {
            DB::transaction(function () use ($evidenList, $satkerId) {
                $penilaian = Penilaian::query()->create([
                    'satuan_kerja_id' => $satkerId,
                    'tahun_kinerja' => getTahunKinerja(),
                    'status' => Penilaian::STATUS_IN_ASSESSMENT,
                ]);

                foreach ($evidenList as $eviden) {
                    RiwayatEviden::query()
                        ->create([
                            'penilaian_id' => $penilaian->id,
                            'eviden_id' => $eviden->id,
                            'parameter_id' => $eviden->parameter_id,
                            'eviden' => $eviden->eviden,
                            'nilai' => $eviden->parameter_id,
                        ]);
                }
            });
        } catch (Exception $e) {
            throw $e;
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil submit eviden',
            'status' => Penilaian::STATUS_IN_ASSESSMENT,
        ]);
    }

    public function submitPenilaian2()
    {
        $user = Auth::user();
        $satkerId = $user->satuan_kerja_id;

        $statusPenilaian = Penilaian::query()
            ->roleSatuanKerja()
            ->tahunKinerja()
            ->whereIn('status', [Penilaian::STATUS_IN_ASSESSMENT_2, Penilaian::STATUS_DONE_2])
            ->orderBy('id')
            ->value('status');

        if ($statusPenilaian == Penilaian::STATUS_DONE_2) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa submit penilaian karena penilaian sudah selesai',
            ]);
        }

        if ($statusPenilaian == Penilaian::STATUS_IN_ASSESSMENT_2) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa submit penilaian karena sedang dalam tahap penilaian',
            ]);
        }

        $evidenList = Eviden::query()
            ->roleSatuanKerja()
            ->tahunKinerja()
            ->get();

        $isComplete = true;
        $uncompleteKriteriaId = null;

        foreach ($evidenList as $eviden) {
            if (! $eviden->parameter_id) {
                $isComplete = false;
                $uncompleteKriteriaId = $eviden->kriteria_id;
                break;
            }

            if (! $eviden->eviden || ! count($eviden->eviden)) {
                $isComplete = false;
                $uncompleteKriteriaId = $eviden->kriteria_id;
                break;
            }

            foreach ($eviden->eviden as $item) {
                if (! $item) {
                    $isComplete = false;
                    $uncompleteKriteriaId = $eviden->kriteria_id;
                    break;
                }
            }
        }

        if (! $isComplete) {
            $kriteria = Kriteria::query()->where('id', $uncompleteKriteriaId)->value('nama');

            return response()->json([
                'success' => false,
                'message' => "Tidak bisa submit penilaian karena terdapat jawaban atau eviden yang belum diisi [{$kriteria}]",
            ]);
        }

        try {
            DB::transaction(function () use ($evidenList, $satkerId) {
                $penilaian = Penilaian::query()->create([
                    'satuan_kerja_id' => $satkerId,
                    'tahun_kinerja' => getTahunKinerja(),
                    'status' => Penilaian::STATUS_IN_ASSESSMENT_2,
                ]);

                foreach ($evidenList as $eviden) {
                    RiwayatEviden::query()
                        ->create([
                            'penilaian_id' => $penilaian->id,
                            'eviden_id' => $eviden->id,
                            'parameter_id' => $eviden->parameter_id,
                            'eviden' => $eviden->eviden,
                            'nilai' => $eviden->parameter_id,
                        ]);
                }
            });
        } catch (Exception $e) {
            throw $e;
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil submit eviden',
            'status' => Penilaian::STATUS_IN_ASSESSMENT_2,
        ]);
    }

    public function hasilSelfAssessment(Request $request)
    {
        $filter = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        $result = self::getHasilSelfAssessment($filter['satuan_kerja_id']);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'bobotTotal' => $result['bobotTotal'],
            'skorTotal' => $result['skorTotal'],
            'skorTotal2' => $result['skorTotal2'],
            'predikat' => $result['predikat'],
            'predikat2' => $result['predikat2'],
        ]);
    }

    public static function getHasilSelfAssessment(int $satkerId)
    {
        $bobotTotal = 0;
        $skorTotal = 0;
        $skorTotal2 = 0;

        $data = Komponen::tahunKinerja()
            ->with([
                'subKomponen' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria.eviden' => fn (Builder $query) => $query->roleSatuanKerja($satkerId)->tahunKinerja()->orderBy('id'),
                'subKomponen.kriteria.eviden.riwayat' => fn (Builder $query) => $query->orderBy('id'),
                'subKomponen.kriteria.eviden.riwayat.penilaian:id,status',
                'subKomponen.kriteria.eviden.riwayat.parameter' => fn (Builder $query) => $query->orderBy('nomor'),
            ])
            ->orderBy('nomor')
            ->get()
            ->transform(function (Komponen $komponen) use (&$bobotTotal, &$skorTotal, &$skorTotal2) {
                $bobotKomponen = 0;
                $skorKomponen1 = 0;
                $skorKomponen2 = 0;

                $komponen->subKomponen->transform(function (SubKomponen $subKomponen) use (&$bobotKomponen, &$skorKomponen1, &$skorKomponen2) {
                    $bobotSubKomponen = 0;
                    $skorSubKomponen1 = 0;
                    $skorSubKomponen2 = 0;

                    foreach ($subKomponen->kriteria as $kriteria) {
                        $bobotSubKomponen += $kriteria->bobot;

                        $riwayat1 = $kriteria->eviden->riwayat->first(fn (RiwayatEviden $item) => in_array($item->penilaian->status, [Penilaian::STATUS_IN_ASSESSMENT, Penilaian::STATUS_DONE]));
                        if ($riwayat1) {
                            $skorSubKomponen1 += $riwayat1->parameter->skor * $kriteria->bobot;
                        } else {
                            // jika tidak ada riwayat artinya belum submit, maka ambil dari eviden saat ini yang belum disumbit
                            $skorSubKomponen1 += ($kriteria->eviden->parameter->skor ?? 0) * $kriteria->bobot;
                        }

                        $riwayat2 = $kriteria->eviden->riwayat->first(fn (RiwayatEviden $item) => in_array($item->penilaian->status, [Penilaian::STATUS_IN_ASSESSMENT_2, Penilaian::STATUS_DONE_2]));

                        if ($riwayat2) {
                            $skorSubKomponen2 += $riwayat2->parameter->skor * $kriteria->bobot;
                        }
                    }

                    $bobotSubKomponen = round($bobotSubKomponen, 2);
                    $skorSubKomponen1 = round($skorSubKomponen1, 2);
                    $skorSubKomponen2 = round($skorSubKomponen2, 2);

                    $subKomponen->bobot = $bobotSubKomponen;
                    $subKomponen->skor = $skorSubKomponen1;
                    $subKomponen->skor2 = $skorSubKomponen2;

                    $bobotKomponen += $bobotSubKomponen;
                    $skorKomponen1 += $skorSubKomponen1;
                    $skorKomponen2 += $skorSubKomponen2;

                    return $subKomponen;
                });

                $bobotKomponen = round($bobotKomponen, 2);
                $skorKomponen1 = round($skorKomponen1, 2);
                $skorKomponen2 = round($skorKomponen2, 2);

                $komponen->bobot = $bobotKomponen;
                $komponen->skor = $skorKomponen1;
                $komponen->skor2 = $skorKomponen2;

                $bobotTotal += $bobotKomponen;
                $skorTotal += $skorKomponen1;
                $skorTotal2 += $skorKomponen2;

                return $komponen;
            });

        return [
            'data' => $data,
            'bobotTotal' => $bobotTotal,
            'skorTotal' => $skorTotal,
            'skorTotal2' => $skorTotal2,
            'predikat' => self::getPredikat($skorTotal),
            'predikat2' => self::getPredikat($skorTotal2),
        ];
    }

    public static function getPredikat(float $skorTotal): array
    {
        if ($skorTotal >= 90) {
            return [
                'AA',
                'Sangat Memuaskan',
                'Telah terwujud Good Governance. Seluruh kinerja dikelola dengan sangat memuaskan di seluruh unit kerja. Telah terbentuk pemerintah yang yang dinamis, adaptif, dan efisien (Reform). Pengukuran kinerja telah dilakukan sampai ke level individu.',
            ];
        } elseif ($skorTotal >= 80) {
            return [
                'A',
                'Memuaskan',
                'Terdapat gambaran bahwa instansi pemerintah/unit kerja dapat memimpin perubahan dalam mewujudkan pemerintahan berorientasi hasil, karena pengukuran kinerja telah dilakukan sampai ke level eselon 4/Pengawas/Subkoordinator.',
            ];
        } elseif ($skorTotal >= 70) {
            return [
                'BB',
                'Sangat Baik',
                'Terdapat gambaran bahwa AKIP sangat baik pada 2/3 unit kerja, baik itu unit kerja utama, maupun unit kerja pendukung. Akuntabilitas yang sangat baik ditandai dengan mulai memiliki sistem manajemen kinerja yang andal dan terwujudnya efisiensi penggunaan anggaran dalam mencapai kinerja, berbasis teknologi informasi, serta pengukuran 3/koordinator. kinerja telah dilakukan sampai ke level eselon',
            ];
        } elseif ($skorTotal >= 60) {
            return [
                'B',
                'Baik',
                'Terdapat gambaran bahwa AKIP sudah baik pada 1/3 unit kerja, khususnya pada unit kerja utama. Terlihat masih perlu adanya sedikit perbaikan pada unit kerja, serta komitmen dalam manajemen kinerja. Pengukuran kinerja baru dilaksanakan sampai dengan level eselon 2/unit kerja.',
            ];
        } elseif ($skorTotal >= 50) {
            return [
                'CC',
                'Cukup (Memadai)',
                'Terdapat gambaran bahwa AKIP cukup baik. Namun demikian, masih perlu banyak perbaikan walaupun tidak mendasar khususnya akuntabilitas kinerja pada unit kerja.',
            ];
        } elseif ($skorTotal >= 30) {
            return [
                'C',
                'Kurang',
                'Sistem dan tatanan dalam AKIP kurang dapat diandalkan. Belum terimplementasi sistem manajemen kinerja sehingga masih perlu banyak perbaikan mendasar di level pusat.',
            ];
        } else {
            return [
                'D',
                'Sangat Kurang',
                'Sistem dan tatanan dalam AKIP sama sekali tidak dapat diandalkan. Sama sekali belum terdapat penerapan manajemen kinerja sehingga masih perlu banyak mendasar, khususnya dalam implementasi SAKIP.perbaikan/perubahan yang sifatnya sangat',
            ];
        }
    }

    public function hasilAkhir()
    {
        $result = self::getHasilAkhir(Auth::user()->satuan_kerja_id);

        return response()->json($result);
    }

    public static function getHasilAkhir(int $satkerId)
    {
        $doneList = Penilaian::query()
            ->roleSatuanKerja($satkerId)
            ->tahunKinerja()
            ->whereIn('status', [Penilaian::STATUS_DONE, Penilaian::STATUS_DONE_2])
            ->orderByDesc('id')
            ->get();

        if (! $doneList->count()) {
            return [
                'success' => false,
                'message' => 'Penilaian belum selesai',
            ];
        }

        $done1 = $doneList->where('status', Penilaian::STATUS_DONE)->first();
        $done2 = $doneList->where('status', Penilaian::STATUS_DONE_2)->first();
        $doneForHumanis = $doneList->first(fn ($item) => $item->status === Penilaian::STATUS_DONE_2);

        $penilaianKomponen = $doneForHumanis
            ? PenilaianKomponen::query()
                ->where('penilaian_id', $doneForHumanis->id)
                ->pluck('nilai', 'komponen_id')
            : [];

        $bobotTotal = 0;
        $skorTotal = 0;
        $skorTotal2 = 0;
        $skorTotalPenilaianKomponen = 0;

        $data = Komponen::tahunKinerja()
            ->with([
                'subKomponen' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria.eviden' => fn (Builder $query) => $query->roleSatuanKerja($satkerId)->tahunKinerja()->orderBy('id'),
                'subKomponen.kriteria.eviden.riwayat' => fn (Builder $query) => $query->whereIn('penilaian_id', [$done1->id, $done2?->id])->orderBy('id'),
                'subKomponen.kriteria.eviden.riwayat.parameterNilai' => fn (Builder $query) => $query->orderBy('id'),
            ])
            ->orderBy('nomor')
            ->get()
            ->transform(function (Komponen $komponen) use (&$bobotTotal, &$skorTotal, &$skorTotal2, &$skorTotalPenilaianKomponen, $penilaianKomponen, $done1, $done2) {
                $bobotKomponen = 0;
                $skorKomponen1 = 0;
                $skorKomponen2 = 0;

                $komponen->subKomponen->transform(function (SubKomponen $subKomponen) use (&$bobotKomponen, &$skorKomponen1, &$skorKomponen2, $done1, $done2) {
                    $bobotSubKomponen = 0;
                    $skorSubKomponen1 = 0;
                    $skorSubKomponen2 = 0;

                    foreach ($subKomponen->kriteria as $kriteria) {
                        $bobotSubKomponen += $kriteria->bobot;

                        $riwayat1 = $kriteria->eviden->riwayat->first(fn ($item) => $item->penilaian_id === $done1->id);
                        $skorSubKomponen1 += $riwayat1->parameterNilai->skor * $kriteria->bobot;

                        if ($done2) {
                            $riwayat2 = $kriteria->eviden->riwayat->first(fn ($item) => $item->penilaian_id === $done2->id);
                            $skorSubKomponen2 += $riwayat2->parameterNilai->skor * $kriteria->bobot;
                        }
                    }

                    $bobotSubKomponen = round($bobotSubKomponen, 2);
                    $skorSubKomponen1 = round($skorSubKomponen1, 2);
                    $skorSubKomponen2 = round($skorSubKomponen2, 2);

                    $subKomponen->bobot = $bobotSubKomponen;
                    $subKomponen->skor = $skorSubKomponen1;
                    $subKomponen->skor2 = $skorSubKomponen2;

                    $bobotKomponen += $bobotSubKomponen;
                    $skorKomponen1 += $skorSubKomponen1;
                    $skorKomponen2 += $skorSubKomponen2;

                    return $subKomponen;
                });

                $bobotKomponen = round($bobotKomponen, 2);
                $skorKomponen1 = round($skorKomponen1, 2);
                $skorKomponen2 = round($skorKomponen2, 2);

                $komponen->bobot = $bobotKomponen;
                $komponen->skor = $skorKomponen1;
                $komponen->skor2 = $skorKomponen2;
                $komponen->skor_penilaian = round($penilaianKomponen[$komponen->id] ?? 0, 2);

                $bobotTotal += $bobotKomponen;
                $skorTotal += $skorKomponen1;
                $skorTotal2 += $skorKomponen2;
                $skorTotalPenilaianKomponen += $komponen->skor_penilaian;

                return $komponen;
            });

        return [
            'success' => true,
            'data' => $data,
            'bobotTotal' => $bobotTotal,
            'skorTotal' => $skorTotal,
            'skorTotal2' => $skorTotal2,
            'skorTotalPenilaianKomponen' => $skorTotalPenilaianKomponen,
            'predikat' => self::getPredikat($skorTotal),
            'predikat2' => self::getPredikat($skorTotal2),
            'predikatKomponen' => self::getPredikat($skorTotalPenilaianKomponen),
            'done1' => $done1 ? true : false,
            'done2' => $done2 ? true : false,
        ];
    }
}
