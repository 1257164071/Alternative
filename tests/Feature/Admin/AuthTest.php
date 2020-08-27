<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function username_is_required()
    {
        $this->withExceptionHandling();
        $this->json('POST', '/api/admin/authorizations', [
            'username' => '',
            'password' => '123456',
        ])->assertStatus(422)->assertJsonValidationErrors(['username']);
    }

    /** @test */
    public function password_is_required()
    {
        $this->withExceptionHandling();
        $this->json('POST', '/api/admin/authorizations', [
            'username' => 'admin',
            'password' => '',
        ])->assertStatus(422)->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function credentials_error_cant_login()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->json('POST', '/api/admin/authorizations', [
            'username' => 'admin',
            'password' => '123456789000',
        ]);
    }

    /** @test */
    public function login_in()
    {
        $password = '123456789000';
        $admin = factory(Admin::class)->create([
            'username' => 'admin',
            'password' => bcrypt($password),
        ]);
        $this->json('POST', '/api/admin/authorizations', [
           'username' => $admin->username,
           'password' => $password,
        ])->assertStatus(201)->assertJsonStructure(['access_token','token_type']);
    }
}
