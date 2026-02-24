<?php

namespace Tests\Feature\PublicDisplay;

use Tests\TestCase;

class DisplayMikroTest extends TestCase
{
    public function test_renstra()
    {
        $this->getJson('/api/public-display/display-mikro/renstra')
            ->assertOk();
    }

    public function test_rkt()
    {
        $this->getJson('/api/public-display/display-mikro/rkt')
            ->assertOk();
    }

    public function test_perjanjian_kinerja()
    {
        $this->getJson('/api/public-display/display-mikro/perjanjian-kinerja')
            ->assertOk();
    }

    public function test_rencana_aksi()
    {
        $this->getJson('/api/public-display/display-mikro/rencana-aksi')
            ->assertOk();
    }

    public function test_capaian_kinerja_pd()
    {
        $this->getJson('/api/public-display/display-mikro/capaian-kinerja-pd')
            ->assertOk();
    }

    public function test_capaian_kinerja_efisiensi_anggaran()
    {
        $this->getJson('/api/public-display/display-mikro/capaian-kinerja-efisiensi-anggaran')
            ->assertOk();
    }

    public function test_capaian_kinerja_keuangan()
    {
        $this->getJson('/api/public-display/display-mikro/capaian-kinerja-keuangan')
            ->assertOk();
    }

    public function test_program_inovatif()
    {
        $this->getJson('/api/public-display/display-mikro/program-inovatif')
            ->assertOk();
    }

    public function test_capaian_kinerja_kegiatan()
    {
        $this->getJson('/api/public-display/display-mikro/capaian-kinerja-kegiatan')
            ->assertOk();
    }

    public function test_capaian_kinerja_sub_kegiatan()
    {
        $this->getJson('/api/public-display/display-mikro/capaian-kinerja-sub-kegiatan')
            ->assertOk();
    }

    public function test_capaian_kinerja_langkah_aksi()
    {
        $this->getJson('/api/public-display/display-mikro/capaian-kinerja-langkah-aksi')
            ->assertOk();
    }

    public function test_cascading_need_satuan_kerja_id()
    {
        $this->getJson('/api/public-display/display-mikro/cascading')
            ->assertStatus(422);
    }

    public function test_cascading()
    {
        $this->getJson('/api/public-display/display-mikro/cascading?satuan_kerja_id=1030')
            ->assertOk();
    }
}
