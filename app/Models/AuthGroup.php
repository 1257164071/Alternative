<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AuthGroup
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AuthGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthGroup query()
 * @mixin \Eloquent
 */
class AuthGroup extends Model
{
    //
    const TYPE_MENU = 0;
    const TYPE_OPERATE = 1;


}
