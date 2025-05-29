<?php
header("Content-Type: application/json");
require_once '../incluye/conexion1.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

// Obtener datos JSON
$input = file_get_contents("php://input");
$datos = json_decode($input, true);

if (!$datos) {
    echo json_encode(["error" => "Datos inválidos"]);
    exit;
}

$id = intval($datos['id']);
$nombre = mysqli_real_escape_string($conn, $datos['nombre']);
$cedula = mysqli_real_escape_string($conn, $datos['cedula']);
$fecha_nacimiento = mysqli_real_escape_string($conn, $datos['fecha_nacimiento']);
$nacionalidad = mysqli_real_escape_string($conn, $datos['nacionalidad']);
$telefono = mysqli_real_escape_string($conn, $datos['telefono']);
$correo = mysqli_real_escape_string($conn, $datos['correo']);

// Funciones para validar
function validar_fecha($fecha) {
    $d = DateTime::createFromFormat("Y-m-d", $fecha);
    return $d && $d->format("Y-m-d") === $fecha;
}

function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validar_telefono($telefono) {
    return preg_match('/^\d{7,14}$/', $telefono);
}

function validar_nombre($nombre) {
    return preg_match('/^[a-zA-ZÀ-ÿ\s]{1,40}$/', $nombre);
}

function validar_cedula($cedula) {
    return preg_match('/^[a-zA-Z0-9\-]{4,30}$/', $cedula);
}

function validar_nacionalidad($nacionalidad) {
    return preg_match('/^[a-zA-ZÀ-ÿ\s]{1,40}$/', $nacionalidad);
}

// Validar campos
if (
    !validar_nombre($nombre) ||
    !validar_cedula($cedula) ||
    !validar_fecha($fecha_nacimiento) ||
    !validar_nacionalidad($nacionalidad) ||
    !validar_telefono($telefono) ||
    !validar_email($email)
) {
    echo json_encode(["error" => "Datos inválidos, por favor revisa los campos."]);
    exit;
}

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Conexión a la base de datos
$conexion = new mysqli("localhost", "tu_usuario", "tu_contraseña", "registro_aspirantes");

if ($conexion->connect_error) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

// Preparamos la consulta para actualizar
$sql = "UPDATE aspirantes SET 
            nombre = ?, 
            cedula_pasaporte = ?, 
            fecha_nacimiento = ?, 
            nacionalidad = ?, 
            telefono = ?, 
            correo_contacto = ?
        WHERE usuario_id = ?";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación de la consulta"]);
    exit;
}

$stmt->bind_param(
    "ssssssi",
    $nombre,
    $cedula,
    $fecha_nacimiento,
    $nacionalidad,
    $telefono,
    $email,
    $usuario_id
);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "No se pudo actualizar los datos"]);
}

$stmt->close();
$conexion->close();
