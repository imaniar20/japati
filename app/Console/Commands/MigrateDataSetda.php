<?php

namespace App\Console\Commands;

use App\Models\KinerjaKegiatan;
use App\Models\KinerjaLangkahAksi;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use App\Models\VisiMisiRpjmd;
use Illuminate\Console\Command;

class MigrateDataSetda extends Command
{
    private array $satkerIds = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:data-setda';

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

        $this->satkerIds = getSatuanKerjaIds(SATKER_SETDA);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->visiMisi();
        $this->sasaranStrategisRpjmd();
        $this->sasaranStrategisPd();
    }

    private function sasaranStrategisPd()
    {
        SasaranStrategisPd::whereIn('satuan_kerja_id', $this->satkerIds)->update([
            'satuan_kerja_id' => SATKER_SETDA,
        ]);

        $data = SasaranStrategisPd::where('satuan_kerja_id', SATKER_SETDA)->get();
        $data = $this->loopFilter($data, [
            'sasaran_strategis_id',
            'indikator_sasaran_strategis_id',
            'sasaran_strategis_satker',
            'iku',
            'sasaran_strategis_rpjmd_id',
        ]);

        foreach ($data as $item) {
            KinerjaKegiatan::whereIn('sasaran_strategis_pd_id', $item['remove'])
                ->update([
                    'sasaran_strategis_pd_id' => $item['keep'],
                ]);

            KinerjaLangkahAksi::whereIn('sasaran_strategis_pd_id', $item['remove'])
                ->update([
                    'sasaran_strategis_pd_id' => $item['keep'],
                ]);

            KinerjaProgram::whereIn('sasaran_strategis_pd_id', $item['remove'])
                ->update([
                    'sasaran_strategis_pd_id' => $item['keep'],
                ]);

            KinerjaProgram::whereIn('satker_iku_id', $item['remove'])
                ->update([
                    'satker_iku_id' => $item['keep'],
                ]);

            KinerjaSubKegiatan::whereIn('sasaran_strategis_pd_id', $item['remove'])
                ->update([
                    'sasaran_strategis_pd_id' => $item['keep'],
                ]);
        }
    }

    private function sasaranStrategisRpjmd()
    {
        SasaranStrategisRpjmd::whereIn('satuan_kerja_id', $this->satkerIds)->update([
            'satuan_kerja_id' => SATKER_SETDA,
        ]);

        $data = SasaranStrategisRpjmd::where('satuan_kerja_id', SATKER_SETDA)->get();
        $data = $this->loopFilter($data, [
            'misi_id',
            'tujuan_id',
            'sasaran_strategis_id',
            'indikator_sasaran_strategis_id',
            'indikator_tujuan_id',
            'target_visi_misi_rpjmd_id',
        ]);

        foreach ($data as $item) {
            SasaranStrategisPd::whereIn('sasaran_strategis_rpjmd_id', $item['remove'])
                ->update([
                    'sasaran_strategis_rpjmd_id' => $item['keep'],
                ]);

            KinerjaKegiatan::whereIn('sasaran_strategis_rpjmd_id', $item['remove'])
                ->update([
                    'sasaran_strategis_rpjmd_id' => $item['keep'],
                ]);

            KinerjaSubKegiatan::whereIn('sasaran_strategis_rpjmd_id', $item['remove'])
                ->update([
                    'sasaran_strategis_rpjmd_id' => $item['keep'],
                ]);

            KinerjaLangkahAksi::whereIn('sasaran_strategis_rpjmd_id', $item['remove'])
                ->update([
                    'sasaran_strategis_rpjmd_id' => $item['keep'],
                ]);
        }
    }

    private function visiMisi()
    {
        VisiMisiRpjmd::whereIn('satuan_kerja_id', $this->satkerIds)->update([
            'satuan_kerja_id' => SATKER_SETDA,
        ]);

        $data = VisiMisiRpjmd::where('satuan_kerja_id', SATKER_SETDA)->get();
        $data = $this->loopFilter($data, ['misi_id', 'tujuan_id', 'indikator_tujuan_id']);

        foreach ($data as $item) {
            SasaranStrategisRpjmd::whereIn('target_visi_misi_rpjmd_id', $item['remove'])
                ->update([
                    'target_visi_misi_rpjmd_id' => $item['keep'],
                ]);
        }
    }

    private function loopRemove(object $data)
    {
        $result = [];

        $data->each(function ($items) use (&$result) {
            $keep = $items->pop();

            $result[] = [
                'keep' => $keep->id,
                'remove' => $items->pluck('id')->toArray(),
            ];

            foreach ($items as $item) {
                $item->delete();
            }
        });

        return $result;
    }

    private function loopFilter(object $data, array $columns)
    {
        $lastColumn = array_pop($columns);

        foreach ($columns as $column) {
            $data = $this->filter($data, $column);
        }

        $data = $this->transform($data, $lastColumn);

        $result = $this->loopRemove($data);

        return $result;
    }

    private function filter(object $data, string $column)
    {
        return $data->groupBy($column)
            ->filter(fn ($item) => $item->count() > 1)
            ->flatten();
    }

    private function transform(object $data, string $column)
    {
        return $data->groupBy($column)
            ->filter(fn ($item) => $item->count() > 1)
            ->values();
    }
}
