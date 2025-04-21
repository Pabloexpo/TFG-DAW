<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\Equipo;
use App\Models\Rol; 
use Hash; 

class EquipoJugadorSeeder extends Seeder
{
    public function run()
    {
        // Primero, creamos el equipo
        $equipo = Equipo::create([
            'equipo_nombre' => 'Equipo Ejemplo',
            'equipo_estado' => 'activo',
            'equipo_fec_creacion' => now(),
            'equipo_fec_cese' => null,
        ]);

        // Creamos a los jugadores y los asignamos al equipo
        $jugador1 = Persona::create([
            'persona_nombre' => 'Jugador 1',
            'persona_rol' => 2, 
            'persona_email' => 'jugador5@example.com',
            'persona_pwd' => Hash::make('password123'),
            'persona_equipo' => $equipo->equipo_id, 
        ]);

        $jugador2 = Persona::create([
            'persona_nombre' => 'Jugador 4',
            'persona_rol' => 2,
            'persona_email' => 'jugador4@example.com',
            'persona_pwd' => Hash::make('password123'),
            'persona_equipo' => $equipo->equipo_id, 
        ]);
        // Ahora actualizamos el equipo para asignar los jugadores a los campos correspondientes
        $equipo->update([
            'equipo_jug_1' => $jugador1->persona_id, 
            'equipo_jug_2' => $jugador2->persona_id, 
        ]);
    }
}
