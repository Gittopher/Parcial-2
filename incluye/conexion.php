<?php
$host = "localhost";
$usuario = "root";
$contrasena = ""; // sin contraseña por defecto en XAMPP
$base_datos = "registro_aspirantes"; // Cambia esto por el nombre real

$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
