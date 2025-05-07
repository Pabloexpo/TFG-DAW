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
        Schema::table('partidos', function (Blueprint $table) {
            //Añadimos el campo arbitro a los partidos
            $table->integer('partido_arbitro')->unsigned()->nullable();
            
            $table->foreign('partido_arbitro')->references('persona_id')->on('personas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            //
            $table->dropForeign(['partido_arbitro']);
        });
    }
};
