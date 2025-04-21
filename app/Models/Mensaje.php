<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model {

    //
    public $timestamps = false;
    protected $table = 'mensajes';
    protected $primaryKey = 'mensaje_id';
    protected $fillable = [
        'nombre', 
        'email', 
        'mensaje'
    ];
}
