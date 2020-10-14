<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AdminTestCase;
use Lauthz\Facades\Enforcer;

class AuthTest extends AdminTestCase
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


//    /** @test */
//    public function test_auth()
//    {
//
//        // adds permissions to a user
//        Enforcer::guard('admin')->addPermissionForUser('eve', 'articles', 'read');
//        // adds a role for a user.
//        Enforcer::guard('admin')->addRoleForUser('eve', 'role1');
//        // adds permissions to a rulez
//        Enforcer::guard('admin')->addPolicy('role1', 'articles','edit');
//        Enforcer::guard('admin')->addPolicy('role1', 'articles','add');
//        Enforcer::guard('admin')->addPolicy('role1', 'articles','ac');
//
//        Enforcer::guard('admin')->addPolicy('role2', 'articles','fff');
//        Enforcer::guard('admin')->addRoleForUser('eve', 'role2');
//
//        dd(\DB::table('rules')->select()->get());
//        dd(Enforcer::guard('basic')->enforce("eve", "articles", "fff"));
//
//    }


}
