<?php

namespace App\Http\Controllers\PublicDisplay;

use App\Http\Controllers\Controller;
use App\Models\KinerjaSubKegiatan;
use App\Models\SatuanKerja;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RencanaAksiController extends Controller
{
    // tidak diakumulasi
    const TRIWULAN_BULAN = [
        1 => ['jan', 'feb', 'mar'],
        2 => ['apr', 'may', 'jun'],
        3 => ['jul', 'aug', 'sep'],
        4 => ['oct', 'nov', 'dec'],
    ];

    public function index(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['nullable', 'numeric'],
            'sasaran_strategis_pd_id' => ['required', 'numeric'],
        ]);
        $satuanKerja = SatuanKerja::find($validated['satuan_kerja_id']);
        // return response()->json($validated['satuan_kerja_id']);
        $data = KinerjaSubKegiatan::tahunKinerja()
            ->roleSatuanKerja($satuanKerja->satuan_kerja_id)
            ->select('id', 'rencana_aksi', 'indikator', 'target_bulanan', 'realisasi_bulanan', 'satuan', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id')
            ->whereHas('kinerjaKegiatan.kinerjaProgram.sasaranStrategisPd', fn (Builder $query) => $query
                ->where('id', $validated['sasaran_strategis_pd_id'])
            )
            // ->whereHas('skp', fn (Builder $query) => $query->where('is_skp', true))
            ->whereNotNull('rencana_aksi')
            ->with([
                'timKerja:id,nama,nip_ketua',
                'timKerja.ketua:peg_nip,peg_nama',
                'strukturOrganisasi' => fn (Builder $query) => $query
                    ->selectRaw("
                        id,
                        jabatan_nama,
                        CONCAT_WS(', ',
                            lv7_unit_kerja_nama,
                            lv6_unit_kerja_nama,
                            lv5_unit_kerja_nama,
                            lv4_unit_kerja_nama,
                            lv3_unit_kerja_nama,
                            lv2_unit_kerja_nama,
                            lv1_unit_kerja_nama
                        ) AS unit_kerja_nama_full
                    "),
            ])
            ->paginate(20);

        /**
         * @var \Illuminate\Pagination\AbstractPaginator $data
         */
        $data->setCollection($data->getCollection()->transform(function (KinerjaSubKegiatan $item) {
            foreach (self::TRIWULAN_BULAN as $triwulan => $bulanList) {
                $targetTemp = 0;
                $realisasiTemp = 0;

                foreach ($bulanList as $bulan) {
                    $targetTemp += (float) $item->target_bulanan[$bulan];
                    $realisasiTemp += (float) $item->realisasi_bulanan[$bulan];
                }

                $item->{"tw{$triwulan}"} = $targetTemp;
                $item->{"tw{$triwulan}_realisasi"} = $realisasiTemp;
            }

            return $item;
        }));

        return response()->json($data);
    }

    public function gubernur(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['nullable', 'numeric'],
            'sasaran_strategis_rpjmd_id' => ['required', 'numeric'],
        ]);

        $data = KinerjaSubKegiatan::tahunKinerja()
            ->roleSatuanKerja($validated['satuan_kerja_id'] ?? null)
            ->select('id', 'rencana_aksi', 'indikator', 'target_bulanan', 'realisasi_bulanan', 'satuan', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id')
            ->whereHas(
                'kinerjaKegiatan.kinerjaProgram.sasaranStrategisPd.sasaranStrategisRpjmd',
                fn (Builder $query) => $query->where('id', $validated['sasaran_strategis_rpjmd_id'])
            )
            // ->whereHas('skp', fn (Builder $query) => $query->where('is_skp', true))
            ->whereNotNull('rencana_aksi')
            ->where('is_rencana_aksi_gubernur', true)
            ->with([
                'timKerja:id,nama,nip_ketua',
                'timKerja.ketua:peg_nip,peg_nama',
                'strukturOrganisasi' => fn (Builder $query) => $query
                    ->selectRaw("
                        id,
                        jabatan_nama,
                        CONCAT_WS(', ',
                            lv7_unit_kerja_nama,
                            lv6_unit_kerja_nama,
                            lv5_unit_kerja_nama,
                            lv4_unit_kerja_nama,
                            lv3_unit_kerja_nama,
                            lv2_unit_kerja_nama,
                            lv1_unit_kerja_nama
                        ) AS unit_kerja_nama_full
                    "),
            ])
            ->paginate(20);

        /**
         * @var \Illuminate\Pagination\AbstractPaginator $data
         */
        $data->setCollection($data->getCollection()->transform(function (KinerjaSubKegiatan $item) {
            foreach (self::TRIWULAN_BULAN as $triwulan => $bulanList) {
                $targetTemp = 0;
                $realisasiTemp = 0;

                foreach ($bulanList as $bulan) {
                    $targetTemp += (float) $item->target_bulanan[$bulan];
                    $realisasiTemp += (float) $item->realisasi_bulanan[$bulan];
                }

                $item->{"tw{$triwulan}"} = $targetTemp;
                $item->{"tw{$triwulan}_realisasi"} = $realisasiTemp;
            }

            return $item;
        }));

        return response()->json($data);
    }
}
