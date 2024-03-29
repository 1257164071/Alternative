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
            $table->string('recharge_type');
            $table->string('recharge_status')->default(\App\Models\Order::RECHARGE_STATUS_PENDING);
            $table->text('recharge_json')->nullable();
            $table->string('telephone');
            $table->string('product_id')->nullable();
            $table->decimal('price',10,2);
            $table->decimal('recharge',10,2)->default(0);
            $table->string("remark")->nullable();
            $table->dateTime("paid_at")->nullable();
            $table->string("payment_no")->nullable();
            $table->string('refund_status')->default(\App\Models\Order::REFUND_STATUS_PENDING);
            $table->string("refund_no")->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('extra')->nullable();
            $table->tinyInteger('closed')->nullable()->default(0);
            $table->string('order_number')->nullable();
            $table->text('recharge_order_json')->nullable();
            $table->text('notify_order_json')->nullable();

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
