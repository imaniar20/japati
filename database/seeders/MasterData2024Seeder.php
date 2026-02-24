<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\Program;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use App\Models\SatuanKerja;
use App\Models\SubKegiatan;
use App\Models\VisiMisiRpjmd;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MasterData2024Seeder extends Seeder
{
    private array $sasaranStrategisPdMap = [];

    private array $kinerjaProgramMap = [];

    private array $kinerjaKegiatanMap = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->visiMisiRpjmd();
            $this->sasaranStrategisRpjmd();
            $this->sasaranStrategisPd();
            $this->kinerjaProgram();
            $this->kinerjaKegiatan();
            $this->kinerjaSubKegiatan();
        });
    }

    private function visiMisiRpjmd()
    {
        $query = VisiMisiRpjmd::tahunMulai(2024)
            ->select('satuan_kerja_id');

        $satkerIds = SatuanKerja::query()
            ->where('satuan_kerja_nama', 'NOT ILIKE', 'BIRO%')
            ->whereNotIn('satuan_kerja_id', $query)
            ->pluck('satuan_kerja_id');

        foreach ($satkerIds as $satkerId) {
            VisiMisiRpjmd::query()->create([
                'satuan_kerja_id' => $satkerId,
                'visi_id' => 8,
                'misi_id' => 12,
                'tujuan_id' => 14,
                'indikator_tujuan_id' => 14,
                'satuan' => '-',
                'tahun_mulai' => 2024,
                'target_baseline' => '-',
                'target_1' => '-',
                'target_2' => '-',
                'target_3' => '-',
                'target_4' => '-',
                'target_5' => '-',
            ]);
        }
    }

    private function sasaranStrategisRpjmd()
    {
        $query = SasaranStrategisRpjmd::tahunMulai(2024)
            ->select('satuan_kerja_id');

        $satkerIds = SatuanKerja::query()
            ->where('satuan_kerja_nama', 'NOT ILIKE', 'BIRO%')
            ->whereNotIn('satuan_kerja_id', $query)
            ->pluck('satuan_kerja_id');

        foreach ($satkerIds as $satkerId) {
            $target_visi_misi_rpjmd_id = VisiMisiRpjmd::tahunMulai(2024)
                ->where('satuan_kerja_id', $satkerId)
                ->where('indikator_tujuan_id', 14)
                ->value('id');

            if (! $target_visi_misi_rpjmd_id) {
                $target_visi_misi_rpjmd_id = VisiMisiRpjmd::tahunMulai(2024)
                    ->where('satuan_kerja_id', $satkerId)
                    ->value('id');
            }

            SasaranStrategisRpjmd::query()->create([
                'satuan_kerja_id' => $satkerId,
                'sasaran_strategis_id' => 28,
                'indikator_sasaran_strategis_id' => 37,
                'satuan' => '-',
                'tahun_mulai' => 2024,
                'target_baseline' => 0,
                'target_1' => 0,
                'target_2' => 0,
                'target_3' => 0,
                'target_4' => 0,
                'target_5' => 0,
                'misi_id' => 12,
                'tujuan_id' => 14,
                'indikator_tujuan_id' => 14,
                'target_visi_misi_rpjmd_id' => $target_visi_misi_rpjmd_id,
            ]);
        }
    }

    private function sasaranStrategisPd()
    {
        SasaranStrategisPd::tahunMulai(2019)->chunkById(100, function (Collection $data) {
            /** @var SasaranStrategisPd */
            foreach ($data as $item) {
                $rpjmd = SasaranStrategisRpjmd::tahunMulai(2024)->where('satuan_kerja_id', $item->satuan_kerja_id)->first();

                $this->sasaranStrategisPdMap[$item->id] = SasaranStrategisPd::query()->create([
                    'satuan_kerja_id' => $item->satuan_kerja_id,
                    'sasaran_strategis_id' => $rpjmd->sasaran_strategis_id,
                    'indikator_sasaran_strategis_id' => $rpjmd->indikator_sasaran_strategis_id,
                    'sasaran_strategis_satker' => $item->sasaran_strategis_satker,
                    'iku' => $item->iku,
                    'satuan' => $item->satuan,
                    'tahun_mulai' => 2024,
                    'target_baseline' => $item->target_baseline,
                    'target_1' => $item->target_1,
                    'target_2' => $item->target_2,
                    'target_3' => $item->target_3,
                    'target_4' => $item->target_4,
                    'target_5' => $item->target_5,
                    'realisasi_baseline' => $item->realisasi_baseline,
                    'realisasi_1' => $item->realisasi_1,
                    'realisasi_2' => $item->realisasi_2,
                    'realisasi_3' => $item->realisasi_3,
                    'realisasi_4' => $item->realisasi_4,
                    'realisasi_5' => $item->realisasi_5,
                    'rata_nasional' => $item->rata_nasional,
                    'peringkat_nasional' => $item->peringkat_nasional,
                    'redaksi' => $item->redaksi,
                    'lampiran' => $item->lampiran,
                    'capaian_baseline' => $item->capaian_baseline,
                    'capaian_1' => $item->capaian_1,
                    'capaian_2' => $item->capaian_2,
                    'capaian_3' => $item->capaian_3,
                    'capaian_4' => $item->capaian_4,
                    'capaian_5' => $item->capaian_5,
                    'sasaran_strategis_rpjmd_id' => $rpjmd->id,
                    'narasi_keberhasilan' => $item->narasi_keberhasilan,
                    'penyebab_kegagalan_baseline' => $item->penyebab_kegagalan_baseline,
                    'penyebab_kegagalan_1' => $item->penyebab_kegagalan_1,
                    'penyebab_kegagalan_2' => $item->penyebab_kegagalan_2,
                    'penyebab_kegagalan_3' => $item->penyebab_kegagalan_3,
                    'penyebab_kegagalan_4' => $item->penyebab_kegagalan_4,
                    'penyebab_kegagalan_5' => $item->penyebab_kegagalan_5,
                ]);
            }
        });
    }

    private function kinerjaProgram()
    {
        KinerjaProgram::tahunKinerja(2023)
            ->whereIn('sasaran_strategis_pd_id', SasaranStrategisPd::tahunMulai(2019)->select('id'))
            ->with('program')
            ->chunkById(100, function (Collection $data) {
                /** @var KinerjaProgram */
                foreach ($data as $item) {
                    $sasaranPd = $this->sasaranStrategisPdMap[$item->sasaran_strategis_pd_id];
                    $programId = Program::tahunKinerja(2024)
                        ->where('satuan_kerja_id', $item->program->satuan_kerja_id)
                        ->where('kode', $item->program->kode)
                        ->value('id');

                    $this->kinerjaProgramMap[$item->id] = KinerjaProgram::query()->create([
                        'satuan_kerja_id' => $item->satuan_kerja_id,
                        'sasaran_strategis_pd_id' => $sasaranPd->id,
                        'satker_iku_id' => $sasaranPd->id,
                        'program_id' => $programId,
                        'sasaran' => $item->sasaran,
                        'indikator' => $item->indikator,
                        'satuan' => $item->satuan,
                        'target' => $item->target,
                        'target_bulanan' => $item->target_bulanan,
                        'realisasi_bulanan' => $item->realisasi_bulanan,
                        'anggaran' => $item->anggaran,
                        'anggaran_bulanan' => $item->anggaran_bulanan,
                        'realisasi_anggaran_bulanan' => $item->realisasi_anggaran_bulanan,
                        'realisasi' => $item->realisasi,
                        'capaian' => $item->capaian,
                        'realisasi_anggaran' => $item->realisasi_anggaran,
                        'capaian_anggaran' => $item->capaian_anggaran,
                        'tahun_kinerja' => 2024,
                        'v_struktur_organisasi_id' => $item->v_struktur_organisasi_id,
                        'penyebab_kegagalan' => $item->penyebab_kegagalan,
                        'pengampu' => $item->pengampu,
                        'tim_kerja_id' => $item->tim_kerja_id,
                    ]);
                }
            });
    }

    private function kinerjaKegiatan()
    {
        KinerjaKegiatan::tahunKinerja(2023)
            ->whereIn('sasaran_strategis_pd_id', SasaranStrategisPd::tahunMulai(2019)->select('id'))
            ->whereIn('kinerja_program_id', KinerjaProgram::tahunKinerja(2023)->select('id'))
            ->whereNotNull('kegiatan_id')
            ->whereHas('kinerjaProgram', fn (Builder $query) => $query->whereIn('sasaran_strategis_pd_id', SasaranStrategisPd::tahunMulai(2019)->select('id')))
            ->with(['program', 'kegiatan'])
            ->chunkById(100, function (Collection $data) {
                /** @var KinerjaKegiatan */
                foreach ($data as $item) {
                    $sasaranPd = $this->sasaranStrategisPdMap[$item->sasaran_strategis_pd_id];
                    $programId = Program::tahunKinerja(2024)
                        ->where('satuan_kerja_id', $item->program->satuan_kerja_id)
                        ->where('kode', $item->program->kode)
                        ->value('id');

                    $kegiatanId = Kegiatan::tahunKinerja(2024)
                        ->where('satuan_kerja_id', $item->kegiatan->satuan_kerja_id)
                        ->where('kode', $item->kegiatan->kode)
                        ->value('id');

                    $rpjmd = SasaranStrategisRpjmd::tahunMulai(2024)->where('satuan_kerja_id', parseSatuanKerjaId($item->satuan_kerja_id))->first();
                    $this->kinerjaKegiatanMap[$item->id] = KinerjaKegiatan::query()->create([
                        'satuan_kerja_id' => $item->satuan_kerja_id,
                        'program_id' => $programId,
                        'sasaran_program' => $item->sasaran_program,
                        'kegiatan_id' => $kegiatanId,
                        'sasaran' => $item->sasaran,
                        'indikator' => $item->indikator,
                        'satuan' => $item->satuan,
                        'target' => $item->target,
                        'target_bulanan' => $item->target_bulanan,
                        'realisasi_bulanan' => $item->realisasi_bulanan,
                        'anggaran' => $item->anggaran,
                        'anggaran_bulanan' => $item->anggaran_bulanan,
                        'realisasi_anggaran_bulanan' => $item->realisasi_anggaran_bulanan,
                        'realisasi' => $item->realisasi,
                        'capaian' => $item->capaian,
                        'realisasi_anggaran' => $item->realisasi_anggaran,
                        'capaian_anggaran' => $item->capaian_anggaran,
                        'sasaran_strategis_rpjmd_id' => $rpjmd->id,
                        'kinerja_program_id' => $this->kinerjaProgramMap[$item->kinerja_program_id]->id,
                        'sasaran_strategis_pd_id' => $sasaranPd->id,
                        'tahun_kinerja' => 2024,
                        'v_struktur_organisasi_id' => $item->v_struktur_organisasi_id,
                        'pengampu' => $item->pengampu,
                        'tim_kerja_id' => $item->tim_kerja_id,
                        'penyebab_kegagalan' => $item->penyebab_kegagalan,
                    ]);
                }
            });
    }

    private function kinerjaSubKegiatan()
    {
        KinerjaSubKegiatan::tahunKinerja(2023)
            ->whereIn('sasaran_strategis_pd_id', SasaranStrategisPd::tahunMulai(2019)->select('id'))
            ->whereHas('kinerjaProgram', fn (Builder $query) => $query->whereIn('sasaran_strategis_pd_id', SasaranStrategisPd::tahunMulai(2019)->select('id')))
            ->whereHas('kinerjaKegiatan', fn (Builder $query) => $query
                ->whereIn('sasaran_strategis_pd_id', SasaranStrategisPd::tahunMulai(2019)->select('id'))
                ->whereIn('kinerja_program_id', KinerjaProgram::tahunKinerja(2023)->select('id'))
                ->whereNotNull('kegiatan_id')
            )
            ->whereNotNull('sub_kegiatan_id')
            ->with(['kegiatan', 'subKegiatan'])
            ->chunkById(100, function (Collection $data) {
                /** @var KinerjaSubKegiatan */
                foreach ($data as $item) {
                    $rpjmd = SasaranStrategisRpjmd::tahunMulai(2024)->where('satuan_kerja_id', parseSatuanKerjaId($item->satuan_kerja_id))->first();

                    $kegiatanId = Kegiatan::tahunKinerja(2024)
                        ->where('satuan_kerja_id', $item->kegiatan->satuan_kerja_id)
                        ->where('kode', $item->kegiatan->kode)
                        ->value('id');
                    $subKegiatanId = SubKegiatan::tahunKinerja(2024)
                        ->where('satuan_kerja_id', $item->subKegiatan->satuan_kerja_id)
                        ->where('kode', $item->subKegiatan->kode)
                        ->value('id');

                    KinerjaSubKegiatan::query()->create([
                        'satuan_kerja_id' => $item->satuan_kerja_id,
                        'kegiatan_id' => $kegiatanId,
                        'sub_kegiatan_id' => $subKegiatanId,
                        'sasaran' => $item->sasaran,
                        'indikator' => $item->indikator,
                        'satuan' => $item->satuan,
                        'target' => $item->target,
                        'target_bulanan' => $item->target_bulanan,
                        'realisasi' => $item->realisasi,
                        'anggaran' => $item->anggaran,
                        'anggaran_bulanan' => $item->anggaran_bulanan,
                        'has_inovasi' => $item->has_inovasi,
                        'inovasi_uraian' => $item->inovasi_uraian,
                        'inovasi_tujuan' => $item->inovasi_tujuan,
                        'inovasi_lampiran' => $item->inovasi_lampiran,
                        'realisasi_bulanan' => $item->realisasi_bulanan,
                        'realisasi_anggaran_bulanan' => $item->realisasi_anggaran_bulanan,
                        'capaian' => $item->capaian,
                        'realisasi_anggaran' => $item->realisasi_anggaran,
                        'capaian_anggaran' => $item->capaian_anggaran,
                        'sasaran_strategis_rpjmd_id' => $rpjmd->id,
                        'kinerja_program_id' => $this->kinerjaProgramMap[$item->kinerja_program_id]->id,
                        'kinerja_kegiatan_id' => $this->kinerjaKegiatanMap[$item->kinerja_kegiatan_id]->id,
                        'sasaran_strategis_pd_id' => $this->sasaranStrategisPdMap[$item->sasaran_strategis_pd_id]->id,
                        'tahun_kinerja' => 2024,
                        'v_struktur_organisasi_id' => $item->v_struktur_organisasi_id,
                        'pengampu' => $item->pengampu,
                        'tim_kerja_id' => $item->tim_kerja_id,
                        'penyebab_kegagalan' => $item->penyebab_kegagalan,
                        'indikator_kemendagri' => $item->indikator_kemendagri,
                    ]);
                }
            });
    }
}
