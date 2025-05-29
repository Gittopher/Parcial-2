<?php
session_start();
require_once '../incluye/conexion.php';

// Registrar info de aspirantes en su postulación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['registro']) && $_POST['registro'] === "true") { // Asegura que es un registro
        $cedula = trim($_POST['cedula']);
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $estado_civil = $_POST['estado_civil'];
        $genero = $_POST['genero'];
        $tipo_sangre = $_POST['tipo_sangre'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $nacionalidad = $_POST['nacionalidad'];
        $telefono = trim($_POST['telefono']);
        $residencia = trim($_POST['residencia']);
        $email = trim($_POST['email']);

        // Validaciones básicas
        if (empty($cedula) || empty($nombre) || empty($apellido) || empty($telefono) || empty($email)) {
            echo json_encode(["error" => "Todos los campos obligatorios deben ser completados"]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["error" => "El correo no es válido"]);
            exit;
        }

        if (!preg_match('/^\d{7,10}$/', $telefono)) {
            echo json_encode(["error" => "Número de teléfono inválido"]);
            exit;
        }

        // Verificar que la sesión está iniciada y que `usuario_id` está disponible
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(["error" => "Sesión no iniciada. Usuario ID no disponible."]);
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Verificar si la cédula ya está registrada en aspirantes
        $stmt = $conexion->prepare("SELECT id FROM aspirantes WHERE cedula_pasaporte = ?");
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            echo json_encode(["error" => "Este aspirante ya está registrado"]);
            exit;
        }

        // Insertar aspirante en la base de datos con usuario_id
        $stmt = $conexion->prepare("INSERT INTO aspirantes (usuario_id, cedula_pasaporte, nombre, apellido, estado_civil, genero, tipo_sangre, fecha_nacimiento, nacionalidad, telefono, residencia, correo_contacto) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("isssssssssss", $usuario_id, $cedula, $nombre, $apellido, $estado_civil, $genero, $tipo_sangre, $fecha_nacimiento, $nacionalidad, $telefono, $residencia, $email);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Solicitud enviada correctamente"]);
            header("Location: http://localhost/Parcial2/pantallas/InfoAsp.html"); 
            exit;
        } else {
            echo json_encode(["error" => "Error al registrar aspirante"]);
            exit;
        }

        $stmt->close();
        $conexion->close();
    }

    echo json_encode(["error" => "Acción no válida"]); // 👈 Evita ejecutar código no deseado
}
?>