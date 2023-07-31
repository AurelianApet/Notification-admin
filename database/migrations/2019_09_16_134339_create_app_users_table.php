<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('pwd');
            $table->integer('group_id');
            $table->string('lat')->default("0.0");
            $table->string('lng')->default("0.0");
            $table->dateTime('position_update_time')->nullable();
            $table->boolean('is_allow')->default(true);
            $table->string('token')->default("");
            $table->string('platform')->default("");
            $table->string('model')->default("");
            $table->string('number')->default("");
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
        Schema::dropIfExists('app_users');
    }
}
