<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypecateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typecate', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->decimal('price',10, 2);
            $table->decimal('amount',10,2);
            $table->integer('type_id');
            $table->tinyInteger('type')->default(1);
            $table->string('cate_name');
            $table->tinyInteger('isp')->nullable();
            $table->string('name');
            $table->tinyInteger('is_open')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('typecate');
    }
}
