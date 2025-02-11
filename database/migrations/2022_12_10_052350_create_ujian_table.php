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
        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_ujian_id')->default(1)->nullable(); // Harian, Mingguan, Akhir. Opt. UTs dll.
            $table->string('nama_ujian');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('nilai')->nullable();
            // $table->string('jenis', 20)->default('Akhir'); // Harian, Mingguan, Akhir. Opt. UTs dll.

            $table->foreign('jenis_ujian_id')->references('id')->on('jenis_ujian');

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
        Schema::dropIfExists('ujian');
    }
};
