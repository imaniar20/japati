<?php

namespace Tests\Feature\PublicDisplay;

use Tests\TestCase;

class DisplayMakroTest extends TestCase
{
    public function test_rpjmd()
    {
        $this->getJson('/api/public-display/display-makro/rpjmd')
            ->assertOk();
    }

    public function test_rkpd()
    {
        $this->getJson('/api/public-display/display-makro/rkpd')
            ->assertOk();
    }

    public function test_perjanjian_kinerja()
    {
        $this->getJson('/api/public-display/display-makro/perjanjian-kinerja')
            ->assertOk();
    }

    public function test_capaian_kinerja_pemda()
    {
        $this->getJson('/api/public-display/display-makro/capaian-kinerja-pemda')
            ->assertOk();
    }

    public function test_capaian_kinerja_efisiensi_anggaran()
    {
        $this->getJson('/api/public-display/display-makro/capaian-kinerja-efisiensi-anggaran')
            ->assertOk();
    }

    public function test_program_inovatif()
    {
        $this->getJson('/api/public-display/display-makro/program-inovatif')
            ->assertOk();
    }

    public function test_capaian_kinerja_keuangan()
    {
        $this->getJson('/api/public-display/display-makro/capaian-kinerja-keuangan')
            ->assertOk();
    }

    public function test_capaian_kinerja_sub_kegiatan()
    {
        $this->getJson('/api/public-display/display-makro/capaian-kinerja-sub-kegiatan')
            ->assertOk();
    }

    public function test_rencana_aksi()
    {
        $this->getJson('/api/public-display/display-makro/rencana-aksi')
            ->assertOk();
    }

    public function test_capaian_kinerja_aktivitas()
    {
        $this->getJson('/api/public-display/display-makro/capaian-kinerja-aktivitas')
            ->assertOk();
    }

    public function test_capaian_kinerja_kegiatan()
    {
        $this->getJson('/api/public-display/display-makro/capaian-kinerja-kegiatan')
            ->assertOk();
    }

    public function test_cascading()
    {
        $this->getJson('/api/public-display/display-makro/cascading')
            ->assertOk();
    }
}
