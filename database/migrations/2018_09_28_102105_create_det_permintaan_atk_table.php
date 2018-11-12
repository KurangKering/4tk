<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetPermintaanAtkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_permintaan_atk', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('permintaan_atk_id')->nullable();
            $table->unsignedInteger('mst_atk_id')->nullable();
            $table->unsignedInteger('jumlah')->nullable();
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
        Schema::dropIfExists('det_permintaan_atk');
    }
}
