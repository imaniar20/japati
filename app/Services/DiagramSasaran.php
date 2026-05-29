<?php

namespace App\Services;

use App\Models\KinerjaProgram;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class DiagramSasaran
{
    /**
     * Untuk sasaran strategis rpjmd, dan sasaran strategis pd, satuan_kerja_id adalah hasil parse parameter $satuanKerjaId (kasus di setda),
     * Untuk kinerja program, kinerja kegiatan, kenerja sub kegiatan, khusus biro, satuan_kerja_id adalah sesuai parameter $satuanKerjaId,
     *
     * @param  string  $type  default|arsitektur-kinerja
     */
    public static function get(?int $satuanKerjaId, int $maxLevel = 5, string $type = 'default'): SupportCollection
    {
        if (! is_null($satuanKerjaId)) {
            $isBiro = isBiro($satuanKerjaId);
            $satuanKerjaIdParsed = parseSatuanKerjaId($satuanKerjaId);
        }

        // type arsitektur kinerja hanya tampilkan sasaran saja supaya tidak berat
        $isDefaultType = $type == 'default';

        // type arsitektur kinerja hanya menampilkan pengampu selain (KEPALA SUBBAGIAN TATA USAHA dan SEKRETARIS)
        // atau belum di set pengampunya
        // atau pengampunya tim
        /**
         * pengampu unit kerja
         * - v_struktur_organisasi_id = null
         * - atau tidak punya strukturOrganisasi (relasi datanya tidak ada)
         * - pengampu selain KEPALA SUBBAGIAN TATA USAHA dan SEKRETARIS
         *
         * pengampu tim kerja
         * - semua masuk
         */
        $excludePengampuQuery = fn (Builder $query) => $query
            ->where(fn (Builder $query) => $query
                ->where(fn (Builder $query) => $query
                    ->where('pengampu', 'unit-kerja')
                    ->where(fn (Builder $query) => $query
                        ->whereDoesntHave('strukturOrganisasi')
                        ->orWhereHas('strukturOrganisasi', fn (Builder $query) => $query
                            ->where('jabatan_nama', '<>', 'KEPALA SUBBAGIAN TATA USAHA')
                            ->where('jabatan_nama', '<>', 'SEKRETARIS')
                        )
                        ->orWhereNull('v_struktur_organisasi_id')
                    )
                )
                ->orWhere('pengampu', 'tim-kerja')
            );

        $excludePengampuUkerQuery = fn (Builder $query) => $query
            ->where(fn (Builder $query) => $query
                ->whereHas('strukturOrganisasi', fn (Builder $query) => $query
                    ->where('jabatan_nama', '<>', 'KEPALA SUBBAGIAN TATA USAHA')
                    ->where('jabatan_nama', '<>', 'SEKRETARIS')
                )
                ->orWhereNull('v_struktur_organisasi_id')
            );

        // kinerja tidak tercapai dari kinerja masalah (tahun sebelumnya)
        $prevKinerjaTidakTercapaiQuery = fn (Builder $query) => $query->where('tahun_kinerja', getTahunKinerja() - 1);

        $kinerjaTidakTercapaiQuery = fn (Builder $query) => $query->tahunKinerja();

        $kinerjaTercapaiQuery = fn (Builder $query) => $query->tahunKinerja();

        // original function from client/plugins/helpers.js::getKeyTahun()
        $getKeyTahun = function ($key, $offset = 0) {
            $index = (getTahunKinerja() - getTahunMulai()) + 1 + $offset;

            if ($index < 1) {
                $index = 'baseline';
            }

            return "{$key}_{$index}";
        };

        // convert penamaan kolom capaian per tahun
        $selectColumnByTahun = [
            $getKeyTahun('target').' AS target',
            $getKeyTahun('realisasi').' AS realisasi',
            $getKeyTahun('capaian').' AS capaian',
            $getKeyTahun('penyebab_kegagalan').' AS penyebab_kegagalan',
        ];

        $arrayFilter = function (array $array) {
            return array_filter($array, function ($value, $key) {
                if ($value && $key !== '') {
                    return true;
                }
            }, ARRAY_FILTER_USE_BOTH);
        };

        $isWithSkp = request()->get('show_skp');

        $relations = [
            // level 1
            [
                'sasaranStrategis:id,sasaran',
                'indikatorSasaranStrategis:id,indikator',
                'kinerjaTidakTercapai' => $kinerjaTidakTercapaiQuery,
            ],
            // level 2
            [
                'sasaranStrategisPd' => fn (Builder $query) => $query
                    ->select(['id', 'sasaran_strategis_rpjmd_id', 'sasaran_strategis_satker', 'iku', 'satuan_kerja_id', 'kinerja_bayangan_id', 'definisi_operasional', 'do_rumus', ...$selectColumnByTahun])
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                        $isDefaultType ? null : 'kinerjaBayangan',
                        'kinerjaTidakTercapai' => $kinerjaTidakTercapaiQuery,

                        ! $isWithSkp ? null : 'skp' => fn (Builder $query) => $query->select('id', 'model_class', 'model_id')->tahunKinerja()->where('is_skp', true),
                    ]))
                    ->withExists([
                        'sasaranStrategisPdCross' => fn (Builder $query) => $query
                            ->whereHas('sasaranStrategisRpjmd', fn (Builder $query) => $query->where('satuan_kerja_id', $satuanKerjaId)),
                    ]),

                'sasaranStrategisPdCross.sasaranStrategisPd' => fn (Builder $query) => $query
                    ->select(['id', 'sasaran_strategis_rpjmd_id', 'sasaran_strategis_satker', 'iku', 'satuan_kerja_id', 'kinerja_bayangan_id', 'definisi_operasional', ...$selectColumnByTahun])
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                    ])),
            ],
            // level 3
            [
                // TODO: saat ini setwan belum ada pelantikan, jadi pegawai masih di unit kerja lama
                // jadi pengampu masih bisa pilih unit kerja yang sudah inaktif
                // hapus $withInactive saat sudah pelantikan

                'sasaranStrategisPd.kinerjaProgram' => fn (Builder $query) => $query
                    ->select('id', 'sasaran_strategis_pd_id', 'sasaran', 'indikator', 'program_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'kinerja_bayangan_id', 'do_rumus')
                    ->tahunKinerja()
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'program:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw('id, jabatan_nama')
                            ->when($satuanKerjaId == SATKER_SETWAN, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',

                        ! $isDefaultType ? null : 'solusi.masalah:id,sasaran,indikator,program_id,v_struktur_organisasi_id,target,realisasi,capaian',
                        ! $isDefaultType ? null : 'solusi.masalah.program:id,nama',
                        ! $isDefaultType ? null : 'solusi.masalah.kinerjaTidakTercapai' => $prevKinerjaTidakTercapaiQuery,
                        ! $isDefaultType ? null : 'solusi.masalah.strukturOrganisasi:id,jabatan_nama',

                        $isDefaultType ? null : 'kinerjaBayangan',
                        'kinerjaTidakTercapai' => $kinerjaTidakTercapaiQuery,
                        'kinerjaTercapai' => $kinerjaTercapaiQuery,

                        ! $isWithSkp ? null : 'skp' => fn (Builder $query) => $query->select('id', 'model_class', 'model_id')->tahunKinerja()->where('is_skp', true),
                    ]))
                    ->withExists([
                        'kinerjaProgramCross' => fn (Builder $query) => $query
                            ->whereHas('sasaranStrategisPd', fn (Builder $query) => $query->where('satuan_kerja_id', $satuanKerjaId)),
                    ])
                    ->when($satuanKerjaId && $isBiro, fn (Builder $query2) => $query2->where('satuan_kerja_id', $satuanKerjaId))
                    ->when(! $isDefaultType, $excludePengampuUkerQuery)
                    ->orderBy('order'),

                'sasaranStrategisPd.kinerjaProgramCross.kinerjaProgram' => fn (Builder $query) => $query
                    ->select('id', 'sasaran_strategis_pd_id', 'sasaran', 'indikator', 'program_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'kinerja_bayangan_id', 'satuan_kerja_id')
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                        ! $isDefaultType ? null : 'program:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw('id, jabatan_nama')
                            ->when($satuanKerjaId == SATKER_SETWAN, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',
                    ])),
            ],
            // level 4
            [
                // TODO: saat ini setwan belum ada pelantikan, jadi pegawai masih di unit kerja lama
                // jadi pengampu masih bisa pilih unit kerja yang sudah inaktif
                // hapus $withInactive saat sudah pelantikan

                'sasaranStrategisPd.kinerjaProgram.kinerjaKegiatan' => fn (Builder $query) => $query
                    ->select('id', 'kinerja_program_id', 'sasaran', 'indikator', 'kegiatan_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'kinerja_bayangan_id', 'do_rumus')
                    ->tahunKinerja()
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'kegiatan:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw('id, jabatan_nama')
                            ->when($satuanKerjaId == SATKER_SETWAN, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',

                        ! $isDefaultType ? null : 'solusi.masalah:id,sasaran,indikator,kegiatan_id,v_struktur_organisasi_id,pengampu,tim_kerja_id,target,realisasi,capaian',
                        ! $isDefaultType ? null : 'solusi.masalah.kinerjaTidakTercapai' => $prevKinerjaTidakTercapaiQuery,
                        ! $isDefaultType ? null : 'solusi.masalah.kegiatan:id,nama',
                        ! $isDefaultType ? null : 'solusi.masalah.strukturOrganisasi:id,jabatan_nama',
                        ! $isDefaultType ? null : 'solusi.masalah.timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'solusi.masalah.timKerja.ketua:peg_nip,peg_nama',

                        $isDefaultType ? null : 'kinerjaBayangan',
                        'kinerjaTidakTercapai' => $kinerjaTidakTercapaiQuery,
                        'kinerjaTercapai' => $kinerjaTercapaiQuery,

                        ! $isWithSkp ? null : 'skp' => fn (Builder $query) => $query->select('id', 'model_class', 'model_id')->tahunKinerja()->where('is_skp', true),
                    ]))
                    ->withExists([
                        'kinerjaKegiatanCross' => fn (Builder $query) => $query
                            ->whereHas('kinerjaProgram', fn (Builder $query) => $query->where('satuan_kerja_id', $satuanKerjaId)),
                    ])
                    ->when($satuanKerjaId && $isBiro, fn (Builder $query2) => $query2->where('satuan_kerja_id', $satuanKerjaId))
                    ->when(! $isDefaultType, $excludePengampuQuery),

                'sasaranStrategisPd.kinerjaProgram.kinerjaKegiatanCross.kinerjaKegiatan' => fn (Builder $query) => $query
                    ->select('id', 'kinerja_program_id', 'sasaran', 'indikator', 'kegiatan_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'kinerja_bayangan_id', 'satuan_kerja_id')
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                        ! $isDefaultType ? null : 'kegiatan:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw('id, jabatan_nama')
                            ->when($satuanKerjaId == SATKER_SETWAN, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',
                    ])),
            ],
            // level 5
            [
                // TODO: jangan tampilkan unit kerja lama jika pegawai sudah dipindah

                'sasaranStrategisPd.kinerjaProgram.kinerjaKegiatan.kinerjaSubKegiatan' => fn (Builder $query) => $query
                    ->select('id', 'kinerja_kegiatan_id', 'sasaran', 'indikator', 'indikator_kemendagri', 'sub_kegiatan_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'satuan', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'is_external')
                    ->tahunKinerja()
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'subKegiatan:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw("
                                id,
                                jabatan_nama,
                                CONCAT_WS(', ',
                                    lv7_unit_kerja_nama,
                                    lv6_unit_kerja_nama,
                                    lv5_unit_kerja_nama,
                                    lv4_unit_kerja_nama,
                                    lv3_unit_kerja_nama,
                                    lv2_unit_kerja_nama,
                                    lv1_unit_kerja_nama
                                ) AS unit_kerja_nama_full
                            ")
                            ->when($satuanKerjaId == SATKER_DISDUKCAPIL, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',

                        ! $isDefaultType ? null : 'solusi.masalah:id,sasaran,indikator,sub_kegiatan_id,v_struktur_organisasi_id,pengampu,tim_kerja_id,target,realisasi,capaian',
                        ! $isDefaultType ? null : 'solusi.masalah.kinerjaTidakTercapai' => $prevKinerjaTidakTercapaiQuery,
                        ! $isDefaultType ? null : 'solusi.masalah.subKegiatan:id,nama',
                        ! $isDefaultType ? null : 'solusi.masalah.strukturOrganisasi:id,jabatan_nama',
                        ! $isDefaultType ? null : 'solusi.masalah.timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'solusi.masalah.timKerja.ketua:peg_nip,peg_nama',

                        'kinerjaTidakTercapai' => $kinerjaTidakTercapaiQuery,
                        'kinerjaTercapai' => $kinerjaTercapaiQuery,

                        ! $isWithSkp ? null : 'skp' => fn (Builder $query) => $query->select('id', 'model_class', 'model_id')->tahunKinerja()->where('is_skp', true),
                    ]))
                    ->withExists([
                        'kinerjaSubKegiatanCross' => fn (Builder $query) => $query
                            ->whereHas('kinerjaKegiatan', fn (Builder $query) => $query->where('satuan_kerja_id', $satuanKerjaId)),
                    ])
                    ->when($satuanKerjaId && $isBiro, fn (Builder $query2) => $query2->where('satuan_kerja_id', $satuanKerjaId))
                    ->when(! $isDefaultType, $excludePengampuQuery),

                'sasaranStrategisPd.kinerjaProgram.kinerjaKegiatan.kinerjaSubKegiatanKabKota' => fn (Builder $query) => $query
                    ->select('id', 'kinerja_kegiatan_id', 'sasaran', 'indikator', 'indikator_kemendagri', 'sub_kegiatan_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'satuan', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'is_external', 'kab_kota_id')
                    ->tahunKinerja()
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'subKegiatan:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw("
                                id,
                                jabatan_nama,
                                CONCAT_WS(', ',
                                    lv7_unit_kerja_nama,
                                    lv6_unit_kerja_nama,
                                    lv5_unit_kerja_nama,
                                    lv4_unit_kerja_nama,
                                    lv3_unit_kerja_nama,
                                    lv2_unit_kerja_nama,
                                    lv1_unit_kerja_nama
                                ) AS unit_kerja_nama_full
                            ")
                            ->when($satuanKerjaId == SATKER_DISDUKCAPIL, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',

                        ! $isDefaultType ? null : 'solusi.masalah:id,sasaran,indikator,sub_kegiatan_id,v_struktur_organisasi_id,pengampu,tim_kerja_id,target,realisasi,capaian',
                        ! $isDefaultType ? null : 'solusi.masalah.kinerjaTidakTercapai' => $prevKinerjaTidakTercapaiQuery,
                        ! $isDefaultType ? null : 'solusi.masalah.subKegiatan:id,nama',
                        ! $isDefaultType ? null : 'solusi.masalah.strukturOrganisasi:id,jabatan_nama',
                        ! $isDefaultType ? null : 'solusi.masalah.timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'solusi.masalah.timKerja.ketua:peg_nip,peg_nama',

                        'kinerjaTidakTercapai' => $kinerjaTidakTercapaiQuery,
                        'kinerjaTercapai' => $kinerjaTercapaiQuery,

                        ! $isWithSkp ? null : 'skp' => fn (Builder $query) => $query->select('id', 'model_class', 'model_id')->tahunKinerja()->where('is_skp', true),
                        ! $isDefaultType ? null : 'kinerjaKabKota',
                    ]))
                    ->when($satuanKerjaId && $isBiro, fn (Builder $query2) => $query2->where('satuan_kerja_id', $satuanKerjaId))
                    ->when(! $isDefaultType, $excludePengampuQuery),

                'sasaranStrategisPd.kinerjaProgram.kinerjaKegiatan.kinerjaSubKegiatanCross.kinerjaSubKegiatan' => fn (Builder $query) => $query
                    ->select('id', 'kinerja_kegiatan_id', 'sasaran', 'indikator', 'indikator_kemendagri', 'sub_kegiatan_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'satuan', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'is_external', 'satuan_kerja_id')
                    ->tahunKinerja()
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                        ! $isDefaultType ? null : 'subKegiatan:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw("
                                id,
                                jabatan_nama,
                                CONCAT_WS(', ',
                                    lv7_unit_kerja_nama,
                                    lv6_unit_kerja_nama,
                                    lv5_unit_kerja_nama,
                                    lv4_unit_kerja_nama,
                                    lv3_unit_kerja_nama,
                                    lv2_unit_kerja_nama,
                                    lv1_unit_kerja_nama
                                ) AS unit_kerja_nama_full
                            ")
                            ->when($satuanKerjaId == SATKER_DISDUKCAPIL, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',
                    ])),
            ],
        ];

        $relations = self::flatten(array_slice($relations, 0, $maxLevel));

        $data = SasaranStrategisRpjmd::tahunMulai()
            ->select(['id', 'sasaran_strategis_id', 'indikator_sasaran_strategis_id', 'satuan_kerja_id', ...$selectColumnByTahun])
            ->with($relations)
            ->when($satuanKerjaId, fn (Builder $query) => $query->where('satuan_kerja_id', $satuanKerjaIdParsed))
            ->get()
            ->groupBy('sasaran_strategis_id')
            ->map(function (Collection $sasaranList) {
                /**
                 * merge Sasaran Strategis RPJMD yang memiliki sasaran sama tapi indikator berbeda
                 * karena sebenarnya itu adalah satu kesatuan
                 * tapi jangan di merge jika memiliki children sasaranStrategisPd
                 */
                $sasaranList = $sasaranList->map(function (SasaranStrategisRpjmd $sasaran) {
                    $sasaran->indikator_merge = [
                        $sasaran->indikatorSasaranStrategis->indikator,
                    ];

                    return $sasaran;
                });

                /**
                 * @var SasaranStrategisRpjmd
                 */
                $nonEmpty = $sasaranList->first(fn (SasaranStrategisRpjmd $sasaran) => $sasaran->sasaranStrategisPd->count());

                if (! $nonEmpty) {
                    return $sasaranList;
                }

                $indikatorMerge = $nonEmpty->indikator_merge;

                $sasaranList = $sasaranList->map(function (SasaranStrategisRpjmd $sasaran) use (&$indikatorMerge) {
                    if (! $sasaran->sasaranStrategisPd->count()) {
                        $indikatorMerge[] = $sasaran->indikatorSasaranStrategis->indikator;
                        $sasaran->need_to_remove = true;
                    } else {
                        $sasaran->need_to_remove = false;
                    }

                    return $sasaran;
                });

                $nonEmpty->indikator_merge = array_unique($indikatorMerge);

                return $sasaranList->filter(fn (SasaranStrategisRpjmd $sasaran) => ! $sasaran->need_to_remove)->values();
            })
            ->flatten(1)
            ->values()
            ->map(function (SasaranStrategisRpjmd $sasaranRpjmd) {
                $sasaranStrategisPd = $sasaranRpjmd->sasaranStrategisPd
                    ->groupBy('sasaran_strategis_satker')
                    ->map(function (Collection $sasaranList) {
                        /**
                         * merge Sasaran Strategis PD yang memiliki sasaran sama tapi indikator berbeda
                         * tapi jangan di merge jika memiliki children kinerjaProgram
                         */
                        $sasaranList = $sasaranList->map(function (SasaranStrategisPd $sasaran) {
                            $sasaran->indikator_merge = [
                                $sasaran->iku,
                            ];

                            return $sasaran;
                        });

                        /**
                         * @var SasaranStrategisPd
                         */
                        $nonEmpty = $sasaranList->first(fn (SasaranStrategisPd $sasaran) => $sasaran->kinerjaProgram->isNotEmpty());

                        if (! $nonEmpty) {
                            return $sasaranList;
                        }

                        $indikatorMerge = $nonEmpty->indikator_merge;

                        $sasaranList = $sasaranList->map(function (SasaranStrategisPd $sasaran) use (&$indikatorMerge) {
                            if ($sasaran->kinerjaProgram->isEmpty()) {
                                $indikatorMerge[] = $sasaran->iku;
                                $sasaran->need_to_remove = true;
                            } else {
                                $sasaran->need_to_remove = false;
                            }

                            return $sasaran;
                        });

                        $nonEmpty->indikator_merge = array_unique($indikatorMerge);

                        return $sasaranList->filter(fn (SasaranStrategisPd $sasaran) => ! $sasaran->need_to_remove)->values();
                    })
                    ->flatten(1)
                    ->values();

                unset($sasaranRpjmd->sasaranStrategisPd);
                $sasaranRpjmd->sasaran_strategis_pd = $sasaranStrategisPd;

                return $sasaranRpjmd;
            })
            ->map(function (SasaranStrategisRpjmd $sasaranRpjmd) {
                // Process each sasaran_strategis_pd
                $sasaranRpjmd->sasaran_strategis_pd = $sasaranRpjmd->sasaran_strategis_pd->map(function (SasaranStrategisPd $sasaranPd) {
                    if ($sasaranPd->kinerjaProgram && $sasaranPd->kinerjaProgram->isNotEmpty()) {
                        $sasaranProgram = $sasaranPd->kinerjaProgram
                            ->groupBy('sasaran')
                            ->map(function (Collection $sasaranList) {
                                /**
                                 * merge Sasaran Program yang memiliki sasaran sama tapi indikator berbeda
                                 * tapi jangan di merge jika memiliki children kinerjaKegiatan
                                 */
                                $sasaranList = $sasaranList->map(function (KinerjaProgram $sasaran) {
                                    $sasaran->indikator_merge = [
                                        $sasaran->indikator,
                                    ];

                                    return $sasaran;
                                });

                                /**
                                 * @var KinerjaProgram
                                 */
                                $nonEmpty = $sasaranList->first(fn (KinerjaProgram $sasaran) => $sasaran->kinerjaKegiatan->isNotEmpty());

                                if (! $nonEmpty) {
                                    return $sasaranList;
                                }

                                $indikatorMerge = $nonEmpty->indikator_merge;

                                $sasaranList = $sasaranList->map(function (KinerjaProgram $sasaran) use (&$indikatorMerge) {
                                    if ($sasaran->kinerjaKegiatan->isEmpty()) {
                                        $indikatorMerge[] = $sasaran->indikator;
                                        $sasaran->need_to_remove = true;
                                    } else {
                                        $sasaran->need_to_remove = false;
                                    }

                                    return $sasaran;
                                });

                                $nonEmpty->indikator_merge = array_unique($indikatorMerge);

                                return $sasaranList->filter(fn (KinerjaProgram $sasaran) => ! $sasaran->need_to_remove)->values();
                            })
                            ->flatten(1)
                            ->values();

                        // Replace the kinerjaProgram with the processed version
                        // $sasaranPd->kinerjaProgram = $sasaranProgram;
                    }

                    return $sasaranPd;
                });

                return $sasaranRpjmd;
            });

        return $data;

    }

    public static function getExport(?int $satuanKerjaId, int $maxLevel = 5, string $type = 'default'): SupportCollection
    {
        if (! is_null($satuanKerjaId)) {
            $isBiro = isBiro($satuanKerjaId);
            $satuanKerjaIdParsed = parseSatuanKerjaId($satuanKerjaId);
        }

        // type arsitektur kinerja hanya tampilkan sasaran saja supaya tidak berat
        $isDefaultType = $type == 'default';

        // type arsitektur kinerja hanya menampilkan pengampu selain (KEPALA SUBBAGIAN TATA USAHA dan SEKRETARIS)
        // atau belum di set pengampunya
        // atau pengampunya tim
        /**
         * pengampu unit kerja
         * - v_struktur_organisasi_id = null
         * - atau tidak punya strukturOrganisasi (relasi datanya tidak ada)
         * - pengampu selain KEPALA SUBBAGIAN TATA USAHA dan SEKRETARIS
         *
         * pengampu tim kerja
         * - semua masuk
         */
        $excludePengampuQuery = fn (Builder $query) => $query
            ->where(fn (Builder $query) => $query
                ->where(fn (Builder $query) => $query
                    ->where('pengampu', 'unit-kerja')
                    ->where(fn (Builder $query) => $query
                        ->whereDoesntHave('strukturOrganisasi')
                        ->orWhereHas('strukturOrganisasi', fn (Builder $query) => $query
                            ->where('jabatan_nama', '<>', 'KEPALA SUBBAGIAN TATA USAHA')
                            ->where('jabatan_nama', '<>', 'SEKRETARIS')
                        )
                        ->orWhereNull('v_struktur_organisasi_id')
                    )
                )
                ->orWhere('pengampu', 'tim-kerja')
            );

        $excludePengampuUkerQuery = fn (Builder $query) => $query
            ->where(fn (Builder $query) => $query
                ->whereHas('strukturOrganisasi', fn (Builder $query) => $query
                    ->where('jabatan_nama', '<>', 'KEPALA SUBBAGIAN TATA USAHA')
                    ->where('jabatan_nama', '<>', 'SEKRETARIS')
                )
                ->orWhereNull('v_struktur_organisasi_id')
            );

        // kinerja tidak tercapai dari kinerja masalah (tahun sebelumnya)
        $prevKinerjaTidakTercapaiQuery = fn (Builder $query) => $query->where('tahun_kinerja', getTahunKinerja() - 1);

        $kinerjaTidakTercapaiQuery = fn (Builder $query) => $query->tahunKinerja();

        $kinerjaTercapaiQuery = fn (Builder $query) => $query->tahunKinerja();

        // original function from client/plugins/helpers.js::getKeyTahun()
        $getKeyTahun = function ($key, $offset = 0) {
            $index = (getTahunKinerja() - getTahunMulai()) + 1 + $offset;

            if ($index < 1) {
                $index = 'baseline';
            }

            return "{$key}_{$index}";
        };

        // convert penamaan kolom capaian per tahun
        $selectColumnByTahun = [
            $getKeyTahun('target').' AS target',
            $getKeyTahun('realisasi').' AS realisasi',
            $getKeyTahun('capaian').' AS capaian',
            $getKeyTahun('penyebab_kegagalan').' AS penyebab_kegagalan',
        ];

        $arrayFilter = function (array $array) {
            return array_filter($array, function ($value, $key) {
                if ($value && $key !== '') {
                    return true;
                }
            }, ARRAY_FILTER_USE_BOTH);
        };

        $isWithSkp = request()->get('show_skp');

        $relations = [
            // level 1
            [
                'sasaranStrategis:id,sasaran',
                'indikatorSasaranStrategis:id,indikator',
                'tujuan:id,tujuan',
                'kinerjaTidakTercapai' => $kinerjaTidakTercapaiQuery,
            ],
            // level 2
            [
                'sasaranStrategisPd' => fn (Builder $query) => $query
                    ->select(['id', 'sasaran_strategis_rpjmd_id', 'sasaran_strategis_satker', 'iku', 'satuan_kerja_id', 'kinerja_bayangan_id', 'definisi_operasional', 'do_rumus', 'satuan', ...$selectColumnByTahun])
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                        $isDefaultType ? null : 'kinerjaBayangan',
                        'kinerjaTidakTercapai' => $kinerjaTidakTercapaiQuery,

                        ! $isWithSkp ? null : 'skp' => fn (Builder $query) => $query->select('id', 'model_class', 'model_id')->tahunKinerja()->where('is_skp', true),
                    ]))
                    ->withExists([
                        'sasaranStrategisPdCross' => fn (Builder $query) => $query
                            ->whereHas('sasaranStrategisRpjmd', fn (Builder $query) => $query->where('satuan_kerja_id', $satuanKerjaId)),
                    ]),

                'sasaranStrategisPdCross.sasaranStrategisPd' => fn (Builder $query) => $query
                    ->select(['id', 'sasaran_strategis_rpjmd_id', 'sasaran_strategis_satker', 'iku', 'satuan_kerja_id', 'kinerja_bayangan_id', 'definisi_operasional', ...$selectColumnByTahun])
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                    ])),
            ],
            // level 3
            [
                // TODO: saat ini setwan belum ada pelantikan, jadi pegawai masih di unit kerja lama
                // jadi pengampu masih bisa pilih unit kerja yang sudah inaktif
                // hapus $withInactive saat sudah pelantikan

                'sasaranStrategisPd.kinerjaProgram' => fn (Builder $query) => $query
                    ->select('id', 'sasaran_strategis_pd_id', 'sasaran', 'indikator', 'program_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'kinerja_bayangan_id', 'do_rumus', 'satuan')
                    ->tahunKinerja()
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'program:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw('id, jabatan_nama')
                            ->when($satuanKerjaId == SATKER_SETWAN, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',

                        ! $isDefaultType ? null : 'solusi.masalah:id,sasaran,indikator,program_id,v_struktur_organisasi_id,target,realisasi,capaian',
                        ! $isDefaultType ? null : 'solusi.masalah.program:id,nama',
                        ! $isDefaultType ? null : 'solusi.masalah.kinerjaTidakTercapai' => $prevKinerjaTidakTercapaiQuery,
                        ! $isDefaultType ? null : 'solusi.masalah.strukturOrganisasi:id,jabatan_nama',

                        $isDefaultType ? null : 'kinerjaBayangan',
                        'kinerjaTidakTercapai' => $kinerjaTidakTercapaiQuery,
                        'kinerjaTercapai' => $kinerjaTercapaiQuery,

                        ! $isWithSkp ? null : 'skp' => fn (Builder $query) => $query->select('id', 'model_class', 'model_id')->tahunKinerja()->where('is_skp', true),
                    ]))
                    ->withExists([
                        'kinerjaProgramCross' => fn (Builder $query) => $query
                            ->whereHas('sasaranStrategisPd', fn (Builder $query) => $query->where('satuan_kerja_id', $satuanKerjaId)),
                    ])
                    ->when($satuanKerjaId && $isBiro, fn (Builder $query2) => $query2->where('satuan_kerja_id', $satuanKerjaId))
                    ->when(! $isDefaultType, $excludePengampuUkerQuery)
                    ->orderBy('order'),

                'sasaranStrategisPd.kinerjaProgramCross.kinerjaProgram' => fn (Builder $query) => $query
                    ->select('id', 'sasaran_strategis_pd_id', 'sasaran', 'indikator', 'program_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'kinerja_bayangan_id', 'satuan_kerja_id')
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                        ! $isDefaultType ? null : 'program:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw('id, jabatan_nama')
                            ->when($satuanKerjaId == SATKER_SETWAN, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',
                    ])),
            ],
            // level 4
            [
                // TODO: saat ini setwan belum ada pelantikan, jadi pegawai masih di unit kerja lama
                // jadi pengampu masih bisa pilih unit kerja yang sudah inaktif
                // hapus $withInactive saat sudah pelantikan

                'sasaranStrategisPd.kinerjaProgram.kinerjaKegiatan' => fn (Builder $query) => $query
                    ->select('id', 'kinerja_program_id', 'sasaran', 'indikator', 'kegiatan_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'kinerja_bayangan_id', 'do_rumus', 'satuan')
                    ->tahunKinerja()
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'kegiatan:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw('id, jabatan_nama')
                            ->when($satuanKerjaId == SATKER_SETWAN, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',

                        ! $isDefaultType ? null : 'solusi.masalah:id,sasaran,indikator,kegiatan_id,v_struktur_organisasi_id,pengampu,tim_kerja_id,target,realisasi,capaian',
                        ! $isDefaultType ? null : 'solusi.masalah.kinerjaTidakTercapai' => $prevKinerjaTidakTercapaiQuery,
                        ! $isDefaultType ? null : 'solusi.masalah.kegiatan:id,nama',
                        ! $isDefaultType ? null : 'solusi.masalah.strukturOrganisasi:id,jabatan_nama',
                        ! $isDefaultType ? null : 'solusi.masalah.timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'solusi.masalah.timKerja.ketua:peg_nip,peg_nama',

                        $isDefaultType ? null : 'kinerjaBayangan',
                        'kinerjaTidakTercapai' => $kinerjaTidakTercapaiQuery,
                        'kinerjaTercapai' => $kinerjaTercapaiQuery,

                        ! $isWithSkp ? null : 'skp' => fn (Builder $query) => $query->select('id', 'model_class', 'model_id')->tahunKinerja()->where('is_skp', true),
                    ]))
                    ->withExists([
                        'kinerjaKegiatanCross' => fn (Builder $query) => $query
                            ->whereHas('kinerjaProgram', fn (Builder $query) => $query->where('satuan_kerja_id', $satuanKerjaId)),
                    ])
                    ->when($satuanKerjaId && $isBiro, fn (Builder $query2) => $query2->where('satuan_kerja_id', $satuanKerjaId))
                    ->when(! $isDefaultType, $excludePengampuQuery),

                'sasaranStrategisPd.kinerjaProgram.kinerjaKegiatanCross.kinerjaKegiatan' => fn (Builder $query) => $query
                    ->select('id', 'kinerja_program_id', 'sasaran', 'indikator', 'kegiatan_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'kinerja_bayangan_id', 'satuan_kerja_id')
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                        ! $isDefaultType ? null : 'kegiatan:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw('id, jabatan_nama')
                            ->when($satuanKerjaId == SATKER_SETWAN, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',
                    ])),
            ],
            // level 5
            [
                // TODO: jangan tampilkan unit kerja lama jika pegawai sudah dipindah

                'sasaranStrategisPd.kinerjaProgram.kinerjaKegiatan.kinerjaSubKegiatan' => fn (Builder $query) => $query
                    ->select('id', 'kinerja_kegiatan_id', 'sasaran', 'indikator', 'indikator_kemendagri', 'sub_kegiatan_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'satuan', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'is_external')
                    ->tahunKinerja()
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'subKegiatan:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw("
                                id,
                                jabatan_nama,
                                CONCAT_WS(', ',
                                    lv7_unit_kerja_nama,
                                    lv6_unit_kerja_nama,
                                    lv5_unit_kerja_nama,
                                    lv4_unit_kerja_nama,
                                    lv3_unit_kerja_nama,
                                    lv2_unit_kerja_nama,
                                    lv1_unit_kerja_nama
                                ) AS unit_kerja_nama_full
                            ")
                            ->when($satuanKerjaId == SATKER_DISDUKCAPIL, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',

                        ! $isDefaultType ? null : 'solusi.masalah:id,sasaran,indikator,sub_kegiatan_id,v_struktur_organisasi_id,pengampu,tim_kerja_id,target,realisasi,capaian',
                        ! $isDefaultType ? null : 'solusi.masalah.kinerjaTidakTercapai' => $prevKinerjaTidakTercapaiQuery,
                        ! $isDefaultType ? null : 'solusi.masalah.subKegiatan:id,nama',
                        ! $isDefaultType ? null : 'solusi.masalah.strukturOrganisasi:id,jabatan_nama',
                        ! $isDefaultType ? null : 'solusi.masalah.timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'solusi.masalah.timKerja.ketua:peg_nip,peg_nama',

                        'kinerjaTidakTercapai' => $kinerjaTidakTercapaiQuery,
                        'kinerjaTercapai' => $kinerjaTercapaiQuery,

                        ! $isWithSkp ? null : 'skp' => fn (Builder $query) => $query->select('id', 'model_class', 'model_id')->tahunKinerja()->where('is_skp', true),
                    ]))
                    ->withExists([
                        'kinerjaSubKegiatanCross' => fn (Builder $query) => $query
                            ->whereHas('kinerjaKegiatan', fn (Builder $query) => $query->where('satuan_kerja_id', $satuanKerjaId)),
                    ])
                    ->when($satuanKerjaId && $isBiro, fn (Builder $query2) => $query2->where('satuan_kerja_id', $satuanKerjaId))
                    ->when(! $isDefaultType, $excludePengampuQuery),

                'sasaranStrategisPd.kinerjaProgram.kinerjaKegiatan.kinerjaSubKegiatanKabKota' => fn (Builder $query) => $query
                    ->select('id', 'kinerja_kegiatan_id', 'sasaran', 'indikator', 'indikator_kemendagri', 'sub_kegiatan_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'satuan', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'is_external')
                    ->tahunKinerja()
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'subKegiatan:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw("
                                id,
                                jabatan_nama,
                                CONCAT_WS(', ',
                                    lv7_unit_kerja_nama,
                                    lv6_unit_kerja_nama,
                                    lv5_unit_kerja_nama,
                                    lv4_unit_kerja_nama,
                                    lv3_unit_kerja_nama,
                                    lv2_unit_kerja_nama,
                                    lv1_unit_kerja_nama
                                ) AS unit_kerja_nama_full
                            ")
                            ->when($satuanKerjaId == SATKER_DISDUKCAPIL, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',

                        ! $isDefaultType ? null : 'solusi.masalah:id,sasaran,indikator,sub_kegiatan_id,v_struktur_organisasi_id,pengampu,tim_kerja_id,target,realisasi,capaian',
                        ! $isDefaultType ? null : 'solusi.masalah.kinerjaTidakTercapai' => $prevKinerjaTidakTercapaiQuery,
                        ! $isDefaultType ? null : 'solusi.masalah.subKegiatan:id,nama',
                        ! $isDefaultType ? null : 'solusi.masalah.strukturOrganisasi:id,jabatan_nama',
                        ! $isDefaultType ? null : 'solusi.masalah.timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'solusi.masalah.timKerja.ketua:peg_nip,peg_nama',

                        'kinerjaTidakTercapai' => $kinerjaTidakTercapaiQuery,
                        'kinerjaTercapai' => $kinerjaTercapaiQuery,

                        ! $isWithSkp ? null : 'skp' => fn (Builder $query) => $query->select('id', 'model_class', 'model_id')->tahunKinerja()->where('is_skp', true),
                        'kinerjaKabKota',
                    ]))

                    ->when($satuanKerjaId && $isBiro, fn (Builder $query2) => $query2->where('satuan_kerja_id', $satuanKerjaId))
                    ->when(! $isDefaultType, $excludePengampuQuery),

                'sasaranStrategisPd.kinerjaProgram.kinerjaKegiatan.kinerjaSubKegiatanCross.kinerjaSubKegiatan' => fn (Builder $query) => $query
                    ->select('id', 'kinerja_kegiatan_id', 'sasaran', 'indikator', 'indikator_kemendagri', 'sub_kegiatan_id', 'v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id', 'satuan', 'target', 'realisasi', 'capaian', 'penyebab_kegagalan', 'is_external', 'satuan_kerja_id')
                    ->tahunKinerja()
                    ->with($arrayFilter([
                        ! $isDefaultType ? null : 'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                        ! $isDefaultType ? null : 'subKegiatan:id,nama',
                        ! $isDefaultType ? null : 'strukturOrganisasi' => fn (Builder $query) => $query
                            ->selectRaw("
                                id,
                                jabatan_nama,
                                CONCAT_WS(', ',
                                    lv7_unit_kerja_nama,
                                    lv6_unit_kerja_nama,
                                    lv5_unit_kerja_nama,
                                    lv4_unit_kerja_nama,
                                    lv3_unit_kerja_nama,
                                    lv2_unit_kerja_nama,
                                    lv1_unit_kerja_nama
                                ) AS unit_kerja_nama_full
                            ")
                            ->when($satuanKerjaId == SATKER_DISDUKCAPIL, fn (Builder $query) => $query
                                ->withoutGlobalScope('active')
                            ),
                        ! $isDefaultType ? null : 'timKerja:id,nama,nip_ketua',
                        ! $isDefaultType ? null : 'timKerja.ketua:peg_nip,peg_nama',
                    ])),

            ],
        ];

        $relations = self::flatten(array_slice($relations, 0, $maxLevel));

        $data = SasaranStrategisRpjmd::tahunMulai()
            ->select(['id', 'sasaran_strategis_id', 'indikator_sasaran_strategis_id', 'satuan_kerja_id', 'tujuan_id', ...$selectColumnByTahun])
            ->with($relations)
            ->when($satuanKerjaId, fn (Builder $query) => $query->where('satuan_kerja_id', $satuanKerjaIdParsed))
            ->get()
            ->groupBy('sasaran_strategis_id')
            ->map(function (Collection $sasaranList) {
                /**
                 * merge Sasaran Strategis RPJMD yang memiliki sasaran sama tapi indikator berbeda
                 * karena sebenarnya itu adalah satu kesatuan
                 * tapi jangan di merge jika memiliki children sasaranStrategisPd
                 */
                $sasaranList = $sasaranList->map(function (SasaranStrategisRpjmd $sasaran) {
                    $sasaran->indikator_merge = [
                        $sasaran->indikatorSasaranStrategis->indikator,
                    ];

                    return $sasaran;
                });

                /**
                 * @var SasaranStrategisRpjmd
                 */
                $nonEmpty = $sasaranList->first(fn (SasaranStrategisRpjmd $sasaran) => $sasaran->sasaranStrategisPd->count());

                if (! $nonEmpty) {
                    return $sasaranList;
                }

                $indikatorMerge = $nonEmpty->indikator_merge;

                $sasaranList = $sasaranList->map(function (SasaranStrategisRpjmd $sasaran) use (&$indikatorMerge) {
                    if (! $sasaran->sasaranStrategisPd->count()) {
                        $indikatorMerge[] = $sasaran->indikatorSasaranStrategis->indikator;
                        $sasaran->need_to_remove = true;
                    } else {
                        $sasaran->need_to_remove = false;
                    }

                    return $sasaran;
                });

                $nonEmpty->indikator_merge = array_unique($indikatorMerge);

                return $sasaranList->filter(fn (SasaranStrategisRpjmd $sasaran) => ! $sasaran->need_to_remove)->values();
            })
            ->flatten(1)
            ->values();

        return $data;
    }

    private static function flatten(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $result[] = $value;
            }

            if (is_callable($value)) {
                $result[$key] = $value;
            }

            if (is_array($value)) {
                $result = array_merge($result, self::flatten($value));
            }
        }

        return $result;
    }

    /**
     * Sama seperti method get() tapi diambil 1 PD jika ada kinerja yang diampu beberapa PD
     *
     * @param  string  $type  default|arsitektur-kinerja
     *
     * @throws \InvalidArgumentException
     */
    public static function getCompact(?int $satuanKerjaId, int $maxLevel = 5, string $type = 'default'): SupportCollection
    {
        $result = self::get($satuanKerjaId, $maxLevel, $type)
            ->sortBy('sasaran_strategis_id')
            ->groupBy(fn (SasaranStrategisRpjmd $item) => "{$item->sasaran_strategis_id}-{$item->indikator_sasaran_strategis_id}")
            ->transform(fn (SupportCollection $items) => collect($items->transform(fn ($items) => collect($items)))) // convert supaya key relation bisa di override
            ->transform(function (SupportCollection $sasaranStrategisRpjmd) {
                // merge sasaran kinerja PD
                $data = $sasaranStrategisRpjmd->first();
                $sasaranStrategisPd = collect([]);

                foreach ($sasaranStrategisRpjmd as $item) {
                    $sasaranStrategisPd->push($item['sasaran_strategis_pd']);
                }

                $data['sasaran_strategis_pd'] = $sasaranStrategisPd
                    ->flatten(1)
                    ->groupBy(fn (SasaranStrategisPd $item) => "{$item->sasaran_strategis_satker}-{$item->iku}")
                    ->transform(function (SupportCollection $sasaranStrategisPd) {
                        // merge kinerja program
                        $data = $sasaranStrategisPd->first();
                        $kinerjaProgram = collect([]);

                        foreach ($sasaranStrategisPd as $item) {
                            if (isset($item['kinerja_program'])) {
                                $kinerjaProgram->push($item['kinerja_program']);
                            }
                        }

                        // seharusnya dari kinerja program kebawah sudah unik per OPD
                        // sehingga tidak perlu di-merge lagi
                        $data['kinerja_program'] = $kinerjaProgram
                            ->flatten(1);

                        return $data;
                    })
                    ->values();

                return $data;
            })
            ->values();

        if ($type != 'arsitektur-kinerja') {
            return $result;
        }

        /**
         * tambah flag kinerja bayangan di arsitektur kinerja
         *
         * jika punya bayangan, maka tampilkan (render_status = render)
         * jika tidak punya bayangan:
         * - jika ada children yang punya bayangan, maka render tapi di hide (render_status = hide)
         * - jika tidak ada children yang punya bayangan, maka jangan dirender (render_status = false)
         *
         * false vs hide
         * - false = tidak dirender dan tidak akan ada garis penghubung dan tidak ada children yang dirender
         * - hide = dirender tapi dihide sehingga tidak akan tampil tapi tetap ada garis penghubung dan ada children yang dirender
         */

        return $result->transform(function ($sasaranStrategisRpjmd) {
            $sasaranStrategisRpjmd['sasaran_strategis_pd']->transform(function ($sasaranStrategisPd) {
                $render = isset($sasaranStrategisPd['kinerja_bayangan']) ? 'render' : false;

                // cek apakah ada children yang punya bayangan
                if (! $render) {
                    $sasaranStrategisPd['kinerja_program']->transform(function ($kinerjaProgram) {
                        $render = isset($kinerjaProgram['kinerja_bayangan']) ? 'render' : false;

                        $kinerjaProgram['kinerja_kegiatan'] = collect($kinerjaProgram['kinerja_kegiatan']);

                        // cek apakah ada children yang punya bayangan
                        if (! $render) {
                            $kinerjaProgram['kinerja_kegiatan']->transform(function ($kinerjaKegiatan) {
                                // kinerja kegiatan hanya ada 'render' atau 'false' karena tidak ada pengecekan ke childrennya lagi
                                $kinerjaKegiatan['render_status'] = isset($kinerjaKegiatan['kinerja_bayangan']) ? 'render' : false;

                                return $kinerjaKegiatan;
                            });

                            // jika ada children yang punya bayangan, maka render tapi di hide
                            $render = $kinerjaProgram['kinerja_kegiatan']->contains(fn ($kinerjaKegiatan) => $kinerjaKegiatan['render_status'] != false)
                                ? 'hide'
                                : false;
                        }

                        $kinerjaProgram['render_status'] = $render;

                        return $kinerjaProgram;
                    });

                    // jika ada children yang punya bayangan, maka render tapi di hide
                    $render = $sasaranStrategisPd['kinerja_program']->contains(fn ($kinerjaProgram) => $kinerjaProgram['render_status'] != false)
                        ? 'hide'
                        : false;
                }

                $sasaranStrategisPd['render_status'] = $render;

                return $sasaranStrategisPd;
            });

            return $sasaranStrategisRpjmd;
        });
    }
}
