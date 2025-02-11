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
        Schema::create('gelombang', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->date('tgl_mulai');
            $table->integer('tahun');
            $table->timestamps();
        });

        // tambahkan gelombang_id ke table users, angkatan/kelas

        Schema::table('users', function(Blueprint $table){

            $table->unsignedBigInteger('gelombang_id')->nullable();

        });

        Schema::table('angkatan', function(Blueprint $table){
            $table->unsignedBigInteger('gelombang_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gelombang');

        Schema::table('users', function(Blueprint $table){

            $table->dropColumn('gelombang_id');

        });

        Schema::table('angkatan', function(Blueprint $table){
            $table->dropColumn('gelombang_id');
        });
    }
};
