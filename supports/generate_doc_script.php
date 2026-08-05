<?php

require 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Table;

$phpWord = new PhpWord();

// Define styles
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(11);

// Add custom styles
$phpWord->addTitleStyle(1, ['bold' => true, 'color' => '1B365D', 'size' => 16, 'name' => 'Arial'], ['alignment' => Jc::CENTER, 'spaceAfter' => 200]);
$phpWord->addTitleStyle(2, ['bold' => true, 'color' => '2C3E50', 'size' => 13, 'name' => 'Arial'], ['spaceBefore' => 200, 'spaceAfter' => 100]);
$phpWord->addTitleStyle(3, ['bold' => true, 'color' => '34495E', 'size' => 11, 'name' => 'Arial'], ['spaceBefore' => 150, 'spaceAfter' => 80]);

// Table styles
$tableStyle = [
    'borderSize' => 6,
    'borderColor' => 'BDC3C7',
    'cellMargin' => 80,
    'alignment' => 'center'
];
$headerCellStyle = ['bgColor' => '1B365D'];
$headerTextStyle = ['bold' => true, 'color' => 'FFFFFF', 'size' => 10, 'name' => 'Arial'];
$rowCellStyle = ['bgColor' => 'F8F9FA'];
$altRowCellStyle = ['bgColor' => 'FFFFFF'];

$section = $phpWord->addSection([
    'marginTop' => 1440,
    'marginBottom' => 1440,
    'marginLeft' => 1440,
    'marginRight' => 1440
]);

// Document Title
$section->addTitle('LAPORAN SPESIFIKASI KEBUTUHAN DATA INTEGRASI DATABASE', 1);
$section->addText('Sistem Informasi e-SAKIP / SAKTI (JAPATI)', ['italic' => true, 'size' => 12, 'color' => '7F8C8D'], ['alignment' => Jc::CENTER, 'spaceAfter' => 300]);

$section->addText('Dokumen ini berisi rincian teknis mengenai kebutuhan tabel dan variabel (kolom) dari 3 (tiga) database eksternal pendukung yang diintegrasikan ke dalam Sistem e-SAKIP / SAKTI, yaitu Database SIAP (Perencanaan & Penganggaran), Database E-Kinerja, dan Database SIMPEG.', ['size' => 11], ['spaceAfter' => 200]);

// ==========================================
// SECTION 1: DATABASE SIAP
// ==========================================
$section->addTitle('1. DATABASE SIAP (Perencanaan & Penganggaran / e-Budgeting)', 2);

$section->addText('Database SIAP digunakan untuk sinkronisasi Master Data Perencanaan (Program, Kegiatan, Sub-Kegiatan) beserta pagu anggaran dari seluruh Perangkat Daerah (OPD) dan Biro.', ['size' => 11], ['spaceAfter' => 150]);

$section->addText('Tabel & Schema Utama: ebudgeting.data_belanja', ['bold' => true, 'color' => '1B365D'], ['spaceAfter' => 100]);

$table1 = $section->addTable($tableStyle);
$table1->addRow(400);
$table1->addCell(2000, $headerCellStyle)->addText('Nama Field / Variable', $headerTextStyle);
$table1->addCell(2200, $headerCellStyle)->addText('Kategori Data', $headerTextStyle);
$table1->addCell(4800, $headerCellStyle)->addText('Fungsi & Peruntukan dalam e-SAKIP', $headerTextStyle);

$siapFields = [
    ['tahun', 'Filter / Parameter', 'Tahun Anggaran / Tahun Kinerja yang disinkronkan.'],
    ['id_daerah', 'Filter / Parameter', 'Identifier Daerah Pemda (misal: ID 8).'],
    ['id_rinci_sub_bl', 'Filter Validasi', 'Filter data rincian belanja aktif (WHERE id_rinci_sub_bl IS NOT NULL).'],
    ['sub_giat_locked', 'Filter Validasi', 'Filter data yang tidak dikunci (WHERE sub_giat_locked <> 3).'],
    ['id_skpd & nama_skpd', 'Master OPD', 'Identifier & Nama Satuan Kerja / Perangkat Daerah.'],
    ['id_sub_skpd & nama_sub_skpd', 'Master Biro', 'Identifier & Nama Biro / Sub-SKPD (misal: ID SKPD 1236).'],
    ['kode_program & nama_program', 'Master Program', 'Kode dan Nama Program Perencanaan OPD.'],
    ['id_giat, kode_giat & nama_giat', 'Master Kegiatan', 'Identifier, Kode, dan Nama Kegiatan Perencanaan.'],
    ['kode_sub_giat & nama_sub_giat', 'Master Sub-Kegiatan', 'Kode dan Nama Sub-Kegiatan Perencanaan.'],
    ['rincian', 'Anggaran Belanja', 'Nilai rincian belanja yang ditotal via SUM(rincian) AS anggaran.'],
];

foreach ($siapFields as $index => $field) {
    $bg = ($index % 2 == 0) ? $rowCellStyle : $altRowCellStyle;
    $table1->addRow();
    $table1->addCell(2000, $bg)->addText($field[0], ['bold' => true, 'size' => 9.5]);
    $table1->addCell(2200, $bg)->addText($field[1], ['size' => 9.5]);
    $table1->addCell(4800, $bg)->addText($field[2], ['size' => 9.5]);
}

$section->addText('Artisan Command Sinkronisasi: php artisan sync:master-program, sync:master-kegiatan, sync:master-subkegiatan.', ['italic' => true, 'size' => 9.5, 'color' => '555555'], ['spaceBefore' => 150, 'spaceAfter' => 300]);

// ==========================================
// SECTION 2: DATABASE E-KINERJA
// ==========================================
$section->addTitle('2. DATABASE E-KINERJA (Kinerja Pegawai & Realisasi IKI)', 2);

$section->addText('Database E-Kinerja diakses secara dinamis per tahun kinerja (misal: erk_ekinerja_2024, erk_ekinerja_2025) untuk mengintegrasikan Sasaran Kinerja Pegawai (SKP), Indikator Kinerja Individu (IKI), dan Tim Kerja.', ['size' => 11], ['spaceAfter' => 150]);

$table2 = $section->addTable($tableStyle);
$table2->addRow(400);
$table2->addCell(2200, $headerCellStyle)->addText('Tabel / View', $headerTextStyle);
$table2->addCell(2400, $headerCellStyle)->addText('Nama Field / Variable', $headerTextStyle);
$table2->addCell(4400, $headerCellStyle)->addText('Fungsi & Peruntukan dalam e-SAKIP', $headerTextStyle);

$ekinerjaFields = [
    ['pegawai_data / v_pegawai_data', 'peg_nip, peg_nama, id_satuan_kerja, jabatan_nama, unit_kerja_nama, peg_status', 'Master Data Pegawai aktif beserta posisi jabatan dan unit kerjanya.'],
    ['tim_kerja', 'id, nama, satuan_kerja_id, v_struktur_organisasi_id, nip_ketua, deleted_at', 'Struktur Tim Kerja dan penunjukkan NIP Ketua Tim Kerja per Unit Kerja.'],
    ['sasaran_kinerja', 'id, id_old_skp, sakip_id, deleted_at', 'Pemetaan dokumen SKP Pegawai dengan ID SAKIP.'],
    ['iki (Indikator Kinerja Individu)', 'id, sasaran_kerja_id, sakip_type, sakip_id, validasi, deleted_at', 'Data Indikator Kinerja Individu beserta status validasinya (validasi = 1).'],
    ['iki_bulanan', 'id, sasaran_kinerja_id, target, deleted_at', 'Rincian target dan realisasi bulanan Indikator Kinerja Individu.'],
];

foreach ($ekinerjaFields as $index => $field) {
    $bg = ($index % 2 == 0) ? $rowCellStyle : $altRowCellStyle;
    $table2->addRow();
    $table2->addCell(2200, $bg)->addText($field[0], ['bold' => true, 'size' => 9.5]);
    $table2->addCell(2400, $bg)->addText($field[1], ['size' => 9.5]);
    $table2->addCell(4400, $bg)->addText($field[2], ['size' => 9.5]);
}

$section->addText('Koneksi Database: Configured via config/database.php (dynamic connections: ekinerja_{tahun}).', ['italic' => true, 'size' => 9.5, 'color' => '555555'], ['spaceBefore' => 150, 'spaceAfter' => 300]);

// ==========================================
// SECTION 3: DATABASE SIMPEG
// ==========================================
$section->addTitle('3. DATABASE SIMPEG (Kepegawaian & Struktur Organisasi)', 2);

$section->addText('Database SIMPEG digunakan sebagai acuan hierarki Struktur Organisasi Perangkat Daerah, data pegawai, riwayat jabatan, serta penyusunan Perjanjian Kinerja (PK).', ['size' => 11], ['spaceAfter' => 150]);

$table3 = $section->addTable($tableStyle);
$table3->addRow(400);
$table3->addCell(2400, $headerCellStyle)->addText('Tabel / View', $headerTextStyle);
$table3->addCell(2400, $headerCellStyle)->addText('Nama Field / Variable', $headerTextStyle);
$table3->addCell(4200, $headerCellStyle)->addText('Fungsi & Peruntukan dalam e-SAKIP', $headerTextStyle);

$simpegFields = [
    ['v_struktur_organisasi', 'id, status, unit_kerja_aktif_selesai, satuan_kerja_id, satuan_kerja_nama, level, lv1..lv4_unit_kerja_id & nama', 'Hierarki pohon unit kerja (Level 0 s.d. Level 4) untuk penetapan penanggung jawab indikator dan cascading.'],
    ['v_pegawai_data', 'peg_id, peg_nip, peg_nama, nip_atasan, satuan_kerja_id, unit_kerja_id, unit_kerja_nama, jabatan_jenis, jabatan_nama, tugas_tambahan_jabatan_nama, nm_pkt_akhir, nm_gol_akhir, peg_status', 'Profil pegawai lengkap, relasi NIP Atasan langsung, pangkat/golongan, dan tugas tambahan (PLT) untuk cetak Perjanjian Kinerja.'],
    ['m_spg_jabatan', 'jabatan_id, jf_id', 'Master referensi data Jabatan Pegawai.'],
    ['m_spg_referensi_jf', 'jf_id', 'Master referensi data Jabatan Fungsional (JF).'],
];

foreach ($simpegFields as $index => $field) {
    $bg = ($index % 2 == 0) ? $rowCellStyle : $altRowCellStyle;
    $table3->addRow();
    $table3->addCell(2400, $bg)->addText($field[0], ['bold' => true, 'size' => 9.5]);
    $table3->addCell(2400, $bg)->addText($field[1], ['size' => 9.5]);
    $table3->addCell(4200, $bg)->addText($field[2], ['size' => 9.5]);
}

$section->addText('Koneksi Database: Connection simpeg di config/database.php.', ['italic' => true, 'size' => 9.5, 'color' => '555555'], ['spaceBefore' => 150, 'spaceAfter' => 300]);

// ==========================================
// SECTION 4: KESIMPULAN
// ==========================================
$section->addTitle('4. RINGKASAN & IMPLIKASI TEKNIS', 2);

$section->addText('1. Metode Pengambilan Data: Seluruh integrasi dengan SIAP, E-Kinerja, dan SIMPEG dilakukan melalui Direct Database Access (PostgreSQL Connection), bukan melalui REST API.', ['size' => 10.5], ['spaceAfter' => 80]);
$section->addText('2. Pengelolaan Tahun Kinerja: DB E-Kinerja terpisah per tahun (erk_ekinerja_{tahun}), sedangkan SIAP menggunakan kolom `tahun` pada tabel `ebudgeting.data_belanja`.', ['size' => 10.5], ['spaceAfter' => 80]);
$section->addText('3. Standarisasi SKPD: Pencocokan nama SKPD dari SIAP ke e-SAKIP menggunakan array pemetaan string lokal ($satuanKerjaRev) karena perbedaan konvensi penamaan.', ['size' => 10.5], ['spaceAfter' => 200]);

// Save document
$targetPath1 = 'C:\Users\kuningan\.gemini\antigravity-ide\brain\8b047c44-dc77-460e-bf02-bb203ca25677\Laporan_Kebutuhan_Data_Integrasi_SIAP_EKINERJA_SIMPEG.docx';
$targetPath2 = 'c:\laragon\www\japati\Laporan_Kebutuhan_Data_Integrasi_SIAP_EKINERJA_SIMPEG.docx';

$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($targetPath1);
$objWriter->save($targetPath2);

echo "SUCCESS: Word Document created at:\n - {$targetPath1}\n - {$targetPath2}\n";
