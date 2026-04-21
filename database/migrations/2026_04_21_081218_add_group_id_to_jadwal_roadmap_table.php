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
        Schema::table('jadwal_roadmap', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable()->after('gelombang_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');

            // ubah gelombang_id jadi nullable
            $table->unsignedBigInteger('gelombang_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_roadmap', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');

            // ubah gelombang_id jadi not nullable
            $table->unsignedBigInteger('gelombang_id')->nullable(false)->change();
        });
    }
};
