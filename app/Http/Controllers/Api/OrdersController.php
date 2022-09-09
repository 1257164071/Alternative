<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\OrderResource;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(){
        $orders = \Auth::user()->orders()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return OrderResource::collection($orders);
    }
}
