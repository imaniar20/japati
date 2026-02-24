<?php

namespace App\Http\Controllers\PublicDisplay;

use App\Http\Controllers\Controller;
use App\Models\Ekinerja\IkiBulanan;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaLangkahAksi;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use App\Models\SKP;
use App\Services\DiagramSasaran;
use App\Services\FilterQuery;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DisplayMikroController extends Controller
{
    public function renstra(Request $request)
    {
        $sasaranStrategisPd = SasaranStrategisPd::tahunMulai();

        FilterQuery::parseFilter($sasaranStrategisPd, json_decode($request->filter, true));

        $sasaranStrategisPd = $sasaranStrategisPd->with([
            'satuanKerja',
        ])
            ->select('id', 'sasaran_strategis_satker', 'iku', 'target_1', 'target_2', 'target_3', 'target_4', 'target_5', 'satuan_kerja_id')
            ->paginate(20);

        return response()->json($sasaranStrategisPd);
    }

    public function rkt(Request $request)
    {
        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja();

        FilterQuery::parseFilter($kinerjaSubKegiatan, json_decode($request->filter, true));

        $kinerjaSubKegiatan = $kinerjaSubKegiatan->select('id', 'sub_kegiatan_id', 'sasaran_strategis_pd_id', 'kinerja_program_id', 'kinerja_kegiatan_id', 'satuan_kerja_id', 'anggaran')
            ->with([
                'satuanKerja',
                'subKegiatan',
                'sasaranStrategisPd',
                'kinerjaProgram.program',
                'kinerjaKegiatan.kegiatan',
            ])
            ->paginate(20);

        return response()->json($kinerjaSubKegiatan);
    }

    public function perjanjianKinerja(Request $request)
    {
        $sasaranStrategisPd = SasaranStrategisPd::tahunMulai();

        FilterQuery::parseFilter($sasaranStrategisPd, json_decode($request->filter, true));

        $sasaranStrategisPd = $sasaranStrategisPd->with([
            'satuanKerja',
        ])
            ->select('id', 'sasaran_strategis_satker', 'iku', 'tahun_mulai', 'target_1', 'target_2', 'target_3', 'target_4', 'target_5', 'satuan_kerja_id')
            ->paginate(20);

        return response()->json($sasaranStrategisPd);
    }

    public function rencanaAksi(Request $request)
    {
        $kinerjaLangkahAksi = KinerjaLangkahAksi::tahunKinerja();

        FilterQuery::parseFilter($kinerjaLangkahAksi, json_decode($request->filter, true));

        $kinerjaLangkahAksi = $kinerjaLangkahAksi->with([
            'satuanKerja',
            'sasaranStrategisRpjmd.sasaranStrategis',
            'sasaranStrategisRpjmd.indikatorSasaranStrategis',
            'sasaranStrategisPd',
            'kinerjaProgram.program',
            'kinerjaKegiatan.kegiatan',
            'kinerjaSubKegiatan.subKegiatan',
        ])
            ->paginate(20);

        return response()->json($kinerjaLangkahAksi);
    }

    public function capaianKinerjaPD(Request $request)
    {
        $sasaranStrategisPd = SasaranStrategisPd::tahunMulai();

        FilterQuery::parseFilter($sasaranStrategisPd, json_decode($request->filter, true));

        $sasaranStrategisPd = $sasaranStrategisPd->with([
            'satuanKerja',
        ])
            ->paginate(20);

        return response()->json($sasaranStrategisPd);
    }

    public function capaianKinerjaEfisiensiAnggaran(Request $request)
    {
        $kinerjaProgram = KinerjaProgram::tahunKinerja();

        FilterQuery::parseFilter($kinerjaProgram, json_decode($request->filter, true));

        $kinerjaProgram = $kinerjaProgram->with([
            'satuanKerja',
            'program',
            'sasaranStrategisPd',
        ])
            ->paginate(20);

        return response()->json($kinerjaProgram);
    }

    public function capaianKinerjaKeuangan(Request $request)
    {
        $kinerjaLangkahAksi = KinerjaLangkahAksi::tahunKinerja();

        FilterQuery::parseFilter($kinerjaLangkahAksi, json_decode($request->filter, true));

        $kinerjaLangkahAksi = $kinerjaLangkahAksi->with([
            'satuanKerja',
            'sasaranStrategisPd',
            'kinerjaProgram.program',
            'kinerjaKegiatan.kegiatan',
            'kinerjaSubKegiatan.subKegiatan',
        ])
            ->paginate(20);

        return response()->json($kinerjaLangkahAksi);
    }

    public function programInovatif(Request $request)
    {
        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja();

        FilterQuery::parseFilter($kinerjaSubKegiatan, json_decode($request->filter, true));

        $kinerjaSubKegiatan = $kinerjaSubKegiatan->whereNotNull('inovasi_uraian')
            ->with([
                'satuanKerja',
                'sasaranStrategisPd',
            ])
            ->paginate(20);

        return response()->json($kinerjaSubKegiatan);
    }

    public function capaianKinerjaProgram(Request $request)
    {
        $data = KinerjaProgram::tahunKinerja();

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

    public function capaianKinerjaKegiatan(Request $request)
    {
        $kinerjaKegiatan = KinerjaKegiatan::tahunKinerja();

        FilterQuery::parseFilter($kinerjaKegiatan, json_decode($request->filter, true));

        $kinerjaKegiatan = $kinerjaKegiatan->with([
            'satuanKerja',
            'sasaranStrategisPd',
            'kinerjaProgram.program',
            'kegiatan',
        ])
            ->paginate(20);

        return response()->json($kinerjaKegiatan);
    }

    public function capaianKinerjaSubKegiatan(Request $request)
    {
        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja();

        FilterQuery::parseFilter($kinerjaSubKegiatan, json_decode($request->filter, true));

        $kinerjaSubKegiatan = $kinerjaSubKegiatan->with([
            'satuanKerja',
            'sasaranStrategisPd',
            'kinerjaProgram.program',
            'kinerjaKegiatan.kegiatan',
            'subKegiatan',
        ])
            ->paginate(20);

        return response()->json($kinerjaSubKegiatan);
    }

    public function capaianKinerjaLangkahAksi(Request $request)
    {
        $kinerjaLangkahAksi = KinerjaLangkahAksi::tahunKinerja();

        FilterQuery::parseFilter($kinerjaLangkahAksi, json_decode($request->filter, true));

        $kinerjaLangkahAksi = $kinerjaLangkahAksi->with([
            'satuanKerja',
            'sasaranStrategisPd',
            'kinerjaProgram.program',
            'kinerjaKegiatan.kegiatan',
            'kinerjaSubKegiatan.subKegiatan',
        ])
            ->paginate(20);

        return response()->json($kinerjaLangkahAksi);
    }

    public function rencanaAksiTerintegrasi(Request $request)
    {
        $filter = json_decode($request->filter, true);

        $skpIds = SKP::tahunKinerja()
            ->where('model_class', KinerjaSubKegiatan::class)
            ->when($filter['sasaran_sub_kegiatan'] ?? null, fn (Builder $query, string $sasaran) => $query->where('sasaran', $sasaran))
            ->where('is_skp', true);

        FilterQuery::parseFilter($skpIds, $filter);

        $skpIds = $skpIds->pluck('id');

        $data = IkiBulanan::query()
            ->selectRaw("sasaran_kinerja_id, MIN(indikator_bulanan) indikator_langkah_aksi, json_agg(json_build_object('bulan', bulan, 'target', target, 'satuan', satuan_target, 'realisasi', realisasi)) AS data")
            ->with([
                'sasaranKinerja:id,sakip_id,sakip_type',

                'sasaranKinerja.skp:id,sasaran,parent_id,model_class,model_id,satuan_kerja_id', // SKP KinerjaSubKegiatan
                'sasaranKinerja.skp.skp:id,sub_kegiatan_id', // KinerjaSubKegiatan
                'sasaranKinerja.skp.skp.subKegiatan:id,nama', // SubKegiatan

                'sasaranKinerja.skp.parent:id,sasaran,indikator,parent_id,model_class,model_id', // SKP KinerjaKegiatan
                'sasaranKinerja.skp.parent.skp:id,kegiatan_id', // KinerjaKegiatan
                'sasaranKinerja.skp.parent.skp.kegiatan:id,nama', // Kegiatan

                'sasaranKinerja.skp.parent.parent:id,sasaran,indikator,parent_id,model_class,model_id', // SKP KinerjaProgram
                'sasaranKinerja.skp.parent.parent.skp:id,program_id', // KinerjaProgram
                'sasaranKinerja.skp.parent.parent.skp.program:id,nama', // Program

                'sasaranKinerja.skp.satuanKerja:satuan_kerja_id,satuan_kerja_nama', // SatuanKerja
            ])
            ->whereHas('sasaranKinerja', fn (Builder $query) => $query
                ->whereIn('sakip_id', $skpIds)
                ->where('sakip_type', 'App\Model\Sakip\KinerjaSubKegiatan')
            )
            ->groupBy('sasaran_kinerja_id')
            ->paginate(20);

        /**
         * @var \Illuminate\Pagination\AbstractPaginator $data
         */
        $data->setCollection($data->getCollection()->transform(fn (IkiBulanan $item) => [
            'satuan_kerja' => $item->sasaranKinerja?->skp?->satuanKerja->satuan_kerja_nama,
            'data' => collect(json_decode($item->data, true))->keyBy('bulan'),
            'indikator_langkah_aksi' => $item->indikator_langkah_aksi,
            'sasaran_sub_kegiatan' => $item->sasaranKinerja?->skp?->sasaran,
            'sub_kegiatan' => $item->sasaranKinerja?->skp?->skp?->subKegiatan?->nama,
            'kegiatan' => $item->sasaranKinerja?->skp?->parent?->skp?->kegiatan?->nama,
            'program' => $item->sasaranKinerja?->skp?->parent?->parent?->skp?->program?->nama,
        ]));

        return response()->json($data);
    }

    public function cascading(Request $request)
    {
        $filter = $request->validate([
            'satuan_kerja_id' => ['required', 'integer'],
        ]);

        if (! $filter['satuan_kerja_id']) {
            return response()->json([]);
        }

        $data = DiagramSasaran::get($filter['satuan_kerja_id']);

        return response()->json($data);
    }

    public function why(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja_id' => ['nullable', 'numeric'],
            'sasaran_strategis_rpjmd_id' => ['required', 'numeric'],
            'sasaran_strategis_pd_id' => ['nullable', 'numeric'],
            'programId' => ['nullable', 'numeric'],
            'kegiatanId' => ['nullable', 'numeric'],
            'subKegiatanId' => ['nullable', 'numeric'],
        ]);

        if (isset($validated['sasaran_strategis_rpjmd_id'])) {
            $data = SasaranStrategisRpjmd::whereId($validated['sasaran_strategis_rpjmd_id'])->first();
            if (isset($data)) {
                $data = $data->why;
            }

        }

        if (isset($validated['sasaran_strategis_pd_id'])) {
            $data = SasaranStrategisPd::whereId($validated['sasaran_strategis_pd_id'])->first();
            if (isset($data)) {
                $data = $data->why;
            }
        }

        if (isset($validated['programId'])) {
            $data = KinerjaProgram::whereId($validated['programId'])->first();
            $sasaran = $data->sasaran;

            // Check if first word starts with "Ter" or "Di"
            $words = explode(' ', $sasaran);
            $firstWord = $words[0] ?? '';

            if (str_starts_with($firstWord, 'Ter') || str_starts_with($firstWord, 'Di')) {
                // Add "Belum" at the beginning
                $sasaran = 'Belum '.$sasaran;
            }
            // Check if first word doesn't start with "Ter" and sentence contains "mendapatkan"
            elseif (! str_starts_with($firstWord, 'Ter') && str_contains($sasaran, 'mendapatkan')) {
                // Add "Belum" before "mendapatkan"
                $sasaran = str_replace('mendapatkan', 'belum mendapatkan', $sasaran);
            } elseif (! str_starts_with($firstWord, 'Ter') && str_contains($sasaran, 'dimanfaatkan')) {
                // Add "Belum" before "mendapatkan"
                $sasaran = str_replace('dimanfaatkan', 'belum dimanfaatkan', $sasaran);
            } elseif (str_starts_with($firstWord, 'Meningkatnya')) {
                // Add "Belum" before "mendapatkan"
                $sasaran = str_replace('Meningkatnya', 'Belum Optimalnya', $sasaran);
            }

            // Update the data
            $data = $sasaran;

        }

        if (isset($validated['kegiatanId'])) {
            $data = KinerjaKegiatan::whereId($validated['kegiatanId'])->first();
            $sasaran = $data->sasaran;

            // Check if first word starts with "Ter" or "Di"
            $words = explode(' ', $sasaran);
            $firstWord = $words[0] ?? '';

            if (str_starts_with($firstWord, 'Ter') || str_starts_with($firstWord, 'Di')) {
                // Add "Belum" at the beginning
                $sasaran = 'Belum '.$sasaran;
            }
            // Check if first word doesn't start with "Ter" and sentence contains "mendapatkan"
            elseif (! str_starts_with($firstWord, 'Ter') && str_contains($sasaran, 'mendapatkan')) {
                // Add "Belum" before "mendapatkan"
                $sasaran = str_replace('mendapatkan', 'belum mendapatkan', $sasaran);
            } elseif (! str_starts_with($firstWord, 'Ter') && str_contains($sasaran, 'dimanfaatkan')) {
                // Add "Belum" before "mendapatkan"
                $sasaran = str_replace('dimanfaatkan', 'belum dimanfaatkan', $sasaran);
            } elseif (str_starts_with($firstWord, 'Meningkatnya')) {
                // Add "Belum" before "mendapatkan"
                $sasaran = str_replace('Meningkatnya', 'Belum Optimalnya', $sasaran);
            }

            // Update the data
            $data = $sasaran;
        }

        if (isset($validated['subKegiatanId'])) {
            $data = KinerjaSubKegiatan::whereId($validated['subKegiatanId'])->first();
            $sasaran = $data->sasaran;

            // Check if first word starts with "Ter" or "Di"
            $words = explode(' ', $sasaran);
            $firstWord = $words[0] ?? '';

            if (str_starts_with($firstWord, 'Ter') || str_starts_with($firstWord, 'Di')) {
                // Add "Belum" at the beginning
                $sasaran = 'Belum '.$sasaran;
            }
            // Check if first word doesn't start with "Ter" and sentence contains "mendapatkan"
            elseif (! str_starts_with($firstWord, 'Ter') && str_contains($sasaran, 'mendapatkan')) {
                // Add "Belum" before "mendapatkan"
                $sasaran = str_replace('mendapatkan', 'belum mendapatkan', $sasaran);
            } elseif (! str_starts_with($firstWord, 'Ter') && str_contains($sasaran, 'dimanfaatkan')) {
                // Add "Belum" before "mendapatkan"
                $sasaran = str_replace('dimanfaatkan', 'belum dimanfaatkan', $sasaran);
            } elseif (str_starts_with($firstWord, 'Meningkatnya')) {
                // Add "Belum" before "mendapatkan"
                $sasaran = str_replace('Meningkatnya', 'Belum Optimalnya', $sasaran);
            }

            // Update the data
            $data = $sasaran;
        }

        return response()->json($data);
    }
}
