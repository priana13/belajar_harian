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
        //
        Schema::table('ujian', function (Blueprint $table) {
            //            
            $table->unsignedBigInteger('materi_id');
            $table->string('keterangan', 50);

            $table->foreign('materi_id')->references('id')->on('materi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('ujian', function (Blueprint $table) {
            //
            $table->dropForeign('materi_id');
            $table->drop('keterangan');
        });
    }
};
