<?php
// Mostrar errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Indica que la respuesta será en formato JSON
header('Content-Type: application/json');

// Incluye el archivo de conexión a la base de datos
require_once '../incluye/conexion1.php'; // Asegúrate de que el nombre y la ruta sean correctos

try {
    // Consulta SQL para obtener los datos necesarios de la tabla usuarios
    $sql = "SELECT id, nombre_usuario, correo, contrasena, rol, fecha_creacion FROM usuarios";
    
    // Prepara la consulta
    $stmt = $conn->prepare($sql);

    // Ejecuta la consulta
    $stmt->execute();

    // Inicializa un arreglo para almacenar las solicitudes
    $solicitudes = [];

    // Recorre cada fila de resultados
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $solicitudes[] = [
            'id'     => $row['id'],
            'nombre' => $row['nombre_usuario'],
            'fecha'  => $row['fecha_creacion'],
        ];
    }

    // Devuelve las solicitudes en formato JSON
    echo json_encode($solicitudes);

} catch (PDOException $e) {
    // Devuelve un error si algo falla
    echo json_encode(['error' => 'Error al obtener solicitudes: ' . $e->getMessage()]);
}
