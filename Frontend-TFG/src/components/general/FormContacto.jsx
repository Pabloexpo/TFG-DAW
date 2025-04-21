import React from 'react'

//Elaboramos una prop donde diremos si el form está en contacto o en la pág de inicio
const FormContacto = ({inicio=false}) => {
  //Vamos a realizar un formulario de contacto para aprender a usar los estados y los hooks de react
  const [nombre, setNombre] = React.useState('')
  const [email, setEmail] = React.useState('')
  const [mensaje, setMensaje] = React.useState('')
  //Establecemos un estado para comprobar si el mensaje fue enviado
  const [enviado, setEnviado] = React.useState(false)

  //Realizamos una funcion que se activará al enviar el formulario, al igual que en un evento en vanilla 
  function enviarFormulario(e) {
    e.preventDefault();
    //Implementaremos aquí el envío por post del formulario a la api de laravel
    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        nombre: nombre,
        email: email,
        mensaje: mensaje
      })
    }
    fetch('http://192.168.1.26:8000/api/mensaje', options)
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
      })
      .then(data => {
        console.log('Mensaje enviado:', data);
        //Se nos envia el mensaje y limpiamos todo
        setEnviado(true)
        setNombre('')
        setEmail('')
        setMensaje('')
      })
      .catch(error => {
        console.error('Error:', error);
      });
    console.log({ nombre, email, mensaje });
  }
  //En el caso de que hayamos dicho por la prop que inicio es true, entonces el formulario será diferente
  let estilosForm = inicio ? 'w-1/3' : 'w-1/2';
  return (
    <div className={`flex flex-col sp-4 ${estilosForm}`}>
      <h2>Contacto</h2>
      {/* si el mensj está enviado, mostramos un mensaje de éxito */}
      {enviado ? (<p>Mensaje enviado correctamente!</p>)
        : (
          <form onSubmit={enviarFormulario} className='flex flex-col'>
            <label htmlFor="nombre">Nombre</label>
            <input
              type="text"
              id="nombre"
              className='border-2 border-primary rounded p-2 m-2 hover:border-secondary bg-primary text-white hover:bg-body hover:text-black '
              placeholder='Ingresa tu nombre'
              value={nombre}
              onChange={(e) => setNombre(e.target.value)}
              required />
            <label htmlFor="email">Email</label>
            <input
              type="email"
              id="email"
              className='border-2 border-primary rounded p-2 m-2 hover:border-secondary '
              placeholder='Ingresa tu email'
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required />
            <label htmlFor="mensaje">Mensaje</label>
            <textarea
              id="mensaje"
              value={mensaje}
              placeholder='Escribe tu mensaje aquí'
              className='border-2 border-primary rounded p-2 m-2 hover:border-secondary '
              onChange={(e) => setMensaje(e.target.value)}
              required></textarea>
            <button
              type="submit"
              className='bg-primary text-white font-bold py-2 px-4 rounded hover:bg-secondary hover:text-black transition duration-300 m-auto'>
              Enviar
            </button>
          </form>
        )}
    </div>
  )
}

export default FormContacto