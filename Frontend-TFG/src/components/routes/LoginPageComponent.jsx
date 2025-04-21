import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { loginUsuario } from '../functions/loginUsuario';


const LoginPageComponent = () => {
    // Realizamos una funcion que redirige a la página de inicio en caso de login
    const volverInicio = () => {
        window.location.href = "/";
    }
    // Estados para los inputs, error y token 
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState(null);
    const [token, setToken] = useState(null);


    // Función que se ejecuta al enviar el formulario
    const envioForm = (e) => {
        e.preventDefault();
    
        loginUsuario(email, password, setToken)
          .then(result => {
            if (!result.success) {
              alert(result.error);
              setError(result.error);
            }
          });
      };

    return (
        <main className="max-w-[1600px] mx-auto min-h-30" >
            {/* Hacemos el evento al hacer submit en el formulario */}
            <form onSubmit={envioForm} className='max-w-md m-auto'>
                <div>
                    <input
                        type="email"
                        placeholder="Email"
                        id="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        className="border-2 border-primary rounded p-2 m-2 hover:border-secondary"
                        required
                    />
                </div>
                <div>
                    <input
                        type="password"
                        placeholder="Contraseña"
                        id="pwd"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        className="border-2 border-primary rounded p-2 m-2 hover:border-secondary"
                        required
                    />
                </div>
                <button
                    type="submit"
                    className="bg-primary text-white font-bold py-2 px-4 rounded hover:bg-secondary hover:text-black transition duration-300 m-auto mx-0.5"
                >
                    Iniciar sesión
                </button>
                <button
                    type="submit"
                    className="bg-primary text-white font-bold py-2 px-4 rounded hover:bg-secondary hover:text-black transition duration-300 m-auto"
                >
                    <Link to="/registro">Registrarse</Link>
                </button>
            </form>
            {error && (
                <div style={{ color: "red", marginTop: "1rem", textAlign: "center" }}>
                    {error}
                </div>
            )}

            {token && (
                <>
                {/* En caso de haber token, volvemos a la página de inicio */}
                {volverInicio()}
                </>
            )}
        </main>
    );
};

export default LoginPageComponent;
