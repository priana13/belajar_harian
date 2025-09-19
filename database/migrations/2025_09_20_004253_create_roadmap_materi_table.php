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
        Schema::create('roadmap_materi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('roadmap_id');
            $table->unsignedBigInteger('materi_id');

            // $table->foreignId('roadmap_id')->references('id')->on('roadmaps')->onDelete('casecade');
            // $table->foreignId('materi_id')->references('id')->on('materi')->onDelete('casecade');

            $table->timestamps();

            $table->unique(['roadmap_id', 'materi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmap_materi');
    }
};
