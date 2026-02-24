<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\SubKegiatan;
use Illuminate\Database\Seeder;

class SubKegiatanDinasPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'kode' => '1.01.02.1.01.03',
                'nama' => 'Pembangunan Ruang Guru/Kepala Sekolah/TU',
                'anggaran' => 6942000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.04',
                'nama' => 'Pembangunan Ruang Laboratorium Biologi',
                'anggaran' => 2970000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.05',
                'nama' => 'Pembangunan Ruang Laboratorium Fisika',
                'anggaran' => 2982000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.06',
                'nama' => 'Pembangunan Ruang Laboratorium Kimia',
                'anggaran' => 9112000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.07',
                'nama' => 'Pembangunan Ruang Laboratorium Komputer',
                'anggaran' => 4585000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.10',
                'nama' => 'Pembangunan Ruang Unit Kesehatan Sekolah',
                'anggaran' => 5567000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.11',
                'nama' => 'Pembangunan Perpustakaan Sekolah',
                'anggaran' => 3500000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.14',
                'nama' => 'Pembangunan Sarana, Prasarana dan Utilitas Sekolah',
                'anggaran' => 0,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.19',
                'nama' => 'Rehabilitasi Sedang/Berat Ruang Kelas Sekolah',
                'anggaran' => 77788000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.20',
                'nama' => 'Rehabilitasi Sedang/Berat Ruang Guru/Kepala Sekolah/TU',
                'anggaran' => 8391000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.21',
                'nama' => 'Rehabilitasi Sedang/Berat Ruang Laboratorium Biologi',
                'anggaran' => 6259000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.22',
                'nama' => 'Rehabilitasi Sedang/Berat Ruang Laboratorium Fisika',
                'anggaran' => 5131000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.23',
                'nama' => 'Rehabilitasi Sedang/Berat Ruang Laboratorium Kimia',
                'anggaran' => 5206000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.24',
                'nama' => 'Rehabilitasi Sedang/Berat Ruang Laboratorium Komputer',
                'anggaran' => 1829000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.25',
                'nama' => 'Rehabilitasi Sedang/Berat Ruang Laboratorium Bahasa',
                'anggaran' => 912000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.27',
                'nama' => 'Rehabilitasi Sedang/Berat Ruang Unit Kesehatan Sekolah',
                'anggaran' => 632000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.28',
                'nama' => 'Rehabilitasi Sedang/Berat Perpustakaan Sekolah',
                'anggaran' => 5070000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.01.31',
                'nama' => 'Rehabilitasi Sarana, Prasarana dan Utilitas Sekolah',
                'anggaran' => 2355000000,
                'kegiatan_kode' => '1.01.02.1.01',
            ],
            [
                'kode' => '1.01.02.1.02.04',
                'nama' => 'Pembangunan Ruang Praktik Siswa',
                'anggaran' => 73893300000,
                'kegiatan_kode' => '1.01.02.1.02',
            ],
            [
                'kode' => '1.01.02.1.02.05',
                'nama' => 'Pembangunan Ruang Laboratorium',
                'anggaran' => 7188660000,
                'kegiatan_kode' => '1.01.02.1.02',
            ],
            [
                'kode' => '1.01.02.1.02.07',
                'nama' => 'Pembangunan Perpustakaan Sekolah',
                'anggaran' => 8864280000,
                'kegiatan_kode' => '1.01.02.1.02',
            ],
            [
                'kode' => '1.01.02.1.02.10',
                'nama' => 'Pembangunan Sarana, Prasarana dan Utilitas Sekolah',
                'anggaran' => 0,
                'kegiatan_kode' => '1.01.02.1.02',
            ],
            [
                'kode' => '1.01.02.1.02.15',
                'nama' => 'Rehabilitasi Ruang Kelas Sekolah',
                'anggaran' => 0,
                'kegiatan_kode' => '1.01.02.1.02',
            ],
            [
                'kode' => '1.01.02.1.03.02',
                'nama' => 'Penambahan Ruang Kelas Sekolah',
                'anggaran' => 4757700000,
                'kegiatan_kode' => '1.01.02.1.03',
            ],
            [
                'kode' => '1.01.02.1.03.03',
                'nama' => 'Pembangunan Ruang Guru/Kepala Sekolah/TU',
                'anggaran' => 407200000,
                'kegiatan_kode' => '1.01.02.1.03',
            ],
            [
                'kode' => '1.01.02.1.03.04',
                'nama' => 'Pembangunan Ruang Unit Kesehatan Sekolah',
                'anggaran' => 888000000,
                'kegiatan_kode' => '1.01.02.1.03',
            ],
            [
                'kode' => '1.01.02.1.03.05',
                'nama' => 'Pembangunan Perpustakaan Sekolah',
                'anggaran' => 667200000,
                'kegiatan_kode' => '1.01.02.1.03',
            ],
            [
                'kode' => '1.01.02.1.03.08',
                'nama' => 'Pembangunan Sarana, Prasarana dan Utilitas Sekolah',
                'anggaran' => 0,
                'kegiatan_kode' => '1.01.02.1.03',
            ],
            [
                'kode' => '1.01.02.1.03.10',
                'nama' => 'Pembangunan Kantin Sekolah',
                'anggaran' => 241800000,
                'kegiatan_kode' => '1.01.02.1.03',
            ],
            [
                'kode' => '1.01.02.1.03.16',
                'nama' => 'Pembangunan Ruang Bina Diri dan Bina Gerak untuk Tunadaksa (D)',
                'anggaran' => 2226300000,
                'kegiatan_kode' => '1.01.02.1.03',
            ],
            [
                'kode' => '1.01.02.1.03.18',
                'nama' => 'Rehabilitasi Ruang Kelas Sekolah',
                'anggaran' => 0,
                'kegiatan_kode' => '1.01.02.1.03',
            ],
        ];

        foreach ($data as $items) {
            $kegiatanId = Kegiatan::tahunKinerja(2022)
                ->where('kode', $items['kegiatan_kode'])
                ->where('satuan_kerja_id', 1003)
                ->value('id');

            $exists = SubKegiatan::tahunKinerja(2022)
                ->where('kode', $items['kode'])
                ->where('satuan_kerja_id', 1003)
                ->where('kegiatan_id', $kegiatanId)
                ->exists();

            if ($exists) {
                continue;
            }

            SubKegiatan::create([
                'kode' => $items['kode'],
                'nama' => $items['nama'],
                'anggaran' => $items['anggaran'],
                'kegiatan_id' => $kegiatanId,
                'satuan_kerja_id' => 1003,
                'tahun_kinerja' => 2022,
            ]);
        }
    }
}
