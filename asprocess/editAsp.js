document.addEventListener("DOMContentLoaded", function () {
    const editarBtn = document.getElementById("editarBtn");
    let enModoEdicion = false;

    // Expresiones regulares para validación
    const expresiones = {
        nombre: /^[a-zA-ZÀ-ÿ\s]{1,40}$/, // Letras y espacios con acentos
        cedula: /^[a-zA-Z0-9\-]{4,30}$/, // Letras, números, guion, guion bajo (ajustar si quieres)
        nacionalidad: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
        telefono: /^\d{7,14}$/, // 7 a 14 dígitos numéricos
        email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
    };

    function esFechaValida(fecha) {
        return !isNaN(Date.parse(fecha));
    }

    function convertirFechaParaInput(edadTexto) {
        // edadTexto viene tipo "25 años", convertimos a fecha restando años a hoy
        const edadNum = parseInt(edadTexto);
        if (isNaN(edadNum)) return "";
        const fecha = new Date();
        fecha.setFullYear(fecha.getFullYear() - edadNum);
        return fecha.toISOString().split("T")[0];
    }

    editarBtn.addEventListener("click", function () {
        if (!enModoEdicion) {
            enModoEdicion = true;

            let nombre = document.getElementById("nombre");
            let cedula = document.getElementById("cedula");
            let edad = document.getElementById("edad");
            let nacionalidad = document.getElementById("nacionalidad");
            let telefono = document.getElementById("telefono");
            let email = document.getElementById("email");

            // Reemplazar contenido con inputs
            nombre.innerHTML = `<input type="text" id="nombreInput" value="${nombre.innerText.trim()}">`;
            cedula.innerHTML = `<input type="text" id="cedulaInput" value="${cedula.innerText.trim()}">`;
            edad.innerHTML = `<input type="date" id="fechaNacimientoInput" value="${convertirFechaParaInput(edad.innerText)}">`;
            nacionalidad.innerHTML = `<input type="text" id="nacionalidadInput" value="${nacionalidad.innerText.trim()}">`;
            telefono.innerHTML = `<input type="text" id="telefonoInput" value="${telefono.innerText.trim()}">`;
            email.innerHTML = `<input type="email" id="emailInput" value="${email.innerText.trim()}">`;

            editarBtn.innerText = "Guardar";
        } else {
            // Obtener los valores
            const datosActualizados = {
                nombre: document.getElementById("nombreInput").value.trim(),
                cedula: document.getElementById("cedulaInput").value.trim(),
                fechaNacimiento: document.getElementById("fechaNacimientoInput").value.trim(),
                nacionalidad: document.getElementById("nacionalidadInput").value.trim(),
                telefono: document.getElementById("telefonoInput").value.trim(),
                email: document.getElementById("emailInput").value.trim(),
            };

            // Validaciones
            if (
                !expresiones.nombre.test(datosActualizados.nombre) ||
                !expresiones.cedula.test(datosActualizados.cedula) ||
                !esFechaValida(datosActualizados.fechaNacimiento) ||
                !expresiones.nacionalidad.test(datosActualizados.nacionalidad) ||
                !expresiones.telefono.test(datosActualizados.telefono) ||
                !expresiones.email.test(datosActualizados.email)
            ) {
                alert("Por favor, completa todos los campos correctamente.");
                return;
            }

            // Enviar datos
            fetch("actualizarAspirante.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(datosActualizados),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Restaurar texto visible
                        document.getElementById("nombre").innerText = datosActualizados.nombre;
                        document.getElementById("cedula").innerText = datosActualizados.cedula;

                        // Mostrar edad calculada a partir de fecha de nacimiento
                        const fechaNac = new Date(datosActualizados.fechaNacimiento);
                        const edad = calcularEdad(fechaNac);
                        document.getElementById("edad").innerText = edad + " años";

                        document.getElementById("nacionalidad").innerText = datosActualizados.nacionalidad;
                        document.getElementById("telefono").innerText = datosActualizados.telefono;
                        document.getElementById("email").innerText = datosActualizados.email;

                        editarBtn.innerText = "Editar información";
                        enModoEdicion = false;
                    } else {
                        alert("Error al actualizar los datos: " + data.error);
                    }
                })
                .catch((error) => {
                    console.error("Error en la solicitud:", error);
                    alert("Ocurrió un error al enviar los datos.");
                });
        }
    });

    // Función para calcular edad desde fecha
    function calcularEdad(fechaNacimiento) {
        const hoy = new Date();
        let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
        const mes = hoy.getMonth() - fechaNacimiento.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
            edad--;
        }
        return edad;
    }
});
