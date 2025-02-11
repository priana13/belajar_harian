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
        Schema::create('angkatan_users', function (Blueprint $table) {
            $table->id();

            $table->string('kode_angkatan')->nullable();
            
            $table->unsignedBigInteger("user_id");
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unsignedBigInteger("angkatan_id");
            $table->foreign('angkatan_id')->references('id')->on('angkatan')->cascadeOnDelete();

            $table->unsignedBigInteger("kelas_id");
            $table->foreign('kelas_id')->references('id')->on('kelas')->cascadeOnDelete();


            $table->string('status')->default('Aktif'); // Aktif, Lulus, DO

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
        Schema::dropIfExists('angkatan_users');
    }
};
