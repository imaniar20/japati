<?php

namespace App\Http\Controllers;

use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\KinerjaTercapai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KinerjaTercapaiController extends Controller
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
            'type' => ['required', 'string', Rule::in(['kinerja-program', 'kinerja-kegiatan', 'kinerja-sub-kegiatan'])],
        ]);

        $data = KinerjaTercapai::tahunKinerja()
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
            'type' => ['required', 'string', Rule::in(['kinerja-program', 'kinerja-kegiatan', 'kinerja-sub-kegiatan'])],
            'catatan' => ['required', 'string'],
        ]);

        KinerjaTercapai::create([
            'notable_type' => $this->getType($validated['type']),
            'notable_id' => $validated['id'],
            'tahun_kinerja' => getTahunKinerja(),
            'catatan' => $validated['catatan'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambah data kinerja tercapai',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KinerjaTercapai $kinerjaTercapai)
    {
        $validated = $request->validate([
            'catatan' => ['required', 'string'],
        ]);

        $kinerjaTercapai->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil update data kinerja tercapai',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(KinerjaTercapai $kinerjaTercapai)
    {
        $kinerjaTercapai->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data kinerja tercapai',
        ]);
    }

    private function getType(string $type): string
    {
        switch ($type) {
            case 'kinerja-program':
                return KinerjaProgram::class;
                break;
            case 'kinerja-kegiatan':
                return KinerjaKegiatan::class;
                break;
            case 'kinerja-sub-kegiatan':
                return KinerjaSubKegiatan::class;
                break;

            default:
                abort(500, 'Tipe tidak dikenali');
                break;
        }
    }
}
