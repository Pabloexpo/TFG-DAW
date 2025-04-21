import React from 'react'

const BienvenidaComponent = () => {
    return (
        <header className="relative w-full h-[30vh] bg-[url('/src/assets/segunda-guia-estilos.webp')] bg-cover bg-center">
            <div className="absolute inset-0 flex flex-col items-start justify-center pl-8">
                <h1 className="text-text text-4xl font-bold">Padelistas Cartagena</h1>
                <p className="text-text mt-2">Reserva tus pistas favoritas y compárate con tus amigos</p>
            </div>
        </header>
    )
}

export default BienvenidaComponent