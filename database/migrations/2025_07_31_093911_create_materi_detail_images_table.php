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
        Schema::create('materi_detail_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('materi_detail_id');
            $table->string('image');
            $table->string('image_name')->nullable();
            $table->string('image_type')->nullable(); // image,pdf
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_detail_images');
    }
};
