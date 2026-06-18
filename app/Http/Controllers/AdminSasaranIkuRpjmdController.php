<?php

namespace App\Http\Controllers;

use App\Models\IndikatorSasaranStrategis;
use App\Models\SasaranStrategis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AdminSasaranIkuRpjmdController extends Controller
{
    public function index()
    {
        $tahunMulai = $this->tahunMulai();

        return response()->json([
            'periode' => [
                'tahun_mulai' => $tahunMulai,
                'tahun_selesai' => $tahunMulai + 5,
            ],
            'sasaran' => SasaranStrategis::query()
                ->where('tahun_mulai', $tahunMulai)
                ->orderBy('nomor')
                ->orderBy('id')
                ->get(),
            'indikator' => IndikatorSasaranStrategis::query()
                ->where('tahun_mulai', $tahunMulai)
                ->orderBy('nomor')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function storeSasaran(Request $request)
    {
        $validated = $this->validateSasaran($request);

        $sasaran = SasaranStrategis::query()->create([
            ...$validated,
            'tahun_mulai' => $this->tahunMulai(),
        ]);

        return response()->json($sasaran, 201);
    }

    public function updateSasaran(Request $request, SasaranStrategis $sasaranStrategis)
    {
        $this->ensureSasaranPeriod($sasaranStrategis);

        $validated = $this->validateSasaran($request, $sasaranStrategis);

        $sasaranStrategis->update($validated);

        return response()->json($sasaranStrategis);
    }

    public function destroySasaran(SasaranStrategis $sasaranStrategis)
    {
        $this->ensureSasaranPeriod($sasaranStrategis);

        $usage = $this->findUsage('sasaran_strategis_id', $sasaranStrategis->id);

        if ($usage) {
            return response()->json([
                'message' => "Sasaran strategis tidak bisa dihapus karena sudah dipakai pada tabel {$usage}.",
            ], 400);
        }

        $sasaranStrategis->delete();

        return response()->json();
    }

    public function storeIndikator(Request $request)
    {
        $validated = $this->validateIndikator($request);

        $indikator = IndikatorSasaranStrategis::query()->create([
            ...$validated,
            'tahun_mulai' => $this->tahunMulai(),
        ]);

        return response()->json($indikator, 201);
    }

    public function updateIndikator(Request $request, IndikatorSasaranStrategis $indikatorSasaranStrategis)
    {
        $this->ensureIndikatorPeriod($indikatorSasaranStrategis);

        $validated = $this->validateIndikator($request, $indikatorSasaranStrategis);

        $indikatorSasaranStrategis->update($validated);

        return response()->json($indikatorSasaranStrategis);
    }

    public function destroyIndikator(IndikatorSasaranStrategis $indikatorSasaranStrategis)
    {
        $this->ensureIndikatorPeriod($indikatorSasaranStrategis);

        $usage = $this->findUsage('indikator_sasaran_strategis_id', $indikatorSasaranStrategis->id);

        if ($usage) {
            return response()->json([
                'message' => "Indikator sasaran strategis tidak bisa dihapus karena sudah dipakai pada tabel {$usage}.",
            ], 400);
        }

        $indikatorSasaranStrategis->delete();

        return response()->json();
    }

    private function validateSasaran(Request $request, ?SasaranStrategis $sasaranStrategis = null): array
    {
        return $request->validate([
            'nomor' => $this->nomorRules('sasaran_strategis', $sasaranStrategis?->id),
            'sasaran' => ['required', 'string'],
        ]);
    }

    private function validateIndikator(Request $request, ?IndikatorSasaranStrategis $indikatorSasaranStrategis = null): array
    {
        return $request->validate([
            'nomor' => $this->nomorRules('indikator_sasaran_strategis', $indikatorSasaranStrategis?->id),
            'indikator' => ['required', 'string'],
        ]);
    }

    private function nomorRules(string $table, ?int $ignoreId = null): array
    {
        $uniqueRule = Rule::unique($table, 'nomor')
            ->where(fn ($query) => $query->where('tahun_mulai', $this->tahunMulai()));

        if ($ignoreId) {
            $uniqueRule->ignore($ignoreId);
        }

        return ['required', 'integer', 'min:1', $uniqueRule];
    }

    private function ensureSasaranPeriod(SasaranStrategis $sasaranStrategis): void
    {
        if ((int) $sasaranStrategis->tahun_mulai !== $this->tahunMulai()) {
            abort(404);
        }
    }

    private function ensureIndikatorPeriod(IndikatorSasaranStrategis $indikatorSasaranStrategis): void
    {
        if ((int) $indikatorSasaranStrategis->tahun_mulai !== $this->tahunMulai()) {
            abort(404);
        }
    }

    private function findUsage(string $column, int $id): ?string
    {
        foreach ($this->usageTables() as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
                continue;
            }

            if (DB::table($table)->where($column, $id)->exists()) {
                return $table;
            }
        }

        return null;
    }

    private function usageTables(): array
    {
        return [
            'sasaran_strategis_rpjmd',
            'sasaran_strategis_pd',
            'lkip_narasi_pemda',
            'lkip_narasi_pd',
        ];
    }

    private function tahunMulai(): int
    {
        return getTahunMulai();
    }
}
