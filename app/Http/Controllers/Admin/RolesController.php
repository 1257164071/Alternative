<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\AuthGroupResource;
use App\Http\Resources\Admin\MenuGroupResource;
use App\Http\Resources\Admin\RoleResource;
use App\Models\AuthGroup;
use App\Models\Role;
use App\Models\User;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index(Request $request, Role $role)
    {
        $list = $role->paginate($request->input('limit'));

        return RoleResource::collection($list);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'guard' => 'required',
            'name' => 'required',
        ]);
        $role = Role::create([
            'guard' =>  $request->post('guard'),
            'name'  =>  $request->post('name'),
            'remark'=>  $request->post('remark'),
        ]);
        $role->auth_groups()->attach($request->post('bind_auth_group_ids'));
        return (new RoleResource($role))->response(201);
    }

    public function update(Request $request ,Role $role)
    {
        $this->validate($request, [
            'guard' => 'required',
            'name' => 'required',
        ]);

        $role->update($request->all());
        $role->auth_groups()->detach();
        $role->auth_groups()->attach($request->post('bind_auth_group_ids'));
        return (new RoleResource($role))->response(202);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json([], 204);
    }

    public function bindAuthGroup(Role $role, Request $request)
    {
        $this->validate($request, [
            'auth_group_ids' => 'required',
        ]);

        $ids = $request->post('auth_group_ids');
        $role->auth_groups()->attach($ids);
    }

    public function roleAuthGroup(Role $role)
    {
        return new MenuGroupResource($role->auth_groups);
    }




}
