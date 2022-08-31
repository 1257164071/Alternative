<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no');
            $table->string('user_id');
            $table->text('recharge_type');
            $table->string('recharge_status')->default(\App\Models\Order::RECHARGE_STATUS_PENDING);
            $table->string('recharge_json')->nullable();
            $table->text('telephone');
            $table->string('product_id');
            $table->decimal('price',10,2);
            $table->string("remark")->nullable();
            $table->string("paid_at")->nullable();
            $table->string("payment_no")->nullable();
            $table->string('refund_status')->default(\App\Models\Order::REFUND_STATUS_PENDING);
            $table->string("refund_no")->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('extra')->nullable();
            $table->boolean('closed')->default(false);
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
        Schema::dropIfExists('products');
    }
}
