<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolesSeeder extends Seeder
{
    /**
     * Generaremos seeder para meter datos primitivos, como los roles, y el perfil administrador en personas 
     * posteriormente
     */
    public function run(): void
    {
        //
        Rol::create(['rol_nombre' =>'administrador']); 
        Rol::create(['rol_nombre' =>'jugador']); 
        Rol::create(['rol_nombre' =>'arbitro']); 
    }
}
