<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AuthGroupResource;
use App\Models\AuthGroup;
use Illuminate\Http\Request;

class AuthGroupsController extends Controller
{
    //
    public function index(Request $request, AuthGroup $authGroup)
    {
        $list = $authGroup->paginate($request->input('limit'));
        return AuthGroupResource::collection($list);
    }
}
