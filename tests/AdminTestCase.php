<?php

namespace Tests;


use App\Models\Admin;
use Lauthz\Facades\Enforcer;

abstract class AdminTestCase extends TestCase
{
    protected function singIn($admin = null)
    {
        $admin = $admin ?: create(Admin::class);
        $this->actingAs($admin,'admin');
        return $this;
    }


    public function authorization(string  $rule,string $action,Admin $admin = null)
    {
        if ($admin == null){
            $admin = create(Admin::class);
        }
        Enforcer::guard('admin')->addPolicy($admin->getAuthIdentifier(), $rule, $action);
        return $this->signJwt($admin);
    }

}
