<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Se nos ha olvidado el campo email al generar la tabla personas, lo generamos con migraciones
     */
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('persona_email')->unique()->nullable(false); // Agrega el campo 'email' y lo hace único
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('persona_email'); // Elimina el campo 'email' si la migración se revierte
        });
    }
};
