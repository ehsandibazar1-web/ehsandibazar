<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('subject');
            $table->longText('body');
            $table->boolean('status')->default(false);
            $table->integer('priority')->comment("اولویت")->default(\App\Utility\TicketType::Medium);
            $table->integer('departeman')->comment("دپارتمان")->default(1);
            $table->boolean('send_email')->comment("ارسال ایمیل")->default(false);
            $table->string('tracking_code')->nullable();
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
        Schema::dropIfExists('ticket');
    }
}
