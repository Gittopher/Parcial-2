<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
require_once '../incluye/conexion1.php';

// Conexión procedural
$conexion = mysqli_connect("localhost", "root", "", "registro_aspirantes");

if (!$conexion) {
    echo json_encode(["success" => false, "mensaje" => "Error al conectar con la base de datos: " . mysqli_connect_error()]);
    exit;
}

$sql = "SELECT * FROM aspirantes";
$resultado = mysqli_query($conexion, $sql);

$datos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $datos[] = $fila;
}

echo json_encode($datos);

mysqli_close($conexion);
