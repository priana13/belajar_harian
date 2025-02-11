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
        // Schema::table('soal', function (Blueprint $table) {
        //     //
        //     $table->foreign('materi_id')->references('id')->on('materi');
        // });

        // Schema::table('users', function (Blueprint $table) {
        //     //
        //     $table->foreign('kelompok_id')->references('id')->on('kelompok');
        // });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('soal', function (Blueprint $table) {
            //
            $table->dropForeign(['materi_id']);
        });


        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropForeign('kelompok_id');
        });
    }
};
