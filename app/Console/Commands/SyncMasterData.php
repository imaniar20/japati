<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncMasterData extends Command
{
    private int $tahunKinerja;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:master-data
                            {--tahun-kinerja= : Tahun kinerja. Default mengikuti helper getTahunKinerja()}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync master data program, kegiatan, dan sub kegiatan';

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

        echo 'Sync data program '.$this->tahunKinerja.PHP_EOL;
        $this->call('sync:master-program', ['--tahun-kinerja' => $this->tahunKinerja]);

        echo PHP_EOL.'Sync data kegiatan '.$this->tahunKinerja.PHP_EOL;
        $this->call('sync:master-kegiatan', ['--tahun-kinerja' => $this->tahunKinerja]);

        echo PHP_EOL.'Sync data sub kegiatan '.$this->tahunKinerja.PHP_EOL;
        $this->call('sync:master-subkegiatan', ['--tahun-kinerja' => $this->tahunKinerja]);
    }
}
