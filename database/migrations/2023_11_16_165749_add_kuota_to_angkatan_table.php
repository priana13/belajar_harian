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
        Schema::table('angkatan', function (Blueprint $table) {

            $table->integer('kuota')->after('tanggal_ujian');
            $table->integer('jumlah_per_kelas')->after('kuota')->default(200);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('angkatan', function (Blueprint $table) {
            $table->dropColumn('kuota');
            $table->dropColumn('jumlah_per_kelas');
        });
    }
};
