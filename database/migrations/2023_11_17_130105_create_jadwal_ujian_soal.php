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
        Schema::create('jadwal_ujian_soal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_ujian_id');
            $table->foreign('jadwal_ujian_id')->references('id')->on('jadwal_ujian')->cascadeOnDelete();
            $table->unsignedBigInteger('soal_id');
            $table->foreign('soal_id')->references('id')->on('soal');
            $table->timestamps();
        });

        Schema::table('soal', function (Blueprint $table) {
         
            $table->string('urutan');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal_ujian_soal');

        Schema::table('soal', function (Blueprint $table) {
         
            $table->dropColumn('urutan');
           
        });
    }
};
