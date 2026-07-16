<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->tinyInteger('baseon')->default(0); // 0 => cent | 1 => price
            $table->bigInteger('cent');
            $table->smallInteger('type')->default(0); // 0 => simple (sade) , ...
            $table->smallInteger('discountable_type')->default(3); // 0 => Brand,Product,... 3=>product
            $table->bigInteger('count_user')->nullable(); // 0 => unlimited
            $table->bigInteger('count_buy')->nullable(); // 0 => unlimited
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('discounts');
    }
}
