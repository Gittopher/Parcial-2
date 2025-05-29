<?php
session_start();
require_once "conexion.php"; 

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["error" => "Sesión no iniciada."]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];


$sql = "SELECT nombre, cedula_pasaporte, TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad, 
               nacionalidad, telefono, correo_contacto, estado_civil, genero, residencia, tipo_sangre
        FROM aspirantes WHERE usuario_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

$stmt->close();
$conexion->close();
?>