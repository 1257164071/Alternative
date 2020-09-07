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

    public function auth_groups()
    {
        return $this->belongsToMany(AuthGroup::class,'auth_group_role');
    }
}
