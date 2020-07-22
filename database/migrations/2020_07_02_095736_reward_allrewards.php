<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RewardAllrewards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_allrewards', function (Blueprint $table) {
            $table->increments('id');
			$table->bigInteger('admin_id')->unsigned();
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('reward_title')->nullable();
			$table->text('reward_description')->nullable();
			$table->string('reward_icons')->nullable();
			$table->string('reward_coins')->nullable();
			$table->text('legal_text')->nullable();
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
        Schema::dropIfExists('reward_allrewards');
    }
}
