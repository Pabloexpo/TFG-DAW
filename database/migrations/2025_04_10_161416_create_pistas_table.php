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
        Schema::create('pistas', function (Blueprint $table) {
            $table->increments('pista_id'); 
            $table->string('pista_nombre'); 
            //Permitimos valores nulos para localidad, cp, foto, descripcion, tlfno
            $table->string('pista_localidad')->nullable(); 
            $table->string('pista_cp', 20)->nullable(); 
            $table->string('pista_foto')->nullable(); 
            $table->text('pista_descripcion')->nullable(); 
            $table->string('pista_telefono')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pistas');
    }
};
