<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peticion;
use Illuminate\Support\Facades\DB;

class PeticionesController extends Controller {

    //Hacemos un endopoint que recoja todas las peticiones de cese, junto con el equipo(NOMBRE) que la 
    //ha propuesto
    public function getPeticiones(){
        $peticiones = DB::select("SELECT pc.peticion_id, pc.peticion_causa, pc.peticion_estado, e.equipo_nombre, e.equipo_id
    FROM peticiones_cese pc 
    JOIN equipos e ON pc.peticion_equipo=e.equipo_id
    WHERE pc.peticion_estado is NULL"); 
        
        return response()->json([
            "mensaje" => "Peticiones de cese generadas por los equipos", 
            "peticiones"=> $peticiones
        ]);
    }
    
    //Hacemos un endopoint para almacenar las peticiones de cese de equipos
    public function peticionesCese(Request $request) {
        $validacion = $request->validate([
            "peticion_causa" => "required|string",
            "peticion_equipo" => "required|integer"
        ]);
        
        $peticion = new Peticion; 
        $peticion->peticion_causa=$request->peticion_causa; 
        $peticion->peticion_equipo=$request->peticion_equipo; 
        $peticion->save(); 
        
        return response()->json([
            "mensaje"=> "Se ha registrado la peticion", 
            "peticion" => $peticion
        ]);
    }
}
