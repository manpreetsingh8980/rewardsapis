<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RewardUsersLoginSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_users_login_sessions', function (Blueprint $table) {
            $table->increments('id');
			$table->bigInteger('reward_user_id')->unsigned();
            $table->foreign('reward_user_id')->references('id')->on('reward_users')->onDelete('cascade');
            $table->string('api_token')->nullable();
			$table->tinyInteger('token_status')->default('1');
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
        Schema::dropIfExists('reward_users_login_sessions');
    }
}
