<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetPembelianAtkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_pembelian_atk', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mst_atk_id')->nullable();
            $table->unsignedInteger('pembelian_atk_id')->nullable();
            $table->unsignedInteger('jumlah')->nullable();
            $table->decimal('harga')->nullable();
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
        Schema::dropIfExists('det_pembelian_atk');
    }
}
