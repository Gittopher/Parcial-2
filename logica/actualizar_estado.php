<?php
header('Content-Type: application/json');
require_once '../conexion.php'; // Ajusta el nombre del archivo de conexion con la BD !!!




$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !isset($data['estado'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$id = $data['id'];
$estado = $data['estado'];
$comentarios = $data['comentarios'] ?? '';

try {
    $sql = "UPDATE usuarios SET estado_solicitud = :estado WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar estado']);
}
?>
