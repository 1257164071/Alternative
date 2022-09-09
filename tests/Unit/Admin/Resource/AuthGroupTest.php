<?php

namespace Tests\Unit\Admin\Resource;

use App\Http\Resources\Admin\AuthGroupResource;
use App\Models\AuthGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AdminTestCase;

class AuthGroupTest extends AdminTestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_auth_group()
    {
        $AuthGroup = create(AuthGroup::class);
        $resource = new AuthGroupResource($AuthGroup);
        $this->assertEqualsIgnoringCase($AuthGroup->toArray(),$resource->resolve());
    }

}
