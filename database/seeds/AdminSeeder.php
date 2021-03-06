<?php

use Illuminate\Database\Seeder;
use \App\Models\Admin;
use Lauthz\Facades\Enforcer;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(Admin::class, 10)->create();

        $admin = Admin::find(1);
        $admin->username = 'admin';
        $admin->password = \Hash::make('123456');
        $admin->save();
        Enforcer::guard('admin')->addRoleForUser($admin->id, 'admin');
    }
}
