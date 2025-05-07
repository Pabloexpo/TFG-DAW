<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PistaController; 
use App\Http\Controllers\EquipoJugadorController; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\PeticionesController;
use App\Http\Controllers\PartidosController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/pistas', [PistaController::class, 'index']);
//hacemos la ruta con el endpoint de la pista dependiendo de su id
Route::get('/pista/{id}', [PistaController::class, 'show']);

//hacemos una ruta para mostrar al jugador selecciodo con su equipo
Route::get('/jugador/{id}', [EquipoJugadorController::class, 'mostrarJugador']);

//Ruta para actualizar a un jugador
Route::put('/updateUsuario/{id}', [EquipoJugadorController::class, 'actualizarJugador']);
//Ruta para crear a un nuevo jugador
Route::post('/nuevoUsuario', [EquipoJugadorController::class, 'newUsuario']);
//Ruta para crear equipos
Route::post('/nuevoEquipo', [EquipoJugadorController::class, 'newEquipo']);
//Ruta para ver los equipos sin jugadores
Route::get('/equipoNoJugadores', [EquipoJugadorController::class, 'equiposSinJugadores']);
//Ruta para insertar jugadores en equipos ya creados
Route::put('/insertaJugadorEquipo', [EquipoJugadorController::class, 'insertaEnEquipoExistente']);
//ruta para hacer login
Route::post('/login', [AuthController::class, 'login']);
//ruta para hacer logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
//ruta para mandar mensajes por formulario
Route::post('/mensaje', [MensajeController::class, 'store']); 
//ruta para recuperar los mensajes que han llegado en el panel administrador
Route::get('/getMensajes', [MensajeController::class, 'getMensajes']); 
//ruta para obtener los equipos sin fecha de cese 
Route::get('/equipos/{equipo_id}', [EquipoJugadorController::class, 'getEquipos']);

//ruta para comprobar la disponibilidad  de una pista 
Route::post('/comprobarPista', [PistaController::class, 'compruebaDisponibilidad']);
//Ruta para realizar la reserva
Route::post('/reservarPista', [PistaController::class, 'reservarPista']);

//ruta para realizar una peticion de cese 
Route::post('/peticionCese', [PeticionesController::class, 'peticionesCese']);
//Ruta para recoger las peticiones de cese 
Route::get('/getPeticiones', [PeticionesController::class, 'getPeticiones']);
//Ruta para aceptar la petición de cese
Route::put('/aceptaPeticion/{equipo_id}/{peticion_id}', [EquipoJugadorController::class, 'aceptaPeticion']);
//Ruta para rechazar la petición de cese
Route::put('/rechazaPeticion/{peticion_id}', [EquipoJugadorController::class, 'rechazaPeticion']);

//Ruta para ver las reservas sin finalizar anteriores al día de hoy
Route::get('/getReservasNoFinalizadas', [PartidosController::class, 'getReservasNoFinalizadas']);
//Ruta para recoger los árbitros
Route::get('/getArbitros', [EquipoJugadorController::class, 'getArbitros']);

//Ruta para inscribir los partidos terminados en la tabla partidos
Route::post('/registraPartido', [PartidosController::class, 'registraPartido']);

//peticion get para recuperar los partidos del equipo x
Route::get('/getPartidosEquipo/{equipo_id}', [PartidosController::class, 'getPartidosEquipo']);

//Peticion get para sacar las estadísticas de un equipo
Route::get('/getPartidosEquipoStats/{equipo_id}', [PartidosController::class, 'getPartidosEquipoStats']);

//peticion get para recuperar los partidos del equipo x con PAGINACION
Route::get('/partidosEquipo/{equipo_id}', [PartidosController::class, 'partidosEquipo']);

//peticion para recuperar las pistas utilizadas por x equipo
Route::get('/getPistasUtilizadas/{equipo_id}', [EquipoJugadorController::class, 'getPistasUtilizadas']);

//peticion get para recuperar la clasificación general
Route::get('/clasificacionGeneral/', [PartidosController::class, 'clasificacionGeneral']);