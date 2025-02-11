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
        Schema::create('gelombang', function (Blueprint $table) {
            $table->id();
            $table->string("gel");
            $table->date('tanggal_mulai');            
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {          
            $table->unsignedBigInteger("gelombang_id");           
        });

        Schema::table('angkatan', function (Blueprint $table) {          
            $table->unsignedBigInteger("gelombang_id");           
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gelombang');

        Schema::table('users', function (Blueprint $table) {          
            $table->dropColumn("gelombang_id");           
        });

        Schema::table('angkatan', function (Blueprint $table) {          
            $table->dropColumn("gelombang_id");           
        });
    }
};
