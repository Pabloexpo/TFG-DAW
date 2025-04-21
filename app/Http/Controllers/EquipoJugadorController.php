<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Equipo;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class EquipoJugadorController extends Controller {

    //Vamos a hacer una api que muestre al jugador seleccionado con su equipo
    // Mostrar un jugador por ID y su equipo asociado
    public function mostrarJugador($id) {
        //Buscamos al jugador por SQL para poder hacer JOINS en la BBDD, aquí es más eficaz que usar ORM
        $jugador = DB::select("SELECT *
    FROM personas p
    JOIN equipos e on p.persona_equipo=e.equipo_id
    WHERE p.persona_id= ?", [$id]);

        if (!$jugador) {
            return response()->json(['message' => 'Jugador no encontrado'], 404);
        }
        //Asignamos el primer resultado de la query
        $jugador = $jugador[0];
        //Devolvemos al jugador que encontremos
        return response()->json([
                    'equipo' => [
                        'nombre' => $jugador->equipo_nombre,
                        'estado' => $jugador->equipo_estado,
                        'id' => $jugador->equipo_id
                    ],
                    'jugador' => [
                        'nombre' => $jugador->persona_nombre,
                        'estado' => $jugador->persona_id,
                        'email' => $jugador->persona_email
                    ]
        ]);
    }

    //Vamos a realizar otro endpoint para hacer una petición por PUT para cambiar datos del jugador
    public function actualizarJugador(Request $request, $id) {
        //Validamos los datos que se nos han enviado
        $validacion = $request->validate([
            //Ponemos nullable para que cualquiera de los campos sea opcional
            'persona_nombre' => 'nullable|string|max:255',
            'persona_pwd' => 'nullable|string', 
            'pwd_actual' => 'nullable|string'
        ]);

        //Encontramos al jugador
        $jugador = Persona::find($id);

        if (!$jugador) {
            return response()->json(['message' => 'No se encuentra al jugador'], 404);
        }

        //Actualizamos los datos en caso de encontrar al jugador
        //Actualizamos los campos si están presentes
        if (isset($validacion['persona_nombre'])) {

            $jugador->persona_nombre = $validacion['persona_nombre'];
        }

        if (!empty($validacion['persona_pwd'])) {
            //Comprobamos si la contraseña es la correcta
            if(!Hash::check($validacion['pwd_actual'], $jugador->persona_pwd)){
                return response()->json([
                    'message'=> 'La contraseña actual no es correcta'
                ], 401);
            }
            //pasamos la pwd hasheada
            $jugador->persona_pwd = Hash::make($validacion['persona_pwd']);
        }

        $jugador->save();

        return response()->json([
                    'message' => 'Datos del jugador actualizados',
                    'jugador' => [
                        'id' => $jugador->persona_id,
                        'nombre' => $jugador->persona_nombre
                    ]
        ]);
    }

    //Realizamos un endpoint para generar usuarios 
    public function newUsuario(Request $request) {
        $validacion = $request->validate([
            'persona_nombre' => 'required|string|max:255',
            'persona_email' => 'required|string|max:255',
            //solo permitimos roles 2 o 3, es decir, o jugador o arbitro
            'persona_rol' => 'required|integer|in:2,3',
            'persona_pwd' => 'required'
        ]);
        //hasheamos la pwd antes de insertar
        $validacion['persona_pwd'] = Hash::make($validacion['persona_pwd']);
        $persona = Persona::create($validacion);

        return response()->json([
                    'success' => true,
                    'message' => 'Usuario creado correctamente',
                    'data' => $persona
                        ], 201);
//        ->header('Access-Control-Allow-Origin', '*')
//        ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
//        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }

    //Realizamos un endpoint para crear un equipo
    public function newEquipo(Request $request) {
        //Requeriremos que mínimo tengamos un jugador
        $validacion = $request->validate([
            'equipo_nombre' => 'required|string|max:255',
            'equipo_jug_1' => 'required|integer',
        ]);

        //Generamos un nuevo equipo y le vamos asignando los valores
        $equipo = new Equipo;
        $equipo->equipo_nombre = $validacion['equipo_nombre'];
        $equipo->equipo_jug_1 = $validacion['equipo_jug_1'];
        $equipo->equipo_estado = 'activo';
        $equipo->equipo_fec_creacion = now();
        $equipo->save();

        //Además de esto, tenemos que insertar en la tabla de jugadores al nuevo equipo, para ello
        //vamos a buscar al jugador dependiendo del equipo_jug_1

        $jugador = Persona::find($validacion['equipo_jug_1']);
        $jugador->persona_equipo = $equipo->equipo_id;
        $jugador->save();

        return response()->json([
                    'success' => true,
                    'message' => 'Equipo creado correctamente',
                    'data' => $equipo
                        ], 201);
    }

    //Mostramos un endpint de los equipos que no tienen el total de integrantes 
    public function equiposSinJugadores() {
        //HACEMOS LA QUERY 
        $equipos = DB::select("SELECT * FROM equipos WHERE equipo_jug_1 is NULL or equipo_jug_2 is NULL");

        return response()->json([
                    'equipos' => $equipos
        ]);
    }

    //Hacemos un endpoint para insertar un jugador en un equipo existente
    public function insertaEnEquipoExistente(Request $request) {
        $validacion = $request->validate([
            "equipo_id" => "required|integer",
            "jugador_id" => "required|integer"
        ]);

        //Ahora tenemos que insertar el jugador. ya sea como jugador 1 o como jugador 2
        $equipo = Equipo::find($validacion["equipo_id"]);
        $jugador = Persona::find($validacion['jugador_id']);
        $jugador->persona_equipo = $equipo->equipo_id;
        if ($equipo->equipo_jug_1 == null) {
            $equipo->equipo_jug_1 = $validacion["jugador_id"];
            $equipo->save();
        } else {
            $equipo->equipo_jug_2 = $validacion["jugador_id"];
            $equipo->save();
        }
        
        
        $jugador->persona_equipo = $equipo->equipo_id;
        $jugador->save();
        return response()->json([
                    'success' => true,
                    'message' => 'Equipo modificado correctamente',
                    'data' => $equipo
                        ], 201);
    }
    
    //Funcion para obtener los equipos que no han cesado su actividad
    public function getEquipos(){
        $equipos = DB::select("SELECT * FROM equipos where equipo_fec_cese is NULL"); 
        
        return response()->json([
            "equipos"=>$equipos
        ]); 
    }
    
    
}
