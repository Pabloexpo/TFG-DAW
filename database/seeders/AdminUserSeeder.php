<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash; //Vamos a cifrar la pwd
use App\Models\Persona;
use App\Models\Rol;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //tenemos primero que asegurarnos que hay administrador en roles, si no existe ese rol se crea, lo 
        //hago por posibles fresh en la bbdd en probaturas
        $adminRol= Rol::where('rol_nombre', 'administrador')->first(); 
        
        if (!$adminRol){
            $adminRole = Rol::create(['rol_nombre' => 'administrador']);
        }
         Persona::create([
             'persona_nombre' => 'Administrador', 
             'persona_rol' => $adminRol->rol_id, 
             //Email del admin
             'persona_email' => 'administrador@pablotfg.com', 
             //pwd del admin hasheada con la libreria Hash para que no se vea en la bbdd
             'persona_pwd' => Hash::make('pablotfg')
         ]); 
    }
}
