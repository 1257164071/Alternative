<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\UserResource;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //
    public function me(Request $request)
    {

        return new UserResource($request->user());
    }
}
