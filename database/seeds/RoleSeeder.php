<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role = factory(\App\Models\Role::class)->create();
        $ids = \App\Models\AuthGroup::where('guard', 'admin')->get()->pluck('id')->toArray();
        $role->auth_groups()->attach($ids);
    }
}
