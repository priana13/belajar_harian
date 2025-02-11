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
        Schema::create('materi_detail', function (Blueprint $table) {
            $table->id();            
            $table->string("pertemuan", 100);
            $table->string("judul", 100);
            $table->text("isi")->nullable();
            $table->string('audio', 150)->nullable();

            $table->unsignedBigInteger("materi_id");
            $table->foreign('materi_id')->references('id')->on('materi')->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::table('soal', function(Blueprint $table){

            $table->unsignedBigInteger("materi_detail_id")->nullable();
            $table->foreign('materi_detail_id')->references('id')->on('materi_detail')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materi_detail');

        Schema::table('soal', function(Blueprint $table){

            $table->dropForeign('materi_detail_id');
            $table->dropColumn('materi_detail_id');

        });
    }
};
