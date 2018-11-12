<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetDistribusiAtkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_distribusi_atk', function (Blueprint $table) {
           $table->increments('id');
           $table->unsignedInteger('mst_atk_id')->nullable();
           $table->unsignedInteger('jumlah')->nullable();
           $table->unsignedInteger('tahap_distribusi_atk_id')->nullable();
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
        Schema::dropIfExists('det_distribusi_atk');
    }
}
