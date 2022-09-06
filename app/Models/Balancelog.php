<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balancelog extends Model
{
    //
    protected $table = "balance_log";
    protected $fillable = ['user_id','price','status'];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
