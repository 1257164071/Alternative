<?php

namespace App\Observers\Admin;

use App\Models\Roles;

class RolesObserver
{
    /**
     * Handle the roles "created" event.
     *
     * @param  \App\Models\Roles  $roles
     * @return void
     */
    public function created(Roles $roles)
    {
        if ($roles->role == null) {
            $roles->role = $roles->id . '-' . $roles->name;
            \DB::table('roles')->where('id', $roles->id)->update(['role' => $roles->role]);
        }
    }

    /**
     * Handle the roles "updated" event.
     *
     * @param  \App\Models\Roles  $roles
     * @return void
     */
    public function updated(Roles $roles)
    {
        //
    }

    /**
     * Handle the roles "deleted" event.
     *
     * @param  \App\Models\Roles  $roles
     * @return void
     */
    public function deleted(Roles $roles)
    {
        //
    }

    /**
     * Handle the roles "restored" event.
     *
     * @param  \App\Models\Roles  $roles
     * @return void
     */
    public function restored(Roles $roles)
    {
        //
    }

    /**
     * Handle the roles "force deleted" event.
     *
     * @param  \App\Models\Roles  $roles
     * @return void
     */
    public function forceDeleted(Roles $roles)
    {
        //
    }
}
