import React from 'react'
import BienvenidaComponent from '../home/BienvenidaComponent'
import PistaBienvenida from '../home/PistaBienvenida'
import Presentacion from '../home/Presentacion'
import FormContacto from '../general/FormContacto'

const HomePageComponent = () => {
  return (
    <div  >
      <BienvenidaComponent />
      <main className="flex flex-col justify-center items-center p-3 max-w-[1600px] mx-auto">
        <Presentacion />
        <PistaBienvenida />  
        <FormContacto inicio={true} /> 
      </main>
    </div>
  )
}

export default HomePageComponent