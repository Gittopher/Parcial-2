document.addEventListener("DOMContentLoaded", function () {
    fetch("../logica/procesarAspirante.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error al cargar datos:", data.error);
                return;
            }

            document.getElementById("nombre").textContent = data.nombre;
            document.getElementById("cedula").textContent = data.cedula_pasaporte;
            document.getElementById("edad").textContent = data.edad + " años";
            document.getElementById("nacionalidad").textContent = data.nacionalidad;
            document.getElementById("telefono").textContent = data.telefono;
            document.getElementById("email").textContent = data.correo_contacto;
            document.getElementById("estado_civil").textContent = data.estado_civil;
            document.getElementById("genero").textContent = data.genero;
            document.getElementById("residencia").textContent = data.residencia;
            document.getElementById("tipo_sangre").textContent = data.tipo_sangre;
        })
        .catch(error => console.error("Error en la solicitud:", error));
});