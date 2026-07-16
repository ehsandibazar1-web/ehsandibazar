<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysteminfmanagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('systeminfmanages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('systeminf_id');
            $table->foreign('systeminf_id')->references('id')->on('systeminfs')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('code2')->nullable();
            $table->string('code3')->nullable();
            $table->text('code4')->nullable();
            $table->text('code5')->nullable();
            $table->string('status')->default(0);
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
        Schema::dropIfExists('systeminfmanages');
    }
}
