<?php

namespace App\Http\Controllers;

use App\Models\NilaiJenjangKinerja;
use App\Models\SasaranStrategisPd;
use App\Models\SatuanKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class NilaiJenjangKinerjaController extends Controller
{
    public function index()
    {
        $satuanKerja = SatuanKerja::query()
            ->select('satuan_kerja_id', 'satuan_kerja_nama')
            ->where('satuan_kerja_id', '<>', SATKER_SETDA)
            ->where('satuan_kerja_nama', 'NOT LIKE', 'BIRO%')
            ->orderBy('satuan_kerja_id')
            ->get();

        $nilaiList = NilaiJenjangKinerja::tahunKinerja()
            ->whereIn('satuan_kerja_id', $satuanKerja->pluck('satuan_kerja_id'))
            ->get()
            ->groupBy('satuan_kerja_id');

        $ikuList = SasaranStrategisPd::tahunMulai()
            ->select('id', 'satuan_kerja_id', 'iku')
            ->get()
            ->groupBy('satuan_kerja_id');

        foreach ($satuanKerja as &$satker) {
            $iku = $ikuList[parseSatuanKerjaId($satker->satuan_kerja_id)]
                ->transform(function (SasaranStrategisPd $item) use ($nilaiList) {
                    $nilai = ($nilaiList[$item->satuan_kerja_id] ?? collect([]))->keyBy('sasaran_strategis_pd_id');
                    $item->nilai = (int) ($nilai[$item->id]->nilai ?? 0);
                    $item->nilai_id = $nilai[$item->id]->nilai_id ?? null;

                    return $item;
                });

            $satker->iku = $iku;
        }

        return response()->json([
            'data' => $satuanKerja,
            'options' => self::options(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nilai' => 'required|array',
            'nilai.*.*' => 'required|numeric',
        ]);

        $existing = NilaiJenjangKinerja::tahunKinerja()
            ->get()
            ->groupBy('satuan_kerja_id')
            ->transform(fn (Collection $items) => $items->keyBy('sasaran_strategis_pd_id'));

        foreach ($validated['nilai'] as $satkerId => $value) {
            foreach ($value as $ikuId => $nilaiId) {
                $data = $existing[$satkerId][$ikuId] ?? null;
                $nilai = collect(self::options())->first(fn (array $item) => $item['id'] == $nilaiId)['nilai'];

                if ($data) {
                    $data->update([
                        'nilai_id' => $nilaiId,
                        'nilai' => $nilai,
                    ]);
                } else {
                    NilaiJenjangKinerja::query()->create([
                        'tahun_kinerja' => getTahunKinerja(),
                        'satuan_kerja_id' => $satkerId,
                        'sasaran_strategis_pd_id' => $ikuId,
                        'nilai_id' => $nilaiId,
                        'nilai' => $nilai,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil tambah nilai',
        ]);
    }

    public function rekap()
    {
        $data = NilaiJenjangKinerja::tahunKinerja()
            ->selectRaw('satuan_kerja_id, AVG(nilai) nilai_akhir')
            ->groupBy('satuan_kerja_id')
            ->pluck('nilai_akhir', 'satuan_kerja_id');

        $satuanKerja = SatuanKerja::query()
            ->select('satuan_kerja_id', 'satuan_kerja_nama')
            ->where('satuan_kerja_id', '<>', SATKER_SETDA)
            ->where('satuan_kerja_nama', 'NOT LIKE', 'BIRO%')
            ->get()
            ->transform(fn (SatuanKerja $item) => [
                'satuan_kerja_nama' => $item->satuan_kerja_nama,
                'nilai_akhir' => (float) ($data[$item->satuan_kerja_id] ?? 0),
            ])
            ->sortByDesc('nilai_akhir')
            ->values();

        return response()->json($satuanKerja);
    }

    public static function options(): array
    {
        return [
            [
                'id' => 1,
                'nilai' => 100,
                'label' => 'Menggunakan Indikator Nasional',
            ],
            [
                'id' => 2,
                'nilai' => 100,
                'label' => 'Ultimate/Intermediate Outcome (Berorientasi Hasil) & SMART',
            ],
            [
                'id' => 3,
                'nilai' => 80,
                'label' => 'Immediate Outcome/Output/di luar corebusines/melebihi kewenangan',
            ],
        ];
    }
}
