<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once '../incluye/conexion1.php'; // mysqli procedural

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data || !isset($data['id'], $data['estado'], $data['comentarios'])) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Datos incompletos o mal formateados"
    ]);
    exit;
}

$id = intval($data['id']);
$estado = mysqli_real_escape_string($conn, $data['estado']);
$comentarios = mysqli_real_escape_string($conn, $data['comentarios']);

$sql = "UPDATE aspirantes SET estado_solicitud = '$estado', comentarios = '$comentarios' WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    echo json_encode([
        "success" => true,
        "mensaje" => "Solicitud actualizada correctamente"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "mensaje" => "Error al ejecutar la actualización: " . mysqli_error($conn)
    ]);
}
?>
