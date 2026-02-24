<?php

namespace App\Console\Commands;

use App\Models\KinerjaProgram;
use App\Models\SasaranStrategisPd;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MappingKinerjaProgram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapping:kinerja-program';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $data = DB::select('SELECT
            kinerja_program.id,
            kinerja_program.satuan_kerja_id,
            kinerja_program.sasaran_strategis_pd_id,
            kinerja_program.satker_iku_id,
            ss.sasaran_strategis_satker sasaran,
            iku.iku
        FROM
            kinerja_program
            LEFT JOIN sasaran_strategis_pd ss ON ss.id = kinerja_program.sasaran_strategis_pd_id
            LEFT JOIN sasaran_strategis_pd iku ON iku.id = kinerja_program.satker_iku_id
        WHERE
            sasaran_strategis_pd_id != satker_iku_id
        ORDER BY
            kinerja_program.satuan_kerja_id');

        foreach ($data as $item) {
            $sspd = SasaranStrategisPd::where('satuan_kerja_id', parseSatuanKerjaId($item->satuan_kerja_id))
                ->where('sasaran_strategis_satker', $item->sasaran)
                ->where('iku', $item->iku)
                ->first();

            if (! $sspd) {
                continue;
            }

            KinerjaProgram::where('id', $item->id)->update([
                'sasaran_strategis_pd_id' => $sspd->id,
                'satker_iku_id' => $sspd->id,
            ]);
        }
    }
}
