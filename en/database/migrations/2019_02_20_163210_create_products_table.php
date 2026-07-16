<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->longText('description');
            $table->longText('package_detail')->nullable();
            $table->tinyInteger('type')->default(0);
//            $table->unsignedBigInteger('category_id');
//            $table->foreign('category_id')->references('id')->on('categoryproducts')->onDelete('cascade');
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->bigInteger('viewCount')->default(0);
            $table->bigInteger('commentCount')->default(0);
            $table->bigInteger('soldCount')->default(0);
            $table->string('code')->nullable();
            $table->string('weight')->default(0)->comment("وزن محصول");
            $table->boolean('shipping_cost')->default(1)->comment("هزینه ارسال"); // 0 free and 1 $
            $table->boolean('amazing')->default(0);
            $table->boolean('special')->default(0);
            $table->boolean('sales')->default(0);
            $table->boolean('momentary')->default(0);
            $table->boolean('selected_brand')->default(0);
            $table->integer('sorting')->default(0)->nullable();
            $table->string('lang' , 10)->default('fa');
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
        Schema::dropIfExists('products');
    }
}
