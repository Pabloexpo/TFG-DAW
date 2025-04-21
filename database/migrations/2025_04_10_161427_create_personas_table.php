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
        Schema::create('personas', function (Blueprint $table) {
            $table->increments('persona_id'); 
            $table->string('persona_nombre'); 
            $table->integer('persona_equipo')->unsigned()->nullable(); //FK de equipo id
            $table->integer('persona_rol')->unsigned()->nullable();
            $table->string('persona_pwd');
            
            //fk de rol_id
            $table->foreign('persona_rol')->references('rol_id')->on('roles')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
