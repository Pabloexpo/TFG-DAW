<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reserva extends Model {

    //
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'reserva_id';
    
    protected $table = 'reservas';
    protected $fillable = [
        'reserva_id',
        'reserva_persona',
        'reserva_pista',
        'reserva_fecha',
        'reserva_finalizada'
    ];
}
