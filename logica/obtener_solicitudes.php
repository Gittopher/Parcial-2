<?php
// Indica que la respuesta será en formato JSON
header('Content-Type: application/json');

// Incluye el archivo de conexión a la base de datos
require_once 'conexion.php'; // Ajusta el nombre del archivo de conexión si es necesario

try {
    // Consulta SQL para obtener los datos necesarios de la tabla usuarios
    $sql = "SELECT id, nombre, apellido, fecha_registro, estado_solicitud FROM usuarios";
    // Prepara la consulta para evitar inyecciones SQL
    $stmt = $conn->prepare($sql);
    // Ejecuta la consulta preparada
    $stmt->execute();

    // Inicializa un arreglo para almacenar las solicitudes obtenidas
    $solicitudes = [];

    // Recorre cada fila de resultados obtenidos
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Agrega cada solicitud al arreglo, combinando nombre y apellido
        $solicitudes[] = [
            'id' => $row['id'],
            'nombre' => $row['nombre'] . ' ' . $row['apellido'],
            'fecha' => $row['fecha_registro'],
            'estado' => $row['estado_solicitud']
        ];
    }

    // Devuelve el arreglo de solicitudes en formato JSON
    echo json_encode($solicitudes);
} catch (PDOException $e) {
    // Si ocurre un error, devuelve un mensaje de error en formato JSON
    echo json_encode(['error' => 'Error al obtener solicitudes']);
}
?>