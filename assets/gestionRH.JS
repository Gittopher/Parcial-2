const urlProcesar = 'procesarRH.php';

// Obtener y mostrar personal RH en tarjetas
function obtenerRH() {
    fetch(urlProcesar + '?action=obtener')
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                mostrarMensaje(data.error, true);
                return;
            }
            const contenedor = document.getElementById('tarjetas');
            contenedor.innerHTML = ''; // Limpiar

            if (data.length === 0) {
                contenedor.innerHTML = '<p>No hay personal registrado.</p>';
                return;
            }

            data.forEach(item => {
                const card = document.createElement('div');
                card.classList.add('card');

                card.innerHTML = `
                    <h4>${item.nombre_completo}</h4>
                    <p><strong>Usuario:</strong> ${item.nombre_usuario}</p>
                    <p><strong>Correo:</strong> ${item.correo}</p>
                    <button onclick="eliminarRH(${item.id})">Eliminar</button>
                `;

                contenedor.appendChild(card);
            });
        })
        .catch(() => mostrarMensaje('Error al obtener datos.', true));
}

// Mostrar mensajes en el div #mensaje
function mostrarMensaje(texto, esError = false) {
    const mensaje = document.getElementById('mensaje');
    mensaje.textContent = texto;
    mensaje.style.color = esError ? 'red' : 'green';
    setTimeout(() => { mensaje.textContent = ''; }, 5000);
}

// Procesar formulario para agregar personal RH
function procesarFormulario(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    formData.append('action', 'procesar');

    fetch(urlProcesar, {
        method: 'POST',
        body: formData,
    })
    .then(res => res.text())
    .then(resp => {
        mostrarMensaje(resp, !resp.includes('✅'));
        if (resp.includes('✅')) {
            form.reset();
            obtenerRH();
        }
    })
    .catch(() => mostrarMensaje('Error al enviar datos.', true));
}

// Eliminar personal RH
function eliminarRH(id) {
    if (!confirm('¿Está seguro que desea eliminar este personal RH?')) return;

    const formData = new FormData();
    formData.append('action', 'eliminar');
    formData.append('id', id);

    fetch(urlProcesar, {
        method: 'POST',
        body: formData,
    })
    .then(res => res.text())
    .then(resp => {
        mostrarMensaje(resp, !resp.includes('✅'));
        if (resp.includes('✅')) {
            obtenerRH();
        }
    })
    .catch(() => mostrarMensaje('Error al eliminar.', true));
}

document.addEventListener('DOMContentLoaded', () => {
    obtenerRH();
    const form = document.getElementById('formAgregarRH');
    form.addEventListener('submit', procesarFormulario);
});
