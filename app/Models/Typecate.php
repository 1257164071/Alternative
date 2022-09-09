<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Typecate extends Model
{
    //
    protected $table = "typecate";
    protected $fillable = ['name','price','amount','type_id','cate_name','isp','name','is_open', 'type'];
}
