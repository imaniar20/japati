<?php

namespace Tests\Feature;

use Tests\TestCase;

class HelloTest extends TestCase
{
    public function test_root_route_web()
    {
        $this->get('/')
            ->assertOk();
    }

    public function test_root_route_api()
    {
        $this->getJson('/api')
            ->assertOk();
    }
}
