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
        Schema::table('pockets', function (Blueprint $table) {
            $table->string('nombre')->after('user_id')->default('Nuevo apartado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pockets', function (Blueprint $table) {
            $table->dropColumn('nombre');
        });
    }
};
