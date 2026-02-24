<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Ekinerja{
/**
 * 
 *
 * @property int $id
 * @property int|null $bulan
 * @property int|null $iki_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $validasi
 * @property string|null $realisasi
 * @property string|null $satuan_realisasi
 * @property string|null $attachment_lapor
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $nip18
 * @property float|null $target
 * @property int|null $id_iku
 * @property bool|null $is_pembalik
 * @property string|null $satuan_target
 * @property string|null $indikator_bulanan
 * @property int|null $indikator_tahunan
 * @property string|null $attachment
 * @property string|null $kojab
 * @property string|null $kolok
 * @property string|null $sasaran
 * @property int|null $status
 * @property int|null $sasaran_kerja_id
 * @property int|null $sasaran_kinerja_id
 * @property string|null $link
 * @property bool|null $wajib_youtube
 * @property string|null $ringkasan
 * @property int|null $nilai_substansi
 * @property int|null $nilai_kesesuaian
 * @property int|null $nilai_sistematika
 * @property string|null $keterangan_substansi
 * @property string|null $keterangan_kesesuaian
 * @property string|null $keterangan_sistematika
 * @property string|null $kesimpulan
 * @property string|null $perspektif
 * @property-read \App\Models\Ekinerja\SasaranKinerja|null $sasaranKinerja
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan query()
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereAttachmentLapor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereIdIku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereIkiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereIndikatorBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereIndikatorTahunan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereIsPembalik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereKesimpulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereKeteranganKesesuaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereKeteranganSistematika($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereKeteranganSubstansi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereKojab($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereKolok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereNilaiKesesuaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereNilaiSistematika($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereNilaiSubstansi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereNip18($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan wherePerspektif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereRealisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereRingkasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereSasaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereSasaranKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereSasaranKinerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereSatuanRealisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereSatuanTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereValidasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan whereWajibYoutube($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|IkiBulanan withoutTrashed()
 */
	class IkiBulanan extends \Eloquent {}
}

namespace App\Models\Ekinerja{
/**
 * 
 *
 * @property int $id
 * @property int|null $id_old_skp
 * @property string $nip18
 * @property string $nip_atasan
 * @property string $nip_atasan_sekarang
 * @property string|null $nip_anakin
 * @property int|null $kolok
 * @property int|null $kolok_atasan
 * @property string $rencana_kinerja
 * @property int|null $id_parent_rencana_kinerja
 * @property int|null $id_rencana_kinerja_perspektif
 * @property string|null $target_tahunan
 * @property string|null $target_tahunan_akhir
 * @property string|null $target_tahunan_satuan
 * @property string|null $target_tahunan_keterangan
 * @property string|null $target_tahunan_indikator
 * @property string|null $target_kualitas
 * @property string|null $target_kualitas_akhir
 * @property string|null $target_kualitas_satuan
 * @property string|null $target_kualitas_keterangan
 * @property string|null $target_kualitas_indikator
 * @property int|null $target_waktu
 * @property int|null $target_waktu_akhir
 * @property string|null $target_waktu_satuan
 * @property string|null $target_waktu_keterangan
 * @property string|null $target_waktu_indikator
 * @property float|null $target_biaya
 * @property float|null $target_biaya_akhir
 * @property string|null $target_biaya_keterangan
 * @property string|null $target_biaya_indikator
 * @property string $tanggal_penetapan
 * @property int $status_kinerja
 * @property int $editable
 * @property int $revisi_ke
 * @property string|null $revisi_at
 * @property string|null $validated_at
 * @property string|null $verification_at
 * @property string|null $rencana_kinerja_catatan_atasan
 * @property string|null $rencana_kinerja_catatan_anakin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $kojab
 * @property string|null $kojab_atasan
 * @property int|null $sakip_id
 * @property string|null $sakip_type
 * @property string|null $ekspektasi_kuantitas
 * @property string|null $ekspektasi_kualitas
 * @property string|null $ekspektasi_waktu
 * @property string|null $target_anggaran
 * @property string|null $target_anggaran_akhir
 * @property string|null $target_anggaran_satuan
 * @property string|null $target_anggaran_keterangan
 * @property string|null $target_anggaran_indikator
 * @property string|null $pengampu
 * @property string|null $perspektif
 * @property string|null $no_sk
 * @property string|null $file_sk
 * @property int|null $tim_kerja_id
 * @property string|null $klasifikasi_tim
 * @property string|null $sub_klasifikasi_tim
 * @property string|null $jenis_tim
 * @property string|null $keterangan_banding
 * @property int $tambah_anggota
 * @property-read \App\Models\SKP|null $skp
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja query()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereEditable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereEkspektasiKualitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereEkspektasiKuantitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereEkspektasiWaktu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereFileSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereIdOldSkp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereIdParentRencanaKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereIdRencanaKinerjaPerspektif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereJenisTim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereKeteranganBanding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereKlasifikasiTim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereKojab($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereKojabAtasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereKolok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereKolokAtasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereNip18($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereNipAnakin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereNipAtasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereNipAtasanSekarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereNoSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja wherePengampu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja wherePerspektif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereRencanaKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereRencanaKinerjaCatatanAnakin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereRencanaKinerjaCatatanAtasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereRevisiAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereRevisiKe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereSakipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereSakipType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereStatusKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereSubKlasifikasiTim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTambahAnggota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTanggalPenetapan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetAnggaranAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetAnggaranIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetAnggaranKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetAnggaranSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetBiaya($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetBiayaAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetBiayaIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetBiayaKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetKualitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetKualitasAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetKualitasIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetKualitasKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetKualitasSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetTahunan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetTahunanAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetTahunanIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetTahunanKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetTahunanSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetWaktu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetWaktuAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetWaktuIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetWaktuKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTargetWaktuSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereTimKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereValidatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja whereVerificationAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranKinerja withoutTrashed()
 */
	class SasaranKinerja extends \Eloquent {}
}

namespace App\Models\Ekinerja{
/**
 * 
 *
 * @property int $id
 * @property string $nama
 * @property string $satuan_kerja_id
 * @property string|null $v_struktur_organisasi_id
 * @property string $nip_ketua
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Ekinerja\VPegawaiData|null $ketua
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja query()
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja whereNipKetua($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja whereVStrukturOrganisasiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TimKerja withoutTrashed()
 */
	class TimKerja extends \Eloquent {}
}

namespace App\Models\Ekinerja{
/**
 * 
 *
 * @property string $peg_id
 * @property string|null $last_update
 * @property string|null $peg_nip
 * @property string|null $peg_nip_lama
 * @property string $peg_nama
 * @property string $peg_nama_lengkap
 * @property string|null $peg_lahir_tanggal
 * @property int|null $peg_usia
 * @property string|null $peg_jenis_kelamin
 * @property string|null $peg_rumah_alamat
 * @property string|null $peg_kodepos
 * @property string|null $peg_foto_url
 * @property bool|null $peg_status
 * @property string|null $peg_ketstatus
 * @property string|null $peg_ktp
 * @property int|null $peg_umur_pensiun
 * @property string|null $peg_jabatan_tmt
 * @property string|null $peg_eselon_tmt
 * @property string|null $peg_skpd_tmt
 * @property string|null $peg_npwp
 * @property string|null $peg_telp
 * @property string|null $peg_telp_hp
 * @property int|null $id_agama
 * @property string|null $nm_agama
 * @property int|null $jabatan_id
 * @property int|null $jf_id
 * @property int|null $jfu_id
 * @property int|null $jabatan_jenis
 * @property string|null $jabatan_nama
 * @property int|null $eselon_id
 * @property string|null $eselon_nm
 * @property string|null $unit_kerja_id
 * @property string|null $unit_kerja_nama
 * @property string|null $satuan_kerja_id
 * @property string|null $satuan_kerja_nama
 * @property int|null $gol_id_awal
 * @property string|null $nm_gol_awal
 * @property string|null $nm_pkt_awal
 * @property int|null $gol_id_akhir
 * @property string|null $nm_gol_akhir
 * @property string|null $nm_pkt_akhir
 * @property int|null $id_pend_awal
 * @property string|null $nm_pend_awal
 * @property string|null $kat_nama_pend_awal
 * @property string|null $nm_tingpend_awal
 * @property int|null $id_pend_akhir
 * @property string|null $nm_pend_akhir
 * @property string|null $kat_nama_pend_akhir
 * @property string|null $nm_tingpend_akhir
 * @property string|null $riwayat_pendidikan
 * @property string|null $riwayat_jabatan
 * @property string|null $unit_kerja_parent_nama
 * @property int|null $kedudukan_pegawai
 * @property string|null $kedudukan_pns
 * @property string|null $kode_jabatan
 * @property string|null $peg_tmt_pensiun
 * @property int|null $masa_kerja_tahun
 * @property int|null $masa_kerja_bulan
 * @property int|null $masa_kerja_golongan_tahun
 * @property int|null $masa_kerja_golongan_bulan
 * @property int|null $peg_status_kepegawaian_id
 * @property string|null $peg_status_kepegawaian
 * @property int|null $tugas_tambahan_jabatan_id
 * @property string|null $tugas_tambahan_jenis
 * @property string|null $daftar_spesialisasi
 * @property string|null $tugas_tambahan_jabatan_nama
 * @property string|null $tmt_tugas_tambahan
 * @property string|null $peg_instansi_dpk
 * @property string|null $daftar_spesialisasi_nama
 * @property int|null $peg_jenis_pns
 * @property string|null $jf_nama
 * @property string|null $jfu_nama
 * @property string|null $peg_gol_akhir_tmt
 * @property string|null $peg_ketstatus_tgl
 * @property string|null $peg_cpns_tmt
 * @property string|null $peg_pns_tmt
 * @property string|null $nuptk
 * @property int|null $id_goldar
 * @property string|null $nm_goldar
 * @property string|null $peg_email
 * @property string|null $peg_bapertarum
 * @property string|null $peg_no_askes
 * @property int|null $id_status_kepegawaian
 * @property string|null $peg_lahir_tempat
 * @property int|null $peg_status_perkawinan
 * @property int|null $unit_kerja_level
 * @property string|null $unit_kerja_parent
 * @property string|null $peg_kel_desa
 * @property string|null $kecamatan_nm
 * @property string|null $jabatan_kelas
 * @property string|null $peg_karpeg
 * @property string|null $peg_pend_akhir_th
 * @property string|null $peg_nama_jenis_pns
 * @property string|null $peg_jft_tmt
 * @property int|null $id_kota
 * @property int|null $id_kec
 * @property int|null $id_kel
 * @property int|null $id_provinsi
 * @property string|null $nama_kel
 * @property string|null $nama_kec
 * @property string|null $nama_kota
 * @property string|null $nama_provinsi
 * @property int|null $tugas_tambahan2_jabatan_id
 * @property string|null $tugas_tambahan2_jenis
 * @property string|null $tugas_tambahan2_jabatan_nama
 * @property string|null $tmt_tugas_tambahan2
 * @property float|null $peg_alamat_latitude
 * @property float|null $peg_alamat_longitude
 * @property bool|null $is_gtk
 * @property string|null $peg_alamat_rt
 * @property string|null $peg_alamat_rw
 * @property string|null $peg_alamat_rt_ktp
 * @property string|null $peg_alamat_rw_ktp
 * @property string|null $peg_rumah_alamat_ktp
 * @property string|null $peg_kodepos_ktp
 * @property string|null $nama_kel_ktp
 * @property string|null $nama_kec_ktp
 * @property string|null $nama_kota_ktp
 * @property string|null $nama_provinsi_ktp
 * @property string|null $nip_atasan
 * @property string|null $nama_atasan
 * @property string|null $nip_atasan_bayangan
 * @property string|null $nama_atasan_bayangan
 * @property string|null $peg_kgb_yad_akhir
 * @property string|null $unit_kerja_nama_full
 * @property string|null $organisasi_nama_alias
 * @property string|null $organisasi_id
 * @property string|null $peg_no_rekening
 * @property int|null $peg_masa_kerja_golongan
 * @property int|null $peg_jumlah_anak
 * @property int|null $peg_jumlah_anak_tunjangan
 * @property int|null $peg_jumlah_pasangan_tunjangan
 * @property string|null $peg_email_resmi
 * @property string|null $peg_tmt_kgb
 * @property bool|null $is_nakes
 * @property string|null $no_sk_pns
 * @property string|null $no_sk_cpns
 * @property bool|null $is_calon_jft
 * @property string|null $prajab_tgl_sttp
 * @property string|null $prajab_no_sttp
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData aktif()
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData query()
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereDaftarSpesialisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereDaftarSpesialisasiNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereEselonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereEselonNm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereGolIdAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereGolIdAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereIdAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereIdGoldar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereIdKec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereIdKel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereIdKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereIdPendAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereIdPendAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereIdProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereIdStatusKepegawaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereIsCalonJft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereIsGtk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereIsNakes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereJabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereJabatanJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereJabatanKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereJabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereJfId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereJfNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereJfuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereJfuNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereKatNamaPendAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereKatNamaPendAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereKecamatanNm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereKedudukanPegawai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereKedudukanPns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereKodeJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereLastUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereMasaKerjaBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereMasaKerjaGolonganBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereMasaKerjaGolonganTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereMasaKerjaTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNamaAtasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNamaAtasanBayangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNamaKec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNamaKecKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNamaKel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNamaKelKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNamaKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNamaKotaKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNamaProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNamaProvinsiKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNipAtasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNipAtasanBayangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNmAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNmGolAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNmGolAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNmGoldar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNmPendAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNmPendAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNmPktAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNmPktAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNmTingpendAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNmTingpendAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNoSkCpns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNoSkPns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereNuptk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereOrganisasiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereOrganisasiNamaAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegAlamatLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegAlamatLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegAlamatRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegAlamatRtKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegAlamatRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegAlamatRwKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegBapertarum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegCpnsTmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegEmailResmi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegEselonTmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegFotoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegGolAkhirTmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegInstansiDpk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegJabatanTmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegJenisPns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegJftTmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegJumlahAnak($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegJumlahAnakTunjangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegJumlahPasanganTunjangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegKarpeg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegKelDesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegKetstatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegKetstatusTgl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegKgbYadAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegKodepos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegKodeposKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegLahirTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegLahirTempat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegMasaKerjaGolongan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegNamaJenisPns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegNipLama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegNoAskes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegNoRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegNpwp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegPendAkhirTh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegPnsTmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegRumahAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegRumahAlamatKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegSkpdTmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegStatusKepegawaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegStatusKepegawaianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegStatusPerkawinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegTelp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegTelpHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegTmtKgb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegTmtPensiun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegUmurPensiun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePegUsia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePrajabNoSttp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData wherePrajabTglSttp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereRiwayatJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereRiwayatPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereSatuanKerjaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereTmtTugasTambahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereTmtTugasTambahan2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereTugasTambahan2JabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereTugasTambahan2JabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereTugasTambahan2Jenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereTugasTambahanJabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereTugasTambahanJabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereTugasTambahanJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereUnitKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereUnitKerjaLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereUnitKerjaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereUnitKerjaNamaFull($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereUnitKerjaParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VPegawaiData whereUnitKerjaParentNama($value)
 */
	class VPegawaiData extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $nomor
 * @property string $indikator
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $tahun_mulai
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SasaranStrategisPd> $sasaranStrategisPd
 * @property-read int|null $sasaran_strategis_pd_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SasaranStrategisRpjmd> $sasaranStrategisRpjmd
 * @property-read int|null $sasaran_strategis_rpjmd_count
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSasaranStrategis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSasaranStrategis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSasaranStrategis query()
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSasaranStrategis tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSasaranStrategis tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSasaranStrategis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSasaranStrategis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSasaranStrategis whereIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSasaranStrategis whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSasaranStrategis whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSasaranStrategis whereUpdatedAt($value)
 */
	class IndikatorSasaranStrategis extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $sub_kegiatan_id
 * @property string $indikator
 * @property string|null $target
 * @property string|null $satuan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSubKegiatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSubKegiatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSubKegiatan query()
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSubKegiatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSubKegiatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSubKegiatan whereIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSubKegiatan whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSubKegiatan whereSubKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSubKegiatan whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorSubKegiatan whereUpdatedAt($value)
 */
	class IndikatorSubKegiatan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $nomor
 * @property string $indikator
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $tahun_mulai
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorTujuan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorTujuan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorTujuan query()
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorTujuan tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorTujuan tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorTujuan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorTujuan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorTujuan whereIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorTujuan whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorTujuan whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndikatorTujuan whereUpdatedAt($value)
 */
	class IndikatorTujuan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tahun_mulai
 * @property int $tahun_kinerja
 * @property int $satuan_kerja_id
 * @property string $model_class
 * @property int $model_id
 * @property string|null $pengampu
 * @property int|null $jabatan_id
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $kamusIndikatorFungsional
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional query()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional whereJabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional whereModelClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional wherePengampu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsional whereUpdatedAt($value)
 */
	class KamusIndikatorFungsional extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tahun_kinerja
 * @property int $satuan_kerja_id
 * @property string $jenis_indikator
 * @property int $sasaran_id
 * @property string $indikator
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KamusIndikatorFungsionalSasaran $sasaran
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual query()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual whereIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual whereJenisIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual whereSasaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalManual whereUpdatedAt($value)
 */
	class KamusIndikatorFungsionalManual extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tahun_kinerja
 * @property int $satuan_kerja_id
 * @property string $pengampu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KamusIndikatorFungsionalPengampuJF> $jf
 * @property-read int|null $jf_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KamusIndikatorFungsionalSasaran> $sasaran
 * @property-read int|null $sasaran_count
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampu query()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampu roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampu tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampu tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampu wherePengampu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampu whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampu whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampu whereUpdatedAt($value)
 */
	class KamusIndikatorFungsionalPengampu extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tahun_kinerja
 * @property int $satuan_kerja_id
 * @property int $pengampu_id
 * @property int $jabatan_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Simpeg\Jabatan|null $jabatan
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampuJF newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampuJF newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampuJF query()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampuJF roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampuJF tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampuJF tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampuJF whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampuJF whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampuJF whereJabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampuJF wherePengampuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampuJF whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampuJF whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalPengampuJF whereUpdatedAt($value)
 */
	class KamusIndikatorFungsionalPengampuJF extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tahun_kinerja
 * @property int $satuan_kerja_id
 * @property int $pengampu_id
 * @property string $sasaran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KamusIndikatorFungsionalPengampu $pengampu
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalSasaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalSasaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalSasaran query()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalSasaran roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalSasaran tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalSasaran tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalSasaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalSasaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalSasaran wherePengampuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalSasaran whereSasaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalSasaran whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalSasaran whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorFungsionalSasaran whereUpdatedAt($value)
 */
	class KamusIndikatorFungsionalSasaran extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tahun_kinerja
 * @property int $satuan_kerja_id
 * @property bool $is_validasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorValidasiBappeda newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorValidasiBappeda newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorValidasiBappeda query()
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorValidasiBappeda whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorValidasiBappeda whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorValidasiBappeda whereIsValidasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorValidasiBappeda whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorValidasiBappeda whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamusIndikatorValidasiBappeda whereUpdatedAt($value)
 */
	class KamusIndikatorValidasiBappeda extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property int $anggaran
 * @property int $program_id
 * @property int $satuan_kerja_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $tahun_kinerja
 * @property-read \App\Models\Program|null $program
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan whereAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan whereProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kegiatan whereUpdatedAt($value)
 */
	class Kegiatan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property string $sasaran
 * @property string $indikator
 * @property string $satuan
 * @property int $tahun_mulai
 * @property string $target_baseline
 * @property string $target_1
 * @property string $target_2
 * @property string $target_3
 * @property string $target_4
 * @property string $target_5
 * @property string|null $realisasi_baseline
 * @property string|null $realisasi_1
 * @property string|null $realisasi_2
 * @property string|null $realisasi_3
 * @property string|null $realisasi_4
 * @property string|null $realisasi_5
 * @property float|null $capaian_baseline
 * @property float|null $capaian_1
 * @property float|null $capaian_2
 * @property float|null $capaian_3
 * @property float|null $capaian_4
 * @property float|null $capaian_5
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\IndikatorTujuan|null $indikatorTujuan
 * @property-read \App\Models\Misi|null $misi
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SasaranStrategisPd> $sasaranStrategisPd
 * @property-read int|null $sasaran_strategis_pd_count
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @property-read \App\Models\Tujuan|null $tujuan
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan query()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereCapaian1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereCapaian2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereCapaian3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereCapaian4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereCapaian5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereCapaianBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereRealisasi1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereRealisasi2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereRealisasi3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereRealisasi4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereRealisasi5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereRealisasiBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereSasaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereTarget1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereTarget2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereTarget3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereTarget4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereTarget5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereTargetBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaBayangan whereUpdatedAt($value)
 */
	class KinerjaBayangan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property int $program_id
 * @property string $sasaran_program
 * @property int|null $kegiatan_id
 * @property string|null $sasaran
 * @property string $indikator
 * @property string $satuan
 * @property float $target
 * @property array $target_bulanan
 * @property array $realisasi_bulanan
 * @property int $anggaran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array $anggaran_bulanan
 * @property array $realisasi_anggaran_bulanan
 * @property float|null $realisasi
 * @property float|null $capaian
 * @property int $realisasi_anggaran
 * @property float|null $capaian_anggaran
 * @property int|null $sasaran_strategis_rpjmd_id
 * @property int|null $kinerja_program_id
 * @property int|null $sasaran_strategis_pd_id
 * @property int|null $tahun_kinerja
 * @property string|null $v_struktur_organisasi_id
 * @property string|null $pengampu
 * @property int|null $tim_kerja_id
 * @property string|null $penyebab_kegagalan
 * @property int|null $kinerja_bayangan_id
 * @property bool $is_kemiskinan
 * @property bool $is_sekretariat
 * @property bool $is_stunting
 * @property bool $is_inflasi
 * @property bool $is_investasi
 * @property bool $is_penggunaan_pdn
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KamusIndikatorFungsional> $kamusIndikatorFungsional
 * @property-read int|null $kamus_indikator_fungsional_count
 * @property-read \App\Models\Kegiatan|null $kegiatan
 * @property-read \App\Models\KinerjaBayangan|null $kinerjaBayangan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaKegiatanCross> $kinerjaKegiatanCross
 * @property-read int|null $kinerja_kegiatan_cross_count
 * @property-read \App\Models\KinerjaProgram|null $kinerjaProgram
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaSubKegiatan> $kinerjaSubKegiatan
 * @property-read int|null $kinerja_sub_kegiatan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaSubKegiatanCross> $kinerjaSubKegiatanCross
 * @property-read int|null $kinerja_sub_kegiatan_cross_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaTercapai> $kinerjaTercapai
 * @property-read int|null $kinerja_tercapai_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaTidakTercapai> $kinerjaTidakTercapai
 * @property-read int|null $kinerja_tidak_tercapai_count
 * @property-read \App\Models\Program|null $program
 * @property-read \App\Models\RiwayatValidasiSKP|null $riwayatSkpRejectedLatest
 * @property-read \App\Models\RiwayatValidasiSKP|null $riwayatSkpValidatedLatest
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RiwayatValidasiSKP> $riwayatValidasiSkp
 * @property-read int|null $riwayat_validasi_skp_count
 * @property-read \App\Models\SasaranStrategisPd|null $sasaranStrategisPd
 * @property-read \App\Models\SasaranStrategisRpjmd|null $sasaranStrategisRpjmd
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SKP> $skp
 * @property-read int|null $skp_count
 * @property-read \App\Models\SolusiKinerja|null $solusi
 * @property-read \App\Models\VStrukturOrganisasi|null $strukturOrganisasi
 * @property-read \App\Models\Ekinerja\TimKerja|null $timKerja
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan query()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereAnggaranBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereCapaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereCapaianAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereIsInflasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereIsInvestasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereIsKemiskinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereIsPenggunaanPdn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereIsSekretariat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereIsStunting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereKinerjaBayanganId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereKinerjaProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan wherePengampu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan wherePenyebabKegagalan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereRealisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereRealisasiAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereRealisasiAnggaranBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereRealisasiBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereSasaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereSasaranProgram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereSasaranStrategisPdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereSasaranStrategisRpjmdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereTargetBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereTimKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatan whereVStrukturOrganisasiId($value)
 */
	class KinerjaKegiatan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $kinerja_program_id
 * @property int $kinerja_kegiatan_id
 * @property-read \App\Models\KinerjaKegiatan $kinerjaKegiatan
 * @property-read \App\Models\KinerjaProgram $kinerjaProgram
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatanCross newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatanCross newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatanCross query()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatanCross whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatanCross whereKinerjaKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaKegiatanCross whereKinerjaProgramId($value)
 */
	class KinerjaKegiatanCross extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property int $sub_kegiatan_id
 * @property string $langkah_aksi
 * @property string|null $sasaran
 * @property string $indikator
 * @property string $satuan
 * @property string $target
 * @property array $target_bulanan
 * @property int $anggaran
 * @property array $anggaran_bulanan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array|null $realisasi_bulanan
 * @property array $realisasi_anggaran_bulanan
 * @property string|null $realisasi
 * @property float|null $capaian
 * @property int $realisasi_anggaran
 * @property float|null $capaian_anggaran
 * @property int|null $sasaran_strategis_rpjmd_id
 * @property int|null $sasaran_strategis_pd_id
 * @property int|null $kinerja_program_id
 * @property int|null $kinerja_kegiatan_id
 * @property int|null $kinerja_sub_kegiatan_id
 * @property int|null $tahun_kinerja
 * @property int|null $penyebab_kegagalan_id
 * @property string|null $penyebab_kegagalan
 * @property-read \App\Models\KinerjaKegiatan|null $kinerjaKegiatan
 * @property-read \App\Models\KinerjaProgram|null $kinerjaProgram
 * @property-read \App\Models\KinerjaSubKegiatan|null $kinerjaSubKegiatan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaTidakTercapai> $kinerjaTidakTercapai
 * @property-read int|null $kinerja_tidak_tercapai_count
 * @property-read \App\Models\SasaranStrategisPd|null $sasaranStrategisPd
 * @property-read \App\Models\SasaranStrategisRpjmd|null $sasaranStrategisRpjmd
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @property-read \App\Models\SolusiKinerja|null $solusi
 * @property-read \App\Models\SubKegiatan|null $subKegiatan
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi query()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereAnggaranBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereCapaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereCapaianAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereKinerjaKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereKinerjaProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereKinerjaSubKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereLangkahAksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi wherePenyebabKegagalan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi wherePenyebabKegagalanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereRealisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereRealisasiAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereRealisasiAnggaranBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereRealisasiBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereSasaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereSasaranStrategisPdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereSasaranStrategisRpjmdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereSubKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereTargetBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaLangkahAksi whereUpdatedAt($value)
 */
	class KinerjaLangkahAksi extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property int $sasaran_strategis_pd_id
 * @property int $satker_iku_id
 * @property int $program_id
 * @property string|null $sasaran
 * @property string $indikator
 * @property string $satuan
 * @property string $target
 * @property array $target_bulanan
 * @property array $realisasi_bulanan
 * @property int $anggaran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array $anggaran_bulanan
 * @property array $realisasi_anggaran_bulanan
 * @property string|null $realisasi
 * @property float|null $capaian
 * @property int $realisasi_anggaran
 * @property float|null $capaian_anggaran
 * @property int|null $tahun_kinerja
 * @property string|null $v_struktur_organisasi_id
 * @property string|null $penyebab_kegagalan
 * @property int|null $kinerja_bayangan_id
 * @property string|null $pengampu
 * @property int|null $tim_kerja_id
 * @property bool $is_kemiskinan
 * @property int $order
 * @property bool $is_sekretariat
 * @property bool $is_stunting
 * @property bool $is_inflasi
 * @property bool $is_investasi
 * @property bool $is_penggunaan_pdn
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KamusIndikatorFungsional> $kamusIndikatorFungsional
 * @property-read int|null $kamus_indikator_fungsional_count
 * @property-read \App\Models\KinerjaBayangan|null $kinerjaBayangan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaKegiatan> $kinerjaKegiatan
 * @property-read int|null $kinerja_kegiatan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaKegiatanCross> $kinerjaKegiatanCross
 * @property-read int|null $kinerja_kegiatan_cross_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaProgramCross> $kinerjaProgramCross
 * @property-read int|null $kinerja_program_cross_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaTercapai> $kinerjaTercapai
 * @property-read int|null $kinerja_tercapai_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaTidakTercapai> $kinerjaTidakTercapai
 * @property-read int|null $kinerja_tidak_tercapai_count
 * @property-read \App\Models\Program|null $program
 * @property-read \App\Models\RiwayatValidasiSKP|null $riwayatSkpRejectedLatest
 * @property-read \App\Models\RiwayatValidasiSKP|null $riwayatSkpValidatedLatest
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RiwayatValidasiSKP> $riwayatValidasiSkp
 * @property-read int|null $riwayat_validasi_skp_count
 * @property-read \App\Models\SasaranStrategisPd|null $sasaranStrategisPd
 * @property-read \App\Models\SasaranStrategisPd|null $satkerIku
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SKP> $skp
 * @property-read int|null $skp_count
 * @property-read \App\Models\SolusiKinerja|null $solusi
 * @property-read \App\Models\VStrukturOrganisasi|null $strukturOrganisasi
 * @property-read \App\Models\Ekinerja\TimKerja|null $timKerja
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram query()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereAnggaranBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereCapaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereCapaianAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereIsInflasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereIsInvestasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereIsKemiskinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereIsPenggunaanPdn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereIsSekretariat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereIsStunting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereKinerjaBayanganId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram wherePengampu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram wherePenyebabKegagalan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereRealisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereRealisasiAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereRealisasiAnggaranBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereRealisasiBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereSasaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereSasaranStrategisPdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereSatkerIkuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereTargetBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereTimKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgram whereVStrukturOrganisasiId($value)
 */
	class KinerjaProgram extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $sasaran_strategis_pd_id
 * @property int $kinerja_program_id
 * @property-read \App\Models\KinerjaProgram $kinerjaProgram
 * @property-read \App\Models\SasaranStrategisPd $sasaranStrategisPd
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgramCross newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgramCross newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgramCross query()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgramCross whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgramCross whereKinerjaProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaProgramCross whereSasaranStrategisPdId($value)
 */
	class KinerjaProgramCross extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property int $kegiatan_id
 * @property int|null $sub_kegiatan_id
 * @property string|null $sasaran
 * @property string $indikator
 * @property string $satuan
 * @property float $target
 * @property array $target_bulanan
 * @property float $realisasi
 * @property int $anggaran
 * @property array $anggaran_bulanan
 * @property bool $has_inovasi
 * @property string|null $inovasi_uraian
 * @property string|null $inovasi_tujuan
 * @property array|null $inovasi_lampiran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array $realisasi_bulanan
 * @property array $realisasi_anggaran_bulanan
 * @property float|null $capaian
 * @property int $realisasi_anggaran
 * @property float|null $capaian_anggaran
 * @property int|null $sasaran_strategis_rpjmd_id
 * @property int|null $kinerja_program_id
 * @property int|null $kinerja_kegiatan_id
 * @property int|null $sasaran_strategis_pd_id
 * @property int|null $tahun_kinerja
 * @property string|null $v_struktur_organisasi_id
 * @property string|null $pengampu
 * @property int|null $tim_kerja_id
 * @property string|null $penyebab_kegagalan
 * @property string|null $indikator_kemendagri
 * @property bool $is_kemiskinan
 * @property bool $is_sekretariat
 * @property bool $is_stunting
 * @property bool $is_inflasi
 * @property bool $is_investasi
 * @property bool $is_penggunaan_pdn
 * @property string|null $rencana_aksi
 * @property bool $is_external
 * @property bool $is_rencana_aksi_gubernur
 * @property array $eviden_bulanan
 * @property array $validasi_bulanan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KamusIndikatorFungsional> $kamusIndikatorFungsional
 * @property-read int|null $kamus_indikator_fungsional_count
 * @property-read \App\Models\Kegiatan|null $kegiatan
 * @property-read \App\Models\KinerjaKegiatan|null $kinerjaKegiatan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaLangkahAksi> $kinerjaLangkahAksi
 * @property-read int|null $kinerja_langkah_aksi_count
 * @property-read \App\Models\KinerjaProgram|null $kinerjaProgram
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaSubKegiatanCross> $kinerjaSubKegiatanCross
 * @property-read int|null $kinerja_sub_kegiatan_cross_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaTercapai> $kinerjaTercapai
 * @property-read int|null $kinerja_tercapai_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaTidakTercapai> $kinerjaTidakTercapai
 * @property-read int|null $kinerja_tidak_tercapai_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PenyebabKegagalan> $penyebabKegagalan
 * @property-read int|null $penyebab_kegagalan_count
 * @property-read \App\Models\RiwayatValidasiSKP|null $riwayatSkpRejectedLatest
 * @property-read \App\Models\RiwayatValidasiSKP|null $riwayatSkpValidatedLatest
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RiwayatValidasiSKP> $riwayatValidasiSkp
 * @property-read int|null $riwayat_validasi_skp_count
 * @property-read \App\Models\SasaranStrategisPd|null $sasaranStrategisPd
 * @property-read \App\Models\SasaranStrategisRpjmd|null $sasaranStrategisRpjmd
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SKP> $skp
 * @property-read int|null $skp_count
 * @property-read \App\Models\SolusiKinerja|null $solusi
 * @property-read \App\Models\VStrukturOrganisasi|null $strukturOrganisasi
 * @property-read \App\Models\SubKegiatan|null $subKegiatan
 * @property-read \App\Models\Ekinerja\TimKerja|null $timKerja
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan query()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan targetBulanan(string $bulan)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan targetBulananTriwulan(int $triwulan)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereAnggaranBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereCapaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereCapaianAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereEvidenBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereHasInovasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereIndikatorKemendagri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereInovasiLampiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereInovasiTujuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereInovasiUraian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereIsExternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereIsInflasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereIsInvestasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereIsKemiskinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereIsPenggunaanPdn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereIsRencanaAksiGubernur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereIsSekretariat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereIsStunting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereKinerjaKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereKinerjaProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan wherePengampu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan wherePenyebabKegagalan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereRealisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereRealisasiAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereRealisasiAnggaranBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereRealisasiBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereRencanaAksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereSasaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereSasaranStrategisPdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereSasaranStrategisRpjmdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereSubKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereTargetBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereTimKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereVStrukturOrganisasiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatan whereValidasiBulanan($value)
 */
	class KinerjaSubKegiatan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $kinerja_kegiatan_id
 * @property int $kinerja_sub_kegiatan_id
 * @property-read \App\Models\KinerjaKegiatan $kinerjaKegiatan
 * @property-read \App\Models\KinerjaSubKegiatan $kinerjaSubKegiatan
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatanCross newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatanCross newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatanCross query()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatanCross whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatanCross whereKinerjaKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaSubKegiatanCross whereKinerjaSubKegiatanId($value)
 */
	class KinerjaSubKegiatanCross extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tahun_kinerja
 * @property string $notable_type
 * @property int $notable_id
 * @property string $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notable
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTercapai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTercapai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTercapai query()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTercapai tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTercapai tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTercapai whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTercapai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTercapai whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTercapai whereNotableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTercapai whereNotableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTercapai whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTercapai whereUpdatedAt($value)
 */
	class KinerjaTercapai extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tahun_kinerja
 * @property string $notable_type
 * @property int $notable_id
 * @property string $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notable
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTidakTercapai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTidakTercapai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTidakTercapai query()
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTidakTercapai tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTidakTercapai tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTidakTercapai whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTidakTercapai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTidakTercapai whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTidakTercapai whereNotableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTidakTercapai whereNotableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTidakTercapai whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KinerjaTidakTercapai whereUpdatedAt($value)
 */
	class KinerjaTidakTercapai extends \Eloquent {}
}

namespace App\Models\LKE{
/**
 * 
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property int $tahun_kinerja
 * @property int $user_id
 * @property array|null $catatan
 * @property array|null $rekomendasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi query()
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi whereRekomendasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatatanRekomendasi whereUserId($value)
 */
	class CatatanRekomendasi extends \Eloquent {}
}

namespace App\Models\LKE{
/**
 * 
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property int $tahun_kinerja
 * @property int $kriteria_id
 * @property int|null $parameter_id
 * @property array|null $eviden
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LKE\Kriteria $kriteria
 * @property-read \App\Models\LKE\Parameter|null $parameter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LKE\RiwayatEviden> $riwayat
 * @property-read int|null $riwayat_count
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden query()
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden whereEviden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden whereKriteriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden whereParameterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eviden whereUpdatedAt($value)
 */
	class Eviden extends \Eloquent {}
}

namespace App\Models\LKE{
/**
 * 
 *
 * @property int $id
 * @property string $nama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $tahun_kinerja
 * @property int $nomor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LKE\SubKomponen> $subKomponen
 * @property-read int|null $sub_komponen_count
 * @method static \Illuminate\Database\Eloquent\Builder|Komponen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Komponen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Komponen query()
 * @method static \Illuminate\Database\Eloquent\Builder|Komponen tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Komponen tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Komponen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Komponen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Komponen whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Komponen whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Komponen whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Komponen whereUpdatedAt($value)
 */
	class Komponen extends \Eloquent {}
}

namespace App\Models\LKE{
/**
 * 
 *
 * @property int $id
 * @property int $sub_komponen_id
 * @property string $nama
 * @property float $bobot
 * @property bool $is_auto
 * @property bool $is_locked
 * @property int $jumlah_eviden
 * @property array $keterangan_eviden
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $nomor
 * @property string|null $nomor_full kolom ini sudah otomatis diisi oleh sistem
 * @property-read \App\Models\LKE\Eviden|null $eviden
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LKE\Parameter> $parameter
 * @property-read int|null $parameter_count
 * @property-read \App\Models\LKE\SubKomponen $subKomponen
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria whereBobot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria whereIsAuto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria whereIsLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria whereJumlahEviden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria whereKeteranganEviden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria whereNomorFull($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria whereSubKomponenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kriteria whereUpdatedAt($value)
 */
	class Kriteria extends \Eloquent {}
}

namespace App\Models\LKE{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property array|null $data
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUserId($value)
 */
	class Log extends \Eloquent {}
}

namespace App\Models\LKE{
/**
 * 
 *
 * @property int $id
 * @property int $kriteria_id
 * @property string $nama
 * @property float $skor
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $nomor
 * @method static \Illuminate\Database\Eloquent\Builder|Parameter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Parameter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Parameter query()
 * @method static \Illuminate\Database\Eloquent\Builder|Parameter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parameter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parameter whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parameter whereKriteriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parameter whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parameter whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parameter whereSkor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parameter whereUpdatedAt($value)
 */
	class Parameter extends \Eloquent {}
}

namespace App\Models\LKE{
/**
 * 
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property int $tahun_kinerja
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LKE\PenilaianKomponen> $penilaianKomponen
 * @property-read int|null $penilaian_komponen_count
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @method static \Illuminate\Database\Eloquent\Builder|Penilaian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Penilaian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Penilaian query()
 * @method static \Illuminate\Database\Eloquent\Builder|Penilaian roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Penilaian tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Penilaian tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Penilaian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penilaian whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penilaian whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penilaian whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penilaian whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penilaian whereUpdatedAt($value)
 */
	class Penilaian extends \Eloquent {}
}

namespace App\Models\LKE{
/**
 * 
 *
 * @property int $id
 * @property int $penilaian_id
 * @property int $komponen_id
 * @property float|null $nilai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianKomponen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianKomponen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianKomponen query()
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianKomponen roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianKomponen tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianKomponen tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianKomponen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianKomponen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianKomponen whereKomponenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianKomponen whereNilai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianKomponen wherePenilaianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianKomponen whereUpdatedAt($value)
 */
	class PenilaianKomponen extends \Eloquent {}
}

namespace App\Models\LKE{
/**
 * 
 *
 * @property int $id
 * @property int|null $penilaian_id
 * @property int $eviden_id
 * @property int|null $parameter_id
 * @property array|null $eviden
 * @property int|null $nilai
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool|null $status
 * @property-read \App\Models\LKE\Parameter|null $parameter
 * @property-read \App\Models\LKE\Parameter|null $parameterNilai
 * @property-read \App\Models\LKE\Penilaian|null $penilaian
 * @property-read \App\Models\LKE\Eviden $riwayat
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatEviden newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatEviden newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatEviden query()
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatEviden whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatEviden whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatEviden whereEviden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatEviden whereEvidenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatEviden whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatEviden whereNilai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatEviden whereParameterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatEviden wherePenilaianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatEviden whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatEviden whereUpdatedAt($value)
 */
	class RiwayatEviden extends \Eloquent {}
}

namespace App\Models\LKE{
/**
 * 
 *
 * @property int $id
 * @property int $komponen_id
 * @property string $nama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $nomor
 * @property-read \App\Models\LKE\Komponen $komponen
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LKE\Kriteria> $kriteria
 * @property-read int|null $kriteria_count
 * @method static \Illuminate\Database\Eloquent\Builder|SubKomponen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubKomponen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubKomponen query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubKomponen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKomponen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKomponen whereKomponenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKomponen whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKomponen whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKomponen whereUpdatedAt($value)
 */
	class SubKomponen extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property int $tahun_kinerja
 * @property int $sasaran_strategis_id
 * @property int $indikator_sasaran_strategis_id
 * @property string $sasaran_strategis_pd
 * @property string $iku_pd
 * @property string|null $narasi_1
 * @property string|null $narasi_2
 * @property string|null $narasi_3
 * @property string|null $narasi_4
 * @property string|null $narasi_5
 * @property string|null $narasi_6
 * @property string|null $narasi_7
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\IndikatorSasaranStrategis|null $indikatorSasaranStrategis
 * @property-read \App\Models\SasaranStrategis|null $sasaranStrategis
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD query()
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereIkuPd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereIndikatorSasaranStrategisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereNarasi1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereNarasi2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereNarasi3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereNarasi4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereNarasi5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereNarasi6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereNarasi7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereSasaranStrategisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereSasaranStrategisPd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPD whereUpdatedAt($value)
 */
	class LKIPNarasiPD extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property int $tahun_kinerja
 * @property int $sasaran_strategis_id
 * @property int $indikator_sasaran_strategis_id
 * @property string|null $narasi_1
 * @property string|null $narasi_2
 * @property string|null $narasi_3
 * @property string|null $narasi_4
 * @property string|null $narasi_5
 * @property string|null $narasi_6
 * @property string|null $narasi_7
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\IndikatorSasaranStrategis|null $indikatorSasaranStrategis
 * @property-read \App\Models\SasaranStrategis|null $sasaranStrategis
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda query()
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereIndikatorSasaranStrategisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereNarasi1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereNarasi2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereNarasi3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereNarasi4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereNarasi5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereNarasi6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereNarasi7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereSasaranStrategisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LKIPNarasiPemda whereUpdatedAt($value)
 */
	class LKIPNarasiPemda extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $nomor
 * @property string $misi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $tahun_mulai
 * @method static \Illuminate\Database\Eloquent\Builder|Misi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Misi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Misi query()
 * @method static \Illuminate\Database\Eloquent\Builder|Misi tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Misi tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Misi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Misi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Misi whereMisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Misi whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Misi whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Misi whereUpdatedAt($value)
 */
	class Misi extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tahun_kinerja
 * @property int $satuan_kerja_id
 * @property int $sasaran_strategis_pd_id
 * @property float $nilai
 * @property string|null $keterangan
 * @property string|null $eviden
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $nilai_id
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja query()
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja whereEviden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja whereNilai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja whereNilaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja whereSasaranStrategisPdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiJenjangKinerja whereUpdatedAt($value)
 */
	class NilaiJenjangKinerja extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $kinerja_sub_kegiatan_id
 * @property int $triwulan
 * @property string $penyebab
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KinerjaSubKegiatan|null $kinerjaSubKegiatan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaLangkahAksi> $langkahAksi
 * @property-read int|null $langkah_aksi_count
 * @method static \Illuminate\Database\Eloquent\Builder|PenyebabKegagalan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PenyebabKegagalan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PenyebabKegagalan query()
 * @method static \Illuminate\Database\Eloquent\Builder|PenyebabKegagalan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenyebabKegagalan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenyebabKegagalan whereKinerjaSubKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenyebabKegagalan wherePenyebab($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenyebabKegagalan whereTriwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenyebabKegagalan whereUpdatedAt($value)
 */
	class PenyebabKegagalan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tahun_kinerja
 * @property int $satuan_kerja_id
 * @property int $awal
 * @property int $akhir
 * @property int $perubahan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutput newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutput newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutput query()
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutput whereAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutput whereAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutput whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutput whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutput wherePerubahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutput whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutput whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutput whereUpdatedAt($value)
 */
	class PerubahanJumlahOutput extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tahun_kinerja
 * @property int $satuan_kerja_id
 * @property int $tw1
 * @property int $tw2
 * @property int $tw3
 * @property int $tw4
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 query()
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 whereTw1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 whereTw2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 whereTw3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 whereTw4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerubahanJumlahOutputV2 whereUpdatedAt($value)
 */
	class PerubahanJumlahOutputV2 extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property int $satuan_kerja_id
 * @property int $anggaran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $tahun_kinerja
 * @method static \Illuminate\Database\Eloquent\Builder|Program newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Program newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Program query()
 * @method static \Illuminate\Database\Eloquent\Builder|Program tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Program tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Program whereAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Program whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Program whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Program whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Program whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Program whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Program whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Program whereUpdatedAt($value)
 */
	class Program extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tahun_mulai
 * @property int $tahun_kinerja
 * @property int $satuan_kerja_id
 * @property string|null $pengampu
 * @property string|null $v_struktur_organisasi_id
 * @property int|null $tim_kerja_id
 * @property string $model_class
 * @property int $model_id
 * @property int $status status validasi = 0: ditolak, 1: diterima
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $riwayatValidasiSkp
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP query()
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP rejected()
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP validated()
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP whereModelClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP wherePengampu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP whereTimKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiwayatValidasiSKP whereVStrukturOrganisasiId($value)
 */
	class RiwayatValidasiSKP extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tahun_mulai
 * @property int $tahun_kinerja
 * @property int $satuan_kerja_id
 * @property string|null $pengampu
 * @property string|null $v_struktur_organisasi_id
 * @property int|null $tim_kerja_id
 * @property string $model_class
 * @property int $model_id
 * @property string $sasaran
 * @property string $indikator
 * @property string $target
 * @property string $satuan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $parent_id
 * @property bool $is_skp
 * @property-read SKP|null $parent
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $skp
 * @method static \Illuminate\Database\Eloquent\Builder|SKP newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SKP newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SKP query()
 * @method static \Illuminate\Database\Eloquent\Builder|SKP roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereIsSkp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereModelClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP wherePengampu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereSasaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereTimKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SKP whereVStrukturOrganisasiId($value)
 */
	class SKP extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $nomor
 * @property string $sasaran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $tahun_mulai
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SasaranStrategisRpjmd> $sasaranStrategisRpjmd
 * @property-read int|null $sasaran_strategis_rpjmd_count
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategis query()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategis tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategis tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategis whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategis whereSasaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategis whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategis whereUpdatedAt($value)
 */
	class SasaranStrategis extends \Eloquent {}
}

namespace App\Models{
/**
 * Khusus Sasaran Strategis PD di Sekretariat Daerah,
 * yang bisa olah data adalah akun `setda`, akun `biro` view-only,
 * `satuan_kerja_id` nya juga di set ke `setda`
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property int $sasaran_strategis_id
 * @property int $indikator_sasaran_strategis_id
 * @property string|null $sasaran_strategis_satker
 * @property string $iku
 * @property string $satuan
 * @property int $tahun_mulai
 * @property float $target_baseline
 * @property float $target_1
 * @property float $target_2
 * @property float $target_3
 * @property float $target_4
 * @property float $target_5
 * @property float|null $realisasi_baseline
 * @property float|null $realisasi_1
 * @property float|null $realisasi_2
 * @property float|null $realisasi_3
 * @property float|null $realisasi_4
 * @property float|null $realisasi_5
 * @property float|null $rata_nasional
 * @property int|null $peringkat_nasional
 * @property string|null $redaksi
 * @property array|null $lampiran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float|null $capaian_baseline
 * @property float|null $capaian_1
 * @property float|null $capaian_2
 * @property float|null $capaian_3
 * @property float|null $capaian_4
 * @property float|null $capaian_5
 * @property int|null $sasaran_strategis_rpjmd_id
 * @property string|null $narasi_keberhasilan
 * @property int|null $kinerja_bayangan_id
 * @property string|null $penyebab_kegagalan_baseline
 * @property string|null $penyebab_kegagalan_1
 * @property string|null $penyebab_kegagalan_2
 * @property string|null $penyebab_kegagalan_3
 * @property string|null $penyebab_kegagalan_4
 * @property string|null $penyebab_kegagalan_5
 * @property bool $is_kemiskinan
 * @property bool $is_stunting
 * @property bool $is_inflasi
 * @property bool $is_investasi
 * @property bool $is_penggunaan_pdn
 * @property string|null $definisi_operasional
 * @property bool $is_rapor_kinerja
 * @property array $target_baseline_triwulan
 * @property array $target_1_triwulan
 * @property array $target_2_triwulan
 * @property array $target_3_triwulan
 * @property array $target_4_triwulan
 * @property array $target_5_triwulan
 * @property array $realisasi_baseline_triwulan
 * @property array $realisasi_1_triwulan
 * @property array $realisasi_2_triwulan
 * @property array $realisasi_3_triwulan
 * @property array $realisasi_4_triwulan
 * @property array $realisasi_5_triwulan
 * @property array $capaian_baseline_triwulan
 * @property array $capaian_1_triwulan
 * @property array $capaian_2_triwulan
 * @property array $capaian_3_triwulan
 * @property array $capaian_4_triwulan
 * @property array $capaian_5_triwulan
 * @property float|null $rata_internasional
 * @property int|null $peringkat_internasional
 * @property-read \App\Models\IndikatorSasaranStrategis|null $indikatorSasaranStrategis
 * @property-read \App\Models\KinerjaBayangan|null $kinerjaBayangan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaProgram> $kinerjaProgram
 * @property-read int|null $kinerja_program_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaProgramCross> $kinerjaProgramCross
 * @property-read int|null $kinerja_program_cross_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaTidakTercapai> $kinerjaTidakTercapai
 * @property-read int|null $kinerja_tidak_tercapai_count
 * @property-read \App\Models\RiwayatValidasiSKP|null $riwayatSkpRejectedLatest
 * @property-read \App\Models\RiwayatValidasiSKP|null $riwayatSkpValidatedLatest
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RiwayatValidasiSKP> $riwayatValidasiSkp
 * @property-read int|null $riwayat_validasi_skp_count
 * @property-read \App\Models\SasaranStrategis|null $sasaranStrategis
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SasaranStrategisPdCross> $sasaranStrategisPdCross
 * @property-read int|null $sasaran_strategis_pd_cross_count
 * @property-read \App\Models\SasaranStrategisRpjmd|null $sasaranStrategisRpjmd
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SKP> $skp
 * @property-read int|null $skp_count
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd query()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereCapaian1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereCapaian1Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereCapaian2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereCapaian2Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereCapaian3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereCapaian3Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereCapaian4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereCapaian4Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereCapaian5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereCapaian5Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereCapaianBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereCapaianBaselineTriwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereDefinisiOperasional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereIku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereIndikatorSasaranStrategisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereIsInflasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereIsInvestasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereIsKemiskinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereIsPenggunaanPdn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereIsRaporKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereIsStunting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereKinerjaBayanganId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereLampiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereNarasiKeberhasilan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd wherePenyebabKegagalan1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd wherePenyebabKegagalan2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd wherePenyebabKegagalan3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd wherePenyebabKegagalan4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd wherePenyebabKegagalan5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd wherePenyebabKegagalanBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd wherePeringkatInternasional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd wherePeringkatNasional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRataInternasional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRataNasional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRealisasi1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRealisasi1Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRealisasi2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRealisasi2Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRealisasi3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRealisasi3Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRealisasi4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRealisasi4Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRealisasi5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRealisasi5Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRealisasiBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRealisasiBaselineTriwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereRedaksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereSasaranStrategisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereSasaranStrategisRpjmdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereSasaranStrategisSatker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereTarget1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereTarget1Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereTarget2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereTarget2Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereTarget3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereTarget3Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereTarget4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereTarget4Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereTarget5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereTarget5Triwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereTargetBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereTargetBaselineTriwulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPd whereUpdatedAt($value)
 */
	class SasaranStrategisPd extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $sasaran_strategis_rpjmd_id
 * @property int $sasaran_strategis_pd_id
 * @property-read \App\Models\SasaranStrategisPd $sasaranStrategisPd
 * @property-read \App\Models\SasaranStrategisRpjmd $sasaranStrategisRpjmd
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPdCross newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPdCross newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPdCross query()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPdCross whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPdCross whereSasaranStrategisPdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisPdCross whereSasaranStrategisRpjmdId($value)
 */
	class SasaranStrategisPdCross extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property int $sasaran_strategis_id
 * @property int $indikator_sasaran_strategis_id
 * @property string $satuan
 * @property int $tahun_mulai
 * @property string $target_baseline
 * @property string $target_1
 * @property string $target_2
 * @property string $target_3
 * @property string $target_4
 * @property string $target_5
 * @property string|null $realisasi_baseline
 * @property string|null $realisasi_1
 * @property string|null $realisasi_2
 * @property string|null $realisasi_3
 * @property string|null $realisasi_4
 * @property string|null $realisasi_5
 * @property float|null $rata_nasional
 * @property int|null $peringkat_nasional
 * @property string|null $strategi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $misi_id
 * @property int|null $tujuan_id
 * @property int|null $indikator_tujuan_id
 * @property int|null $target_visi_misi_rpjmd_id
 * @property float|null $capaian_baseline
 * @property float|null $capaian_1
 * @property float|null $capaian_2
 * @property float|null $capaian_3
 * @property float|null $capaian_4
 * @property float|null $capaian_5
 * @property string|null $peningkatan_realisasi
 * @property float|null $capaian_terhadap_target_akhir
 * @property string|null $penghargaan
 * @property string|null $perbandingan_realisasi_tahun_1
 * @property string|null $perbandingan_realisasi_tahun_2
 * @property string|null $perbandingan_realisasi_tahun_3
 * @property string|null $perbandingan_realisasi_tahun_4
 * @property string|null $perbandingan_realisasi_tahun_5
 * @property string|null $perbandingan_realisasi_target_1
 * @property string|null $perbandingan_realisasi_target_2
 * @property string|null $perbandingan_realisasi_target_3
 * @property string|null $perbandingan_realisasi_target_4
 * @property string|null $perbandingan_realisasi_target_5
 * @property string|null $penyebab_kegagalan_baseline
 * @property string|null $penyebab_kegagalan_1
 * @property string|null $penyebab_kegagalan_2
 * @property string|null $penyebab_kegagalan_3
 * @property string|null $penyebab_kegagalan_4
 * @property string|null $penyebab_kegagalan_5
 * @property bool $is_kemiskinan
 * @property bool $is_stunting
 * @property bool $is_inflasi
 * @property bool $is_investasi
 * @property bool $is_penggunaan_pdn
 * @property-read \App\Models\IndikatorSasaranStrategis|null $indikatorSasaranStrategis
 * @property-read \App\Models\IndikatorTujuan|null $indikatorTujuan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaBayangan> $kinerjaBayangan
 * @property-read int|null $kinerja_bayangan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaTidakTercapai> $kinerjaTidakTercapai
 * @property-read int|null $kinerja_tidak_tercapai_count
 * @property-read \App\Models\Misi|null $misi
 * @property-read \App\Models\SasaranStrategis|null $sasaranStrategis
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SasaranStrategisPd> $sasaranStrategisPd
 * @property-read int|null $sasaran_strategis_pd_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SasaranStrategisPdCross> $sasaranStrategisPdCross
 * @property-read int|null $sasaran_strategis_pd_cross_count
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @property-read \App\Models\VisiMisiRpjmd|null $targetVisiMisi
 * @property-read \App\Models\Tujuan|null $tujuan
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd query()
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereCapaian1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereCapaian2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereCapaian3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereCapaian4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereCapaian5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereCapaianBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereCapaianTerhadapTargetAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereIndikatorSasaranStrategisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereIndikatorTujuanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereIsInflasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereIsInvestasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereIsKemiskinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereIsPenggunaanPdn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereIsStunting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereMisiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePenghargaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePeningkatanRealisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePenyebabKegagalan1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePenyebabKegagalan2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePenyebabKegagalan3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePenyebabKegagalan4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePenyebabKegagalan5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePenyebabKegagalanBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePerbandinganRealisasiTahun1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePerbandinganRealisasiTahun2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePerbandinganRealisasiTahun3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePerbandinganRealisasiTahun4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePerbandinganRealisasiTahun5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePerbandinganRealisasiTarget1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePerbandinganRealisasiTarget2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePerbandinganRealisasiTarget3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePerbandinganRealisasiTarget4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePerbandinganRealisasiTarget5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd wherePeringkatNasional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereRataNasional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereRealisasi1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereRealisasi2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereRealisasi3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereRealisasi4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereRealisasi5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereRealisasiBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereSasaranStrategisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereStrategi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereTarget1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereTarget2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereTarget3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereTarget4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereTarget5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereTargetBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereTargetVisiMisiRpjmdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereTujuanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SasaranStrategisRpjmd whereUpdatedAt($value)
 */
	class SasaranStrategisRpjmd extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $satuan_kerja_id
 * @property int|null $tahun_id
 * @property string|null $satuan_kerja_nama
 * @property string|null $kode
 * @property string|null $satuan_kerja_alamat
 * @property string|null $satuan_kerja_kel_ds
 * @property int|null $kecamatan_id
 * @property string|null $satuan_kerja_khusus
 * @property int|null $status
 * @property string|null $kode_skpd
 * @property string|null $create_username
 * @property string|null $update_username
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $kota
 * @property string|null $kecamatan
 * @property string|null $kelurahan
 * @property string|null $satuan_kerja_nama_alias
 * @property string|null $sapk_id
 * @property float|null $bobot
 * @property int|null $m_kabkot_id
 * @property int|null $rumpun_id
 * @property float|null $lampiran_no
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja query()
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereBobot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereCreateUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereKecamatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereKelurahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereKodeSkpd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereLampiranNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereMKabkotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereRumpunId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereSapkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereSatuanKerjaAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereSatuanKerjaKelDs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereSatuanKerjaKhusus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereSatuanKerjaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereSatuanKerjaNamaAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereTahunId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereUpdateUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SatuanKerja whereUpdatedAt($value)
 */
	class SatuanKerja extends \Eloquent {}
}

namespace App\Models\Simpeg{
/**
 * 
 *
 * @property int $jf_id
 * @property int|null $gol_id
 * @property int|null $rumpun_id
 * @property string|null $jabkat_id
 * @property string|null $jf_kode
 * @property string|null $jf_nama
 * @property int|null $jf_gol_awal_id
 * @property int|null $jf_gol_akhir_id
 * @property int|null $jf_bup
 * @property string|null $jf_rumpun
 * @property string|null $jf_skill
 * @property string|null $jf_kategori
 * @property int|null $jf_jenis
 * @property string|null $kode_jabatan
 * @property string|null $jf_kelas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $create_username
 * @property string|null $update_username
 * @property string|null $sapk_id
 * @property string|null $status_menpan
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|JF newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JF newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JF onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|JF query()
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereCreateUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereGolId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereJabkatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereJfBup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereJfGolAkhirId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereJfGolAwalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereJfId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereJfJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereJfKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereJfKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereJfKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereJfNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereJfRumpun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereJfSkill($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereKodeJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereRumpunId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereSapkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereStatusMenpan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereUpdateUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JF withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|JF withoutTrashed()
 */
	class JF extends \Eloquent {}
}

namespace App\Models\Simpeg{
/**
 * 
 *
 * @property int $jabatan_id
 * @property int|null $jfu_id
 * @property string|null $satuan_kerja_id
 * @property string|null $unit_kerja_id
 * @property int|null $jabkat_id
 * @property int|null $gol_id_awal
 * @property int|null $gol_id_akhir
 * @property int|null $jf_id
 * @property int|null $eselon_id
 * @property string|null $jabatan_nama
 * @property int $jabatan_jenis
 * @property int|null $jabatan_type
 * @property string|null $kode_jabatan
 * @property string|null $jabatan_kelas
 * @property int|null $jabatan_bup
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $create_username
 * @property string|null $update_username
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Simpeg\JF|null $jf
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereCreateUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereEselonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereGolIdAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereGolIdAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereJabatanBup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereJabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereJabatanJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereJabatanKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereJabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereJabatanType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereJabkatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereJfId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereJfuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereKodeJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereUnitKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereUpdateUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Jabatan withoutTrashed()
 */
	class Jabatan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $masalah_id
 * @property string $masalah_type
 * @property int $solusi_id
 * @property string $solusi_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $masalah
 * @property-read mixed $masalah_string
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $solusi
 * @property-read mixed $solusi_string
 * @method static \Illuminate\Database\Eloquent\Builder|SolusiKinerja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolusiKinerja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolusiKinerja query()
 * @method static \Illuminate\Database\Eloquent\Builder|SolusiKinerja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolusiKinerja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolusiKinerja whereMasalahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolusiKinerja whereMasalahType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolusiKinerja whereSolusiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolusiKinerja whereSolusiType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolusiKinerja whereUpdatedAt($value)
 */
	class SolusiKinerja extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property int $anggaran
 * @property int $kegiatan_id
 * @property int $satuan_kerja_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $tahun_kinerja
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\IndikatorSubKegiatan> $indikator
 * @property-read int|null $indikator_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaSubKegiatan> $kinerjaSubKegiatan
 * @property-read int|null $kinerja_sub_kegiatan_count
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan whereAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan whereKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubKegiatan whereUpdatedAt($value)
 */
	class SubKegiatan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $nomor
 * @property string $tujuan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $tahun_mulai
 * @method static \Illuminate\Database\Eloquent\Builder|Tujuan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tujuan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tujuan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tujuan tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tujuan tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tujuan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tujuan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tujuan whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tujuan whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tujuan whereTujuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tujuan whereUpdatedAt($value)
 */
	class Tujuan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $nama
 * @property string $username
 * @property mixed $password
 * @property int|null $satuan_kerja_id
 * @property int $role_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $is_active
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class User extends \Eloquent implements \PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $satuan_kerja_id
 * @property string|null $satuan_kerja_nama
 * @property int|null $lv0_jabatan_id
 * @property string|null $lv0_jabatan_nama
 * @property string|null $lv0_eselon_nm
 * @property string|null $lv1_unit_kerja_id
 * @property string|null $lv1_unit_kerja_nama
 * @property int|null $lv1_jabatan_id
 * @property string|null $lv1_jabatan_nama
 * @property string|null $lv1_eselon_nm
 * @property string|null $lv2_unit_kerja_id
 * @property string|null $lv2_unit_kerja_nama
 * @property int|null $lv2_jabatan_id
 * @property string|null $lv2_jabatan_nama
 * @property string|null $lv2_eselon_nm
 * @property string|null $lv3_unit_kerja_id
 * @property string|null $lv3_unit_kerja_nama
 * @property int|null $lv3_jabatan_id
 * @property string|null $lv3_jabatan_nama
 * @property string|null $lv3_eselon_nm
 * @property string|null $lv4_unit_kerja_id
 * @property string|null $lv4_unit_kerja_nama
 * @property int|null $lv4_jabatan_id
 * @property string|null $lv4_jabatan_nama
 * @property string|null $lv4_eselon_nm
 * @property string|null $lv5_unit_kerja_id
 * @property string|null $lv5_unit_kerja_nama
 * @property int|null $lv5_jabatan_id
 * @property string|null $lv5_jabatan_nama
 * @property string|null $lv5_eselon_nm
 * @property string|null $lv6_unit_kerja_id
 * @property string|null $lv6_unit_kerja_nama
 * @property int|null $lv6_jabatan_id
 * @property string|null $lv6_jabatan_nama
 * @property string|null $lv6_eselon_nm
 * @property string|null $lv7_unit_kerja_id
 * @property string|null $lv7_unit_kerja_nama
 * @property int|null $lv7_jabatan_id
 * @property string|null $lv7_jabatan_nama
 * @property string|null $lv7_eselon_nm
 * @property int|null $level
 * @property string|null $unit_kerja_kode
 * @property string $id
 * @property string|null $parent_id
 * @property int|null $jabatan_id
 * @property string|null $eselon_nm
 * @property string|null $kode_unit_kerja
 * @property int|null $eselon_id
 * @property string|null $jabatan_nama
 * @property string|null $unit_kerja_nama
 * @property int|null $gol_id_minimum
 * @property string|null $alamat
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int|null $m_kabkot_id
 * @property string|null $nama_kota
 * @property string|null $unit_kerja_id_baru
 * @property int|null $status
 * @property string|null $unit_kerja_aktif_mulai
 * @property string|null $unit_kerja_aktif_selesai
 * @property string|null $kode_jabatan
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi query()
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereEselonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereEselonNm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereGolIdMinimum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereJabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereJabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereKodeJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereKodeUnitKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv0EselonNm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv0JabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv0JabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv1EselonNm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv1JabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv1JabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv1UnitKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv1UnitKerjaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv2EselonNm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv2JabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv2JabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv2UnitKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv2UnitKerjaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv3EselonNm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv3JabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv3JabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv3UnitKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv3UnitKerjaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv4EselonNm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv4JabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv4JabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv4UnitKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv4UnitKerjaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv5EselonNm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv5JabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv5JabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv5UnitKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv5UnitKerjaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv6EselonNm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv6JabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv6JabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv6UnitKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv6UnitKerjaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv7EselonNm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv7JabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv7JabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv7UnitKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereLv7UnitKerjaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereMKabkotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereNamaKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereSatuanKerjaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereUnitKerjaAktifMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereUnitKerjaAktifSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereUnitKerjaIdBaru($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereUnitKerjaKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VStrukturOrganisasi whereUnitKerjaNama($value)
 */
	class VStrukturOrganisasi extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property int $tahun_kinerja
 * @property int $tahap 1: Bappeda, 2: BPKAD, 3: BKD
 * @property bool|null $status true: terima, false: tolak, null: belum divalidasi
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan query()
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan whereTahap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan whereTahunKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ValidasiPerencanaan whereUpdatedAt($value)
 */
	class ValidasiPerencanaan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $visi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $tahun_mulai
 * @method static \Illuminate\Database\Eloquent\Builder|Visi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visi query()
 * @method static \Illuminate\Database\Eloquent\Builder|Visi tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Visi tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Visi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visi whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visi whereVisi($value)
 */
	class Visi extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $satuan_kerja_id
 * @property int $visi_id
 * @property int $misi_id
 * @property int $tujuan_id
 * @property int $indikator_tujuan_id
 * @property string $satuan
 * @property int $tahun_mulai
 * @property string $target_baseline
 * @property string $target_1
 * @property string $target_2
 * @property string $target_3
 * @property string $target_4
 * @property string $target_5
 * @property string|null $realisasi_baseline
 * @property string|null $realisasi_1
 * @property string|null $realisasi_2
 * @property string|null $realisasi_3
 * @property string|null $realisasi_4
 * @property string|null $realisasi_5
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float|null $capaian_baseline
 * @property float|null $capaian_1
 * @property float|null $capaian_2
 * @property float|null $capaian_3
 * @property float|null $capaian_4
 * @property float|null $capaian_5
 * @property-read \App\Models\IndikatorTujuan|null $indikatorTujuan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KinerjaTidakTercapai> $kinerjaTidakTercapai
 * @property-read int|null $kinerja_tidak_tercapai_count
 * @property-read \App\Models\Misi|null $misi
 * @property-read \App\Models\SatuanKerja|null $satuanKerja
 * @property-read \App\Models\Tujuan|null $tujuan
 * @property-read \App\Models\Visi|null $visi
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd query()
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd roleSatuanKerja(array|int|null $satkerIds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd tahunKinerja($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd tahunMulai($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereCapaian1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereCapaian2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereCapaian3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereCapaian4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereCapaian5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereCapaianBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereIndikatorTujuanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereMisiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereRealisasi1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereRealisasi2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereRealisasi3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereRealisasi4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereRealisasi5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereRealisasiBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereSatuanKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereTarget1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereTarget2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereTarget3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereTarget4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereTarget5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereTargetBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereTujuanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisiMisiRpjmd whereVisiId($value)
 */
	class VisiMisiRpjmd extends \Eloquent {}
}

