<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class AuthorizationsController extends Controller
{

    public function store(Request $request)
    {
        $this->validate(request(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('username','password');
        if (!$token = \Auth::guard('admin')->attempt($credentials)) {
            throw new AuthenticationException('用户名或密码错误');
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ],201);
    }


}
