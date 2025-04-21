<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PistaController; 
use App\Http\Controllers\EquipoJugadorController; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\PeticionesController;
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
//ruta para obtener los equipos sin fecha de cese 
Route::get('/equipos', [EquipoJugadorController::class, 'getEquipos']);

//ruta para comprobar la disponibilidad  de una pista 
Route::post('/comprobarPista', [PistaController::class, 'compruebaDisponibilidad']);
//Ruta para realizar la reserva
Route::post('/reservarPista', [PistaController::class, 'reservarPista']);

//ruta para realizar una peticion de cese 
Route::post('/peticionCese', [PeticionesController::class, 'peticionesCese']);