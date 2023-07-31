<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->unique();
            $table->integer('media_balance')->nullable();
            $table->date('media_expired')->nullable()->default(null);
            $table->boolean('media_activate')->nullable();
            $table->integer('output_balance')->nullable();
            $table->date('output_expired')->nullable()->default(null);
            $table->boolean('output_activate')->default(false);
            $table->string("output_user_name");
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
        Schema::dropIfExists('bills');
    }
}
