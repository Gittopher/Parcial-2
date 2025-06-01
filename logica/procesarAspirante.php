<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once "/xampp/htdocs/Parcial2/incluye/conexion.php"; 

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["error" => "Sesión no iniciada."]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT nombre, apellido, cedula_pasaporte, TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad, 
               nacionalidad, telefono, correo_contacto, estado_civil, genero, residencia, tipo_sangre
        FROM aspirantes WHERE usuario_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $datos = $resultado->fetch_assoc();

    // Opcional: controlar si la edad vino como NULL por fecha inválida
    if (is_null($datos['edad'])) {
        $datos['edad'] = "No especificada";
    }

    echo json_encode($datos);
} else {
    echo json_encode(["error" => "No se encontró información."]);
}

$stmt->close();
$conexion->close();
?>
