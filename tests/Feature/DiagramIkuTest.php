<?php

namespace Tests\Feature;

use Tests\TestCase;

class DiagramIkuTest extends TestCase
{
    public function test_diagram_iku_gubernur_without_satuan_kerja()
    {
        $this->getJson('/api/diagram-iku-gubernur')
            ->assertOk();
    }

    public function test_diagram_iku_gubernur_with_satuan_kerja()
    {
        $this->getJson('/api/diagram-iku-gubernur/1030')
            ->assertOk();
    }
}
