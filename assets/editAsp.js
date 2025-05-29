document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("editarBtn").addEventListener("click", function () {
        let nombre = document.getElementById("nombre");
        let cedula = document.getElementById("cedula");
        let edad = document.getElementById("edad");
        let nacionalidad = document.getElementById("nacionalidad");
        let telefono = document.getElementById("telefono");
        let email = document.getElementById("email");

        // Reemplazar el contenido por campos editables
        nombre.innerHTML = `<input type="text" id="nombreInput" value="${nombre.innerText}">`;
        cedula.innerHTML = `<input type="text" id="cedulaInput" value="${cedula.innerText}">`;
        edad.innerHTML = `<input type="number" id="edadInput" value="${edad.innerText.replace(' años', '')}">`;
        nacionalidad.innerHTML = `<input type="text" id="nacionalidadInput" value="${nacionalidad.innerText}">`;
        telefono.innerHTML = `<input type="text" id="telefonoInput" value="${telefono.innerText}">`;
        email.innerHTML = `<input type="email" id="emailInput" value="${email.innerText}">`;

        // Cambiar el botón de "Editar" por "Guardar"
        let editarBtn = document.getElementById("editarBtn");
        editarBtn.innerText = "Guardar";
        editarBtn.setAttribute("id", "guardarBtn");

        // Agregar evento para guardar los cambios en la base de datos
        document.getElementById("guardarBtn").addEventListener("click", function () {
            let datosActualizados = {
                nombre: document.getElementById("nombreInput").value,
                cedula: document.getElementById("cedulaInput").value,
                edad: document.getElementById("edadInput").value,
                nacionalidad: document.getElementById("nacionalidadInput").value,
                telefono: document.getElementById("telefonoInput").value,
                email: document.getElementById("emailInput").value,
            };

            fetch("/actualizarAspirante.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(datosActualizados),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    nombre.innerText = datosActualizados.nombre;
                    cedula.innerText = datosActualizados.cedula;
                    edad.innerText = datosActualizados.edad + " años";
                    nacionalidad.innerText = datosActualizados.nacionalidad;
                    telefono.innerText = datosActualizados.telefono;
                    email.innerText = datosActualizados.email;

                    // Restaurar el botón de "Editar"
                    let guardarBtn = document.getElementById("guardarBtn");
                    guardarBtn.innerText = "Editar información";
                    guardarBtn.setAttribute("id", "editarBtn");
                } else {
                    console.error("Error al actualizar datos:", data.error);
                }
            })
            .catch(error => console.error("Error en la solicitud:", error));
        });
    });
});