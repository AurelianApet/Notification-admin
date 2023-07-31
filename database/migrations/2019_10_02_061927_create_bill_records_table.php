<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('description')->nullable();
            $table->string('media_attach')->nullable();
            $table->string('output_attach')->nullable();
            $table->integer('media_balance')->nullable();
            $table->date('media_expired')->nullable()->default(null);
            $table->integer('output_balance')->nullable();
            $table->date('output_expired')->nullable()->default(null);
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
        Schema::dropIfExists('bill_records');
    }
}
