<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTahapDistribusiAtkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tahap_distribusi_atk', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('tanggal_distribusi');
            $table->unsignedInteger('distribusi_atk_id');
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
        Schema::dropIfExists('tahap_distribusi_atk');
    }
}
