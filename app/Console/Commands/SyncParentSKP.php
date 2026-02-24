<?php

namespace App\Console\Commands;

use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\SasaranStrategisPd;
use App\Models\SKP;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class SyncParentSKP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:parent-skp {--tahun=} {--satker=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync kolom parent_id di tabel skp';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tahun = $this->option('tahun');
        $satker = $this->option('satker');

        if ($tahun) {
            setTahunKinerja($tahun);
        }

        $model = SKP::query()
            ->whereIn('model_class', [
                KinerjaProgram::class,
                KinerjaKegiatan::class,
                KinerjaSubKegiatan::class,
            ])
            ->when($tahun, fn ($query) => $query->where('tahun_kinerja', $tahun))
            ->when(count($satker), fn ($query) => $query->whereIn('satuan_kerja_id', $satker));

        $bar = $this->output->createProgressBar($model->count());

        $model->chunkById(500, function (Collection $skpList) use ($bar) {
            foreach ($skpList as $skp) {
                $parentClass = null;
                $parentId = null;

                switch ($skp->model_class) {
                    case KinerjaProgram::class:
                        $parentClass = SasaranStrategisPd::class;
                        $model = KinerjaProgram::query()->find($skp->model_id);

                        if (! $model) {
                            $skp->delete();
                            break;
                        }

                        $parentId = $model->sasaran_strategis_pd_id;
                        break;
                    case KinerjaKegiatan::class:
                        $parentClass = KinerjaProgram::class;
                        $model = KinerjaKegiatan::query()->find($skp->model_id);

                        if (! $model) {
                            $skp->delete();
                            break;
                        }

                        $parentId = $model->kinerja_program_id;
                        break;
                    case KinerjaSubKegiatan::class:
                        $parentClass = KinerjaKegiatan::class;
                        $model = KinerjaSubKegiatan::query()->find($skp->model_id);

                        if (! $model) {
                            $skp->delete();
                            break;
                        }

                        $parentId = $model->kinerja_kegiatan_id;
                        break;
                }

                if ($parentClass && $parentId) {
                    $parentId = SKP::query()
                        ->where('model_class', $parentClass)
                        ->where('model_id', $parentId)
                        ->where('tahun_kinerja', $skp->tahun_kinerja)
                        ->value('id');

                    $skp->timestamps = false;

                    $skp['parent_id'] = $parentId;
                    // $skp['pengampu'] = $model->pengampu;
                    // $skp['v_struktur_organisasi_id'] = $model->pengampu == 'unit-kerja' ? $model->v_struktur_organisasi_id : null;
                    // $skp['tim_kerja_id'] = $model->pengampu == 'tim-kerja' ? $model->tim_kerja_id : null;
                    $skp->save();
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();

        return Command::SUCCESS;
    }
}
