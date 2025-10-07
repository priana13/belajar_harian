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
        Schema::table('jadwal_belajar', function (Blueprint $table) {
            $table->unsignedBigInteger('roadmap_id')->nullable();
            $table->unsignedBigInteger('gelombang_id')->nullable();
            $table->unsignedBigInteger('jadwal_roadmap_id')->nullable();

            $table->unsignedBigInteger("angkatan_id")->nullable()->change();


        });


        Schema::table('jadwal_ujian', function (Blueprint $table) {
            $table->unsignedBigInteger('roadmap_id')->nullable();
            $table->unsignedBigInteger('gelombang_id')->nullable();

            $table->unsignedBigInteger("angkatan_id")->nullable()->change();
        });
      
        Schema::table('ujian', function (Blueprint $table) {
            $table->unsignedBigInteger('roadmap_id')->nullable();
            $table->unsignedBigInteger('gelombang_id')->nullable();

            $table->unsignedBigInteger("angkatan_id")->nullable()->change();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_belajar', function (Blueprint $table) {
            $table->dropColumn('roadmap_id');
            $table->dropColumn('gelombang_id');
             $table->dropColumn('jadwal_roadmap_id');

            $table->unsignedBigInteger("angkatan_id")->change();
        });


        Schema::table('jadwal_ujian', function (Blueprint $table) {
            $table->dropColumn('roadmap_id');
            $table->dropColumn('gelombang_id');

            $table->unsignedBigInteger("angkatan_id")->change();
        });
      
        Schema::table('ujian', function (Blueprint $table) {
            $table->dropColumn('roadmap_id');
            $table->dropColumn('gelombang_id');

            $table->unsignedBigInteger("angkatan_id")->change();
        });


    }
};
