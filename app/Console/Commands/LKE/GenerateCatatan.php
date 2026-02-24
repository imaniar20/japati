<?php

namespace App\Console\Commands\LKE;

use App\Http\Controllers\LKE\PenilaianController;
use App\Models\LKE\CatatanRekomendasi;
use App\Models\LKE\Komponen;
use App\Models\LKE\Penilaian;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\Eloquent\Builder;

class GenerateCatatan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lke:generate-catatan {tahun}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate catatan pada hasil penilaian LKE';

    private int $tahun;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->tahun = $this->argument('tahun');

        $satuanKerjaList = Penilaian::tahunKinerja($this->tahun)
            ->where('status', Penilaian::STATUS_DONE_2)
            ->pluck('satuan_kerja_id');

        foreach ($satuanKerjaList as $satkerId) {
            $this->generate($satkerId);
        }
    }

    private function generate(int $satkerId)
    {
        $komponenList = Komponen::tahunKinerja($this->tahun)
            ->with([
                'subKomponen' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria.eviden' => fn (Builder $query) => $query->roleSatuanKerja($satkerId)->tahunKinerja($this->tahun)->orderBy('id'),
                'subKomponen.kriteria.eviden.parameter' => fn (Builder $query) => $query->orderBy('nomor'),
                'subKomponen.kriteria.eviden.riwayat' => fn (Builder $query) => $query
                    ->whereHas('penilaian', fn (Builder $query) => $query
                        ->whereIn('status', [Penilaian::STATUS_DONE_2]))
                    ->orderBy('id'),
                'subKomponen.kriteria.eviden.riwayat.parameterNilai' => fn (Builder $query) => $query->orderBy('id'),
                'subKomponen.kriteria.parameter' => fn (Builder $query) => $query->orderBy('nomor'),
            ])
            ->orderBy('nomor')
            ->get();

        $catatan = [];

        foreach ($komponenList as $komponen) {
            foreach ($komponen->subKomponen as $subKomponen) {
                foreach ($subKomponen->kriteria as $kriteria) {
                    $eviden = $kriteria->eviden;
                    $lastRiwayat = $kriteria->eviden->riwayat->last();

                    if ($eviden->parameter_id == $lastRiwayat->nilai && $lastRiwayat->status) {
                        continue;
                    }

                    $catatanString = $lastRiwayat->parameterNilai->nama;

                    if ($lastRiwayat->parameterNilai->keterangan) {
                        $catatanString .= "\n{$lastRiwayat->parameterNilai->keterangan}";
                    }

                    $catatan[] = [
                        'kriteria' => "{$kriteria->nomor_full} {$kriteria->nama}",
                        'catatan' => $catatanString,
                    ];
                }
            }
        }

        $userId = $this->findUserId($satkerId);

        $catatanRekomendasi = CatatanRekomendasi::query()
            ->where('satuan_kerja_id', $satkerId)
            ->where('tahun_kinerja', $this->tahun)
            ->where('user_id', $userId)
            ->first();

        if ($catatanRekomendasi) {
            $catatanRekomendasi->update([
                'catatan' => $catatan,
            ]);
        } else {
            CatatanRekomendasi::query()->create([
                'satuan_kerja_id' => $satkerId,
                'tahun_kinerja' => $this->tahun,
                'user_id' => $userId,
                'catatan' => $catatan,
                'rekomendasi' => [],
            ]);
        }
    }

    private function findUserId(int $satkerId)
    {
        $username = array_keys(array_filter(PenilaianController::mappingValidator(), fn (array $values) => in_array($satkerId, $values)));

        return User::query()
            ->where('username', $username)
            ->value('id');
    }
}
