<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    public $timestamps = false;

    protected $table = 'partidos';
    protected $primaryKey = 'partido_id';

    protected $fillable = [
        'partido_equ_1',
        'partido_equ_2',
        'partido_sets_equ_1',
        'partiddo_sets_equ_2',
        'partido_ganador',
        'partido_fecha',
        'partido_pista',
    ];

    // Relaciones: cada partido pertenece a dos equipos (y al equipo ganador) y a una pista

    public function equipo1()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function equipo2()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function ganadorEquipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function pista()
    {
        return $this->belongsTo(Pista::class, 'pista_id');
    }
}
