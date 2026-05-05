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
        // buat table struktur
        Schema::create('struktur', function (Blueprint $table) {
            $table->id();
            $table->string('nama_struktur');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('struktur_id')->nullable()->after('jenis_user_id');
                $table->foreign('struktur_id')->references('id')->on('struktur')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['struktur_id']);
            $table->dropColumn('struktur_id');
        });

        Schema::dropIfExists('struktur');
    }
};