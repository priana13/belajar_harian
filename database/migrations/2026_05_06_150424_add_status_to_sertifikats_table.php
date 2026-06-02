<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sertifikats', function (Blueprint $table) {

            $table->string('status')->default('Aktif');
            $table->string('ttd_nama')->nullable();
            $table->string('ttd_jabatan')->nullable();
            $table->string('ttd_image')->nullable();

            $table->string('ttd_nama2')->nullable();
            $table->string('ttd_jabatan2')->nullable();
            $table->string('ttd_image2')->nullable();

        });

        // tambah juga ke sertifikat_user
        Schema::table('sertifikat_user', function (Blueprint $table) {
               
                $table->string('ttd_nama2')->nullable();
                $table->string('ttd_jabatan2')->nullable();
                $table->string('ttd_image2')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sertifikats', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('ttd_nama');
            $table->dropColumn('ttd_jabatan');
            $table->dropColumn('ttd_image');

            $table->dropColumn('ttd_nama2');
            $table->dropColumn('ttd_jabatan2');
            $table->dropColumn('ttd_image2');            
        });

        Schema::table('sertifikat_user', function (Blueprint $table) {           
            $table->dropColumn('ttd_nama2');
            $table->dropColumn('ttd_jabatan2');
            $table->dropColumn('ttd_image2');
        });
    }
};
