import React, { useState, useEffect } from 'react'
import CalendarioComponent from './CalendarioComponent';

const PistaSeleccionada = () => {
    const [idPista, setIdPista] = React.useState('');
    const [pista, setPista] = React.useState('');
    useEffect(() => {
        {
            // Obtenemos el id de la pista seleccionada desde la URL
            const url = window.location.href;
            const id = url.substring(url.lastIndexOf('/') + 1);
            setIdPista(id);
            // Realizamos la llamada a la API para obtener los datos de la pista
            fetch(`http://192.168.1.26:8000/api/pista/${id}`)
                .then(response => response.json())
                .then(data => {
                    setPista(data)
                    console.log(data)
                })
                .catch(error => console.error('Error:', error));
        }
    }, [])

    return (
        <main className='max-w-[1200px] mx-auto min-h-30 my-7'>
            {/* Vamos a hacer un grid de dos columnas donde en una vamos a poner los datos de la pista en la otra vamos a hacer la programatica de las reservas */}
            <div className='grid grid-cols-1 md:grid-cols-1 gap-4'>
                <div className=' bg-white rounded-lg p-5'>
                    <h1 className=' text-3xl font-bold mb-5'>Detalles de la pista</h1>
                    <div className='grid grid-cols-1 md:grid-cols-2 gap-4'>
                        <div className='flex flex-col justify-evenly'>
                            <p><strong>Nombre:</strong> {pista.pista_nombre}</p>
                            <p><strong>Localidad:</strong>  {pista.pista_localidad}</p>
                            <p><strong>Código Postal:</strong>  {pista.pista_cp}</p>
                            <p><strong>Descripción:</strong>  {pista.pista_descripcion}</p>
                            <p><strong>Teléfono:</strong>  {pista.pista_telefono}</p>
                        </div>
                        <div>
                            <img src={pista.pista_foto} alt={pista.pista_nombre} />
                        </div>
                    </div>

                </div>
                <div className='bg-white rounded-lg p-5'>
                    <h1 className='text-3xl font-bold mb-5'>Reservar pista</h1>
                    <h2>Selecciona una fecha y hora</h2>
                    <CalendarioComponent pista={pista}/>
                </div>
            </div>
        </main>
    )
}

export default PistaSeleccionada