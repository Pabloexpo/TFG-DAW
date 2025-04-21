// src/services/authService.js
export const loginUsuario = (email, password, setToken) => {
    return fetch("http://192.168.1.26:8000/api/login", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ email, password })
    })
      .then(response => {
        if (!response.ok) {
          throw new Error("Credenciales incorrectas");
        }
        return response.json();
      })
      .then(data => {
        // Guardamos en localStorage
        localStorage.setItem("access_token", data.access_token);
        localStorage.setItem("nombre", data.nombre);
        localStorage.setItem("rol", data.rol);
        localStorage.setItem("id", data.id);
        localStorage.setItem("equipo", data.equipo);
  
        // Seteamos el token desde el componente que llama
        setToken(data.access_token);
  
        return { success: true, data };
      })
      .catch(error => {
        console.error("Error en loginUsuario:", error);
        return { success: false, error: error.message };
      });
  };
  