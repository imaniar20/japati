<?php

namespace App\Http\Controllers;

use App\Models\PohonKinerja;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PohonKinerjaController extends Controller
{
    private function formatTree(array &$node): void
    {
        if (! empty($node['children'])) {
            foreach ($node['children'] as &$child) {
                $this->formatTree($child);
            }
            $node['children'] = array_values($node['children']);
        }
    }

    public function detail(Request $request): JsonResponse
    {
        $request->validate([
            'pohon_kinerja_id' => 'required',
        ]);
        $pohonKinerjaId = $request->input('pohon_kinerja_id');
        $pohonKinerja = PohonKinerja::find($pohonKinerjaId);

        return response()->json([$pohonKinerja]);
    }

    public function sendAIKorelasi(Request $request): JsonResponse
    {
        $request->validate([
            'pohon_kinerja_id' => 'required',
        ]);
        $aiUrl = config('app.ai_url');
        $linkApi = $aiUrl.'/api/get_ai_korelasi_pohon_kinerja/'.$request->input('pohon_kinerja_id');
        $response = Http::get($linkApi);

        return response()->json($linkApi);
    }

    public function sendAIRekomendasi(Request $request): JsonResponse
    {
        $request->validate([
            'pohon_kinerja_id' => 'required',
        ]);
        $aiUrl = config('app.ai_url');
        $linkApi = $aiUrl.'/api/get_ai_rekomendasi_sasaran_pohon_kinerja/'.$request->input('pohon_kinerja_id');
        $response = Http::get($linkApi);

        return response()->json($linkApi);
    }

    public function index(Request $request): JsonResponse
    {
        setTahunKinerja(20252);
        $request->validate([
            'sasaran_pohon_kinerja_id' => 'required',
        ]);
        $sasaran = $request->input('sasaran_pohon_kinerja_id');
        $data = DB::select('SELECT * FROM get_pohon_kinerja_hierarchy(?)', [$sasaran]);
        $tree = [];
        foreach ($data as $row) {
            $currentNode = &$tree;
            for ($i = 1; $i <= 9; $i++) {
                // Define all the keys for the current level
                $idKey = "id_lv{$i}";
                $sasaranKey = "sasaran_lv{$i}";
                $indikatorKey = "indikator_lv{$i}";
                $crosscuttingKey = "is_crosscutting_lv{$i}";
                $satuanKerjaKey = "satuan_kerja_lv{$i}";
                $labelAiKey = "label_ai_lv{$i}";
                $scoreAiKey = "score_ai_lv{$i}";
                $isAi = "is_ai_lv{$i}";
                $isAiRekomendasi = "is_ai_rekomendasi_lv{$i}";

                if (is_null($row->$sasaranKey)) {
                    break;
                }

                $sasaranValue = $row->$sasaranKey;

                // If this sasaran doesn't exist at the current level, create it
                if (! isset($currentNode[$sasaranValue])) {
                    $currentNode[$sasaranValue] = [
                        $idKey => $row->$idKey, // <--- ID ADDED HERE
                        $sasaranKey => $row->$sasaranKey,
                        $indikatorKey => $row->$indikatorKey,
                        $satuanKerjaKey => $row->$satuanKerjaKey,
                        $labelAiKey => $row->$labelAiKey,
                        $scoreAiKey => $row->$scoreAiKey,
                        $isAi => $isAi,
                        $isAiRekomendasi => $row->$isAiRekomendasi,
                        'children' => [],
                    ];
                    if ($i > 1 && property_exists($row, $crosscuttingKey)) {
                        $currentNode[$sasaranValue][$crosscuttingKey] = $row->$crosscuttingKey;
                    }
                }
                $currentNode = &$currentNode[$sasaranValue]['children'];
            }
        }

        // 3. Format the final tree to use numeric arrays for children
        foreach ($tree as &$rootNode) {
            $this->formatTree($rootNode);
        }

        // Return the final structure as a JSON response
        return response()->json(array_values($tree));
    }
}
