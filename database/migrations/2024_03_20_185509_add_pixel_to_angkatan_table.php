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
            $table->string('fb_pixel_id')->nullable();
            $table->string('fb_capi')->nullable();
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
            $table->dropColumn('fb_pixel_id');
            $table->dropColumn('fb_capi');
        });
    }
};
