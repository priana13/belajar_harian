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
        // Migration untuk tabel sertifikat_user
        Schema::create('sertifikat_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sertifikat_id');
            $table->unsignedBigInteger('materi_id');
            $table->string('predikat');
            $table->string('tanggal'); // ex: Bogor, 29 Jan 2024
            $table->string('code')->unique();
            $table->string('ttd_image')->nullable();
            $table->string('ttd_nama');
            $table->string('ttd_jabatan');
            $table->timestamps();
            
            // Foreign keys - sesuaikan nama tabel dengan database Anda
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('sertifikat_id')->references('id')->on('sertifikats')->onDelete('cascade');
            // $table->foreign('materi_id')->references('id')->on('materis')->onDelete('cascade');
            
            // Index untuk performa query
            $table->index(['user_id', 'materi_id']);
        });

        // Migration untuk tabel daftar_nilai
        Schema::create('daftar_nilai', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('materi_id');
            $table->decimal('ujian_harian', 5, 2)->nullable();
            $table->string('predikat_ujian_harian')->nullable();
            $table->decimal('ujian_pekanan', 5, 2)->nullable();
            $table->string('predikat_ujian_pekanan')->nullable();
            $table->decimal('ujian_akhir', 5, 2)->nullable();
            $table->string('predikat_ujian_akhir')->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->decimal('ipk', 3, 2)->nullable();
            $table->string('predikat');
            $table->string('tanggal'); // ex: Bogor, 29 Jan 2024
            $table->string('code')->unique();
            $table->string('ttd_image')->nullable();
            $table->string('ttd_nama');
            $table->string('ttd_jabatan');
            $table->timestamps();
            
            // Foreign keys - sesuaikan nama tabel dengan database Anda
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('materi_id')->references('id')->on('materis')->onDelete('cascade');
            
            // Index untuk performa query
            $table->index(['user_id', 'materi_id']);
            // Unique constraint untuk mencegah duplikasi nilai per user per materi
            $table->unique(['user_id', 'materi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('daftar_nilai');
         Schema::dropIfExists('sertifikat_user');
    }
};
