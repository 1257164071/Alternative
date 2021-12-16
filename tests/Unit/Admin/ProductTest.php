<?php

namespace Tests\Unit\Admin;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AdminTestCase;

class ProductTest extends AdminTestCase
{
    use RefreshDatabase;
    /** @test */
    public function products_has_category()
    {
        $product = factory(Product::class)->create();
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsTo', $product->category());
    }
}
