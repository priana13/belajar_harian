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
        Schema::table('soal_ujian', function (Blueprint $table) {
            $table->foreign('soal_id')->references('id')->on('soal')->cascadeOnDelete();
            $table->foreign('ujian_id')->references('id')->on('ujian')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('soal_ujian', function (Blueprint $table) {
            $table->dropForeign(['soal_id']);
            $table->dropForeign(['ujian_id']);
            $table->dropForeign(['user_id']);
        });
    }
};
