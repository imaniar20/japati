<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;

class PohonKinerjaInsertDataController extends Controller
{
    public function importToPohonKinerja(Request $request): JsonResponse
    {
        ini_set('max_execution_time', 3000);
        $dataSasaranItems = DB::select("select distinct sasaran from pohon_kinerja_raw2 where parent_id is null and status_lv = '1'");
        foreach ($dataSasaranItems as $item) {
            $sasaran = $item->sasaran;

            $data = DB::select('SELECT * FROM get_pohon_kinerja_wide_sasaran(?)', [$sasaran]);

            $tree = $this->buildTree($data);

            DB::beginTransaction();
            try {

                foreach ($tree as $rootNode) {
                    $this->insertNodeRecursively(
                        $rootNode,
                        null,
                        1,
                    );
                }

                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'success' => false,
                    'message' => 'Error importing data: '.$e->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data successfully imported to pohon_kinerja table',
        ]);

    }

    /**
     * Build tree structure from flat data
     */
    private function buildTree($data): array
    {
        $tree = [];
        foreach ($data as $row) {
            $currentNode = &$tree;
            for ($i = 1; $i <= 8; $i++) {
                $idKey = "id_lv{$i}";
                $sasaranKey = "sasaran_lv{$i}";
                $indikatorKey = "indikator_lv{$i}";
                $crosscuttingKey = "is_crosscutting_lv{$i}";
                $satuanKerjaKey = "satuan_kerja_lv{$i}";

                if (is_null($row->$sasaranKey)) {
                    break;
                }

                $sasaranValue = $row->$sasaranKey;

                if (! isset($currentNode[$sasaranValue])) {
                    $currentNode[$sasaranValue] = [
                        $idKey => Str::uuid()->toString(),
                        $sasaranKey => $row->$sasaranKey,
                        $indikatorKey => $row->$indikatorKey,
                        $satuanKerjaKey => $row->$satuanKerjaKey,
                        'children' => [],
                    ];

                    if ($i > 1 && property_exists($row, $crosscuttingKey)) {
                        $currentNode[$sasaranValue][$crosscuttingKey] = $row->$crosscuttingKey;
                    }
                }

                $currentNode = &$currentNode[$sasaranValue]['children'];
            }
        }

        // Format the tree
        foreach ($tree as &$rootNode) {
            $this->formatTree($rootNode);
        }

        return array_values($tree);
    }

    /**
     * Format tree to use numeric arrays for children
     */
    private function formatTree(&$node): void
    {
        if (! empty($node['children'])) {
            $node['children'] = array_values($node['children']);
            foreach ($node['children'] as &$child) {
                $this->formatTree($child);
            }
        }
    }

    /**
     * Recursively insert nodes into pohon_kinerja table
     */
    private function insertNodeRecursively(
        array $node,
        ?string $parentId,
        int $level
    ): void {
        // Extract data based on level
        $idKey = "id_lv{$level}";
        $sasaranKey = "sasaran_lv{$level}";
        $indikatorKey = "indikator_lv{$level}";
        $crosscuttingKey = "is_crosscutting_lv{$level}";
        $satuanKerjaKey = "satuan_kerja_lv{$level}";

        // Prepare data for insertion
        $insertData = [
            'id' => $node[$idKey],
            'satuan_kerja' => $node[$satuanKerjaKey] ?? null,
            'sasaran' => $node[$sasaranKey] ?? null,
            'indikator' => $node[$indikatorKey] ?? null,
            'is_crosscutting' => $node[$crosscuttingKey] ?? false,
            'status_lv' => (string) $level,
            'parent_id' => $parentId,
            'tahun_kinerja' => null,
            'satuan_kerja_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Insert the node and get its ID
        $nodeId = DB::table('pohon_kinerja')->insertGetId($insertData);

        // Process children if they exist
        if (! empty($node['children'])) {
            foreach ($node['children'] as $child) {
                $this->insertNodeRecursively(
                    $child,
                    $nodeId,
                    $level + 1
                );
            }
        }
    }

    /**
     * Alternative: Import using batch insert for better performance
     */
    public function importToPohonKinerjaBatch(Request $request): JsonResponse
    {
        $request->validate([
            'satuan_kerja_id' => 'required|integer',
            'tahun_kinerja' => 'required|integer',
        ]);

        $satuanKerjaId = $request->input('satuan_kerja_id');
        $tahunKinerja = $request->input('tahun_kinerja');

        $satuanKerja = DB::table('satuan_kerja')
            ->where('id', $satuanKerjaId)
            ->value('nama_satuan_kerja') ?? 'Unknown';

        $data = DB::select('SELECT * FROM get_pohon_kinerja_wide(?)', [$satuanKerjaId]);
        $tree = $this->buildTree($data);

        DB::beginTransaction();
        try {
            // Clear existing data
            DB::table('pohon_kinerja_live')
                ->where('satuan_kerja_id', $satuanKerjaId)
                ->where('tahun_kinerja', $tahunKinerja)
                ->delete();

            // Collect all nodes with temporary IDs
            $allNodes = [];
            $nodeIdMap = []; // Map temporary IDs to actual database IDs

            foreach ($tree as $rootNode) {
                $this->collectNodes(
                    $rootNode,
                    null,
                    1,
                    $satuanKerja,
                    $satuanKerjaId,
                    $tahunKinerja,
                    $allNodes
                );
            }

            // Insert nodes level by level
            $this->insertNodesLevelByLevel($allNodes, $nodeIdMap);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data successfully imported using batch insert',
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error importing data: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Collect all nodes with temporary IDs for batch processing
     */
    private function collectNodes(
        array $node,
        ?string $parentTempId,
        int $level,
        string $satuanKerja,
        int $satuanKerjaId,
        int $tahunKinerja,
        array &$allNodes
    ): void {
        $sasaranKey = "sasaran_lv{$level}";
        $indikatorKey = "indikator_lv{$level}";
        $crosscuttingKey = "is_crosscutting_lv{$level}";
        $idKey = "id_lv{$level}";

        // Create a temporary ID for this node
        $tempId = $level.'_'.($node[$idKey] ?? uniqid());

        $nodeData = [
            'temp_id' => $tempId,
            'parent_temp_id' => $parentTempId,
            'level' => $level,
            'satuan_kerja' => $satuanKerja,
            'sasaran' => $node[$sasaranKey] ?? null,
            'indikator' => $node[$indikatorKey] ?? null,
            'is_crosscutting' => $node[$crosscuttingKey] ?? false,
            'status_lv' => (string) $level,
            'tahun_kinerja' => $tahunKinerja,
            'satuan_kerja_id' => $satuanKerjaId,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $allNodes[] = $nodeData;

        // Process children
        if (! empty($node['children'])) {
            foreach ($node['children'] as $child) {
                $this->collectNodes(
                    $child,
                    $tempId,
                    $level + 1,
                    $satuanKerja,
                    $satuanKerjaId,
                    $tahunKinerja,
                    $allNodes
                );
            }
        }
    }

    /**
     * Insert nodes level by level and maintain parent relationships
     */
    private function insertNodesLevelByLevel(array $allNodes, array &$nodeIdMap): void
    {
        // Group nodes by level
        $nodesByLevel = [];
        foreach ($allNodes as $node) {
            $nodesByLevel[$node['level']][] = $node;
        }

        // Sort levels
        ksort($nodesByLevel);

        // Insert level by level
        foreach ($nodesByLevel as $level => $nodes) {
            foreach ($nodes as $node) {
                // Set parent_id based on the map
                $parentId = null;
                if ($node['parent_temp_id'] !== null && isset($nodeIdMap[$node['parent_temp_id']])) {
                    $parentId = $nodeIdMap[$node['parent_temp_id']];
                }

                // Prepare insert data
                $insertData = [
                    'satuan_kerja' => $node['satuan_kerja'],
                    'sasaran' => $node['sasaran'],
                    'indikator' => $node['indikator'],
                    'is_crosscutting' => $node['is_crosscutting'],
                    'status_lv' => $node['status_lv'],
                    'parent_id' => $parentId,
                    'tahun_kinerja' => $node['tahun_kinerja'],
                    'satuan_kerja_id' => $node['satuan_kerja_id'],
                    'created_at' => $node['created_at'],
                    'updated_at' => $node['updated_at'],
                ];

                // Insert and store the mapping
                $insertedId = DB::table('pohon_kinerja')->insertGetId($insertData);
                $nodeIdMap[$node['temp_id']] = $insertedId;
            }
        }
    }
}
