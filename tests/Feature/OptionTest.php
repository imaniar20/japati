<?php

namespace Tests\Feature;

use Tests\TestCase;

class OptionTest extends TestCase
{
    public function test_misi()
    {
        $this->getJson('/api/option/misi')
            ->assertOk();
    }

    public function test_tujuan()
    {
        $this->getJson('/api/option/tujuan')
            ->assertOk();
    }

    public function test_indikator_tujuan()
    {
        $this->getJson('/api/option/indikator-tujuan')
            ->assertOk();
    }

    public function test_satuan_kerja()
    {
        $this->getJson('/api/option/satuan-kerja')
            ->assertOk();
    }

    public function test_unit_kerja_need_satuan_kerja_id()
    {
        $this->getJson('/api/option/unit-kerja/')
            ->assertNotFound();
    }

    public function test_unit_kerja()
    {
        $this->getJson('/api/option/unit-kerja/1030')
            ->assertOk();
    }

    public function test_sasaran_strategis()
    {
        $this->getJson('/api/option/sasaran-strategis')
            ->assertOk();
    }

    public function test_indikator_sasaran_strategis()
    {
        $this->getJson('/api/option/indikator-sasaran-strategis')
            ->assertOk();
    }

    public function test_sasaran_kinerja_program_should_unauthorized()
    {
        $this->getJson('/api/option/sasaran/kinerja-program')
            ->assertUnauthorized();
    }

    public function test_sasaran_kinerja_kegiatan_should_unauthorized()
    {
        $this->getJson('/api/option/sasaran/kinerja-kegiatan')
            ->assertUnauthorized();
    }

    public function test_sasaran_kinerja_sub_kegiatan_should_unauthorized()
    {
        $this->getJson('/api/option/sasaran/kinerja-sub-kegiatan')
            ->assertUnauthorized();
    }

    public function test_sasaran_kinerja_langkah_aksi_should_unauthorized()
    {
        $this->getJson('/api/option/sasaran/kinerja-langkah-aksi')
            ->assertUnauthorized();
    }

    public function test_indikator_kinerja_program_should_unauthorized()
    {
        $this->getJson('/api/option/indikator/kinerja-program')
            ->assertUnauthorized();
    }

    public function test_indikator_kinerja_kegiatan_should_unauthorized()
    {
        $this->getJson('/api/option/indikator/kinerja-kegiatan')
            ->assertUnauthorized();
    }

    public function test_indikator_kinerja_sub_kegiatan_should_unauthorized()
    {
        $this->getJson('/api/option/indikator/kinerja-sub-kegiatan')
            ->assertUnauthorized();
    }

    public function test_indikator_kinerja_langkah_aksi_should_unauthorized()
    {
        $this->getJson('/api/option/indikator/kinerja-langkah-aksi')
            ->assertUnauthorized();
    }

    public function test_sasaran_kinerja_program_should_ok()
    {
        $user = $this->getUserPemerintahDaerah();

        $this->actingAs($user)
            ->getJson('/api/option/sasaran/kinerja-program')
            ->assertOk();
    }

    public function test_sasaran_kinerja_kegiatan_should_ok()
    {
        $user = $this->getUserPemerintahDaerah();

        $this->actingAs($user)
            ->getJson('/api/option/sasaran/kinerja-kegiatan')
            ->assertOk();
    }

    public function test_sasaran_kinerja_sub_kegiatan_should_ok()
    {
        $user = $this->getUserPemerintahDaerah();

        $this->actingAs($user)
            ->getJson('/api/option/sasaran/kinerja-sub-kegiatan')
            ->assertOk();
    }

    public function test_sasaran_kinerja_langkah_aksi_should_ok()
    {
        $user = $this->getUserPemerintahDaerah();

        $this->actingAs($user)
            ->getJson('/api/option/sasaran/kinerja-langkah-aksi')
            ->assertOk();
    }

    public function test_indikator_kinerja_program_should_ok()
    {
        $user = $this->getUserPemerintahDaerah();

        $this->actingAs($user)
            ->getJson('/api/option/indikator/kinerja-program')
            ->assertOk();
    }

    public function test_indikator_kinerja_kegiatan_should_ok()
    {
        $user = $this->getUserPemerintahDaerah();

        $this->actingAs($user)
            ->getJson('/api/option/indikator/kinerja-kegiatan')
            ->assertOk();
    }

    public function test_indikator_kinerja_sub_kegiatan_should_ok()
    {
        $user = $this->getUserPemerintahDaerah();

        $this->actingAs($user)
            ->getJson('/api/option/indikator/kinerja-sub-kegiatan')
            ->assertOk();
    }

    public function test_indikator_kinerja_langkah_aksi_should_ok()
    {
        $user = $this->getUserPemerintahDaerah();

        $this->actingAs($user)
            ->getJson('/api/option/indikator/kinerja-langkah-aksi')
            ->assertOk();
    }
}
