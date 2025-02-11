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
        Schema::create('soal', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('jenis_ujian_id')->default(1); // Harian, Mingguan, Akhir. ops: UTs, dll.
            $table->foreign('jenis_ujian_id')->references('id')->on('jenis_ujian');
           
            $table->integer('nomor');

            $table->integer('pekan')->nullable(); // untuk soal Mingguan

            $table->unsignedBigInteger('materi_id');
            $table->string('judul');
            $table->text('detail')->nullable();
            $table->string('a');
            $table->string('b');
            $table->string('c')->nullable();
            $table->string('d')->nullable();
            $table->string('e')->nullable();
            $table->string('kunci',2);
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
        Schema::dropIfExists('soal');
    }
};
