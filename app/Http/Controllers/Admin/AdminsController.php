<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminsController extends Controller
{

    //
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
