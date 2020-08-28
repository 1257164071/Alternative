<?php

namespace Tests;


use App\Models\Admin;

abstract class AdminTestCase extends TestCase
{
    protected function singIn($admin = null)
    {
        $admin = $admin ?: create(Admin::class);
        $this->actingAs($admin,'admin');
        return $this;
    }

}
