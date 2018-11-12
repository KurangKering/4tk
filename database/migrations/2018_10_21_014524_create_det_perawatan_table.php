<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetPerawatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_perawatan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('perawatan_id')->nullable();
            $table->unsignedInteger('mst_barang_id')->nullable();
            $table->unsignedInteger('jumlah')->nullable();
            $table->decimal('biaya')->nullable();
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
        Schema::dropIfExists('det_perawatan');
    }
}
