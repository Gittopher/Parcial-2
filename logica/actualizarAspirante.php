<?php
session_start();
header("Content-Type: application/json");
require_once "../incluye/conexion.php";

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit;
}

$usuario_id = $_SESSION['usuario_id']; // Obtener ID del usuario autenticado
$datos = json_decode(file_get_contents("php://input"), true);

if (!$datos) {
    echo json_encode(["error" => "Datos no enviados correctamente"]);
    exit;
}

// Preparar la consulta SQL
$sql = "UPDATE aspirantes SET 
            nombre = ?, 
            cedula_pasaporte = ?, 
            fecha_nacimiento = DATE_SUB(CURDATE(), INTERVAL ? YEAR), 
            nacionalidad = ?, 
            telefono = ?, 
            correo_contacto = ?
        WHERE usuario_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
    "sssissi",
    $datos["nombre"],
    $datos["cedula"],
    $datos["edad"],
    $datos["nacionalidad"],
    $datos["telefono"],
    $datos["email"],
    $usuario_id
);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Error al actualizar los datos"]);
}

$stmt->close();
$conexion->close();
?>