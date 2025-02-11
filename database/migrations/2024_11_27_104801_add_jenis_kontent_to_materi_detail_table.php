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
        Schema::table('materi_detail', function (Blueprint $table) {
            $table->string("jenis_kontent")->after('judul')->default("Audio"); // Audio , Video , Text
            $table->string("video_url")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materi_detail', function (Blueprint $table) {
            $table->dropColumn('jenis_kontent');
            $table->dropColumn('video_url');
        });
    }
};
