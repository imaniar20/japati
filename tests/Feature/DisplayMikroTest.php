<?php

namespace Tests\Feature;

use Tests\TestCase;

class DisplayMikroTest extends TestCase
{
    private function get_renstra()
    {
        $this->getJson('/api/display-mikro/renstra')
            ->assertOk();
    }

    private function get_rkt()
    {
        $this->getJson('/api/display-mikro/rkt')
            ->assertOk();
    }

    private function get_perjanjian_kinerja()
    {
        $this->getJson('/api/display-mikro/perjanjian-kinerja')
            ->assertOk();
    }

    private function get_rencana_aksi()
    {
        $this->getJson('/api/display-mikro/rencana-aksi')
            ->assertOk();
    }

    private function get_capaian_kinerja_pd()
    {
        $this->getJson('/api/display-mikro/capaian-kinerja-pd')
            ->assertOk();
    }

    private function get_capaian_kinerja_efisiensi_anggaran()
    {
        $this->getJson('/api/display-mikro/capaian-kinerja-efisiensi-anggaran')
            ->assertOk();
    }

    private function get_capaian_kinerja_keuangan()
    {
        $this->getJson('/api/display-mikro/capaian-kinerja-keuangan')
            ->assertOk();
    }

    private function get_program_inovatif()
    {
        $this->getJson('/api/display-mikro/program-inovatif')
            ->assertOk();
    }

    private function get_capaian_kinerja_kegiatan()
    {
        $this->getJson('/api/display-mikro/capaian-kinerja-kegiatan')
            ->assertOk();
    }

    private function get_capaian_kinerja_sub_kegiatan()
    {
        $this->getJson('/api/display-mikro/capaian-kinerja-sub-kegiatan')
            ->assertOk();
    }

    private function get_capaian_kinerja_langkah_aksi()
    {
        $this->getJson('/api/display-mikro/capaian-kinerja-langkah-aksi')
            ->assertOk();
    }

    private function get_cascading()
    {
        $this->getJson('/api/display-mikro/cascading')
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
            'get_renstra',
            'get_rkt',
            'get_perjanjian_kinerja',
            'get_rencana_aksi',
            'get_capaian_kinerja_pd',
            'get_capaian_kinerja_efisiensi_anggaran',
            'get_capaian_kinerja_keuangan',
            'get_program_inovatif',
            'get_capaian_kinerja_kegiatan',
            'get_capaian_kinerja_sub_kegiatan',
            'get_capaian_kinerja_langkah_aksi',
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

    public function test_cascading_need_satuan_kerja_id_with_super_user()
    {
        $this->actingAs($this->getUserSuper());

        $this->getJson('/api/display-mikro/cascading')
            ->assertOk()
            ->assertJsonCount(0);
    }

    public function test_cascading_need_satuan_kerja_id_with_setda_user()
    {
        $this->actingAs($this->getUserSetda());

        $this->getJson('/api/display-mikro/cascading')
            ->assertOk()
            ->assertJsonCount(0);
    }
}
