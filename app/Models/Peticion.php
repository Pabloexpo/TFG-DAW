<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peticion extends Model {

    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'peticion_id';
    protected $table = 'peticiones_cese';
    protected $fillable = [
        'peticion_equipo',
        'peticion_causa',
        'peticion_estado',
    ];

}
