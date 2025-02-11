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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();

            $table->string('nama_kelas'); 

            $table->unsignedBigInteger("angkatan_id");
            $table->foreign('angkatan_id')->references('id')->on('angkatan')->cascadeOnDelete();

            $table->unsignedBigInteger('admin1')->nullable();
            $table->foreign('admin1')->references('id')->on('users')->nullOnDelete();

            $table->unsignedBigInteger('admin2')->nullable();
            $table->foreign('admin2')->references('id')->on('users')->nullOnDelete();



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
        Schema::dropIfExists('kela');
    }
};
