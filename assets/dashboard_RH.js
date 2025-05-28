// Espera a que el DOM esté completamente cargado antes de ejecutar el código
document.addEventListener("DOMContentLoaded", function () {
  fetchSolicitudes(); // Carga las solicitudes al iniciar la página

  // Agrega un listener al formulario de modificación para manejar el envío
  document.getElementById("formModificar").addEventListener("submit", async function (e) {
    e.preventDefault(); // Previene el comportamiento por defecto del formulario

    // Obtiene los valores de los campos del formulario
    const id = document.getElementById("solicitudId").value;
    const estado = document.getElementById("estado").value;
    const comentarios = document.getElementById("comentarios").value;

    // Envía los datos al backend usando fetch y espera la respuesta
    const response = await fetch("actualizar_estado.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, estado, comentarios })
    });

    // Procesa la respuesta del backend
    const result = await response.json();
    mostrarMensaje(result.mensaje, result.success); // Muestra un mensaje al usuario
    cerrarModal(); // Cierra el modal de modificación
    fetchSolicitudes(); // Recarga la lista de solicitudes
  });
});

// Función para obtener y mostrar las solicitudes en la tabla
function fetchSolicitudes() {
  fetch("obtener_solicitudes.php")
    .then(res => res.json()) // Convierte la respuesta a JSON
    .then(data => {
      const tbody = document.querySelector("#tablaSolicitudes tbody");
      tbody.innerHTML = ""; // Limpia la tabla antes de llenarla

      // Recorre cada solicitud y la agrega como una fila en la tabla
      data.forEach(solicitud => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${solicitud.id}</td>
          <td>${solicitud.nombre} ${solicitud.apellido}</td>
          <td>${solicitud.fecha_registro}</td>
          <td>${solicitud.estado_solicitud}</td>
          <td>${solicitud.comentarios || ""}</td>
          <td><button onclick="abrirModal(${solicitud.id}, '${solicitud.estado_solicitud}', \`${solicitud.comentarios || ""}\`)">Modificar</button></td>
        `;
        tbody.appendChild(row); // Agrega la fila a la tabla
      });
    });
}

// Abre el modal de modificación y llena los campos con los datos de la solicitud seleccionada
function abrirModal(id, estado, comentarios = "") {
  document.getElementById("solicitudId").value = id;
  document.getElementById("estado").value = estado;
  document.getElementById("comentarios").value = comentarios;
  document.getElementById("modalModificar").style.display = "block"; // Muestra el modal
}

// Cierra el modal de modificación
function cerrarModal() {
  document.getElementById("modalModificar").style.display = "none";
}

// Muestra un mensaje temporal al usuario, indicando éxito o error
function mostrarMensaje(mensaje, exito = true) {
  const div = document.getElementById("message");
  div.textContent = mensaje;
  div.className = `message ${exito ? "success" : "error"}`;
  setTimeout(() => div.textContent = "", 3000); // Borra el mensaje después de 3 segundos
}