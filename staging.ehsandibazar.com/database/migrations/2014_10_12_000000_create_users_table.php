<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('active')->default(0);
            $table->tinyInteger('block')->default(0);
            $table->integer('level')->default(\App\Utility\Level::USER);
            $table->string('name');
            $table->string('family')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile');
            $table->text('image_national_cart')->nullable();
            $table->text('image_license')->nullable();
            $table->string('tell')->nullable();
            $table->string('national_code')->nullable();
            $table->string('economic_code')->nullable();
            $table->text('full_address')->nullable();
            $table->integer('age')->nullable();
            $table->integer('discount_percent')->default(0);
            $table->integer('sex')->default(0);
            $table->integer('code')->nullable();
            $table->string('password');
            $table->string('wallet')->default(0)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
