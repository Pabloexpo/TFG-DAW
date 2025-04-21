import React from 'react'

const Presentacion = () => {
    return (
        <section className='max-w-[1200px] mx-auto flex flex-col justify-center p-4 w-5/6'>
            <article>
                <h2 className='text-center text-2xl'>Nuestro proyecto</h2>
                <p>Padelistas Cartagena es una aplicación web diseñada para facilitar la reserva de pistas de pádel en Cartagena. Con una interfaz intuitiva y fácil de usar, los usuarios pueden buscar y reservar pistas en diferentes clubes de la ciudad, comparar estadísticas y disfrutar de una experiencia de juego sin complicaciones.</p>
                <p>La aplicación está diseñada para ser accesible y útil tanto para jugadores experimentados como para principiantes, brindando información clara sobre las instalaciones y servicios disponibles en cada pista.</p>
                <p>Además, Padelistas Cartagena fomenta la comunidad de pádel en la ciudad de Cartagena, permitiendo a los usuarios conectarse con otros jugadores y compartir su pasión por el deporte.</p>
                <p>¡Únete a nosotros y disfruta del pádel en Cartagena!</p>
            </article>
            <article className='flex justify-center items-center my-10'>
                <img src="src\assets\img-bienvenida.webp" alt="Pista de bienvenida" className='w-2xl' />
            </article>

        </section>
    )
}

export default Presentacion