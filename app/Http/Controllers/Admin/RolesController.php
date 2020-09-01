<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\AuthGroupResource;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Role;
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
        $role = Role::create([
            'guard' =>  $request->post('guard'),
            'name'  =>  $request->post('name'),
            'remark'=>  $request->post('remark'),
        ]);
        return (new RoleResource($role))->response(201);
    }
}
