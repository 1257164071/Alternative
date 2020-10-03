<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\RoleResource;
use App\Models\AuthGroup;
use App\Models\Roles;
use App\Services\RoleService;
use Lauthz\Facades\Enforcer;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    //
    public function index()
    {
        $list = Roles::get()->append('auth_group_ids');

        return new RoleResource($list);
    }


    public function store(Request $request, Roles $roles, AuthGroup $authGroup)
    {
        $this->validate($request, [
            'name'  => ['required'],
            'guard' => ['required'],
            'remark' => ['max:255'],
        ]);
        $roles->fill($request->post())->save();
        if ($bind_auth_group_ids = $request->post('bind_auth_group_ids')){
            $authGroup->whereIn('id', $bind_auth_group_ids)->each(function ($item) use($roles) {
                Enforcer::guard($roles->guard)->addPolicy($roles->role, $item['rule'], $item['action']);
            });
        }
        return response()->json($roles, 201);
    }


    public function update(Roles $roles, Request $request, AuthGroup $authGroup)
    {

        $authGroupIds = $request->post('bind_auth_group_ids');

        Enforcer::guard($roles->guard)->deletePermissionsForUser($roles->role);
        $authGroup->whereIn('id', $authGroupIds)->each(function ($item) use ($roles) {
            Enforcer::guard($roles->guard)->addPolicy($roles->role, $item['rule'], $item['action']);
        });

        $roles->update($request->all());
        return response()->json([], 200);
    }



}
