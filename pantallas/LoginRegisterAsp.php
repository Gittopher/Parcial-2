<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <link rel="stylesheet" href="../assets/estilos.css">
</head>
<body>
<form action="/Parcial-2/logica/procesarLogin.php" method="POST">
    <label for="usuario">Usuario:</label>
    <input type="text" name="usuario" id="usuario" required>

      <br>

    <label for="password">Contraseña:</label>
    <input type="password" name="password" id="password" required>
          
      <br>

    <button type="submit">Iniciar Sesión</button>

  <br>
  <br>

  <a href="/Parcial-2/pantallas/RegisterUser.php">
    <button type="button">¿No tienes cuenta? Regístrate</button>
  </a>

</form>

</body>
</html>