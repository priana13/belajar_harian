<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('type', 100); //Safda , Jaulah Dakwah
            $table->string('tempat', 100);
            $table->string('link_lokasi', 100)->nullable();
            $table->timestamp('waktu')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('jenis', 20)->nullable();
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
        Schema::dropIfExists('kegiatan');
    }
};
