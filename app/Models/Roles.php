<?php

namespace App\Models;

use App\Services\RoleService;
use Illuminate\Database\Eloquent\Model;
use Lauthz\Facades\Enforcer;

class Roles extends Model
{
    //
    public $fillable = ['name', 'remark', 'guard'];

    public function getAuthGroupIdsAttribute()
    {
        $service = new RoleService();
        if ($this->role != null) {
            return $service->getRoleAuthGroupIds($this->role)->pluck('id');
        }

        return [];
    }
}
