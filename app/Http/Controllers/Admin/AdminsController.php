<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InvalidRequestException;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminsController extends Controller
{

    //
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function index(Request $request, Admin $admin)
    {

        $admins = $admin->paginate($request->input('limit'))->append('roles');

        return AdminResource::collection($admins);
    }

    public function store(Request $request, Admin $admin)
    {
        $this->validate(request(), [
            'username'  =>  ['required', 'unique:admins'],
            'password'  =>  'required',
            'name'  =>  'required',
        ]);

        $admin->fill($request->post())->save();
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
        $this->validate($request, [
            'username' => ['unique:admins'],
        ]);
        if ($admin->id == $request->user()->id) {
            throw new InvalidRequestException('无法对此用户进行操作',422);
        }

        if (!$admin->update($request->all())) {
            throw  new InvalidRequestException('用户不存在', 422);
        }
        return response()->json([], 200);
    }
}
