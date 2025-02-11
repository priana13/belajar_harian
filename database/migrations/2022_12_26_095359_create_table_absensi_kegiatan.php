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
        Schema::create('absensi_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('materi_detail_id')->nullable();
            $table->unsignedBigInteger('kegiatan_id')->nullable();
            $table->string('status_kehadiran', 100)->nullable();
            $table->boolean('siap_hadir')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('kegiatan_id')->references('id')->on('kegiatan');
            // $table->foreign('kelompok_id')->references('id')->on('kelompok');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absensi_kegiatan');
    }
};
