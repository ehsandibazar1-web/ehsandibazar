<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('birth_date')->nullable();
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->text('address')->nullable();
            $table->text('mobile')->nullable();
            $table->string('job')->nullable();
            $table->string('history_sports_activities')->nullable();
            $table->text('prohibition_sports')->nullable();
            $table->text('physical_limitations')->nullable();
            $table->text('fear_injury')->nullable();
            $table->text('self_defense_skills')->nullable();
            $table->text('purpose_exercise')->nullable();
            $table->text('get_acquainted')->nullable();
            $table->string('social_networkId')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('consultations');
    }
}
