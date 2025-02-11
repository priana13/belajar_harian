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
        Schema::create('angkatan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_angkatan')->nullable()->unique();
            $table->string('kode_daftar')->nullable()->unique(); // untuk url public layaknya slug
            $table->unsignedBigInteger("materi_id");
            $table->timestamp('tanggal_mulai');
            $table->timestamp('tanggal_akhir');
            $table->timestamp('tanggal_ujian');
            // $table->string('admin1', 30)->nullable();
            // $table->string('admin2', 30)->nullable();
            $table->string('status')->default('Pendaftaran'); // Pendaftaran,Aktif,Selesai,Full
            $table->timestamps();

            $table->foreign('materi_id')->references('id')->on('materi');

        });

        Schema::table('ujian', function(Blueprint $table){

            $table->unsignedBigInteger("angkatan_id");
            $table->foreign('angkatan_id')->references('id')->on('angkatan');

        });

        Schema::table('jadwal_belajar', function(Blueprint $table){

            $table->unsignedBigInteger("angkatan_id");
            $table->foreign('angkatan_id')->references('id')->on('angkatan');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('angkatan');


        Schema::table('ujian', function(Blueprint $table){

            $table->dropForeign('angkatan_id');
            $table->dropColumn('angkatan_id');

        });

        Schema::table('jadwal_belajar', function(Blueprint $table){

            $table->dropForeign('angkatan_id');
            $table->dropColumn('angkatan_id');

        });


    }
};
