<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    //No necesitaremos timestamps en ninguna tabla
    public $timestamps = false; 
    
    protected $table = 'roles'; 
    protected $primaryKey='rol_id'; 
    
    protected $fillable = [
        'nombre'
    ]; 
    
    //Realizaremos relaciones en el modelo ORM por si fueran necesarias usarlas
    public function personas(){
        return $this->hasMany(Persona::class, 'persona_rol'); 
    }
}
