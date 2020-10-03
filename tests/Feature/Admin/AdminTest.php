<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Lauthz\Facades\Enforcer;
use Tests\AdminTestCase;
use Tymon\JWTAuth\JWTAuth;

class AdminTest extends AdminTestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_get_self_info()
    {
        $this->withExceptionHandling();
        $this->json('GET', '/api/admin/me')->assertStatus(401);
    }

    /** @test */
    public function can_get_self_info()
    {
        $user = ['username' => 'fdsfds'];
        $admin = factory(Admin::class)->create($user);
        $auth = $this->authorization('/api/admin/me', 'GET', $admin);

        $this->json('GET', '/api/admin/me', [], $auth)->assertStatus(200)->assertJsonFragment($user);
    }

    /** @test */
    public function admin_can_get_admins_list()
    {
        $admins = create(Admin::class,[], 40);

        $auth = $this->authorization('/api/admin/admins', 'GET', $admins->get(1));

        $result = $this->json('GET', '/api/admin/admins', [], $auth);
        $result->assertStatus(200);
        $this->assertCount(15, $result['data']);
        $this->assertEquals(40 ,$result['meta']['total']);
    }

    /** @test */
    public function admin_can_store_new_admin_and_bind_role()
    {
        $auth = $this->authorization('/api/admin/admins', 'POST');

        $admin = make(Admin::class);
        $admin = $admin->toArray();
        $admin['password'] = 123456;
        $result = $this->json('POST', '/api/admin/admins', $admin, $auth);

        $result->assertStatus(201);
    }

    /** @test */
    public function admin_cannot_create_repeat_admin_username()
    {
        $admin = create(Admin::class);
        $auth = $this->authorization('/api/admin/admins', 'POST', $admin);

        $admin = make(Admin::class, ['username' => $admin->username]);
        $admin = $admin->toArray();
        $admin['password'] = 123456;
        $this->withExceptionHandling();
        $result = $this->json('POST', '/api/admin/admins', $admin, $auth);
        $result->assertStatus(422);
    }

    /** @test */
    public function admin_cannot_destroy_self()
    {

        $admins = create(Admin::class, [], 2);
        $this->withExceptionHandling();
        $auth = $this->authorization('/api/admin/admins/*', 'DELETE', $admins->get(1));
        $result = $this->json('DELETE', '/api/admin/admins/'. $admins->get(1)->id, [], $auth);
        $result->assertStatus(422);
    }

    /** @test */
    public function admin_can_destroy_else_admin()
    {
        $admins = create(Admin::class, [], 2);
        $auth = $this->authorization('/api/admin/admins/*', 'DELETE', $admins->get(0));
        $result = $this->json('DELETE', '/api/admin/admins/'. $admins->get(1)->id, [], $auth);
        $result->assertStatus(204);
        $this->assertCount(1, Admin::all());
    }

    /** @test */
    public function admin_can_update_else_admin()
    {
        $admins = create(Admin::class, [], 2);
        $auth = $this->authorization('/api/admin/admins/*', 'PUT', $admins->get(0));

        $result = $this->json('PUT', '/api/admin/admins/' .$admins->get(1)->id, [
            'name' => '11111',
            'username' => '22222',
            'password'  =>  \Hash::make('123456AACC'),
        ], $auth);
        $result->assertStatus(200);
        $admin = Admin::find( $admins->get(1)->id);
        $this->assertEquals('11111', $admin->name);
        $this->assertEquals('22222', $admin->username);
        $token = \Auth::guard ('admin')->attempt([
            'username' => '22222',
            'password' => '123456AACC',
        ]);
        $this->assertIsString($token);
    }

    /** @test */
    public function admin_cannot_update_equal_username_else_admin()
    {
        $this->withExceptionHandling();
        $admins = create(Admin::class, [], 2);
        $auth = $this->authorization('/api/admin/admins/*', 'PUT', $admins->get(0));
        $result = $this->json('PUT', '/api/admin/admins/' .$admins->get(0)->id, [
            'name' => $admins->get(0)->username,
        ], $auth);
        $result->assertStatus(422);
    }

    /** @test */
    public function admin_cannot_update_self()
    {
        $this->withExceptionHandling();
        $admins = create(Admin::class, [], 2);
        $auth = $this->authorization('/api/admin/admins/*', 'PUT', $admins->get(0));
        $result = $this->json('PUT', '/api/admin/admins/' .$admins->get(0)->id, [
            'name' => 'fsd',
        ], $auth);
        $result->assertStatus(422);
    }
}
