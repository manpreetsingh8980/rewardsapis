<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RewardSurvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_survey', function (Blueprint $table) {
            $table->increments('id');
			$table->string('campaign_id')->nullable();
			$table->string('icon')->nullable();
			$table->string('name')->nullable();
			$table->longText('url')->nullable();
			$table->string('instructions')->nullable();
			$table->string('description')->nullable();
			$table->string('short_description')->nullable();
			$table->string('amount')->nullable();
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
        Schema::dropIfExists('reward_survey');
    }
}
