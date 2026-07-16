<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_costs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('of_weight')->unsigned()->default(1)->comment("از وزن");
            $table->bigInteger('upto_weight')->unsigned()->default(1)->comment("تا وزن");
            $table->bigInteger('price')->unsigned()->default(500)->comment("قیمت");
            $table->tinyInteger('type')->default(false)->comment("نوع : پیشتاز،سفارشی");
            $table->tinyInteger('post_type')->default(false)->comment("نوع ارسال : درون شهری،برون شهری");
            $table->text('description')->nullable()->comment("توضیحات");
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
        Schema::dropIfExists('shipping_costs');
    }
}
