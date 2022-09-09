<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\AuthGroupResource;
use App\Http\Resources\Admin\RoleResource;
use App\Models\AuthGroup;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Lauthz\Facades\Enforcer;

class AuthGroupsController extends Controller
{
    //
    public function index(Request $request, AuthGroup $authGroup)
    {
        $list = $authGroup->paginate($request->input('limit'));

        return  AuthGroupResource::collection($list);
    }

    public function treeIndex( RoleService $service)
    {
        $authGroup = $service->getRoleAuthGroupIds(request()->user()->getAuthIdentifier());
        $tree = $service->getRoleTree(0 ,$authGroup);
        return new AuthGroupResource($tree);
    }

}
