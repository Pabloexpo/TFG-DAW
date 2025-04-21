export const CerrarSesion = () => {
    const token = localStorage.getItem("access_token");
    if (!token) {
      console.error("No se encontró token de autenticación.");
      return;
    }
    fetch("http://192.168.1.26:8000/api/logout", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer " + token,
      },
    })
      .then((response) => {
        if (!response.ok) {
          return response.json().then((errData) => {
            console.error(errData.message || "Error al cerrar sesión");
            throw new Error("Error al cerrar sesión");
          });
        }
        return response.json();
      })
      .then((data) => {
        // Limpia el token del localStorage
        localStorage.removeItem("access_token");
        localStorage.removeItem("nombre");
        localStorage.removeItem("rol");
        localStorage.removeItem("id");
        localStorage.removeItem("equipo");
        alert(data.message || "Logout exitoso");
        // Redirige al usuario a la página de inicio
        window.location.href = "/";
      })
      .catch((err) => {
        console.error("Error en el logout:", err);
      });
  };