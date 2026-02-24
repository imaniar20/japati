<?php

namespace Database\Seeders;

use App\Models\IndikatorSasaranStrategis;
use App\Models\IndikatorTujuan;
use App\Models\Kegiatan;
use App\Models\Misi;
use App\Models\Program;
use App\Models\SasaranStrategis;
use App\Models\SubKegiatan;
use App\Models\Tujuan;
use App\Models\Visi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Master2024 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->visi();
            $this->misi();
            $this->tujuan();
            $this->indikatorTujuan();
            $this->sasaran();
            $this->indikator();
            $this->program();
            $this->kegiatan();
            $this->subKegiatan();
        });
    }

    private function visi()
    {
        Visi::query()->where('tahun_mulai', 0)->update(['tahun_mulai' => 2019]);
        Visi::query()->create([
            'tahun_mulai' => 2024,
            'visi' => 'Visi 2024-2028',
        ]);
    }

    private function misi()
    {
        Misi::query()->where('tahun_mulai', 0)->update(['tahun_mulai' => 2019]);
        Misi::query()->create([
            'tahun_mulai' => 2024,
            'nomor' => 1,
            'misi' => 'Misi 2024-2028',
        ]);
    }

    private function tujuan()
    {
        Tujuan::query()->where('tahun_mulai', 0)->update(['tahun_mulai' => 2019]);
        $tujuan = [
            'Terwujudnya Sumber Daya Manusia Yang Berkualitas dan Berdaya Saing',
            'Terwujudnya Pertumbuhan Ekonomi Yang Inklusif dan Berkelanjutan',
            'Terwujudnya Pemerataan Pembangunan Wilayah yang didukung Infrastruktur Berkualitas dan Lingkungan yang Berkelanjutan',
            'Terciptanya demokrasi dan bikrokrasi yang berkualitas dan didukung oleh inovasi daerah',
        ];

        foreach ($tujuan as $index => $value) {
            Tujuan::query()->create([
                'tahun_mulai' => 2024,
                'nomor' => $index + 1,
                'tujuan' => $value,
            ]);
        }
    }

    private function indikatorTujuan()
    {
        IndikatorTujuan::query()->where('tahun_mulai', 0)->update(['tahun_mulai' => 2019]);
        $indikator = [
            'Indeks Pembangunan Manusia',
            'Laju Pertumbuhan Ekonomi',
            'Gini Ratio',
            'Indeks Williamson',
            'Indeks Demokrasi Indonesia (IDI) Jawa Barat',
            'Indeks Reformasi Birokrasi',
        ];

        foreach ($indikator as $index => $value) {
            IndikatorTujuan::query()->create([
                'tahun_mulai' => 2024,
                'nomor' => $index + 1,
                'indikator' => $value,
            ]);
        }
    }

    private function sasaran()
    {
        SasaranStrategis::query()->where('tahun_mulai', 0)->update(['tahun_mulai' => 2019]);
        $sasaran = [
            'Meningkatnya Akses Pendidikan Masyarakat',
            'Meningkatnya derajat kesehatan masyarakat',
            'Meningkatnya Taraf Hidup Masyarakat',
            'Meningkatnya Pemberdayaan Perempuan, dan perlindungan   anak, serta pembangunan pemuda',
            'Terkendalinya pertumbuhan dan distribusi penduduk',
            'Meningkatnya pertumbuhan sektor perindustrian dan perdagangan',
            'Meningkatnya pertumbuhan sektor pertanian, kehutanan, kelautan,  perikanan, dan ketahanan pangan',
            'Meningkatnya pertumbuhan sektor Penyediaan akomodasi makan dan minum',
            'Meningkatnya nilai investasi dan kualitas usaha yang disertai dengan meningkatnya daya saing dan penempatan tenaga kerja',
            'Meningkatnya konektivitas antarwilayah dan pelayanan infrastruktur',
            'Meningkatnya Kualitas infrastruktur',
            'Meningkatnya Kualitas Perumahan dan Permukiman',
            'Meningkatnya kualitas lingkungan hidup',
            'Meningkatnya pembangunan rendah karbon dan menurunnya risiko bencana',
            'Meningkatnya Pemerataan Pembangunan di wilayah Perdesaan',
            'Meningkatnya kebebasan, kesetaraan, dan kapasitas lembaga demokrasi',
            'Meningkatnya Kualitas dan Kapasitas Tata Kelola Pemerintahan Daerah',
            'Meningkatnya penerapan inovasi daerah dalam pembangunan',
        ];

        foreach ($sasaran as $index => $value) {
            SasaranStrategis::query()->create([
                'tahun_mulai' => 2024,
                'nomor' => $index + 1,
                'sasaran' => $value,
            ]);
        }
    }

    private function indikator()
    {
        IndikatorSasaranStrategis::query()->where('tahun_mulai', 0)->update(['tahun_mulai' => 2019]);
        $iku = [
            'Rata-Rata Lama Sekolah',
            'Harapan Lama Sekolah',
            'Angka Harapan Hidup',
            'Prevalensi Stunting',
            'Pengeluaran per kapita (Rp.000)',
            'Persentase Penduduk Miskin (%)',
            'Indeks Pemberdayaan Gender',
            'Indeks Perlindungan Anak',
            'Indeks Pembangunan Pemuda',
            'Laju Pertumbuhan Penduduk',
            'Laju Pertumbuhan Sektor Industri',
            'Laju Pertumbuhan Sektor Perdagangan',
            'Skor Pola Pangan Harapan',
            'Nilai Tukar Petani (NTP)',
            'Laju Pertumbuhan Sektor Pertanian, Kehutanan dan Perikanan',
            'Laju Pertumbuhan Sektor penyediaan akomodasi makan dan minum',
            'Pembentukan Modal Tetap Bruto (PMTB) ADHB',
            'Proporsi Kredit UMKM terhadap Total Kredit',
            'Tingkat Pengangguran Terbuka',
            'Tingkat Konektivitas',
            'Indeks Kualitas Infrastruktur',
            'Persentase rumah tangga hunian layak',
            'Indeks Kualitas Lingkungan Hidup',
            'Tingkat Penurunan Emisi Gas Rumah Kaca',
            'Indeks Risiko Bencana',
            'Indeks Desa Membangun',
            'Indeks Demokrasi Indonesia (IDI) Jawa Barat',
            'Indeks Reformasi Birokrasi',
            'Indeks Inovasi Daerah',
        ];

        foreach ($iku as $index => $value) {
            IndikatorSasaranStrategis::query()->create([
                'tahun_mulai' => 2024,
                'nomor' => $index + 1,
                'indikator' => $value,
            ]);
        }
    }

    private function program()
    {
        Program::query()
            ->where('tahun_kinerja', 2023)
            ->chunkById(100, function (Collection $data) {
                /** @var Program */
                foreach ($data as $item) {
                    $new = $item->replicate()->fill([
                        'tahun_kinerja' => 2024,
                    ]);

                    Program::query()->create($new->toArray());
                }
            });
    }

    private function kegiatan()
    {
        Kegiatan::query()
            ->where('tahun_kinerja', 2023)
            ->chunkById(100, function (Collection $data) {
                /** @var Kegiatan */
                foreach ($data as $item) {
                    $new = $item->replicate()->fill([
                        'tahun_kinerja' => 2024,
                    ]);

                    Kegiatan::query()->create($new->toArray());
                }
            });
    }

    private function subKegiatan()
    {
        SubKegiatan::query()
            ->where('tahun_kinerja', 2023)
            ->chunkById(100, function (Collection $data) {
                /** @var SubKegiatan */
                foreach ($data as $item) {
                    $new = $item->replicate()->fill([
                        'tahun_kinerja' => 2024,
                    ]);

                    SubKegiatan::query()->create($new->toArray());
                }
            });
    }
}
