<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mensaje;

class MensajeController extends Controller
{
    //
    public function store (Request $request){
        $validacion = $request->validate([
            'nombre' => 'required|string|max:255', 
            'email' => 'required|email|max:255', 
            'mensaje'=> 'required|string'
        ]); 
        
        //Al validar. creamos el msj en la bbdd
        $mensaje = Mensaje::create($validacion); 
        
        //retornamos respuesta de msj exitoso
        return response()->json([
            'success'=>true, 
            'message' => 'Mensaje enviado correctamente', 
            'data' => $mensaje
        ], 201); 
    }
    
    public function getMensajes(){
        $mensajes = Mensaje::all(); 
        
        return response()->json([
            "mensaje"=> "Mensajes de consumidores recuperados con éxito", 
            "mensajes"=>$mensajes
        ], 200);
    }
}
