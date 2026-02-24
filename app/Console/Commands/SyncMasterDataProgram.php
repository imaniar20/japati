<?php

namespace App\Console\Commands;

use App\Models\Program;
use App\Models\SatuanKerja;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SyncMasterDataProgram extends Command
{
    private int $tahunKinerja;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:master-program
                            {--tahun-kinerja= : Tahun kinerja. Default mengikuti helper getTahunKinerja()}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync master data program';

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
                db.kode_program AS kode,
                db.nama_program AS nama,
                db.nama_skpd AS satuan_kerja,
                COALESCE ( SUM ( rincian ), 0 ) AS anggaran 
            FROM
                ebudgeting.data_belanja db 
            WHERE
                id_daerah = 8 
                AND id_rinci_sub_bl IS NOT NULL 
                AND sub_giat_locked <> 3 
                AND tahun = ?
            GROUP BY
                kode_program,
                nama_program,
                id_skpd,
                nama_skpd,
                id_daerah', [$this->tahunKinerja]);

        $dataBiro = DB::connection('siap')
            ->select('SELECT DISTINCT
                db.kode_program AS kode,
                db.nama_program AS nama,
                db.nama_sub_skpd AS satuan_kerja,
                COALESCE ( SUM ( rincian ), 0 ) AS anggaran 
            FROM
                ebudgeting.data_belanja db 
            WHERE
                id_daerah = 8 
                AND id_skpd = 1236 
                AND id_rinci_sub_bl IS NOT NULL 
                AND sub_giat_locked <> 3 
                AND tahun = ?
            GROUP BY
                kode_program,
                nama_program,
                id_skpd,
                nama_skpd,
                id_sub_skpd,
                nama_sub_skpd,
                id_daerah', [$this->tahunKinerja]);

        $data = collect($dataOpd)->merge($dataBiro)
            ->transform(function ($item) use ($satuanKerja, $satuanKerjaRev) {
                $item->satuan_kerja_id = $satuanKerja[Str::upper($item->satuan_kerja)] ?? $satuanKerja[$satuanKerjaRev[Str::upper($item->satuan_kerja)]];

                unset($item->satuan_kerja);

                return $item;
            });

        $bar = $this->output->createProgressBar($data->count());
        $bar->start();

        foreach ($data as $item) {
            $update = [
                'kode' => $item->kode,
                'satuan_kerja_id' => $item->satuan_kerja_id,
                'tahun_kinerja' => $this->tahunKinerja,
            ];

            $create = array_diff_assoc((array) $item, $update);

            Program::updateOrCreate($update, $create);
            $bar->advance();
        }

        $bar->finish();
    }
}
