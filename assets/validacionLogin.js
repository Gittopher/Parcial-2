document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formLogin');
  const inputs = document.querySelectorAll('#formLogin input');

  const expresiones = {
    usuario: /^[a-zA-Z0-9_\-]{4,16}$/, // Letras, números, guión y guión bajo (mínimo 4 caracteres)
    password: /^.{4,12}$/ // Contraseña de 4 a 12 caracteres
  };

  const campos = {
    usuario: false,
    password: false
  };

  const validarCampo = (expresion, input, campo) => {
    const grupo = document.getElementById(`grupo__${campo}`);
    if (expresion.test(input.value)) {
      grupo.classList.remove('formulario__grupo-incorrecto');
      grupo.classList.add('formulario__grupo-correcto');
      grupo.querySelector('i').classList.remove('fa-times-circle');
      grupo.querySelector('i').classList.add('fa-check-circle');
      grupo.querySelector('.formulario__input-error').style.display = 'none';
      campos[campo] = true;
    } else {
      grupo.classList.add('formulario__grupo-incorrecto');
      grupo.classList.remove('formulario__grupo-correcto');
      grupo.querySelector('i').classList.add('fa-times-circle');
      grupo.querySelector('i').classList.remove('fa-check-circle');
      grupo.querySelector('.formulario__input-error').style.display = 'block';
      campos[campo] = false;
    }
  };

  const validarFormulario = (e) => {
    switch (e.target.name) {
      case 'usuario':
        validarCampo(expresiones.usuario, e.target, 'usuario');
        break;
      case 'password':
        validarCampo(expresiones.password, e.target, 'password');
        break;
    }
  };

  inputs.forEach(input => {
    input.addEventListener('keyup', validarFormulario);
    input.addEventListener('blur', validarFormulario);
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    console.log("Formulario enviado"); // <--- Verifica si esto aparece en la consola

    const formData = new FormData(form);
    console.log("Datos enviados:", Object.fromEntries(formData)); // <--- Esto te mostrará los datos

    fetch('../logica/procesarLogin.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta del servidor:", data);
        if (data.success) {
            alert('Inicio de sesión exitoso');
            window.location.href = data.redirect; // Redirige según el rol
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => console.error('Error en la solicitud:', error));
});
});