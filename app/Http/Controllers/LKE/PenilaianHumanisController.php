<?php

namespace App\Http\Controllers\LKE;

use App\Http\Controllers\Controller;
use App\Models\LKE\Komponen;
use App\Models\LKE\Log;
use App\Models\LKE\Penilaian;
use App\Models\LKE\PenilaianKomponen;
use App\Models\LKE\SubKomponen;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenilaianHumanisController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        $penilaianId = Penilaian::query()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->tahunKinerja()
            ->where('status', Penilaian::STATUS_DONE_2)
            ->value('id');

        if (! $penilaianId) {
            return response()->json([
                'success' => false,
                'message' => 'Perangkat Daerah belum melakukan submit penilaian',
            ]);
        }

        $penilaianKomponen = PenilaianKomponen::query()
            ->where('penilaian_id', $penilaianId)
            ->pluck('nilai', 'komponen_id');

        $bobotTotal = 0;
        $skorTotal = 0;

        $data = Komponen::tahunKinerja()
            ->with([
                'subKomponen' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria.eviden' => fn (Builder $query) => $query->roleSatuanKerja($validated['satuan_kerja_id'])->tahunKinerja()->orderBy('id'),
                'subKomponen.kriteria.eviden.riwayat' => fn (Builder $query) => $query->where('penilaian_id', $penilaianId)->orderBy('id'),
                'subKomponen.kriteria.eviden.riwayat.parameterNilai' => fn (Builder $query) => $query->orderBy('id'),
            ])
            ->orderBy('nomor')
            ->get()
            ->transform(function (Komponen $komponen) use (&$bobotTotal, &$skorTotal, $penilaianKomponen) {
                $bobotKomponen = 0;
                $skorKomponen = 0;

                $komponen->subKomponen->transform(function (SubKomponen $subKomponen) use (&$bobotKomponen, &$skorKomponen) {
                    $bobotSubKomponen = 0;
                    $skorSubKomponen = 0;

                    foreach ($subKomponen->kriteria as $kriteria) {
                        $bobotSubKomponen += $kriteria->bobot;
                        $skorSubKomponen += $kriteria->eviden->riwayat[0]->parameterNilai->skor * $kriteria->bobot;
                    }

                    $bobotSubKomponen = round($bobotSubKomponen, 1);
                    $skorSubKomponen = round($skorSubKomponen, 1);

                    $subKomponen->bobot = $bobotSubKomponen;
                    $subKomponen->skor = $skorSubKomponen;

                    $bobotKomponen += $bobotSubKomponen;
                    $skorKomponen += $skorSubKomponen;

                    return $subKomponen;
                });

                $bobotKomponen = round($bobotKomponen, 1);
                $skorKomponen = round($skorKomponen, 1);

                $komponen->bobot = $bobotKomponen;
                $komponen->skor = $skorKomponen;
                $komponen->skor_penilaian = $penilaianKomponen[$komponen->id] ?? 0;

                $bobotTotal += $bobotKomponen;
                $skorTotal += $skorKomponen;

                return $komponen;
            });

        $isHumanisDone = Penilaian::query()
            ->roleSatuanKerja($validated['satuan_kerja_id'])
            ->tahunKinerja()
            ->where('status', Penilaian::STATUS_HUMANIS_DONE)
            ->exists();

        return response()->json([
            'success' => true,
            'data' => $data,
            'bobotTotal' => $bobotTotal,
            'skorTotal' => $skorTotal,
            'isHumanisDone' => $isHumanisDone,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
            'data' => ['required', 'array'],
            'data.*.id' => ['required', 'numeric'],
            'data.*.nilai' => ['required', 'numeric'],
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                $penilaianId = Penilaian::query()
                    ->roleSatuanKerja($validated['satuan_kerja_id'])
                    ->tahunKinerja()
                    ->where('status', Penilaian::STATUS_DONE_2)
                    ->value('id');

                $upsert = [];

                foreach ($validated['data'] as $data) {
                    $upsert[] = [
                        'penilaian_id' => $penilaianId,
                        'komponen_id' => $data['id'],
                        'nilai' => $data['nilai'],
                    ];
                }

                PenilaianKomponen::query()->upsert($upsert, ['penilaian_id', 'komponen_id'], ['nilai']);

                Log::query()->create([
                    'user_id' => Auth::id(),
                    'action' => Log::ACTION_STORE_PENILAIAN_HUMANIS,
                    'data' => $request->all(),
                ]);
            });

            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $th) {
            throw $th;
        }
    }

    public function close(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['required', 'numeric'],
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                Penilaian::query()->create([
                    'satuan_kerja_id' => $validated['satuan_kerja_id'],
                    'tahun_kinerja' => getTahunKinerja(),
                    'status' => Penilaian::STATUS_HUMANIS_DONE,
                ]);

                Log::query()->create([
                    'user_id' => Auth::id(),
                    'action' => Log::ACTION_CLOSE_PENILAIAN_HUMANIS,
                    'data' => $request->all(),
                ]);
            });

            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $th) {
            throw $th;
        }
    }
}
