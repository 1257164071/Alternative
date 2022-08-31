<?php

namespace Tests\Feature\Api;

use App\Models\Admin;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\UserTestCase;
use Lauthz\Facades\Enforcer;

class OrderTest extends UserTestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_test()
    {
        $product = factory(Order::class)->create();
        dump($product->toArray());
    }



}
