<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Persona extends Authenticatable {

    /*
     * Para que un usuario se pueda identificar, tenemos que haber creado primero una tabla de tokens
     * (ya creado), para posteriormente dar al usuario un token cada vez que haga login
     */
    
    use HasApiTokens,
        Notifiable;

    public $timestamps = false;
    protected $table = 'personas';
    protected $primaryKey = 'persona_id';
    protected $fillable = [
        'persona_nombre',
        'persona_equipo',
        'persona_rol',
        'persona_pwd',
        'persona_email'
    ];
    // Ocultamos la contraseña en las conversiones a arrays o JSON
    protected $hidden = [
        'persona_pwd',
    ];

    //usamos BelongsTo para una relación uno a uno
    public function rol() {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    public function equipo() {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    //usamos esta función que retorna la contraseña que será usada al logearse
    public function getAuthPassword() {
        return $this->persona_pwd;
    }
}
