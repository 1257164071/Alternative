<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\RoleResource;
use App\Models\AuthGroup;
use App\Services\RoleService;
use Illuminate\Http\Request;

class AuthGroupsController extends Controller
{
    //
    public function index(Request $request, AuthGroup $authGroup)
    {
        $list = $authGroup->paginate($request->input('limit'));
        return RoleResource::collection($list);
    }

    public function treeIndex(AuthGroup $authGroup, RoleService $service)
    {
        $tree = $service->getRoleTree(0 ,$authGroup->get());
        return new RoleResource($tree);
    }

}
