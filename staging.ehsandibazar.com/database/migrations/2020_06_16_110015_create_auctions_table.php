<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('start_date');
            $table->string('start_price');
            $table->string('end_price');
            $table->integer('click_count')->default(0);
            $table->string('every_click_price');
            $table->string('every_click_price_for_pay');
            $table->string('participant_count');
            $table->boolean('status')->default(true)->comment('وضعیت : در حال برگزاری 1 یا اتمام 0');
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
        Schema::dropIfExists('auctions');
    }
}
