<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RewardRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_request', function (Blueprint $table) {
            $table->increments('id');
			$table->bigInteger('reward_user_id')->unsigned();
            $table->foreign('reward_user_id')->references('id')->on('reward_users')->onDelete('cascade');
			$table->integer('reward_id')->unsigned();
            $table->foreign('reward_id')->references('id')->on('reward_allrewards')->onDelete('cascade');
			$table->tinyInteger('reward_status')->default('0')->comment = '0 for Pending, 1 for approved, 2 for rejected';
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
        Schema::dropIfExists('reward_request');
    }
}
