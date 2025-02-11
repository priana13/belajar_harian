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
        Schema::table('angkatan_users', function (Blueprint $table) {
            $table->double('nilai')->nullable();
            $table->integer('peringkat')->nullable();            
        });

        Schema::table('ujian', function (Blueprint $table) {

            $table->unsignedBigInteger('kelas_id')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('angkatan_users', function (Blueprint $table) {
            $table->dropColumn('nilai');
            $table->dropColumn('peringkat');
        });


        Schema::table('ujian', function (Blueprint $table) {

            $table->dropColumn('kelas_id')->nullable();            
        });
    }
};
