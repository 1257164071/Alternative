<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InvalidRequestException;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;
use App\Models\Roles;
use Illuminate\Http\Request;
use Lauthz\Facades\Enforcer;

class AdminsController extends Controller
{

    //
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function index(Request $request, Admin $admin)
    {

        $admins = $admin->paginate($request->input('limit'));
        array_map(function (&$item){
            $item->roles = $item->roles;
            return $item;
        }, $admins->items());
        return AdminResource::collection($admins);
    }

    public function store(Request $request, Admin $admin)
    {
        $this->validate(request(), [
            'username'  =>  ['required', 'unique:admins'],
            'password'  =>  'required',
            'name'  =>  'required',
        ]);
        $admin->fill($request->post());
        $admin->password = \Hash::make($admin->password);
        $admin->save();

        if($role = Roles::find($request->post('roles'))){
            Enforcer::guard($role->guard)->addRoleForUser($admin->getAuthIdentifier(), $role->role);
        }

        return (new AdminResource($admin))->response(201);
    }

    public function destroy($adminId, Request $request)
    {
        if ($request->user()->id == $adminId) {
            throw new InvalidRequestException('无法对此用户进行操作',422);
        }
        if(!Admin::where('id', $adminId)->delete()) {
            throw  new InvalidRequestException('用户不存在', 422);
        }
        return response()->json([], 204);
    }

    public function update(Admin $admin,Request $request)
    {
        if ($admin->id == $request->user()->id) {
            throw new InvalidRequestException('无法对此用户进行操作',422);
        }
        $temp = $request->all();
        if($request->post('password') != ''){
            $temp['password'] = \Hash::make($temp['password']);
        }
        Enforcer::deleteRolesForUser($admin->getAuthIdentifier());

        if($role = Roles::where(['role' => $request->post('roles')])->first()){
            Enforcer::guard($role->guard)->addRoleForUser($admin->getAuthIdentifier(), $role->role);
        }

        if (!$admin->update($temp)) {
            throw  new InvalidRequestException('用户不存在', 422);
        }
        return response()->json([], 200);
    }
}
