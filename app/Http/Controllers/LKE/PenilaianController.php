<?php

namespace App\Http\Controllers\LKE;

use App\Exports\LKE\HasilPenilaianExport;
use App\Exports\LKE\PenilaianExport;
use App\Http\Controllers\Controller;
use App\Models\LKE\Komponen;
use App\Models\LKE\Log;
use App\Models\LKE\Penilaian;
use App\Models\LKE\PenilaianKomponen;
use App\Models\LKE\RiwayatEviden;
use App\Models\LKE\SubKomponen;
use App\Models\Role;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class PenilaianController extends Controller
{
    public function index(Request $request, bool $isExport = false)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        if (! in_array($validated['satuan_kerja_id'], self::mappingValidatorByAuth())) {
            abort(403, 'Anda tidak memiliki akses ke OPD tersebut');
        }

        $penilaian = Penilaian::query()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->tahunKinerja()
            ->orderBy('id')
            ->first(['id', 'status']);

        $statusPenilaian = $penilaian?->status;

        $user = Auth::user();

        if ($statusPenilaian) {
            $data = Komponen::tahunKinerja()
                ->with([
                    'subKomponen' => fn (Builder $query) => $query->orderBy('nomor'),
                    'subKomponen.kriteria' => fn (Builder $query) => $query->orderBy('nomor'),
                    'subKomponen.kriteria.eviden' => fn (Builder $query) => $query->roleSatuanKerja($validated['satuan_kerja_id'])->tahunKinerja()->orderBy('id'),
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
        } else {
            $data = [];
        }

        if ($isExport) {
            return $data;
        }

        return response()->json(compact('data', 'statusPenilaian'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        $data = $this->index($request, true);

        return Excel::download(new PenilaianExport($data), 'Penilaian.xlsx');
    }

    public function index2(Request $request, bool $isExport = false)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        if (! in_array($validated['satuan_kerja_id'], self::mappingValidatorByAuth())) {
            abort(403, 'Anda tidak memiliki akses ke OPD tersebut');
        }

        $penilaian = Penilaian::query()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->tahunKinerja()
            ->whereIn('status', [Penilaian::STATUS_IN_ASSESSMENT_2, Penilaian::STATUS_DONE_2])
            ->orderBy('id')
            ->first(['id', 'status']);

        $statusPenilaian = $penilaian?->status;

        $user = Auth::user();

        if ($statusPenilaian) {
            $data = Komponen::tahunKinerja()
                ->with([
                    'subKomponen' => fn (Builder $query) => $query->orderBy('nomor'),
                    'subKomponen.kriteria' => fn (Builder $query) => $query->orderBy('nomor'),
                    'subKomponen.kriteria.eviden' => fn (Builder $query) => $query->roleSatuanKerja($validated['satuan_kerja_id'])->tahunKinerja()->orderBy('id'),
                    'subKomponen.kriteria.eviden.parameter' => fn (Builder $query) => $query->orderBy('nomor'),
                    'subKomponen.kriteria.eviden.riwayat' => fn (Builder $query) => $query
                        ->whereHas('penilaian', fn (Builder $query) => $query
                            ->whereIn('status', [Penilaian::STATUS_IN_ASSESSMENT_2, Penilaian::STATUS_DONE_2]))
                        ->orderBy('id'),
                    'subKomponen.kriteria.eviden.riwayat.parameter' => fn (Builder $query) => $query->orderBy('nomor'),
                    'subKomponen.kriteria.eviden.riwayat.parameterNilai' => fn (Builder $query) => $query->orderBy('id'),
                    'subKomponen.kriteria.parameter' => fn (Builder $query) => $query->orderBy('id'),
                    'subKomponen.kriteria.eviden.riwayatPenilaianSebelumnya',
                ])
                ->orderBy('nomor')
                ->get();
        } else {
            $data = [];
        }

        if ($isExport) {
            return $data;
        }

        return response()->json(compact('data', 'statusPenilaian'));
    }

    public function export2(Request $request)
    {
        $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        $data = $this->index2($request, true);

        return Excel::download(new PenilaianExport($data), 'Penilaian.xlsx');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
            'data' => ['required', 'array'],
            'data.*.id' => ['required', 'numeric'],
            'data.*.nilai' => ['required', 'numeric'],
            'data.*.catatan' => ['nullable', 'string'],
            'data.*.status' => ['nullable', 'boolean'],
        ]);

        if (! in_array($validated['satuan_kerja_id'], self::mappingValidatorByAuth())) {
            abort(403, 'Anda tidak memiliki akses ke OPD tersebut');
        }

        try {
            DB::transaction(function () use ($validated, $request) {
                foreach ($validated['data'] as $data) {
                    RiwayatEviden::query()
                        ->where('id', $data['id'])
                        ->update([
                            'nilai' => $data['nilai'],
                            'catatan' => $data['catatan'],
                            'status' => $data['status'],
                        ]);
                }

                Log::query()
                    ->create([
                        'user_id' => Auth::id(),
                        'action' => Log::ACTION_STORE_PENILAIAN,
                        'data' => $request->all(),
                    ]);
            });
        } catch (Exception $e) {
            throw $e;
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil simpan data',
        ]);
    }

    public function hasilAkhir(Request $request)
    {
        $filter = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        $resultHasilSelfAssesment = EvidenController::getHasilSelfAssessment($filter['satuan_kerja_id']);
        $hasilSelfAssesment = $resultHasilSelfAssesment['data'];

        $dataHasilAssesment = collect($hasilSelfAssesment)->map(function ($item) {
            return [
                'nama' => $item['nama'],
                'bobot' => $item['bobot'],
                'skorSelfAssesment' => $item['skor'],
                'skorPerbaikan' => $item['skor2'],
                'subKomponen' => collect($item['subKomponen'])->mapWithKeys(function ($subItem) {
                    return [
                        $subItem['nama'] => [
                            'nama' => $subItem['nama'],
                            'bobot' => $subItem['bobot'],
                            'skorSelfAssesmentSub' => $subItem['skor'],
                            'skorPerbaikanSub' => $subItem['skor2'],
                        ],
                    ];
                }),
            ];
        })->keyBy('nama');

        $doneList = Penilaian::query()
            ->roleSatuanKerja($filter['satuan_kerja_id'])
            ->tahunKinerja()
            ->whereIn('status', [Penilaian::STATUS_DONE, Penilaian::STATUS_DONE_2])
            ->orderByDesc('id')
            ->get();

        if ($doneList->count()) {
            $resultHasilAkhir = EvidenController::getHasilAkhir($filter['satuan_kerja_id']);
            $hasilAkhir = $resultHasilAkhir['data'];

            $dataHasilAkhir = collect($hasilAkhir)->map(function ($item) {
                return [
                    'nama' => $item['nama'],
                    'bobot' => $item['bobot'],
                    'skorPenilaianAwal' => $item['skor'],
                    'skorPenilaianAkhir' => $item['skor2'],
                    'skorPleno' => $item['skor_penilaian'],
                    'subKomponen' => collect($item['subKomponen'])->mapWithKeys(function ($subItem) {
                        return [
                            $subItem['nama'] => [
                                'nama' => $subItem['nama'],
                                'bobot' => $subItem['bobot'],
                                'skorPenilaianAwalSub' => $subItem['skor'],
                                'skorPenilaianAkhirSub' => $subItem['skor2'],
                            ],
                        ];
                    }),
                ];
            })->keyBy('nama');

            // return response()->json([$dataHasilAkhir]);

            $joinedData = $dataHasilAssesment->map(function ($item, $key) use ($dataHasilAkhir) {
                $hasilAkhirItem = $dataHasilAkhir->get($key);

                $mergedSubKomponen = $item['subKomponen']->map(function ($subItem, $subKey) use ($hasilAkhirItem) {
                    $subAkhirItem = $hasilAkhirItem['subKomponen']->get($subKey);

                    return array_merge($subItem, [
                        'skorPenilaianAwalSub' => $subAkhirItem['skorPenilaianAwalSub'] ?? null,
                        'skorPenilaianAkhirSub' => $subAkhirItem['skorPenilaianAkhirSub'] ?? null,
                    ]);
                });

                return array_merge($item, [
                    'skorPenilaianAwal' => $hasilAkhirItem['skorPenilaianAwal'] ?? null,
                    'skorPenilaianAkhir' => $hasilAkhirItem['skorPenilaianAkhir'] ?? null,
                    'skorPleno' => $hasilAkhirItem['skorPleno'] ?? null,
                    'subKomponen' => $mergedSubKomponen->values()->all(),
                ]);
            });

            return response()->json($joinedData->values());
        } else {
            return response()->json($dataHasilAssesment->values());
        }

    }

    public function close(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        if (! in_array($validated['satuan_kerja_id'], self::mappingValidatorByAuth())) {
            abort(403, 'Anda tidak memiliki akses ke OPD tersebut');
        }

        $status = Penilaian::STATUS_DONE;

        Log::query()
            ->create([
                'user_id' => Auth::id(),
                'action' => Log::ACTION_CLOSE_DONE,
                'data' => [
                    'satuan_kerja_id' => $validated['satuan_kerja_id'],
                    'tahun_kinerja' => getTahunKinerja(),
                    'status' => $status,
                    'data' => $request->all(),
                ],
            ]);

        Penilaian::query()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->tahunKinerja()
            ->where('status', Penilaian::STATUS_IN_ASSESSMENT)
            ->update([
                'status' => $status,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil tutup penilaian',
            'status' => $status,
        ]);
    }

    public function close2(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        if (! in_array($validated['satuan_kerja_id'], self::mappingValidatorByAuth())) {
            abort(403, 'Anda tidak memiliki akses ke OPD tersebut');
        }

        $status = Penilaian::STATUS_DONE_2;

        Log::query()
            ->create([
                'user_id' => Auth::id(),
                'action' => Log::ACTION_CLOSE_DONE,
                'data' => [
                    'satuan_kerja_id' => $validated['satuan_kerja_id'],
                    'tahun_kinerja' => getTahunKinerja(),
                    'status' => $status,
                    'data' => $request->all(),
                ],
            ]);

        Penilaian::query()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->tahunKinerja()
            ->where('status', Penilaian::STATUS_IN_ASSESSMENT_2)
            ->update([
                'status' => $status,
            ]);

        Artisan::call('lke:generate-catatan', [
            'tahun' => getTahunKinerja(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil tutup penilaian',
            'status' => $status,
        ]);
    }

    public function cancelClose(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        if (! in_array($validated['satuan_kerja_id'], self::mappingValidatorByAuth())) {
            abort(403, 'Anda tidak memiliki akses ke OPD tersebut');
        }

        try {
            $penilaian = Penilaian::query()
                ->roleSatuanKerja($validated['satuan_kerja_id'])
                ->tahunKinerja()
                ->where('status', Penilaian::STATUS_DONE)
                ->first();

            if (! $penilaian) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil batal validasi penilaian',
                    'status' => Penilaian::STATUS_IN_ASSESSMENT,
                ]);
            }

            // hapus penilaian komponen
            PenilaianKomponen::query()
                ->where('penilaian_id', $penilaian->id)
                ->delete();

            // hapus status penilaian humanis done
            Penilaian::query()
                ->roleSatuanKerja($validated['satuan_kerja_id'])
                ->tahunKinerja()
                ->where('status', Penilaian::STATUS_HUMANIS_DONE)
                ->delete();

            // revert status penilaian done
            $penilaian->update([
                'status' => Penilaian::STATUS_IN_ASSESSMENT,
            ]);

            Log::query()->create([
                'user_id' => Auth::id(),
                'action' => Log::ACTION_CANCEL_CLOSE_DONE,
                'data' => [
                    'satuan_kerja_id' => $validated['satuan_kerja_id'],
                    'tahun_kinerja' => getTahunKinerja(),
                    'status' => Log::ACTION_CANCEL_CLOSE_DONE,
                    'data' => $request->all(),
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil batal validasi penilaian',
                'status' => Penilaian::STATUS_IN_ASSESSMENT,
            ]);
        } catch (Exception $th) {
            throw $th;
        }
    }

    public function cancelClose2(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        if (! in_array($validated['satuan_kerja_id'], self::mappingValidatorByAuth())) {
            abort(403, 'Anda tidak memiliki akses ke OPD tersebut');
        }

        try {
            $penilaian = Penilaian::query()
                ->roleSatuanKerja($validated['satuan_kerja_id'])
                ->tahunKinerja()
                ->where('status', Penilaian::STATUS_DONE_2)
                ->first();

            if (! $penilaian) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil batal validasi penilaian',
                    'status' => Penilaian::STATUS_IN_ASSESSMENT_2,
                ]);
            }

            // revert status penilaian done
            $penilaian->update([
                'status' => Penilaian::STATUS_IN_ASSESSMENT_2,
            ]);

            Log::query()->create([
                'user_id' => Auth::id(),
                'action' => Log::ACTION_CANCEL_CLOSE_DONE,
                'data' => [
                    'satuan_kerja_id' => $validated['satuan_kerja_id'],
                    'tahun_kinerja' => getTahunKinerja(),
                    'status' => Log::ACTION_CANCEL_CLOSE_DONE,
                    'data' => $request->all(),
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil batal validasi penilaian',
                'status' => Penilaian::STATUS_IN_ASSESSMENT_2,
            ]);
        } catch (Exception $th) {
            throw $th;
        }
    }

    public function hasil(Request $request, bool $isExport = false)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        if (! in_array($validated['satuan_kerja_id'], self::mappingValidatorByAuth())) {
            abort(403, 'Anda tidak memiliki akses ke OPD tersebut');
        }

        $doneList = Penilaian::query()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->tahunKinerja()
            ->whereIn('status', [Penilaian::STATUS_DONE, Penilaian::STATUS_DONE_2])
            ->orderByDesc('id')
            ->get();

        if (! $doneList->count()) {
            return response()->json([
                'success' => false,
                'message' => 'Penilaian belum selesai',
            ]);
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
                'subKomponen.kriteria.eviden' => fn (Builder $query) => $query->roleSatuanKerja($validated['satuan_kerja_id'])->tahunKinerja()->orderBy('id'),
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

        $predikat = EvidenController::getPredikat($skorTotal);
        $predikat2 = EvidenController::getPredikat($skorTotal2);
        $predikatKomponen = EvidenController::getPredikat($skorTotalPenilaianKomponen);

        if ($isExport) {
            return [
                'data' => $data,
                'bobotTotal' => $bobotTotal,
                'skorTotal' => $skorTotal,
                'skorTotal2' => $skorTotal2,
                'skorTotalPenilaianKomponen' => $skorTotalPenilaianKomponen,
                'predikat' => $predikat,
                'predikat2' => $predikat2,
                'predikatKomponen' => $predikatKomponen,
                'done1' => $done1 ? true : false,
                'done2' => $done2 ? true : false,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'bobotTotal' => $bobotTotal,
            'skorTotal' => $skorTotal,
            'skorTotal2' => $skorTotal2,
            'skorTotalPenilaianKomponen' => $skorTotalPenilaianKomponen,
            'predikat' => $predikat,
            'predikat2' => $predikat2,
            'predikatKomponen' => $predikatKomponen,
            'done1' => $done1 ? true : false,
            'done2' => $done2 ? true : false,
        ]);
    }

    public function hasilExport(Request $request)
    {
        $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        $data = $this->hasil($request, true);

        return Excel::download(new HasilPenilaianExport(
            $data['data'],
            $data['bobotTotal'],
            $data['skorTotal'],
            $data['skorTotal2'],
            $data['skorTotalPenilaianKomponen'],
            $data['predikat'],
            $data['predikat2'],
            $data['predikatKomponen'],
            $data['done1'],
            $data['done2'],
        ), 'Hasil Penilaian LKE.xlsx');
    }

    public static function mappingValidator(): array
    {
        return [
            'validatorlke2024_peri_w' => [1004, 1012, 1045],
            'validatorlke2024_iis' => [1021, 1010],
            'validatorlke2024_atsri' => [1002, 1046],
            'validatorlke2024_deni_komara' => [1080, 1042, 1017],
            'validatorlke2024_sabar' => [1022, 1026, 1023],
            'validatorlke2024_robi' => [1005],
            'validatorlke2024_gandjar' => [1018, 1009, 1020],
            'validatorlke2024_toni' => [1024, 1031],
            'validatorlke2024_dini' => [100103030000, 1041, 1007],
            'validatorlke2024_fauji' => [1030, 1013, 1003],
            'validatorlke2024_andy_wahyu_gunawan' => [1015],
            'validatorlke2024_agus_mirakusumah' => [1019, 1025, 1011],
            'validatorlke2024_deni_susanto' => [1027, 1008, 1035],
            'validatorlke2024_titi_mugiati' => [1014, 1034, 1016],
            'validatorlke2024_rahmat_juwana' => [1040, 1006, 1043],
            'validator_admin_lke' => [1018, 1009, 1020],
            'superadminlhe' => [
                1021, 1023, 1019, 1015, 1018, 1022, 1014, 1009,
                1012, 1016, 1017, 1042, 1041, 1046, 1011, 1025,
                1027, 1001, 1002, 1003, 1005, 1004, 1006, 1008,
                1007, 1043, 1026, 1013, 1045, 1080, 1024, 1034,
                1010, 1020, 1035, 1030, 1031, 1040,
                100103010000, 100102010000, 100103030000, 100101020000,
                100101030000, 100102030000, 100101010000, 100103020000,
                100102020000,
            ],
        ];
    }

    public static function mappingValidatorByAuth(): array
    {
        return self::mappingValidator()[Auth::user()->username] ?? [];
    }

    public function rekap()
    {
        $data = Penilaian::tahunKinerja()
            ->select('id', 'satuan_kerja_id')
            ->with([
                'satuanKerja:satuan_kerja_id,satuan_kerja_nama_alias',
            ])
            ->withSum('penilaianKomponen', 'nilai')
            ->where('status', Penilaian::STATUS_DONE_2)
            ->when(! Role::isSuper(), fn (Builder $query) => $query->whereIn('satuan_kerja_id', self::mappingValidatorByAuth()))
            ->get()
            ->sortByDesc('penilaian_komponen_sum_nilai')
            ->values();

        return response()->json($data);
    }

    public function getStatusPenilaian()
    {
        $tahunKinerja = (int) getTahunKinerja(); // Cast to integer
        $data = DB::select('SELECT * FROM get_status_penilaian(?)', [$tahunKinerja]);

        return response()->json($data);
    }

    public function sendAIEvaluasi(Request $request): JsonResponse
    {
        $request->validate([
            'evaluasi_id' => 'required',
        ]);
        $aiUrl = config('app.ai_url');
        $linkApi = $aiUrl.'/api/get_ai_evaluasi_lke/'.$request->input('evaluasi_id');
        $response = Http::get($linkApi);

        return response()->json($response->json());
    }
}
