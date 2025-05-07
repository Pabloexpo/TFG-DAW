<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Equipo;
use App\Models\Peticion;
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
            if (!Hash::check($validacion['pwd_actual'], $jugador->persona_pwd)) {
                return response()->json([
                            'message' => 'La contraseña actual no es correcta'
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

        //Como hemos puesto que el nombre del equipo sea único, si queremos mostrar ese error en el front
        //tenemos que hacer un bloque try catch
        try {
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
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                        'exito' => false,
                        'mensaje' => 'Has intentado crear un equipo con un nombre ya existente'
                            ], 500);
        }
    }

    //Mostramos un endpint de los equipos que no tienen el total de integrantes 
    public function equiposSinJugadores() {
        //HACEMOS LA QUERY  IMPORTANTE BUSCAR LOS EQUIPOS CON FECHA DE CESE NO ACTIVA
        $equipos = DB::select("SELECT * FROM equipos WHERE (equipo_jug_1 is NULL or equipo_jug_2 is NULL) "
                . "AND (equipos.equipo_fec_cese is null)");

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
    public function getEquipos($equipo_id) {
        //Tenemos que pasar el id del equipo para evitar la posibilidad de elegir enfrentarnos a nosotros mismos
        
        $equipos = DB::select("SELECT *
    FROM equipos
    WHERE equipo_fec_cese IS NULL
      AND equipo_id <> ?;
    ", [$equipo_id]);

        return response()->json([
                    "equipos" => $equipos
        ]);
    }
    //Funcion para recibir las personas que solamente son árbitros
    public function getArbitros (){
        $arbitros = DB::select("SELECT * FROM personas p where persona_rol = 3"); 
        
        return response()->json([
            "mensaje"=>"Listado de arbitros",
            "arbitros" => $arbitros
        ]);
    }

    //Funcion para eliminar el equipo a través de una petición aceptada
    //Realmente no es eliminar, seria borrar sus integrantes y establecer su fecha de cese a día de hoy
    public function aceptaPeticion($equipo_id, $peticion_id) {
        //Encontramos el equipo
        $equipo = Equipo::find($equipo_id);
        //Una vez encontrado el equipo, encontramos a los jugadores y le quitamos el equipo al que pertenece
        if ($equipo->equipo_jug_1) {
            $jug_1 = Persona::find($equipo->equipo_jug_1);
            //Borramos el equipo al que pertenecen
            $jug_1->persona_equipo = null;
            $jug_1->save();
        }
        if ($equipo->equipo_jug_2) {
            $jug_2 = Persona::find($equipo->equipo_jug_2);
            $jug_2->persona_equipo = null;
            $jug_2->save();
        }


        //Seguimos el proceso de borrado de jugadores del equipo y establecemos el cese a dia de hoy
        $equipo->equipo_jug_1 = null;
        $equipo->equipo_jug_2 = null;
        $equipo->equipo_fec_cese = now();
        $equipo->equipo_estado = "NO activo";
        $equipo->save();
        //Encontramos la petición
        $peticion = Peticion::find($peticion_id);
        //Ponemos que la petición de cese ha sido ACEPTADA
        $peticion->peticion_estado = 'Aceptada';
        $peticion->save();
        return response()->json(['Mensaje' => 'Petición aceptada exitosamente.'], 200);
    }
    //Ahora tenemos que hacer la parte contraria, rechazar una petición de cese 
    public function rechazaPeticion($peticion_id){
        //Como se rechaza, no necesitamos el id del equipo esta vez
        $peticion= Peticion::find($peticion_id); 
        $peticion->peticion_estado= 'Rechazada'; 
        $peticion->save(); 
        return response()->json(['Mensaje' => 'La petición ha sido rechazada correctamente'], 200);
    }
    
    //Realizamos una función para ver las pistas utilizadas por un equipo históricamente
    public function getPistasUtilizadas ($equipo_id){
        $pistas_utilizadas = DB::select("SELECT pi.pista_nombre, COUNT(p.partido_id) as cantidad
    FROM partidos p 
    JOIN pistas pi on pi.pista_id=p.partido_pista
    WHERE p.partido_equ_1=$equipo_id or p.partido_equ_2=$equipo_id
    GROUP BY pi.pista_nombre ORDER BY cantidad desc");
        
        return response()->json([
            "mensaje"=>"Pistas más utilizadas por el equipo pedido", 
            "pistas_utilizadas"=>$pistas_utilizadas
        ]);
    }
}
