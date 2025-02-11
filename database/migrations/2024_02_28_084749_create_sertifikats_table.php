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
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('bg');
            $table->timestamps();
        });

        Schema::table('angkatan', function (Blueprint $table) {
          
            $table->unsignedBigInteger('sertifikat_id')->nullable();
          
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sertifikats');

        Schema::table('angkatan', function (Blueprint $table) {
          
            $table->dropColumn('sertifikat_id');
          
        });


    }
};
