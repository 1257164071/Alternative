<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('action');
            $table->tinyInteger('type')->default(\App\Models\AuthGroup::TYPE_MENU);
            $table->string('rule');
            $table->integer('parent_id');
            $table->string('guard')->default('admin');
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
        Schema::dropIfExists('auth_groups');
    }
}
