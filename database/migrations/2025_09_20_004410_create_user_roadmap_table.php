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
        Schema::create('user_roadmap', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('roadmap_id');
            $table->unsignedBigInteger('user_id');

            // $table->foreignId('roadmap_id')->references('id')->on('roadmaps')->onDelete('casecade');
            // $table->foreignId('user_id')->references('id')->on('users')->onDelete('casecade');

            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikat
            $table->unique(['roadmap_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roadmap');
    }
};
