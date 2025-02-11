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
            $table->date('mulai_pendaftaran')->after('materi_id');
            $table->date('akhir_pendaftaran')->after('mulai_pendaftaran');
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
            $table->dropColumn('mulai_pendaftaran');
            $table->dropColumn('akhir_pendaftaran');
        });
    }
};
