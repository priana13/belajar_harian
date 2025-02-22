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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('tahun_lahir')->nullable();
            $table->string('email', 100)->nullable()->change();
            $table->boolean('status')->nullable()->change();
            $table->string('no_hp', 30)->unique()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tahun_lahir');
            $table->string('email', 100)->change();
            $table->boolean('status')->change();
            $table->string('no_hp', 30)->nullable()->change();
        });
    }
};
