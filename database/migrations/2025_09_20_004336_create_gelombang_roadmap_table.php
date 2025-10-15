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
        Schema::create('gelombang_roadmap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('roadmap_id');
            $table->unsignedBigInteger('gelombang_id');

            // $table->foreignId('roadmap_id')->references('id')->on('roadmaps')->onDelete('casecade');
            // $table->foreignId('gelombang_id')->references('id')->on('gelombang')->onDelete('casecade');

            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikat
            $table->unique(['roadmap_id', 'gelombang_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gelombang_roadmap');
    }
};
