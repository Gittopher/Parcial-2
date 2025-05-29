<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once '../incluye/conexion1.php'; 

// Recibe el JSON del frontend
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Validación
if (!$data || !isset($data['id'], $data['estado'], $data['comentarios'])) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Datos incompletos o mal formateados"
    ]);
    exit;
}

$id = $data['id'];
$estado = $data['estado'];
$comentarios = $data['comentarios'];

try {
    // Prepara y ejecuta usando PDO
    $stmt = $conn->prepare("UPDATE usuarios SET estado = :estado, comentarios = :comentarios WHERE id = :id");
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':comentarios', $comentarios);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "mensaje" => "Solicitud actualizada correctamente"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "mensaje" => "Error al ejecutar la actualización"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Error de base de datos: " . $e->getMessage()
    ]);
}
?>
