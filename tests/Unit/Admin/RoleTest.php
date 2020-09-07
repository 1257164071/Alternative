<?php

namespace Tests\Unit\Admin;

use App\Models\AuthGroup;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AdminTestCase;

class RoleTest extends AdminTestCase
{
    use RefreshDatabase;

    /** @test */
    public function auth_group_belongs_to_many_role()
    {
        $role = create(Role::class);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\belongsToMany', $role->auth_groups());
    }


    /** @test */
    public function can_get_role_list_tree()
    {
        factory(AuthGroup::class, 5)->create()->each(function ($item){
            create(AuthGroup::class,[
                'parent_id' => $item->id,
                'type'  =>  rand(0,1)
            ],5);
        });
        $service = new RoleService();
        $result = $service->getRoleTree();
        $this->assertCount(5,$result);
        $this->assertCount(5, $result[0]['children']);
    }

    /** @test */
    public function role_is_have_matching_auth_group(){
        $role = create(Role::class);
        factory(AuthGroup::class, 5)->create()->each(function ($item){
            create(AuthGroup::class,[
                'parent_id' => $item->id,
                'type'  =>  rand(0,1)
            ],5);
        });
        $auth_group = AuthGroup::all();
        $role->auth_groups()->attach($auth_group->get(1));

        $service = new RoleService();

        $this->assertTrue($service->authorize($role, $auth_group->get(1)->rule));
        $this->expectException('App\Exceptions\InvalidRequestException');
        $service->authorize($role->id, $auth_group->get(2)->rule);
    }

}
