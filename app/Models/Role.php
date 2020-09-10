<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Role
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @mixin \Eloquent
 */
class Role extends Model
{
    //
    protected $fillable = ['guard', 'name', 'remark'];

    protected $appends = ['auth_group_ids'];

    public function auth_groups()
    {
        return $this->belongsToMany(AuthGroup::class,'auth_group_role');
    }

    public function getAuthGroupIdsAttribute ()
    {
        return $this->auth_groups->pluck('id')->toArray();
    }
}
