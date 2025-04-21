<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        
        //llamamos al seeder de introducir las pistas
        $this->call(PistasSeeder::class);
        //llamamos a otro seeder para introducir los roles
        $this->call(RolesSeeder::class);
        //llamamos al seeder que crea el usuario admin 
        $this->call(AdminUserSeeder::class); 
        //Seeder para crear jugadores y equipo de pruebas
        $this->call(EquipoJugadorSeeder::class); 

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
    }
}
