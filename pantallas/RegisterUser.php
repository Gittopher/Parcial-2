<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registro de usuario</title>
  <link rel="stylesheet" href="../assets/estilos.css" />
</head>
<body>
<form action="/Parcial-2/logica/procesar.php" method="post">
  <label for="usuario">Usuario:</label>
  <input type="text" name="usuario" id="usuario" required />

  <label for="password">Contraseña:</label>
  <input type="password" name="password" id="password" required />

  <label for="confirmar_password">Confirmar Contraseña:</label>
  <input type="password" name="confirmar_password" id="confirmar_password" required />

  <button type="submit">Registrarse</button>

  <p>
    ¿Ya tienes una cuenta? <a href="/Parcial-2/pantallas/LoginRegisterAsp.php">Inicia sesión aquí</a>.
  </p>
</form>

</body>
</html>
