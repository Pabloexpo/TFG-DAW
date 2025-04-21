import React, { useState, useEffect } from 'react'
import JugadorSinEquipo from './JugadorCreaEquipo';
import JugadorUneEquipo from './JugadorUneEquipo';
import PeticionAbandono from './PeticionAbandono';
import API_URL from '../components/functions/APIURL';
import Administrador from '../components/administradorPerfil/Administrador';

const PerfilComponent = () => {
    const [nombre, setNombre] = React.useState('');
    const [pwdActual, setPwdActual] = React.useState('');
    const [password, setPassword] = React.useState('');
    const [password2, setPassword2] = React.useState('');
    const [equipo, setEquipo] = React.useState('');
    const [nuevoNombre, setNuevoNombre] = React.useState('');
    const [rol, setRol] = React.useState('');
    const [id, setId] = React.useState('');
    const [errorSpan, setErrorSpan] = React.useState('');
    //Manejamos un estado que alerte de las consecuencias de pedir una disolución de equipo
    const [alertaDisolucion, setAlertaDisolucion] = React.useState(false);
    //Manejamos otro estado para cuando hayamos pulsado en la petición de abandono, tendemos que incluir un componente donde recogeremos la justificación de abandono 
    //será por dónde mandaremos todos los datos a la api
    const [peticionAbandono, setPeticionAbandono] = React.useState(false);
    
    //Vamos a realizar dos botones en el grid del equipo para, o crear un equipo nuevo o unirse a uno existente
    //Tendremos 3 posibles estados: 'eleccion', 'crear' y 'unirse'
    const [unirseEquipo, setUnirseEquipo] = React.useState('eleccion');
    {console.log(unirseEquipo)}
    //Seteamos nombre y usuario una vez al renderizar el componente, si lo pusieramos fuera, tendríamos un bucle infinito
    useEffect(() => {
        setNombre(localStorage.getItem("nombre"));
        setRol(localStorage.getItem("rol"));
        setId(localStorage.getItem("id"));
        setEquipo(localStorage.getItem("equipo"));
    }, [])
    const actualizarUsuario = (e) => {
        e.preventDefault()

        if (password !== password2) {
            setErrorSpan("Las contraseñas no coinciden");
            return;
        }
        fetch(`http://192.168.1.26:8000/api/updateUsuario/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                persona_nombre: nuevoNombre,
                persona_pwd: password,
                pwd_actual: pwdActual,
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Credenciales incorrectas");
                }
                return response.json();
            })
            .then(data => {
                // Guardamos en localStorage solo en caso de que el nombre haya cambiado
                // y lo actualizamos en el estado del componente
                if (nuevoNombre) {
                    setNombre(nuevoNombre);
                    localStorage.setItem("nombre", nuevoNombre);
                }
                alert("Usuario actualizado correctamente");
                return { success: true, data };
            })
            .catch(error => {
                console.error("Error en modificarUsuario:", error);
                return { success: false, error: error.message };
            });
    }
    //Realizamos esta funcion para pasarle al hijo una props para que una vez creado el equipo, se actualice el estado del padre
    const equipoCreado = (nombreEquipo)=>{
        setEquipo(nombreEquipo);
        localStorage.setItem("equipo", nombreEquipo);
    }
    return (
        <main className='max-w-[1200px] mx-auto min-h-30'>
            <>
                <h1 className='text-3xl font-bold text-center'>Perfil</h1>
                <div className='flex justify-center items-center flex-col'>
                    {/* solo mostramos la primera palabra del nombre, por estética */}
                    <h2 className='text-2xl font-bold'>Hola, {nombre.split(" ")[0]}</h2>
                </div>
            </>
            {/* la intención de aquí a futuro es que el usuario pueda ver sus estadísticas, sus pistas favoritas, etc. y que el administrador pueda ver modificar cosas generales, en funciión del ROL que nos venga dado, pondremos un componente u otro */}
            {/* Vamos a implementar un grid que a la izquierda nos muestre la posibilidad de modificar nuestros datos */}
            <section className="px-4 py-8">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {/* formulario de actualizar los datos */}
                    <article className="bg-white rounded-2xl shadow-md p-6">
                        <h2 className="text-xl font-semibold mb-2">¿Quieres modificar tus datos?</h2>
                        <form onSubmit={actualizarUsuario} className='max-w-md m-auto'>
                            <div className="grid grid-cols-1 md:grid-cols-2 text-center">
                                <label htmlFor="nombre" className='my-4'>Introduce tu nuevo nombre</label>
                                <input type="text" name='nombre' className='w-full border-2 border-primary rounded p-2 m-2 hover:border-secondary' placeholder='Nombre' onChange={(e) => setNuevoNombre(e.target.value)} />
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-2 text-center">
                                <label htmlFor="pwdActual" className='my-4'>Introduce tu contraseña actual</label>
                                <input type="password" name='pwdActual' className='w-full border-2 border-primary rounded p-2 m-2 hover:border-secondary' placeholder='Contraseña actual' onChange={(e) => setPwdActual(e.target.value)} />
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-2 text-center">
                                <label htmlFor="pwd" className='my-4'>Introduce tu nueva contraseña</label>
                                <input type="password" name='pwd' className='w-full border-2 border-primary rounded p-2 m-2 hover:border-secondary' placeholder='Contraseña' onChange={(e) => setPassword(e.target.value)} />
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-2 text-center">
                                <label htmlFor="pwd2" className='my-4'>Repite tu nueva contraseña</label>
                                <input type="password" name='pwd2' className='w-full border-2 border-primary rounded p-2 m-2 hover:border-secondary' placeholder='Repite tu contraseña' onChange={(e) => setPassword2(e.target.value)} />
                            </div>
                            <div>
                                {/* Muestra el mensaje de error si existe */}
                                {errorSpan && (
                                    <span className="text-red-500 block my-2">{errorSpan}</span>
                                )}
                            </div>
                            <div className='flex justify-center items-center my-3'>
                                <button className="bg-primary text-white font-bold py-2 px-4 rounded hover:bg-secondary hover:text-black transition duration-300 m-auto"
                                >Actualizar datos</button>
                            </div>
                        </form>
                    </article>
                    {/* formulario para tratar el tema de los equipos */}
                    <article className="bg-white rounded-2xl shadow-md p-6 flex flex-col justify-evenly items-center">
                        {/* si no hay equipo, mostramos la opción de unirse a uno o crear uno nuevo */}
                        {equipo == 'null' ? (
                            unirseEquipo == 'eleccion' ? (
                                <div>
                                    <h2 className="text-xl font-semibold mb-2">¿Quieres unirte a un equipo o crear uno?</h2>
                                    <div className='flex flex-col justify-between my-3'>
                                        <button onClick={() => setUnirseEquipo('crear')} className="bg-primary text-white font-bold py-2 px-4 my-2 rounded hover:bg-secondary hover:text-black transition duration-300 m-auto">
                                            Crear equipo
                                        </button>
                                        <button onClick={() => setUnirseEquipo('unirse')} className="bg-primary text-white font-bold py-2 px-4 rounded hover:bg-secondary hover:text-black transition duration-300 m-auto">Unirse a un equipo</button>
                                    </div>
                                </div>
                            ) : unirseEquipo == 'crear' ? (
                                <div className='flex flex-col justify-center items-center'>
                                    <h2 className="text-xl font-semibold mb-2"></h2>
                                    {/* implantamos aqui el componente para crear un equipo */}
                                    <JugadorSinEquipo id={id} onEquipoCreado={equipoCreado}/>
                                </div>
                            ) : (
                                <div className='flex flex-col justify-center items-center'>
                                    <h2 className="text-xl font-semibold mb-2">Unirse a un equipo</h2>
                                    {/* implantamos aqui el componente para unirse a un equipo */}
                                    <JugadorUneEquipo/>
                                    
                                </div>
                            )
                        ) : (
                            <div className='flex flex-col justify-center items-center'>
                                <h2 className="text-xl font-semibold mb-2">Ya perteneces al equipo {equipo}</h2>
                                <button onMouseEnter={()=>setAlertaDisolucion(true)} 
                                onMouseLeave={()=>setAlertaDisolucion(false)} 
                                className="bg-primary text-white font-bold py-2 px-4 rounded hover:bg-secondary hover:text-black transition duration-300 m-auto"
                                onClick={()=>setPeticionAbandono(true)}>Petición de abandono de equipo</button>
                                <span className='p-4'>{alertaDisolucion && "El Administrador será notificado de la petición de cese y será quién tenga la última palabra"}</span>
                                {peticionAbandono && <PeticionAbandono id={id} equipo={equipo} setPeticionAbandono={setPeticionAbandono}/>}
                            </div>
                        )}

                    </article>
                </div>
            </section>
            {/* En caso de ser administrador, mostramos el componente de administrador */}
            {rol==="1" && <Administrador/>}
        </main>
    )
}

export default PerfilComponent