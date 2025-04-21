<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    public $timestamps = false;

    protected $table = 'equipos';
    protected $primaryKey = 'equipo_id';

    protected $fillable = [
        'equipo_nombre',
        'equipo_jug_1',
        'equipo_jug_2',
        'equipo_estado',
        'equipo_fec_creacion',
        'equipo_fec_cese',
    ];


    //Referencias a los jugadores
    public function jugador1()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
    public function jugador2()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    //Referencia de las personas hacia el equipo. un equipo puede tener varias personas
    public function personas()
    {
        return $this->hasMany(Persona::class, 'persona_equipo');
    }

    // Relaciones con partidos
    public function partidoEquipo1()
    {
        return $this->belongsTo(Partido::class, 'partido_equ_1');
    }

    public function partidoEquipo2()
    {
        return $this->belongsTo(Partido::class, 'partido_equ_2');
    }

    public function partidoGanador()
    {
        return $this->belongsTo(Partido::class, 'partido_ganador');
    }
}
