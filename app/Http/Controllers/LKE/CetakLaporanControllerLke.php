<?php

namespace App\Http\Controllers\LKE;

use App\Http\Controllers\Controller;
use App\Models\LKE\Komponen;
use App\Models\LKE\Penilaian;
use App\Models\LKE\Rekomendasi;
use App\Models\LKE\SubKomponen;
use App\Models\SatuanKerja;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class CetakLaporanControllerLke extends Controller
{
    public function export(Request $request)
    {
        $req = $request->all();
        $tahun = $req['tahun_kinerja'];
        if (strlen($tahun) == 4) {
            config([
                'database.connections.ekinerja' => config("database.connections.ekinerja_{$tahun}"),
                'database.connections.pgsql' => config('database.connections.pgsql2'),
            ]);
        } else {
            $tahun = substr($tahun, 0, 4);
            config([
                'database.connections.ekinerja' => config("database.connections.ekinerja_{$tahun}"),
                'database.connections.pgsql' => config('database.connections.pgsql'),
            ]);
        }
        if (strlen($tahun) > 4) {
            $tahun = substr($tahun, 0, 4);
        }
        $format = $req['format'];
        $tanggal = '1 April 2025';
        $satuanKerja = SatuanKerja::where('satuan_kerja_id', $req['satuan_kerja_id'])->first();
        $tahunSebelumnya = $tahun - 1;

        $varKepalaSatuanKerja = '';
        if ($satuanKerja->satuan_kerja_id == 1001) {
            $varKepalaSatuanKerja = 'Sekretaris Daerah';
        } else {
            $varKepalaSatuanKerja = 'Kepala '.capitalizeWords($satuanKerja->satuan_kerja_nama);
        }

        $penilaianIdTahunBerjalan = Penilaian::with(['penilaianKomponen', 'penilaianKomponen.komponen'])
            ->Where('satuan_kerja_id', $satuanKerja->satuan_kerja_id)
            ->Where('tahun_kinerja', $tahun)
            ->Where('status', Penilaian::STATUS_DONE_2)
            ->first();
        // return response()->json([$penilaianIdTahunBerjalan]);
        if ($penilaianIdTahunBerjalan) {
            $penilaianTahunBerjalan = $penilaianIdTahunBerjalan->penilaianKomponen->map(function ($item) {
                return [
                    'nama' => $item->komponen->nama,
                    'bobot' => $item->komponen->bobot, // Assuming 'bobot' is a field in the komponen table
                    'skor_penilaian_tahun_berjalan' => $item->nilai,
                    'tahun_kinerja' => $item->komponen->tahun_kinerja,
                ];
            });

            foreach ($penilaianTahunBerjalan as $item) {
                if (isset($mergedData[$item['nama']])) {
                    $mergedData[$item['nama']]['skor_penilaian_tahun_berjalan'] = $item['skor_penilaian_tahun_berjalan'];
                }
            }

        }

        $penilaianIdTahunSebelumnya = Penilaian::with(['penilaianKomponen', 'penilaianKomponen.komponen'])
            ->Where('satuan_kerja_id', $satuanKerja->satuan_kerja_id)
            ->Where('tahun_kinerja', $tahunSebelumnya)
            ->Where('status', Penilaian::STATUS_DONE_2)
            ->first();

        //        $penilaianIdTahunSebelumnya = Penilaian::roleSatuanKerja()
        //            ->with(['penilaianKomponen', 'penilaianKomponen.komponen'])
        //            ->where('tahun_kinerja', $tahunSebelumnya)
        //            ->where('status', Penilaian::STATUS_DONE_2)
        //            ->first();

        if ($penilaianIdTahunSebelumnya) {
            $penilaianTahunSebelumnya = $penilaianIdTahunSebelumnya->penilaianKomponen->map(function ($item) {
                return [
                    'nama' => $item->komponen->nama,
                    'bobot' => $item->komponen->bobot, // Assuming 'bobot' is a field in the komponen table
                    'skor_penilaian_tahun_sebelumnya' => $item->nilai,
                    'tahun_kinerja' => $item->komponen->tahun_kinerja,
                ];
            });
            foreach ($penilaianTahunSebelumnya as $item) {
                $mergedData[$item['nama']] = [
                    'nama' => $item['nama'],
                    'bobot' => $item['bobot'],
                    'skor_penilaian_tahun_sebelumnya' => strval($item['skor_penilaian_tahun_sebelumnya']),
                    'skor_penilaian_tahun_berjalan' => 0, // Default value, will be updated if exists
                ];
            }
        }

        $bobotTotal = 0;
        $skorTotal = 0;
        $totalSkorTahunSebelumnya = 0;
        $totalSkorTahunBerjalan = 0;
        $valuespenilaian = [];
        $id = 1;
        $mergedData = [];

        if ($penilaianIdTahunSebelumnya) {
            foreach ($penilaianTahunSebelumnya as $item) {
                $mergedData[$item['nama']] = [
                    'nama' => $item['nama'],
                    'bobot' => $item['bobot'],
                    'skor_penilaian_tahun_sebelumnya' => strval($item['skor_penilaian_tahun_sebelumnya']),
                    'skor_penilaian_tahun_berjalan' => 0, // Default value, will be updated if exists
                ];
            }

            if ($penilaianIdTahunBerjalan) {
                foreach ($penilaianTahunBerjalan as $item) {
                    if (isset($mergedData[$item['nama']])) {
                        $mergedData[$item['nama']]['skor_penilaian_tahun_berjalan'] = $item['skor_penilaian_tahun_berjalan'];
                    }
                }
            }
        }

        $finalResult = array_values($mergedData);

        foreach ($finalResult as $data) {
            $valuespenilaian[] = [
                'varLkeId' => $id++,
                'varLkeKomponen' => $data['nama'],
                'varLkeBobot' => $data['bobot'],
                'varLkeNilaiTahunSebelumnya' => $data['skor_penilaian_tahun_sebelumnya'],
                'varLkeNilaiTahunBerjalan' => $data['skor_penilaian_tahun_berjalan'],
            ];
            $totalSkorTahunSebelumnya += $data['skor_penilaian_tahun_sebelumnya'];
            $totalSkorTahunBerjalan += $data['skor_penilaian_tahun_berjalan'];
        }

        $statusPenilaian = Penilaian::where('satuan_kerja_id', $satuanKerja->satuan_kerja_id)
            ->where('tahun_kinerja', $tahun)
            ->orderBy('created_at', 'DESC')
            ->pluck('status')
            ->first();

        $dataCatatanPerencanaan = getDataCatatan($satuanKerja, $tahun, 1, $statusPenilaian);
        $dataCatatanPengukuran = getDataCatatan($satuanKerja, $tahun, 2, $statusPenilaian);
        $dataCatatanPelaporan = getDataCatatan($satuanKerja, $tahun, 3, $statusPenilaian);
        $dataCatatanEvaluasi = getDataCatatan($satuanKerja, $tahun, 4, $statusPenilaian);

        // return response()->json($dataCatatanPengukuran);
        $catatanPerencanaan = [];
        $catatanPengukuran = [];
        $catatanPelaporan = [];
        $catatanEvaluasi = [];
        $catatanTindakLanjut = [];
        $catatanRekomendasi = [];

        foreach ($dataCatatanPerencanaan as $index => $data) {
            $catatanPerencanaan[] = [
                'varCatatanPerencanaanId' => getAlphabetLetter($index).'.',
                'varCatatanPerencanaan' => removeSpecialCharacters($data['catatan']),
            ];
        }

        foreach ($dataCatatanPengukuran as $index => $data) {
            $catatanPengukuran[] = [
                'varCatatanPengukuranId' => getAlphabetLetter($index).'.',
                'varCatatanPengukuran' => removeSpecialCharacters($data['catatan']),
            ];
        }

        foreach ($dataCatatanPelaporan as $index => $data) {
            $catatanPelaporan[] = [
                'varCatatanPelaporanId' => getAlphabetLetter($index).'.',
                'varCatatanPelaporan' => removeSpecialCharacters($data['catatan']),
            ];
        }

        foreach ($dataCatatanEvaluasi as $index => $data) {
            $catatanEvaluasi[] = [
                'varCatatanEvaluasiId' => getAlphabetLetter($index).'.',
                'varCatatanEvaluasi' => removeSpecialCharacters($data['catatan']),
            ];
        }

        $dataCatatanTindakLanjut = Rekomendasi::where('tahun_kinerja', $tahunSebelumnya)
            ->where('satuan_kerja_id', $satuanKerja->satuan_kerja_id)
            ->get();

        $dataCatatanRekomendasi = Rekomendasi::where('tahun_kinerja', $tahun)
            ->where('satuan_kerja_id', $satuanKerja->satuan_kerja_id)
            ->get();

        foreach ($dataCatatanTindakLanjut as $index => $data) {
            $catatanTindakLanjut[] = [
                'varCatatanTindakLanjutId' => getAlphabetLetter($index).'.',
                'varCatatanTindakLanjut' => removeSpecialCharacters($data['tindak_lanjut']),
            ];
        }

        foreach ($dataCatatanRekomendasi as $index => $data) {
            $catatanRekomendasi[] = [
                'varCatatanRekomendasiId' => getAlphabetLetter($index).'.',
                'varCatatanRekomendasi' => removeSpecialCharacters($data['rekomendasi']),
            ];
        }

        $valuespenilaian[] = [
            'varLkeId' => '',
            'varLkeKomponen' => 'Nilai Total',
            'varLkeBobot' => 100,
            'varLkeNilaiTahunSebelumnya' => number_format($totalSkorTahunSebelumnya, 2, '.', ''),
            'varLkeNilaiTahunBerjalan' => number_format($totalSkorTahunBerjalan, 2, '.', ''),
        ];

        // return response()->json(getDataCatatan($satuanKerja, $tahun, 1));
        $templateProcessor = new TemplateProcessor(public_path('template_lhe_inspektorat.docx'));

        $templateProcessor->setValue('varTanggalSurat', $tanggal);
        $templateProcessor->setValue('varKepalaSatuanKerja', $varKepalaSatuanKerja);
        $templateProcessor->setValue('varTahunKinerja', $tahun);
        $templateProcessor->setValue('varTahunKinerjaSebelumnya', $tahunSebelumnya);
        $templateProcessor->setValue('varSatuanKerja', capitalizeWords($satuanKerja->satuan_kerja_nama));
        $templateProcessor->setValue('varPredikat', EvidenController::getPredikat($skorTotal)[0]);
        $templateProcessor->setValue('varNilaiTotal', number_format($skorTotal, 2, '.', ''));
        $templateProcessor->setValue('varPredikatHuruf', EvidenController::getPredikat($skorTotal)[1]);
        $templateProcessor->setValue('varPredikatKeterangan', removeSpecialCharacters(EvidenController::getPredikat($skorTotal)[2]));

        $templateProcessor->cloneRowAndSetValues('varLkeId', $valuespenilaian);
        $templateProcessor->cloneRowAndSetValues('varCatatanPerencanaanId', $catatanPerencanaan);
        $templateProcessor->cloneRowAndSetValues('varCatatanPengukuranId', $catatanPengukuran);
        $templateProcessor->cloneRowAndSetValues('varCatatanPelaporanId', $catatanPelaporan);
        $templateProcessor->cloneRowAndSetValues('varCatatanEvaluasiId', $catatanEvaluasi);
        $templateProcessor->cloneRowAndSetValues('varCatatanRekomendasiId', $catatanRekomendasi);
        $templateProcessor->cloneRowAndSetValues('varCatatanTindakLanjutId', $catatanTindakLanjut);

        $outputPath = storage_path('app/temp/generated.docx');
        $templateProcessor->saveAs($outputPath);

        //        return  response()->json([$valuespenilaian]);
        //        return response()->json([
        //            'success' => false,
        //            'message' => 'Perangkat Daerah belum melakukan submit penilaian',
        //            'catatanPerencanaan' => $catatanPerencanaan,
        //            'catatanPelaporan' => $catatanPelaporan,
        //            'catatanPengukuran' => $catatanPengukuran,
        //            'catatanEvaluasi' => $catatanEvaluasi,
        //            'catatanRekomendasi' => $catatanRekomendasi,
        //            'catatanTindakLanjut' => $catatanTindakLanjut,
        //
        //        ]);
        if ($format == 'pdf') {
            return response()->json([
                'success' => false,
                'message' => 'Perangkat Daerah belum melakukan submit penilaian',
                'catatanPerencanaan' => $catatanPerencanaan,
                'catatanPelaporan' => $catatanPelaporan,
                'catatanPengukuran' => $catatanPengukuran,
                'catatanEvaluasi' => $catatanEvaluasi,
                'catatanRekomendasi' => $catatanRekomendasi,
                'catatanTindakLanjut' => $catatanTindakLanjut,

            ]);
        } else {
            $fileName = 'Laporan LKE '.capitalizeWords($satuanKerja->satuan_kerja_nama).' '.$tahun.'.docx';

            return response()->download($outputPath, $fileName)->deleteFileAfterSend(true);
        }

    }

    public function getDataPenilaian($tahun, $penilaianId, $satuanKerja, $penilaianKomponen)
    {
        return Komponen::where('tahun_kinerja', $tahun)
            ->with([
                'subKomponen' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria.eviden' => fn (Builder $query) => $query->roleSatuanKerja($satuanKerja->satuan_kerja_id)->tahunKinerja()->orderBy('id'),
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
                        if ($kriteria->eviden) {
                            $skorSubKomponen += $kriteria->eviden->riwayat[0]->parameterNilai->skor * $kriteria->bobot;
                        }
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
    }
}
