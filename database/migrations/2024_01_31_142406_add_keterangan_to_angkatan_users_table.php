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
            $table->string('keterangan',20)->nullable();
            $table->string('ipk',15)->nullable();
            $table->string('predikat',15)->nullable();
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
            $table->dropColumn('keterangan');
            $table->dropColumn('ipk');
            $table->dropColumn('predikat');
        });
    }
};
