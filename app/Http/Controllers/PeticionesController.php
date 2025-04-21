<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peticion;
use Illuminate\Support\Facades\DB;

class PeticionesController extends Controller {

    //
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
