document.addEventListener('DOMContentLoaded', () => {
  const formulario = document.getElementById('formRegistro');
  const inputs = document.querySelectorAll('#formRegistro input');

  const expresiones = {
    nombre: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
    password: /^.{4,12}$/,
    correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/
  };

  const campos = {
    nombre: false,
    password: false,
    password2: false,
    correo: false
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

  const validarPassword2 = () => {
    const inputPassword1 = document.getElementById('password');
    const inputPassword2 = document.getElementById('password2');
    const grupo = document.getElementById('grupo__password2');

    if (inputPassword2.value.length === 0) {
      // No mostrar error si el campo está vacío aún
      grupo.classList.remove('formulario__grupo-incorrecto', 'formulario__grupo-correcto');
      grupo.querySelector('i').classList.remove('fa-times-circle', 'fa-check-circle');
      grupo.querySelector('.formulario__input-error').style.display = 'none';
      campos['password2'] = false;
      return;
    }

    if (inputPassword1.value === inputPassword2.value) {
      grupo.classList.remove('formulario__grupo-incorrecto');
      grupo.classList.add('formulario__grupo-correcto');
      grupo.querySelector('i').classList.remove('fa-times-circle');
      grupo.querySelector('i').classList.add('fa-check-circle');
      grupo.querySelector('.formulario__input-error').style.display = 'none';
      campos['password2'] = true;
    } else {
      grupo.classList.add('formulario__grupo-incorrecto');
      grupo.classList.remove('formulario__grupo-correcto');
      grupo.querySelector('i').classList.add('fa-times-circle');
      grupo.querySelector('i').classList.remove('fa-check-circle');
      grupo.querySelector('.formulario__input-error').style.display = 'block';
      campos['password2'] = false;
    }
  };

  const validarFormulario = (e) => {
    switch (e.target.name) {
      case 'nombre':
        validarCampo(expresiones.nombre, e.target, 'nombre');
        break;
      case 'password':
        validarCampo(expresiones.password, e.target, 'password');
        validarPassword2();
        break;
      case 'password2':
        validarPassword2();
        break;
      case 'correo':
        validarCampo(expresiones.correo, e.target, 'correo');
        break;
    }
  };

  inputs.forEach(input => {
    input.addEventListener('keyup', validarFormulario);
    input.addEventListener('blur', validarFormulario);
  });

  formulario.addEventListener('submit', e => {
    // Para que pase la validación ambas contraseñas deben coincidir y cumplir longitud
    if (campos.nombre && campos.password && campos.password2 && campos.correo) {
      document.getElementById('formulario__mensaje').style.display = 'none';
      document.getElementById('formulario__mensaje-exito').style.display = 'block';
    } else {
      e.preventDefault();
      document.getElementById('formulario__mensaje').style.display = 'block';
      document.getElementById('formulario__mensaje-exito').style.display = 'none';
    }
  });
});
