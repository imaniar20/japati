<?php

namespace App\Http\Controllers;

use App\Models\KinerjaKabKota;
use App\Models\KinerjaSubKegiatanKabKota;
use Illuminate\Support\Facades\Http;

class KinerjaKabKotaController extends Controller
{
    //    public function test(){
    //        $subKegiatan = KinerjaSubKegiatanKabKota::with('kinerjaKabKota')->get();
    //        dd($subKegiatan);
    //
    //    }
    public function getKabKotaSatuanKerja($kinerjaKabKotaId)
    {
        $linkAppKabKota = KinerjaKabKota::where('id', '=', $kinerjaKabKotaId)->pluck('link_aplikasi')->first();
        $linkApi = $linkAppKabKota.'/api/public-share-kinerja/satuan-kerja';
        $response = Http::get($linkApi);

        return response()->json($response->json());
    }

    public function getKabKotaKinerjaSubKegiatan($kinerjaKabKotaId, $satuanKerjaId)
    {
        $linkAppKabKota = KinerjaKabKota::where('id', '=', $kinerjaKabKotaId)->pluck('link_aplikasi')->first();
        $linkApi = $linkAppKabKota.'/api/public-share-kinerja/kinerja-sub-kegiatan-list/'.getTahunKinerja().'/'.$satuanKerjaId;
        $response = Http::get($linkApi);

        return response()->json($response->json());
    }

    public function getKabKotaKinerjaSubKegiatanDetail($kinerjaKabKotaId, $kinerjaSubKegiatanId)
    {
        $linkAppKabKota = KinerjaKabKota::where('id', '=', $kinerjaKabKotaId)->pluck('link_aplikasi')->first();
        $linkApi = $linkAppKabKota.'/api/public-share-kinerja/kinerja-sub-kegiatan-detail/'.$kinerjaSubKegiatanId;
        $response = Http::get($linkApi);

        return response()->json($response->json());
    }
}
