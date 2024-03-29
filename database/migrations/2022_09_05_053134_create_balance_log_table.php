<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->decimal('price',10,2);
            $table->tinyInteger('status')->default(1); ////1获取   2消费
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balance_log');
    }
}
