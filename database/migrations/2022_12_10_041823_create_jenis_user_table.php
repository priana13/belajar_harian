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
        Schema::create('jenis_user', function (Blueprint $table) {
            $table->id();
            $table->string("nama_jenis");
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            //
            $table->foreign('jenis_user_id')->references('id')->on('jenis_user');

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenis_user');

        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropForeign('jenis_user_id');

        });


    }
};
