<?php

namespace Tests\Unit\Admin;


use App\Http\Middleware\JWTRoleAuth;
use App\Models\Admin;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AdminTestCase;
use Illuminate\Http\Request;
class RoleAuthTest extends AdminTestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_guard_can_get_admin_route()
    {
        $this->signJwt(create(Admin::class));
        $request = Request();
        $next = new class {
            public $called = false;
            public function __invoke($request)
            {
                $this->called = true;
                return $request;
            }
        };
        $middleware = new JWTRoleAuth($this->app->get('tymon.jwt.auth'));
        $response = $middleware->handle($request, $next, 'admin');
        $this->assertTrue($next->called);
        $this->assertSame($request, $response);
    }

}
