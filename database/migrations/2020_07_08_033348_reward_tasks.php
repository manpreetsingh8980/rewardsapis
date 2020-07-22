<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RewardTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_tasks', function (Blueprint $table) {
            $table->increments('id');
			$table->string('title')->nullable();
			$table->text('description')->nullable();
			$table->string('icon')->nullable();
			$table->string('coins')->nullable();
			$table->string('repeat')->nullable();
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
        Schema::dropIfExists('reward_tasks');
    }
}
