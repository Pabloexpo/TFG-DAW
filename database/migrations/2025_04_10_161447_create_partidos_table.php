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
        Schema::create('partidos', function (Blueprint $table) {
            $table->increments('partido_id');
            //id de los equipos participantes
            $table->integer('partido_equ_1')->unsigned()->nullable();
            $table->integer('partido_equ_2')->unsigned()->nullable();
            $table->integer('partido_sets_equ_1')->nullable();
            $table->integer('partido_sets_equ_2')->nullable();
            $table->integer('partido_ganador')->unsigned()->nullable();
            $table->date('partido_fecha')->nullable();
            $table->integer('partido_pista')->unsigned()->nullable();
            
            // Claves foráneas de equipo
            $table->foreign('partido_equ_1')->references('equipo_id')->on('equipos')->onDelete('set null');
            $table->foreign('partido_equ_2')->references('equipo_id')->on('equipos')->onDelete('set null');
            $table->foreign('partido_ganador')->references('equipo_id')->on('equipos')->onDelete('set null');
            //fk de pistas
            $table->foreign('partido_pista')->references('pista_id')->on('pistas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->dropForeign(['partido_eq_1']);
            $table->dropForeign(['partido_eq_2']);
            $table->dropForeign(['partido_ganador']);
            $table->dropForeign(['partido_pista']);
        });
        Schema::dropIfExists('partidos');
    }
};
