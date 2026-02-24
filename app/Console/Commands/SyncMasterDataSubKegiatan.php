<?php

namespace App\Console\Commands;

use App\Models\Kegiatan;
use App\Models\SatuanKerja;
use App\Models\SubKegiatan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SyncMasterDataSubKegiatan extends Command
{
    private int $tahunKinerja;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:master-subkegiatan
                            {--tahun-kinerja= : Tahun kinerja. Default mengikuti helper getTahunKinerja()}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync master data sub kegiatan';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->tahunKinerja = $this->option('tahun-kinerja') ?: getTahunKinerja();
        setTahunKinerja($this->tahunKinerja);

        $satuanKerja = SatuanKerja::selectRaw('satuan_kerja_id, UPPER(satuan_kerja_nama) satuan_kerja_nama')->pluck('satuan_kerja_id', 'satuan_kerja_nama');

        // db siap => db sakip
        $satuanKerjaRev = [
            'SATUAN POLISI DAN PAMONG PRAJA' => 'SATUAN POLISI PAMONG PRAJA',
            'DINAS PEMBERDAYAAN PEREMPUAN, PERLINDUNGAN ANAK, DAN KELUARGA BERENCANA' => 'DINAS PEMBERDAYAAN PEREMPUAN, PERLINDUNGAN ANAK DAN KELUARGA BERENCANA',
            'DINAS PERPUSTAKAAN DAN KEARSIPAN' => 'DINAS PERPUSTAKAAN DAN KEARSIPAN DAERAH',
            'SEKRETARIAT DEWAN PERWAKILAN RAKYAT DAERAH' => 'SEKRETARIAT DPRD',
            'INSPEKTORAT' => 'INSPEKTORAT DAERAH',
            'BIRO BADAN USAHA MILIK DAERAH, INVESTASI DAN ADMINISTRASI PEMBANGUNAN' => 'BIRO BADAN USAHA MILIK DAERAH, INVESTASI DAN ADMINSITRASI PEMBANGUNAN',
            'BIRO PENGADAAN BARANG/JASA' => 'BIRO PENGADAAN BARANG DAN JASA',
        ];

        $dataOpd = DB::connection('siap')
            ->select('SELECT DISTINCT
                db.kode_sub_giat AS kode,
                db.nama_sub_giat AS nama,
                COALESCE ( SUM ( rincian ), 0 ) AS anggaran ,
                db.kode_giat AS kegiatan_kode,
                db.nama_skpd AS satuan_kerja
            FROM
                ebudgeting.data_belanja db 
            WHERE
                id_daerah = 8 
                AND id_rinci_sub_bl IS NOT NULL 
                AND sub_giat_locked <> 3 
                AND tahun = ?
            GROUP BY
                id_giat,
                kode_program,
                kode_giat,
                nama_giat,
                id_skpd,
                nama_skpd,
                id_daerah,
                kode_sub_giat,
                nama_sub_giat', [$this->tahunKinerja]);

        $dataBiro = DB::connection('siap')
            ->select('SELECT DISTINCT
                db.kode_sub_giat AS kode,
                db.nama_sub_giat AS nama,
                COALESCE ( SUM ( rincian ), 0 ) AS anggaran ,
                db.kode_giat AS kegiatan_kode,
                db.nama_sub_skpd AS satuan_kerja
            FROM
                ebudgeting.data_belanja db 
            WHERE
                id_daerah = 8 
                AND id_skpd = 1236 
                AND id_rinci_sub_bl IS NOT NULL 
                AND sub_giat_locked <> 3 
                AND tahun = ?
            GROUP BY
                id_giat,
                kode_program,
                kode_giat,
                nama_giat,
                id_skpd,
                nama_skpd,
                id_sub_skpd,
                nama_sub_skpd,
                id_daerah,
                kode_sub_giat,
                nama_sub_giat', [$this->tahunKinerja]);

        $data = collect($dataOpd)->merge($dataBiro)
            ->transform(function ($item) use ($satuanKerja, $satuanKerjaRev) {
                $item->satuan_kerja_id = $satuanKerja[Str::upper($item->satuan_kerja)] ?? $satuanKerja[$satuanKerjaRev[Str::upper($item->satuan_kerja)]];

                unset($item->satuan_kerja);

                return $item;
            });

        $bar = $this->output->createProgressBar($data->count());
        $bar->start();

        $groupSatuanKerja = $data->groupBy('satuan_kerja_id');

        foreach ($groupSatuanKerja as $satuanKerjaId => $data) {
            $kegiatanList = Kegiatan::tahunKinerja()
                ->where('satuan_kerja_id', $satuanKerjaId)
                ->get();

            foreach ($data as $item) {
                $update = [
                    'kode' => $item->kode,
                    'satuan_kerja_id' => $item->satuan_kerja_id,
                    'tahun_kinerja' => $this->tahunKinerja,
                    'kegiatan_id' => $kegiatanList->where('kode', $item->kegiatan_kode)->first()->id,
                ];

                unset($item->kegiatan_kode);

                $create = array_diff_assoc((array) $item, $update);

                SubKegiatan::updateOrCreate($update, $create);
                $bar->advance();
            }
        }

        $bar->finish();
    }
}
