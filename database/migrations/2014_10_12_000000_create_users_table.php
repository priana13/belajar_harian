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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->nullable()->unique();
            $table->string('kode_user')->nullable()->unique();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 100);
            // $table->unsignedBigInteger('wilayah_id')->nullable();
            // $table->unsignedBigInteger('kelompok_id')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('temp_lahir', 50)->nullable();
            $table->string('alamat', 200)->nullable();
            $table->string('kota', 50)->nullable();
            $table->string('no_hp', 30)->nullable();
            $table->string('foto_profil', 150)->nullable();
            $table->unsignedBigInteger('jenis_user_id')->default(2);
            $table->boolean('status', 1)->default(TRUE);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
