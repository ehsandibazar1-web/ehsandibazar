<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('paymentable_id');
            $table->string('paymentable_type');
            $table->longText('user_info')->nullable();
            $table->string('resnumber')->nullable();
            $table->string('tracking_code')->nullable();
            $table->longText('details')->nullable(); //baraye details kharid haye dg...
            $table->string('price')->default('0');
            $table->boolean('payment')->default(false);
            $table->tinyInteger('payment_type')->default(0);
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
        Schema::dropIfExists('payments');
    }
}
