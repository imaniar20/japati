<?php

namespace App\Http\Controllers;

use App\Models\KinerjaKegiatan;
use App\Models\KinerjaLangkahAksi;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\KinerjaTidakTercapai;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use App\Models\VisiMisiRpjmd;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KinerjaTidakTercapaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'numeric'],
            'type' => ['required', 'string', Rule::in(['visi-misi-rpjmd', 'sasaran-strategis-rpjmd', 'sasaran-strategis-pd', 'kinerja-program', 'kinerja-kegiatan', 'kinerja-sub-kegiatan', 'kinerja-langkah-aksi'])],
        ]);

        $data = KinerjaTidakTercapai::tahunKinerja()
            ->where('notable_type', $this->getType($validated['type']))
            ->where('notable_id', $validated['id'])
            ->orderBy('id')
            ->get();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'numeric'],
            'type' => ['required', 'string', Rule::in(['visi-misi-rpjmd', 'sasaran-strategis-rpjmd', 'sasaran-strategis-pd', 'kinerja-program', 'kinerja-kegiatan', 'kinerja-sub-kegiatan', 'kinerja-langkah-aksi'])],
            'catatan' => ['required', 'string'],
        ]);

        KinerjaTidakTercapai::create([
            'notable_type' => $this->getType($validated['type']),
            'notable_id' => $validated['id'],
            'tahun_kinerja' => getTahunKinerja(),
            'catatan' => $validated['catatan'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambah data kinerja tidak tercapai',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KinerjaTidakTercapai $kinerjaTidakTercapai)
    {
        $validated = $request->validate([
            'catatan' => ['required', 'string'],
        ]);

        $kinerjaTidakTercapai->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil update data kinerja tidak tercapai',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(KinerjaTidakTercapai $kinerjaTidakTercapai)
    {
        $kinerjaTidakTercapai->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data kinerja tidak tercapai',
        ]);
    }

    private function getType(string $type): string
    {
        switch ($type) {
            case 'visi-misi-rpjmd':
                return VisiMisiRpjmd::class;
                break;
            case 'sasaran-strategis-rpjmd':
                return SasaranStrategisRpjmd::class;
                break;
            case 'sasaran-strategis-pd':
                return SasaranStrategisPd::class;
                break;
            case 'kinerja-program':
                return KinerjaProgram::class;
                break;
            case 'kinerja-kegiatan':
                return KinerjaKegiatan::class;
                break;
            case 'kinerja-sub-kegiatan':
                return KinerjaSubKegiatan::class;
                break;
            case 'kinerja-langkah-aksi':
                return KinerjaLangkahAksi::class;
                break;

            default:
                abort(500, 'Tipe tidak dikenali');
                break;
        }
    }
}
