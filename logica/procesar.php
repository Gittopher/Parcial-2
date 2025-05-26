<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];
    $cedula = $_POST["cedula"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $estado_civil = $_POST["estado_civil"];
    $genero = $_POST["genero"];
    $tipo_sangre = $_POST["tipo_sangre"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $nacionalidad = $_POST["nacionalidad"];
    $telefono = $_POST["telefono"];
    $residencia = $_POST["residencia"];
    $email = $_POST["email"];

    // Aquí puedes hacer validaciones, comprobar contra una base de datos, etc.

    // Simulamos un login exitoso
    if ($usuario == "admin" && $password == "1234") {
        // Redirigir al siguiente formulario
        header("Location: ../pantallas/RegisterHR.html");
        exit;
    } else {
        // Si falla, puedes redirigir a otra página o mostrar mensaje
        echo "Usuario o contraseña incorrectos.";
    }
}
?>
