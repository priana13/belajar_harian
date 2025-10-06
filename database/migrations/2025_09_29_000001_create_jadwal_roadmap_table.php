<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalRoadmapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_roadmap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gelombang_id');
            $table->unsignedBigInteger('roadmap_id');
            $table->unsignedBigInteger('materi_id');
            $table->string('judul');
            $table->string('deskripsi')->nullable();
            $table->date('bulan_tahun');
            $table->date('tanggal_ujian')->nullable();
            $table->boolean('is_aktif')->default(true);
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
        Schema::dropIfExists('jadwal_roadmap');
    }
}