<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('equipos', function (Blueprint $table) {
            $table->increments('equipo_id');
            $table->string('equipo_nombre');
            //id de los jugadores que provienen de su tabla
            $table->integer('equipo_jug_1')->unsigned()->nullable();
            $table->integer('equipo_jug_2')->unsigned()->nullable();
            $table->string('equipo_estado')->nullable();
            $table->date('equipo_fec_creacion')->nullable();
            $table->string('equipo_fec_cese')->nullable();

            $table->foreign('equipo_jug_1')->references('persona_id')->on('personas')->onDelete('set null');
            $table->foreign('equipo_jug_2')->references('persona_id')->on('personas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropForeign(['equipo_jug_1']);
            $table->dropForeign(['equipo_jug_2']);
        });
        Schema::dropIfExists('equipos');
    }
};
