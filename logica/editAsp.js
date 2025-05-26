document.getElementById("editarBtn").addEventListener("click", function() {
    // Obtener los elementos que contienen la información
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
    document.getElementById("editarBtn").innerText = "Guardar";
    document.getElementById("editarBtn").setAttribute("id", "guardarBtn");

    // Agregar evento para guardar los cambios
    document.getElementById("guardarBtn").addEventListener("click", function() {
        nombre.innerText = document.getElementById("nombreInput").value;
        cedula.innerText = document.getElementById("cedulaInput").value;
        edad.innerText = document.getElementById("edadInput").value + " años";
        nacionalidad.innerText = document.getElementById("nacionalidadInput").value;
        telefono.innerText = document.getElementById("telefonoInput").value;
        email.innerText = document.getElementById("emailInput").value;

        // Restaurar el botón de "Editar"
        document.getElementById("guardarBtn").innerText = "Editar información";
        document.getElementById("guardarBtn").setAttribute("id", "editarBtn");
    });
});