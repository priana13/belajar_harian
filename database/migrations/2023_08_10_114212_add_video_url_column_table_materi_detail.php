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
            $table->string('multimedia_url', 200)->nullable()->after('isi');
            $table->string('thumbnail', 200)->nullable()->after('multimedia_url');
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
            $table->dropColumn('multimedia_url');
            $table->dropColumn('thumbnail');
        });
    }
};
