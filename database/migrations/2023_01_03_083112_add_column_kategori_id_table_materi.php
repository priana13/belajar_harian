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
        Schema::table('materi', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('kategori_id')->nullable()->after('type');

            $table->foreign('kategori_id')->references('id')->on('kategori_materi');
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
        Schema::table('materi', function (Blueprint $table) {
            //
            $table->dropForeign('kategori_id');
        });
    }
};
