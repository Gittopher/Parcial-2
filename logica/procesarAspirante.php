<?php
header("Content-Type: application/json");
session_start();
require_once "../incluye/conexion1.php"; // Incluye tu clase de conexión

// 🔎 Verificar si el usuario tiene sesión iniciada
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["error" => "Sesión no iniciada."]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// 🛠️ Consulta preparada para mayor seguridad
$sql = "SELECT nombre, cedula_pasaporte, fecha_nacimiento, 
               nacionalidad, telefono, correo_contacto, estado_civil, genero, residencia, tipo_sangre
        FROM aspirantes WHERE usuario_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

$stmt->close();
$conexion->close();
?>