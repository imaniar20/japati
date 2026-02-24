<?php

namespace App\Console\Commands;

use App\Models\KinerjaSubKegiatan;
use App\Models\PerubahanJumlahOutput as JumlahOutput;
use Exception;
use Illuminate\Console\Command;

class PerubahanJumlahOutput extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jumlah-output {tahunKinerja} {awal/akhir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perubahan jumlah output';

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
     */
    public function handle()
    {
        $column = $this->argument('awal/akhir');

        if (! in_array($column, ['awal', 'akhir'])) {
            throw new Exception('Kolom tidak dikenal');
        }

        $data = KinerjaSubKegiatan::query()
            ->where('tahun_kinerja', $this->argument('tahunKinerja'))
            ->selectRaw('satuan_kerja_id, COUNT(*) total')
            ->groupBy('satuan_kerja_id')
            ->pluck('total', 'satuan_kerja_id');

        foreach ($data as $key => $value) {
            $jumlah = JumlahOutput::query()
                ->where('tahun_kinerja', $this->argument('tahunKinerja'))
                ->where('satuan_kerja_id', $key)
                ->first();

            if ($jumlah) {
                if ($column == 'awal') {
                    $jumlah->awal = $value;
                    $diff = $jumlah->akhir - $value;
                    $jumlah->perubahan = $diff < 0 ? 0 : $diff;
                } else {
                    $jumlah->akhir = $value;
                    $diff = $value - $jumlah->awal;
                    $jumlah->perubahan = $diff < 0 ? 0 : $diff;
                }

                $jumlah->save();
            } else {
                JumlahOutput::query()
                    ->create([
                        'tahun_kinerja' => $this->argument('tahunKinerja'),
                        'satuan_kerja_id' => $key,
                        'awal' => $this->argument('awal/akhir') == 'awal' ? $value : 0,
                        'akhir' => $this->argument('awal/akhir') == 'akhir' ? $value : 0,
                        'perubahan' => 0,
                    ]);
            }
        }
    }
}
