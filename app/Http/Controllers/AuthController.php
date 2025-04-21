<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Persona;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validamos los datos introducidos, siendo requeridos tanto pwd como email
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // 2. Buscamos a la persona por su EMAIL 
        $persona = Persona::where('persona_email', $request->email)->first();

        // Verificamos que las pwd hasheadas sean iguales, si no lo son, devolvemos error
        if (!$persona || !Hash::check($request->password, $persona->persona_pwd)) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        // Crearemos un token cada vez que se haga login
        $token = $persona->createToken('login_token')->plainTextToken;

        //Devolvemos datos satisfactorios
        return response()->json([
            'access_token' => $token,
            'rol'          => $persona->persona_rol,
            'nombre'       => $persona->persona_nombre, 
            'id' => $persona->persona_id, 
            'equipo' => $persona->persona_equipo
        ], 200);
    }
    
    //Hacemos un endpoint que cierre la sesión-> será un post a una api
    // En AuthController.php

public function logout(Request $request)
{
    // Borramos el token actual. esto lo que provocará es borrar de nuestra BBDD el token que se ha generado
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logout exitoso'
    ]);
}

}

