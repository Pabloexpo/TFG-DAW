<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\Equipo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Faker\Factory as Faker;

class CreacionEquiposJugadores extends Seeder
{
    public function run()
    {
        //utliziamos la libreria Faker para generar datos de prueba para la BBDD
        $faker = Faker::create('es_ES');
        $numEquipos = 10;

        for ($i = 0; $i < $numEquipos; $i++) {
            // 1) Pedir 2 usuarios aleatorios
            $response = Http::get('https://randomuser.me/api/', [
                'nat'     => 'es',
                'results' => 2,
                'inc'     => 'name,email',
            ]);

            if (! $response->ok()) {
                $this->command->error("Error API en iteración {$i}");
                continue;
            }

            $usuarios = $response->json('results');

            // 2) Nombre aleatorio de equipo
            $teamName = $faker->company;

            // 3) Crear equipo
            $equipo = Equipo::create([
                'equipo_nombre'       => $teamName,
                'equipo_estado'       => 'activo',
                'equipo_fec_creacion' => now(),
                'equipo_fec_cese'     => null,
            ]);

            // 4) Crear personas/jugadores
            $jugadores = [];
            foreach ($usuarios as $idx => $u) {
                $fullName = "{$u['name']['first']} {$u['name']['last']}";
                $email    = $u['email'];

                $jugadores[$idx] = Persona::create([
                    'persona_nombre' => $fullName,
                    'persona_rol'    => 2,
                    'persona_email'  => $email,
                    'persona_pwd'    => Hash::make('password123'),
                    'persona_equipo' => $equipo->equipo_id,
                ]);
            }

            // 5) Asociar en el equipo los dos jugadores
            $equipo->update([
                'equipo_jug_1' => $jugadores[0]->persona_id,
                'equipo_jug_2' => $jugadores[1]->persona_id,
            ]);

            
        }
    }
}
