const getEquiposLibres = () => {
    //retornamos el fetch
    return fetch('http://192.168.1.26:8000/api/equipos')
        .then(response => response.json())
        .catch(error => {
            console.error('Error:', error);
            return []; // Devuelve un array vacío en caso de error para evitar que falle el `.then`
        });
}

export default getEquiposLibres;

