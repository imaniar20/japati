<?php

namespace App\Http\Controllers;

use App\Models\IndikatorTujuan;
use App\Models\Misi;
use App\Models\SasaranStrategisRpjmd;
use App\Models\Tujuan;
use App\Models\Visi;
use App\Models\VisiMisiRpjmd;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminVisiMisiController extends Controller
{
    public function index()
    {
        $tahunMulai = $this->tahunMulai();

        return response()->json([
            'periode' => $this->periode(),
            'visi' => Visi::query()
                ->where('tahun_mulai', $tahunMulai)
                ->orderBy('id')
                ->get(),
            'misi' => Misi::query()
                ->with('visi:id,visi')
                ->where('tahun_mulai', $tahunMulai)
                ->orderBy('nomor')
                ->orderBy('id')
                ->get(),
            'tujuan' => Tujuan::query()
                ->with('misi:id,visi_id,nomor,misi')
                ->where('tahun_mulai', $tahunMulai)
                ->orderBy('nomor')
                ->orderBy('id')
                ->get(),
            'indikator_tujuan' => IndikatorTujuan::query()
                ->with('tujuan:id,misi_id,nomor,tujuan')
                ->where('tahun_mulai', $tahunMulai)
                ->orderBy('nomor')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function storeVisi(Request $request)
    {
        $validated = $this->validateVisiPeriod($request);

        $visi = Visi::query()->create([
            'visi' => $this->formatVisi($validated['tahun_mulai'], $validated['tahun_selesai']),
            'tahun_mulai' => $this->tahunMulai(),
        ]);

        return response()->json($visi, 201);
    }

    public function updateVisi(Request $request, Visi $visi)
    {
        $this->ensureVisiPeriod($visi);

        $validated = $this->validateVisiPeriod($request);

        $visi->update([
            'visi' => $this->formatVisi($validated['tahun_mulai'], $validated['tahun_selesai']),
        ]);

        return response()->json($visi);
    }

    public function destroyVisi(Visi $visi)
    {
        $this->ensureVisiPeriod($visi);

        $isUsed = VisiMisiRpjmd::query()
            ->where('visi_id', $visi->id)
            ->exists()
            || Misi::query()
                ->where('visi_id', $visi->id)
                ->exists();

        if ($isUsed) {
            return response()->json([
                'message' => 'Visi tidak bisa dihapus karena sudah dipakai pada Misi atau Visi, Misi & Tujuan RPJMD',
            ], 400);
        }

        $visi->delete();

        return response()->json();
    }

    public function storeMisi(Request $request)
    {
        $validated = $this->validateMisi($request);

        $misi = Misi::query()->create([
            ...$validated,
            'nomor' => $this->nextMisiNomor($validated['visi_id']),
            'tahun_mulai' => $this->tahunMulai(),
        ]);

        return response()->json($misi, 201);
    }

    public function updateMisi(Request $request, Misi $misi)
    {
        $this->ensureMisiPeriod($misi);

        $validated = $this->validateMisi($request);

        if ((int) $misi->visi_id !== (int) $validated['visi_id']) {
            $validated['nomor'] = $this->nextMisiNomor($validated['visi_id']);
        }

        $misi->update($validated);

        return response()->json($misi);
    }

    public function destroyMisi(Misi $misi)
    {
        $this->ensureMisiPeriod($misi);

        $isUsed = VisiMisiRpjmd::query()
            ->where('misi_id', $misi->id)
            ->exists()
            || SasaranStrategisRpjmd::query()
                ->where('misi_id', $misi->id)
                ->exists()
            || Tujuan::query()
                ->where('misi_id', $misi->id)
                ->exists();

        if ($isUsed) {
            return response()->json([
                'message' => 'Misi tidak bisa dihapus karena sudah dipakai pada data perencanaan',
            ], 400);
        }

        $misi->delete();

        return response()->json();
    }

    public function storeTujuan(Request $request)
    {
        $validated = $this->validateTujuan($request);

        $tujuan = Tujuan::query()->create([
            ...$validated,
            'nomor' => $this->nextTujuanNomor($validated['misi_id']),
            'tahun_mulai' => $this->tahunMulai(),
        ]);

        return response()->json($tujuan, 201);
    }

    public function updateTujuan(Request $request, Tujuan $tujuan)
    {
        $this->ensureTujuanPeriod($tujuan);

        $validated = $this->validateTujuan($request);

        if ((int) $tujuan->misi_id !== (int) $validated['misi_id']) {
            $validated['nomor'] = $this->nextTujuanNomor($validated['misi_id']);
        }

        $tujuan->update($validated);

        return response()->json($tujuan);
    }

    public function destroyTujuan(Tujuan $tujuan)
    {
        $this->ensureTujuanPeriod($tujuan);

        $isUsed = VisiMisiRpjmd::query()
            ->where('tujuan_id', $tujuan->id)
            ->exists()
            || SasaranStrategisRpjmd::query()
                ->where('tujuan_id', $tujuan->id)
                ->exists()
            || IndikatorTujuan::query()
                ->where('tujuan_id', $tujuan->id)
                ->exists();

        if ($isUsed) {
            return response()->json([
                'message' => 'Tujuan tidak bisa dihapus karena sudah dipakai pada data perencanaan',
            ], 400);
        }

        $tujuan->delete();

        return response()->json();
    }

    public function storeIndikatorTujuan(Request $request)
    {
        $validated = $this->validateIndikatorTujuan($request);

        $indikatorTujuan = IndikatorTujuan::query()->create([
            ...$validated,
            'nomor' => $this->nextIndikatorTujuanNomor($validated['tujuan_id']),
            'tahun_mulai' => $this->tahunMulai(),
        ]);

        return response()->json($indikatorTujuan, 201);
    }

    public function updateIndikatorTujuan(Request $request, IndikatorTujuan $indikatorTujuan)
    {
        $this->ensureIndikatorTujuanPeriod($indikatorTujuan);

        $validated = $this->validateIndikatorTujuan($request);

        if ((int) $indikatorTujuan->tujuan_id !== (int) $validated['tujuan_id']) {
            $validated['nomor'] = $this->nextIndikatorTujuanNomor($validated['tujuan_id']);
        }

        $indikatorTujuan->update($validated);

        return response()->json($indikatorTujuan);
    }

    public function destroyIndikatorTujuan(IndikatorTujuan $indikatorTujuan)
    {
        $this->ensureIndikatorTujuanPeriod($indikatorTujuan);

        $isUsed = VisiMisiRpjmd::query()
            ->where('indikator_tujuan_id', $indikatorTujuan->id)
            ->exists()
            || SasaranStrategisRpjmd::query()
                ->where('indikator_tujuan_id', $indikatorTujuan->id)
                ->exists();

        if ($isUsed) {
            return response()->json([
                'message' => 'Indikator tujuan tidak bisa dihapus karena sudah dipakai pada data perencanaan',
            ], 400);
        }

        $indikatorTujuan->delete();

        return response()->json();
    }

    private function validateMisi(Request $request): array
    {
        $tahunMulai = $this->tahunMulai();

        return $request->validate([
            'visi_id' => [
                'required',
                'integer',
                Rule::exists('visi', 'id')->where(fn ($query) => $query->where('tahun_mulai', $tahunMulai)),
            ],
            'misi' => ['required', 'string'],
        ]);
    }

    private function validateTujuan(Request $request): array
    {
        $tahunMulai = $this->tahunMulai();

        return $request->validate([
            'misi_id' => [
                'required',
                'integer',
                Rule::exists('misi', 'id')->where(fn ($query) => $query->where('tahun_mulai', $tahunMulai)),
            ],
            'tujuan' => ['required', 'string'],
        ]);
    }

    private function validateIndikatorTujuan(Request $request): array
    {
        $tahunMulai = $this->tahunMulai();

        return $request->validate([
            'tujuan_id' => [
                'required',
                'integer',
                Rule::exists('tujuan', 'id')->where(fn ($query) => $query->where('tahun_mulai', $tahunMulai)),
            ],
            'indikator' => ['required', 'string'],
        ]);
    }

    private function ensureVisiPeriod(Visi $visi): void
    {
        if ((int) $visi->tahun_mulai !== $this->tahunMulai()) {
            abort(404);
        }
    }

    private function ensureMisiPeriod(Misi $misi): void
    {
        if ((int) $misi->tahun_mulai !== $this->tahunMulai()) {
            abort(404);
        }
    }

    private function ensureTujuanPeriod(Tujuan $tujuan): void
    {
        if ((int) $tujuan->tahun_mulai !== $this->tahunMulai()) {
            abort(404);
        }
    }

    private function ensureIndikatorTujuanPeriod(IndikatorTujuan $indikatorTujuan): void
    {
        if ((int) $indikatorTujuan->tahun_mulai !== $this->tahunMulai()) {
            abort(404);
        }
    }

    private function validateVisiPeriod(Request $request): array
    {
        return $request->validate([
            'tahun_mulai' => ['required', 'integer', Rule::in([$this->tahunMulai()])],
            'tahun_selesai' => ['required', 'integer', Rule::in([$this->tahunSelesai()])],
        ]);
    }

    private function periode(): array
    {
        return [
            'tahun_mulai' => $this->tahunMulai(),
            'tahun_selesai' => $this->tahunSelesai(),
        ];
    }

    private function tahunMulai(): int
    {
        return getTahunMulai();
    }

    private function tahunSelesai(): int
    {
        return $this->tahunMulai() + 5;
    }

    private function formatVisi(int $tahunMulai, int $tahunSelesai): string
    {
        return "Visi {$tahunMulai}-{$tahunSelesai}";
    }

    private function nextMisiNomor(int $visiId): int
    {
        return ((int) Misi::query()
            ->where('tahun_mulai', $this->tahunMulai())
            ->where('visi_id', $visiId)
            ->max('nomor')) + 1;
    }

    private function nextTujuanNomor(int $misiId): int
    {
        return ((int) Tujuan::query()
            ->where('tahun_mulai', $this->tahunMulai())
            ->where('misi_id', $misiId)
            ->max('nomor')) + 1;
    }

    private function nextIndikatorTujuanNomor(int $tujuanId): int
    {
        return ((int) IndikatorTujuan::query()
            ->where('tahun_mulai', $this->tahunMulai())
            ->where('tujuan_id', $tujuanId)
            ->max('nomor')) + 1;
    }
}
