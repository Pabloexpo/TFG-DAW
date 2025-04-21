import React, { useState } from 'react'

const JugadorSinEquipo = (props) => {
    const [ equipoNombre, setEquipoNombre ] = useState('')
    const [ id, setId ] = props.id; 

    const createEquipo = (e) => {
        const options = {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
                equipo_nombre: equipoNombre,
                equipo_jug_1: props.id})
        }
        e.preventDefault() //prevenimos el form
        fetch('http://192.168.1.26:8000/api/nuevoEquipo', options)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error al crear el equipo");
                }
                return response.json();
            })
            .then(data => {
                // Guardamos en localStorage
                localStorage.setItem("equipo", equipoNombre);
                setEquipoNombre(equipoNombre);
                props.onEquipoCreado(equipoNombre); // Llamamos a la función del padre para actualizar el estado
                return { success: true, data };
            })
            .catch(error => {
                console.error("Error en crearEquipo:", error);
                return { success: false, error: error.message };
            });
    }
    return (
        <div>
            <form onSubmit={createEquipo}>
                <div className="grid grid-cols-1 md:grid-cols-2 text-center">
                    <label htmlFor="nombre" className='my-4'>Introduce el nombre del equipo</label>
                    <input type="text" name='nombre' className='w-full border-2 border-primary rounded p-2 m-2 hover:border-secondary' placeholder='Nombre' onChange={(e) => setEquipoNombre(e.target.value)} />
                </div>
                <div className='flex justify-center items-center my-3'>
                    <button className="bg-primary text-white font-bold py-2 px-4 rounded hover:bg-secondary hover:text-black transition duration-300 m-auto"
                    >Crear Equipo</button>
                </div>
            </form>
        </div>
    )
}

export default JugadorSinEquipo