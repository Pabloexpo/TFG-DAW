<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Pista;

class PistasSeeder extends Seeder {

    /**
     * Hemos creado un seeder porque vamos a recoger una apì externa, del ayto de cartagena, que recoge 
     * todas las pistas deportivas del municipio, para poder integrarlas en nuestra tabla de pistas, realizaremos
     * una petición get a la api desde el backend y, una vez con los datos, filtraremos solo los que nos interesan
     * es decir, las pistas de padel (unas 9), que serán las que insertaremos en la tabla Pistas
     */
    public function run(): void {
        //Realizamos la petición a la api
        $respuesta = Http::get('https://datosabiertos.regiondemurcia.es/api/'
                . 'action/datastore_search?resource_id=61506eed-7f6b-41eb-9267-d696ef497a47&limit=89');
        
        //Si obtenemos respuesta, empezamos a realizar la algoritmia
        if ($respuesta->successful()){
            //Convertimos los datos a json
            $datos = $respuesta->json();
            
            //Recogemos los registros que vienen en result.records. si no obtenemos nada, tendremos un array vacío
            $records = $datos['result']['records'] ?? []; 
            
            //Recorremos todos los registros 
            foreach ($records as $registro) {
                //en caso de que webct:descripcion contenga padel, insertamos-> lo hacemos con stripos, que 
                //encuentra la posición numérica de un string en una cadena-> padel y Padel
                if ((strpos($registro['webct:descripcion'], 'padel') !==false) || (strpos($registro['webct:descripcion'], 'Padel') !==false) ){
                    //insertamos usando el model Pista
                    Pista::create([
                        'pista_nombre' => $registro['dc:titulo'], 
                        'pista_localidad' => $registro['webct:barrio'], 
                        'pista_cp' => $registro['webct:cp'], 
                        'pista_foto' => $registro['webct:foto'], 
                        'pista_descripcion' => $registro['webct:descripcion'], 
                        'pista_telefono' => $registro['webct:telefono']
                    ]);
                }
                
            }
        } else {
            //manejamos errores con \log::error y el cuerpo de la respuesta que nos da el get de la api
            \log::error('Error al consumir la api: '. $respuesta->body()); 
        }
        
    }
}
