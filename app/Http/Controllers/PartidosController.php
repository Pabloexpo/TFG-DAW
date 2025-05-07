<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Partido;
use App\Models\Equipo;
use Illuminate\Support\Facades\DB;

class PartidosController extends Controller {

    //
    public function getReservasNoFinalizadas() {
        //Recogemos las reservas cuya fecha de reserva sea inferior al día actual y no estén finalizadas
        $reservas = DB::select("SELECT
    r.reserva_id,
    r.reserva_pista   as pista_id,
    p.pista_nombre,
    r.reserva_equipo1 as equipo1_id,
    e.equipo_nombre   as equipo1_nombre,
    r.reserva_equipo2 as equipo2_id,
    e1.equipo_nombre  as equipo2_nombre,
    r.reserva_fecha
    FROM reservas r
    JOIN pistas  p  on r.reserva_pista   = p.pista_id
    JOIN equipos e  on r.reserva_equipo1 = e.equipo_id
    JOIN equipos e1 on r.reserva_equipo2 = e1.equipo_id
    WHERE r.reserva_fecha < NOW()
    AND r.reserva_finalizada = 0;");
        //Tenemos que hacer dos join a la tabla de equipos para sacar sus nombres y poder mostrarlos

        return response()->json([
                    "mensaje" => "Reservas no finalizadas con fecha anterior al día actual",
                    "reservas" => $reservas
        ]);
    }

    //Insertamos los partidos que el administrador haya mandado por la tabla del front correspondiente 
    public function registraPartido(Request $request) {
        $partido = new Partido;

        $partido->partido_fecha = $request->partido_fecha;
        $partido->partido_pista = $request->partido_pista;
        $partido->partido_equ_1 = $request->partido_equ_1;
        $partido->partido_sets_equ_1 = $request->partido_sets_equ_1;
        $partido->partido_equ_2 = $request->partido_equ_2;
        $partido->partido_sets_equ_2 = $request->partido_sets_equ_2;
        $partido->partido_arbitro = $request->partido_arbitro;

        if ($request->partido_sets_equ_1 > $request->partido_sets_equ_2) {
            $partido->partido_ganador = $request->partido_equ_1;
        } elseif ($request->partido_sets_equ_2 > $request->partido_sets_equ_1) {
            $partido->partido_ganador = $request->partido_equ_2;
        } else {
            $partido->partido_ganador = null;
        }

        $partido->save();

        $reserva = Reserva::find($request->reserva_id);
        $reserva->reserva_finalizada = true;
        $reserva->save();

        return response()->json([
                    "mensaje" => "Inscripción de partido realizada exitosamente",
                    "partido" => $partido
        ]);
    }

    public function partidosEquipo(Request $request, $equipo_id) {
        //Realizamos una paginación para que la lista de partidos no sea infinita
        //Para ello utilizamos pagination de ORM Eloquent
        //Para la consulta, tendremos que hacer los join como siempre hemos hecho pero basados en ORM 
        //Entonces seleccionaremos lo que queremos ver tal y como hemos hecho en la select de partidos totales
        $numItems = $request->query('per_page', 9);
        $query = DB::table('partidos as p')
                ->join('pistas as pi', 'p.partido_pista', '=', 'pi.pista_id')
                ->join('equipos as e', 'e.equipo_id', '=', 'p.partido_equ_1')
                ->join('equipos as e1', 'e1.equipo_id', '=', 'p.partido_equ_2')
                ->leftJoin('personas as per', 'per.persona_id', '=', 'p.partido_arbitro')
                ->where('p.partido_equ_1', $equipo_id)
                ->orWhere('p.partido_equ_2', $equipo_id)
                ->orderBy('p.partido_fecha', 'asc')
                ->select(
                        'pi.pista_nombre',
                        'e.equipo_nombre as local',
                        'e1.equipo_nombre as visitante',
                        'p.partido_sets_equ_1',
                        'p.partido_sets_equ_2',
                        'per.persona_nombre as arbitro',
                        'p.partido_fecha'
                );
        //Paginamos la query en funcion del numero de items que hayamos pedido en la petición 
        $partidosPaginados = $query->paginate($numItems);

        //Devolvemos el objeto con la paginación correspondiente
        return response()->json($partidosPaginados, 200);
    }

    //Encontramos los partidos disputados por un equipo
    public function getPartidosEquipo($equipo_id) {
        //Una vez sabemos el equipo, hacemos la query para saber los partidos que ha disputado ese equipo 
        //y vs quien y donde
        $partidos = DB::select("SELECT pi.pista_nombre, e.equipo_nombre as local, e1.equipo_nombre as visitante, p.partido_sets_equ_1, p.partido_sets_equ_2, per.persona_nombre as arbitro, p.partido_fecha
    FROM partidos p 
    JOIN pistas pi on p.partido_pista=pi.pista_id
    JOIN equipos e on e.equipo_id=p.partido_equ_1
    JOIN equipos e1 on e1.equipo_id=p.partido_equ_2
    JOIN personas per on per.persona_id=p.partido_arbitro
    WHERE p.partido_equ_1=$equipo_id or p.partido_equ_2=$equipo_id");

        return response()->json([
                    "mensaje" => "Partidos recuperados con éxito",
                    "partidos" => $partidos
                        ], 201);
    }

    //Sacamos las estádisticas de un equipo en particular
    public function getPartidosEquipoStats($equipo_id) {
        $estadísticas = DB::select("SELECT 
    MONTH(p.partido_fecha) as mes,
    SUM(p.partido_ganador = $equipo_id) as ganados,
    SUM((p.partido_equ_1 = $equipo_id or p.partido_equ_2 = $equipo_id)
      AND p.partido_ganador <> $equipo_id) as perdidos
    FROM partidos p
    WHERE p.partido_equ_1 = $equipo_id
    OR p.partido_equ_2 = $equipo_id
    GROUP BY MONTH(p.partido_fecha)
    ORDER BY MONTH(p.partido_fecha);");

        return response()->json([
                    'mensaje' => 'Estadísticas calculadas con éxito',
                    'estadisticas' => $estadísticas
                        ], 200);
    }
    //Función para mostrar la clasificacion general de los equipos 
    public function clasificacionGeneral(){
        $clasificacion = DB::select("SELECT 
    e1.equipo_nombre,
    e1.equipo_id,
    COUNT(p.partido_id) AS total_partidos_jugados,
    SUM(CASE WHEN p.partido_ganador = e1.equipo_id THEN 1 ELSE 0 END) AS partidos_ganados
    FROM partidos p
    JOIN equipos e1 ON e1.equipo_id = p.partido_equ_1
    OR e1.equipo_id = p.partido_equ_2
    GROUP BY e1.equipo_nombre
    ORDER BY partidos_ganados DESC");
        
        return response()->json([
            "mensaje"=>"Clasificación general de los equipos", 
            "clasificacion"=>$clasificacion
        ]);
    }
}
