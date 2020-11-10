<?php

namespace Tests\Feature\Permission;

use App\Models\Admin;
use App\Models\AuthGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Lauthz\Facades\Enforcer;
use Tests\AdminTestCase;

class AuthGroupTest extends AdminTestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_view_auth_group()
    {
        $this->withExceptionHandling();
        $this->json('GET', '/api/admin/auth_group')->assertStatus(401);
    }


    /** @test */
    public function signed_in_user_can_view_auth_group()
    {
        factory(AuthGroup::class,40)->create();
        $auth = $this->authorization('/api/admin/auth_group', 'GET');
        $result = $this->json('GET', '/api/admin/auth_group', [], $auth);
        $this->assertCount(15, $result['data']);
        $this->assertEquals(40 ,$result['meta']['total']);
    }

    /** @test */
    public function admin_can_get_auth_groups_list()
    {
        $admin = create(Admin::class);
        $auth = $this->authorization('/api/admin/auth-group-tree', 'GET', $admin);
        factory(AuthGroup::class, 5)->create()->each(function ($item){
            Enforcer::guard('admin')->addPermissionForUser('admin', $item->rule, $item->action);
            create(AuthGroup::class,[
                'parent_id' => $item->id,
                'type'  =>  rand(0,1)
            ],5)->each(function ($item) {
                Enforcer::guard('admin')->addPermissionForUser('admin', $item->rule, $item->action);
            });
        });
        Enforcer::guard('admin')->addRoleForUser($admin->getAuthIdentifier(),'admin');

        $result = $this->json('GET', '/api/admin/auth-group-tree', [], $auth);
        $result->assertStatus(200);

        $this->assertCount(5, $result['data']);
        $this->assertCount(5, $result['data'][0]['children']);
    }

}
