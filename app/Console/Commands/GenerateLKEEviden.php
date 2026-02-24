<?php

namespace App\Console\Commands;

use App\Models\SatuanKerja;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class GenerateLKEEviden extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:lke-eviden';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (SatuanKerja::all() as $satker) {
            Artisan::call('sync:lke-eviden', [
                'tahunKinerja' => 2024,
                'satuanKerja' => $satker->satuan_kerja_id,
            ]);
        }

        return 0;
    }
}
