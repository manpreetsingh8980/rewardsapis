<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RewardContactus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_contactus', function (Blueprint $table) {
            $table->increments('id');
			$table->bigInteger('reward_user_id')->unsigned();
            $table->foreign('reward_user_id')->references('id')->on('reward_users')->onDelete('cascade');
			$table->string('email')->nullable();
			$table->text('question')->nullable();
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
        Schema::dropIfExists('reward_contactus');
    }
}
