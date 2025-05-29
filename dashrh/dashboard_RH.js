// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", function () {
  fetchSolicitudes();

  document.getElementById("formModificar").addEventListener("submit", async function (e) {
    e.preventDefault();

    const id = document.getElementById("solicitudId").value;
    const estado = document.getElementById("estado").value;
    const comentarios = document.getElementById("comentarios").value;

    const response = await fetch("actualizar_estado.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, estado, comentarios })
    });

    const result = await response.json();
    console.log("Respuesta del servidor:", result);
    mostrarMensaje(result.mensaje, result.success);
    cerrarModal();
    fetchSolicitudes();
  });
});

function fetchSolicitudes() {
  fetch("obtener_solicitudes.php", {
    method: "GET",
    cache: "no-store" // 👈 esto evita el caché
  })
    .then(res => res.json())
    .then(data => {
      const contenedor = document.getElementById("contenedorSolicitudes");
      contenedor.innerHTML = "";

      data.forEach(solicitud => {
        const card = document.createElement("div");
        card.className = "tarjeta";

        card.innerHTML = `
          <h3>Solicitud #${solicitud.id}</h3>
          <p><strong>Nombre:</strong> ${solicitud.nombre} ${solicitud.apellido}</p>
          <p><strong>Edad:</strong> ${calcularEdad(solicitud.fecha_nacimiento) || "No registrada"}</p>
          <p><strong>Estado:</strong> ${solicitud.estado_solicitud || "No definido"}</p>
          <p><strong>Comentarios:</strong> ${solicitud.comentarios || "Ninguno"}</p>
        `;

        const btn = document.createElement("button");
        btn.textContent = "Modificar";
        btn.addEventListener("click", () => {
          abrirModal(solicitud.id, solicitud.estado_solicitud, solicitud.comentarios || "");
        });

        card.appendChild(btn);
        contenedor.appendChild(card);
      });
    });
}

function calcularEdad(fechaNacimiento) {
  if (!fechaNacimiento) return "No registrada";
  const hoy = new Date();
  const nacimiento = new Date(fechaNacimiento);
  let edad = hoy.getFullYear() - nacimiento.getFullYear();
  const mes = hoy.getMonth() - nacimiento.getMonth();

  if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
    edad--;
  }

  return edad + " años";
}

function abrirModal(id, estado, comentarios = "") {
  document.getElementById("solicitudId").value = id;
  const estadoSelect = document.getElementById("estado");
  estadoSelect.value = estado || ""; 
  document.getElementById("comentarios").value = comentarios;
  document.getElementById("modalModificar").style.display = "block";
}

function cerrarModal() {
  document.getElementById("modalModificar").style.display = "none";
}

function mostrarMensaje(mensaje, exito = true) {
  const div = document.getElementById("message");
  div.textContent = mensaje;
  div.className = `message ${exito ? "success" : "error"}`;
  div.style.display = "block";
  setTimeout(() => {
    div.style.display = "none";
    div.textContent = "";
  }, 3000);
}
