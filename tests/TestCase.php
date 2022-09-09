<?php

namespace Tests;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function signJwt($user)
    {

        $class = new \ReflectionClass(get_class($user));
        $token = \Auth::guard(strtolower($class->getShortName()))->login($user);
        if ($token == null){
            return false;
        }
        $this->app->request->headers->set('Authorization', 'Bearer ' . $token);

        return [
            'Authorization' => 'Bearer ' . $token
        ];
    }

}
