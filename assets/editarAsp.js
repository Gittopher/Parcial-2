document.addEventListener("DOMContentLoaded", function () {
    fetch("http://localhost/Parcial2/logica/procesarAspirante.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error al cargar datos:", data.error);
                return;
            }

            // Asignar valores a los inputs del formulario
            document.getElementById("cedula").value = data.cedula_pasaporte || "";
            document.getElementById("nombre").value = data.nombre || "";
            document.getElementById("apellido").value = data.apellido || "";
            document.getElementById("estado_civil").value = data.estado_civil || "";
            document.getElementById("genero").value = data.genero || "";
            document.getElementById("tipo_sangre").value = data.tipo_sangre || "";
            document.getElementById("fecha_nacimiento").value = data.fecha_nacimiento || "";
            document.getElementById("nacionalidad").value = data.nacionalidad || "";
            document.getElementById("telefono").value = data.telefono || "";
            document.getElementById("residencia").value = data.residencia || "";
            document.getElementById("email").value = data.correo_contacto || "";
        })
        .catch(error => console.error("Error en la solicitud:", error));
});
