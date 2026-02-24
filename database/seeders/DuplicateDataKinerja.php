<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\Program;
use App\Models\SasaranStrategisPd;
use App\Models\SubKegiatan;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DuplicateDataKinerja extends Seeder
{
    private array $programIdMapping = [];

    private array $kegiatanIdMapping = [];

    private array $subKegiatanIdMapping = [];

    private array $kinerjaProgramMapping = [];

    private array $kinerjaKegiatanMapping = [];

    private array $kinerjaSubKegiatanMapping = [];

    private int $currentTahunKinerja = 2024;

    private int $targetTahunKinerja = 2025;

    private int $currentTahunMulai = 2024;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $this->kinerjaProgram();
            $this->kinerjaKegiatan();
            $this->kinerjaSubKegiatan();
        });
    }

    private function kinerjaProgram()
    {
        $this->command->info('Duplicate Program');

        Program::tahunKinerja($this->currentTahunKinerja)
            ->chunkById(100, function (Collection $data) {
                /** @var Program */
                foreach ($data as $old) {
                    $new = $old->replicate()
                        ->fill([
                            'tahun_kinerja' => $this->targetTahunKinerja,
                        ])
                        ->toArray();

                    $new = Program::query()->create($new);

                    $this->programIdMapping[$old->id] = $new->id;
                }
            });

        $this->command->info('Duplicate Kinerja Program');

        KinerjaProgram::tahunKinerja($this->currentTahunKinerja)
            ->whereIn('sasaran_strategis_pd_id', SasaranStrategisPd::tahunMulai($this->currentTahunMulai)->select('id'))
            ->chunkById(100, function (Collection $data) {
                /** @var KinerjaProgram */
                foreach ($data as $old) {
                    $programId = $this->programIdMapping[$old->program_id] ?? null;

                    if (! $programId) {
                        throw new Exception("Program ID {$old->program_id} not found for KinerjaProgram ID: {$old->id}");

                        continue;
                    }

                    $new = KinerjaProgram::create([
                        'program_id' => $programId,
                        'tahun_kinerja' => $this->targetTahunKinerja,

                        'satuan_kerja_id' => $old->satuan_kerja_id,
                        'sasaran_strategis_pd_id' => $old->sasaran_strategis_pd_id,
                        'satker_iku_id' => $old->satker_iku_id,
                        'sasaran' => $old->sasaran,
                        'indikator' => $old->indikator,
                        'satuan' => $old->satuan,
                        'target' => $old->target,
                        'target_bulanan' => $old->target_bulanan,
                        'realisasi_bulanan' => $old->realisasi_bulanan,
                        'anggaran' => $old->anggaran,
                        'anggaran_bulanan' => $old->anggaran_bulanan,
                        'realisasi_anggaran_bulanan' => $old->realisasi_anggaran_bulanan,
                        'realisasi' => $old->realisasi,
                        'capaian' => $old->capaian,
                        'realisasi_anggaran' => $old->realisasi_anggaran,
                        'capaian_anggaran' => $old->capaian_anggaran,
                        'v_struktur_organisasi_id' => $old->v_struktur_organisasi_id,
                        'pengampu' => $old->pengampu,
                        'tim_kerja_id' => $old->tim_kerja_id,
                        'is_kemiskinan' => $old->is_kemiskinan,
                        'order' => $old->order,
                        'is_sekretariat' => $old->is_sekretariat,
                        'is_stunting' => $old->is_stunting,
                        'is_inflasi' => $old->is_inflasi,
                        'is_investasi' => $old->is_investasi,
                        'is_penggunaan_pdn' => $old->is_penggunaan_pdn,
                    ]);

                    $this->kinerjaProgramMapping[$old->id] = $new;
                }
            });
    }

    private function kinerjaKegiatan()
    {
        $this->command->info('Duplicate Kegiatan');

        Kegiatan::tahunKinerja($this->currentTahunKinerja)
            ->chunkById(100, function (Collection $data) {
                /** @var Kegiatan */
                foreach ($data as $old) {
                    $new = $old->replicate()
                        ->fill([
                            'tahun_kinerja' => $this->targetTahunKinerja,
                        ])
                        ->toArray();

                    $new = Kegiatan::query()->create($new);

                    $this->kegiatanIdMapping[$old->id] = $new->id;
                }
            });

        $this->command->info('Duplicate Kinerja Kegiatan');

        KinerjaKegiatan::tahunKinerja($this->currentTahunKinerja)
            ->whereIn('sasaran_strategis_pd_id', SasaranStrategisPd::tahunMulai($this->currentTahunMulai)->select('id'))
            ->whereIn('kinerja_program_id', KinerjaProgram::tahunKinerja($this->currentTahunKinerja)->select('id'))
            ->whereNotNull('kegiatan_id')
            ->whereHas('kinerjaProgram', fn (Builder|KinerjaProgram $query) => $query
                ->tahunKinerja($this->currentTahunKinerja)
                ->whereIn('sasaran_strategis_pd_id', SasaranStrategisPd::tahunMulai($this->currentTahunMulai)->select('id'))
            )
            ->chunkById(100, function (Collection $data) {
                /** @var KinerjaKegiatan */
                foreach ($data as $old) {
                    $kegiatanId = $this->kegiatanIdMapping[$old->kegiatan_id] ?? null;

                    if (! $kegiatanId) {
                        throw new Exception("Kegiatan ID {$old->kegiatan_id} not found for KinerjaKegiatan ID: {$old->id}");

                        continue;
                    }

                    $kinerjaProgram = $this->kinerjaProgramMapping[$old->kinerja_program_id] ?? null;

                    if (! $kinerjaProgram) {
                        throw new Exception("KinerjaProgram ID {$old->kinerja_program_id} not found for KinerjaKegiatan ID: {$old->id}");

                        continue;
                    }

                    $new = KinerjaKegiatan::create([
                        'tahun_kinerja' => $this->targetTahunKinerja,
                        'kegiatan_id' => $kegiatanId,
                        'kinerja_program_id' => $kinerjaProgram->id,
                        'program_id' => $kinerjaProgram->program_id,
                        'sasaran_program' => $kinerjaProgram->sasaran,

                        'satuan_kerja_id' => $old->satuan_kerja_id,
                        'sasaran' => $old->sasaran,
                        'indikator' => $old->indikator,
                        'satuan' => $old->satuan,
                        'target' => $old->target,
                        'target_bulanan' => $old->target_bulanan,
                        'realisasi_bulanan' => $old->realisasi_bulanan,
                        'anggaran' => $old->anggaran,
                        'anggaran_bulanan' => $old->anggaran_bulanan,
                        'realisasi_anggaran_bulanan' => $old->realisasi_anggaran_bulanan,
                        'realisasi' => $old->realisasi,
                        'capaian' => $old->capaian,
                        'realisasi_anggaran' => $old->realisasi_anggaran,
                        'capaian_anggaran' => $old->capaian_anggaran,
                        'sasaran_strategis_rpjmd_id' => $old->sasaran_strategis_rpjmd_id,
                        'sasaran_strategis_pd_id' => $old->sasaran_strategis_pd_id,
                        'v_struktur_organisasi_id' => $old->v_struktur_organisasi_id,
                        'pengampu' => $old->pengampu,
                        'tim_kerja_id' => $old->tim_kerja_id,
                        'is_kemiskinan' => $old->is_kemiskinan,
                        'is_sekretariat' => $old->is_sekretariat,
                        'is_stunting' => $old->is_stunting,
                        'is_inflasi' => $old->is_inflasi,
                        'is_investasi' => $old->is_investasi,
                        'is_penggunaan_pdn' => $old->is_penggunaan_pdn,
                    ]);

                    $this->kinerjaKegiatanMapping[$old->id] = $new;
                }
            });
    }

    private function kinerjaSubKegiatan()
    {
        $this->command->info('Duplicate SubKegiatan');

        SubKegiatan::tahunKinerja($this->currentTahunKinerja)
            ->chunkById(100, function (Collection $data) {
                /** @var SubKegiatan */
                foreach ($data as $old) {
                    $new = $old->replicate()
                        ->fill([
                            'tahun_kinerja' => $this->targetTahunKinerja,
                        ])
                        ->toArray();

                    $new = SubKegiatan::query()->create($new);

                    $this->subKegiatanIdMapping[$old->id] = $new->id;
                }
            });

        $this->command->info('Duplicate Kinerja SubKegiatan');

        KinerjaSubKegiatan::tahunKinerja($this->currentTahunKinerja)
            ->whereIn('sasaran_strategis_pd_id', SasaranStrategisPd::tahunMulai($this->currentTahunMulai)->select('id'))
            ->whereHas('kinerjaKegiatan', fn (Builder|KinerjaKegiatan $query) => $query
                ->tahunKinerja($this->currentTahunKinerja)
                ->whereIn('sasaran_strategis_pd_id', SasaranStrategisPd::tahunMulai($this->currentTahunMulai)->select('id'))
                ->whereIn('kinerja_program_id', KinerjaProgram::tahunKinerja($this->currentTahunKinerja)->select('id'))
                ->whereNotNull('kegiatan_id')
                ->whereHas('kinerjaProgram', fn (Builder|KinerjaProgram $query) => $query
                    ->tahunKinerja($this->currentTahunKinerja)
                    ->whereIn('sasaran_strategis_pd_id', SasaranStrategisPd::tahunMulai($this->currentTahunMulai)->select('id'))
                )
            )
            ->whereNotNull('sub_kegiatan_id')
            ->chunkById(100, function ($kinerjaSubKegiatan) {
                /** @var KinerjaSubKegiatan */
                foreach ($kinerjaSubKegiatan as $old) {
                    $subKegiatanId = $this->subKegiatanIdMapping[$old->sub_kegiatan_id] ?? null;

                    if (! $subKegiatanId) {
                        throw new Exception("SubKegiatan ID {$old->sub_kegiatan_id} not found for KinerjaKegiatan ID: {$old->id}");

                        continue;
                    }

                    $kinerjaKegiatan = $this->kinerjaKegiatanMapping[$old->kinerja_kegiatan_id] ?? null;

                    if (! $kinerjaKegiatan) {
                        throw new Exception("KinerjaKegiatan ID {$old->kinerja_kegiatan_id} not found for KinerjaSubKegiatan ID: {$old->id}");

                        continue;
                    }

                    $new = KinerjaSubKegiatan::create([
                        'tahun_kinerja' => $this->targetTahunKinerja,
                        'sub_kegiatan_id' => $subKegiatanId,
                        'kinerja_kegiatan_id' => $kinerjaKegiatan->id,
                        'kegiatan_id' => $kinerjaKegiatan->kegiatan_id,
                        'kinerja_program_id' => $kinerjaKegiatan->kinerja_program_id,

                        'satuan_kerja_id' => $old->satuan_kerja_id,
                        'sasaran' => $old->sasaran,
                        'indikator' => $old->indikator,
                        'satuan' => $old->satuan,
                        'target' => $old->target,
                        'target_bulanan' => $old->target_bulanan,
                        'realisasi' => $old->realisasi,
                        'anggaran' => $old->anggaran,
                        'anggaran_bulanan' => $old->anggaran_bulanan,
                        'has_inovasi' => $old->has_inovasi,
                        'inovasi_uraian' => $old->inovasi_uraian,
                        'inovasi_tujuan' => $old->inovasi_tujuan,
                        'inovasi_lampiran' => $old->inovasi_lampiran,
                        'realisasi_bulanan' => $old->realisasi_bulanan,
                        'realisasi_anggaran_bulanan' => $old->realisasi_anggaran_bulanan,
                        'capaian' => $old->capaian,
                        'realisasi_anggaran' => $old->realisasi_anggaran,
                        'capaian_anggaran' => $old->capaian_anggaran,
                        'sasaran_strategis_rpjmd_id' => $old->sasaran_strategis_rpjmd_id,
                        'sasaran_strategis_pd_id' => $old->sasaran_strategis_pd_id,
                        'v_struktur_organisasi_id' => $old->v_struktur_organisasi_id,
                        'pengampu' => $old->pengampu,
                        'tim_kerja_id' => $old->tim_kerja_id,
                        'indikator_kemendagri' => $old->indikator_kemendagri,
                        'is_kemiskinan' => $old->is_kemiskinan,
                        'is_sekretariat' => $old->is_sekretariat,
                        'is_stunting' => $old->is_stunting,
                        'is_inflasi' => $old->is_inflasi,
                        'is_investasi' => $old->is_investasi,
                        'is_penggunaan_pdn' => $old->is_penggunaan_pdn,
                        'rencana_aksi' => $old->rencana_aksi,
                        'is_external' => $old->is_external,
                        'is_rencana_aksi_gubernur' => $old->is_rencana_aksi_gubernur,
                    ]);

                    $this->kinerjaSubKegiatanMapping[$old->id] = $new;
                }
            });
    }
}
