<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermintaanAtkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permintaan_atk', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('subbidang_id')->nullable();
            $table->unsignedInteger('permintaan_user_id')->nullable();
            $table->dateTime('tanggal_permintaan')->nullable();
            $table->string('is_paraf')->nullable();
            $table->unsignedInteger('paraf_user_id')->nullable();
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
        Schema::dropIfExists('permintaan_atk');
    }
}
