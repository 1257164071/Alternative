<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('no')->unique();
            $table->integer('user_id')->nullable();
            $table->decimal('price',10,2);
            $table->tinyInteger('status')->default(0);//0未使用 1 已使用
            $table->dateTime('use_date')->nullable();
            $table->integer('create_user_id')->nullable();
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
        Schema::dropIfExists('card');
    }
}
