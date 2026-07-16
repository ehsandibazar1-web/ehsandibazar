<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */

    public function up()
    {

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            /* serialize user information */
            $table->longText('user_info');

            // serialize , address

            $table->integer('total_amount');
            $table->bigInteger('credit_count')->nullable();

            //total discount
            $table->integer('total_discount')->default(0);
            $table->string('shippingCost')->nullable();
            $table->longText('coupon')->nullable();

            $table->integer('payment_method_id')->default(0);
            /* $table->integer('discount')->default(0);*/
            $table->string('shipping_method_id')->default(0);
            /*     $table->integer('shipping_amount')->default(0);*/
            $table->string('tracking_code');
            $table->string('ref_id')->nullable();

            $table->string('shipping_code')->nullable();
            $table->longText('rest_number')->nullable();
            $table->integer('item_count')->default(1);
            $table->boolean('status')->default(0);
            $table->bigInteger('expire');
            $table->softDeletes();
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
        Schema::dropIfExists('orders');
    }
}
