import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom'
const PistaBienvenida = () => {
  const [pista, setPista] = useState({});

  useEffect(() => {
    // Generamos un random entre los números id posibles de pistas
    const randomId = Math.floor(Math.random() * 9) + 1;
    console.log('Pidiendo pista con id:', randomId);

    fetch(`http://192.168.1.26:8000/api/pista/${randomId}`)
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
      })
      .then(data => {
        console.log(data);
        setPista(data);
      })
      .catch(error => console.error('Error:', error));
  }, []);

  return (
    <section className='max-w-[1600px] mx-auto flex flex-row justify-around items-center w-full'>
      <img
        src={pista.pista_foto}
        alt={pista.pista_nombre}
        className="w-90 h-90 object-cover rounded mb-2 p-4"
      />
      <article className='flex flex-col justify-around space-y-3'>
        <h2 className='text-2xl font-bold'>Reserva en una de nuestras pistas!</h2>
        <p className='text-lg font-medium'>{pista.pista_nombre}</p>
        <p>{pista.pista_nombre}</p>
        <Link to="/pistas">
          <button className='bg-primary text-white font-bold py-2 px-4 rounded hover:bg-secondary hover:text-black transition duration-300'>
            Visita todas nuestras pistas!
          </button>
        </Link>
      </article>
    </section>
  );
}

export default PistaBienvenida;
