<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pista extends Model {
    use HasFactory; 
    public $timestamps = false;
    protected $primaryKey='pista_id'; 
    
    protected $table = 'pistas';
    protected $fillable = [
        'pista_nombre',
        'pista_localidad',
        'pista_cp',
        'pista_foto', 
        'pista_descripcion', 
        'pista_telefono'
    ];

    // Relación: Una pista tiene muchos partidos
    public function partidos() {
        return $this->hasMany(Partido::class, 'partido_pista');
    }
}
