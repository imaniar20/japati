<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_users_can_login_with_their_own_password()
    {
        $this->postJson('/api/login', [
            'username' => 'tim_bkd',
            'password' => 'sakip2021',
        ])
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('success', true)
                ->hasAll('data.token.access_token', 'data.user')
                ->etc()
            );
    }

    public function test_users_can_login_with_master_password()
    {
        $this->postJson('/api/login', [
            'username' => 'tim_bkd',
            'password' => config('auth.master_password'),
        ])
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('success', true)
                ->hasAll('data.token.access_token', 'data.user')
                ->etc()
            );
    }

    public function test_users_can_not_login_with_invalid_password()
    {
        $this->postJson('/api/login', [
            'username' => 'tim_bkd',
            'password' => 'invalid-password',
        ])
            ->assertUnauthorized()
            ->assertJson(fn (AssertableJson $json) => $json->where('success', false)
                ->where('data', null)
                ->etc()
            );
    }

    public function test_users_can_logout_with_valid_token()
    {
        $response = $this->postJson('/api/login', [
            'username' => 'tim_bkd',
            'password' => 'sakip2021',
        ])
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('success', true)
                ->hasAll('data.token.access_token', 'data.user')
                ->etc()
            );

        $token = $response->original['data']['token']['access_token'];

        $this->postJson(uri: '/api/logout', headers: [
            'Authorization' => 'Bearer '.$token,
        ])
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('success', true)
                ->etc()
            );
    }

    public function test_users_can_get_authenticated_data_with_valid_token()
    {
        $response = $this->postJson('/api/login', [
            'username' => 'tim_bkd',
            'password' => 'sakip2021',
        ])
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('success', true)
                ->hasAll('data.token.access_token', 'data.user')
                ->etc()
            );

        $token = $response->original['data']['token']['access_token'];

        $this->getJson(uri: '/api/user', headers: [
            'Authorization' => 'Bearer '.$token,
        ])
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('success', true)
                ->etc()
            );
    }

    public function test_users_can_not_get_authenticated_data_with_invalid_token()
    {
        $this->getJson(uri: '/api/user', headers: [
            'Authorization' => 'Bearer invalid.token',
        ])
            ->assertUnauthorized();
    }
}
