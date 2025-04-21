<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('reservas', function (Blueprint $table) {
            $table->increments('reserva_id');
            $table->integer('reserva_pista')->unsigned()->nullable();
            //quién ha hecho la reserva
            $table->integer('reserva_equipo1')->unsigned()->nullable();
            //contra quien se juega
            $table->integer('reserva_equipo2')->unsigned()->nullable();
            $table->dateTime('reserva_fecha');
            //Esta columna nos indica si el partido se ha realizado ya
            $table->boolean('reserva_finalizada');; 

            //FKs
            $table->foreign('reserva_pista')->references('pista_id')->on('pistas')->onDelete('set null');
            $table->foreign('reserva_equipo1')->references('equipo_id')->on('equipos')->onDelete('set null');
            $table->foreign('reserva_equipo2')->references('equipo_id')->on('equipos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('reservas');
    }
};
