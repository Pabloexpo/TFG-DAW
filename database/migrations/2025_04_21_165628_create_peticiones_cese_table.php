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
        Schema::create('peticiones_cese', function (Blueprint $table) {
            $table->increments('peticion_id');
            $table->integer('peticion_equipo')->unsigned()->nullable(); 
            $table->text('peticion_causa')->nullable(); 
            $table->string('peticion_estado')->nullable();
            
            $table->foreign('peticion_equipo')->references('equipo_id')->on('equipos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peticiones_cese');
    }
};
