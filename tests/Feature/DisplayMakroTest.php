<?php

namespace Tests\Feature;

use Tests\TestCase;

class DisplayMakroTest extends TestCase
{
    private function get_rpjmd()
    {
        $this->getJson('/api/display-makro/rpjmd')
            ->assertOk();
    }

    private function get_rkpd()
    {
        $this->getJson('/api/display-makro/rkpd')
            ->assertOk();
    }

    private function get_perjanjian_kinerja()
    {
        $this->getJson('/api/display-makro/perjanjian-kinerja')
            ->assertOk();
    }

    private function get_capaian_kinerja_pemda()
    {
        $this->getJson('/api/display-makro/capaian-kinerja-pemda')
            ->assertOk();
    }

    private function get_capaian_kinerja_efisiensi_anggaran()
    {
        $this->getJson('/api/display-makro/capaian-kinerja-efisiensi-anggaran')
            ->assertOk();
    }

    private function get_program_inovatif()
    {
        $this->getJson('/api/display-makro/program-inovatif')
            ->assertOk();
    }

    private function get_capaian_kinerja_keuangan()
    {
        $this->getJson('/api/display-makro/capaian-kinerja-keuangan')
            ->assertOk();
    }

    private function get_capaian_kinerja_sub_kegiatan()
    {
        $this->getJson('/api/display-makro/capaian-kinerja-sub-kegiatan')
            ->assertOk();
    }

    private function get_rencana_aksi()
    {
        $this->getJson('/api/display-makro/rencana-aksi')
            ->assertOk();
    }

    private function get_capaian_kinerja_aktivitas()
    {
        $this->getJson('/api/display-makro/capaian-kinerja-aktivitas')
            ->assertOk();
    }

    private function get_capaian_kinerja_kegiatan()
    {
        $this->getJson('/api/display-makro/capaian-kinerja-kegiatan')
            ->assertOk();
    }

    private function get_cascading()
    {
        $this->getJson('/api/display-makro/cascading')
            ->assertOk();
    }

    public function userFeatures()
    {
        $users = [
            'super' => 'getUserSuper',
            'setda' => 'getUserSetda',
            'user tim' => 'getUserPemerintahDaerah',
            'user perencanaan' => 'getUserPerangkatDaerah',
        ];

        $userFeatures = [];

        foreach ($users as $label => $user) {
            foreach ($this->features() as $feature) {
                $userFeatures[$label.' '.$feature] = [$user, $feature];
            }
        }

        return $userFeatures;
    }

    private function features()
    {
        return [
            'get_rpjmd',
            'get_rkpd',
            'get_perjanjian_kinerja',
            'get_capaian_kinerja_pemda',
            'get_capaian_kinerja_efisiensi_anggaran',
            'get_program_inovatif',
            'get_capaian_kinerja_keuangan',
            'get_capaian_kinerja_sub_kegiatan',
            'get_rencana_aksi',
            'get_capaian_kinerja_aktivitas',
            'get_capaian_kinerja_kegiatan',
            'get_cascading',
        ];
    }

    /**
     * @dataProvider userFeatures
     */
    public function test_user_can_access_features($user, $feature)
    {
        $this->actingAs($this->{$user}());
        $this->{$feature}();
    }
}
