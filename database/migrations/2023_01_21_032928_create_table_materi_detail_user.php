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
        Schema::create('materi_detail_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('materi_detail_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status')->nullable(); // sudah baca, sudah ujian
            $table->timestamps();

            $table->foreign('materi_detail_id')->references('id')->on('materi_detail')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materi_detail_user');
    }
};
