<?php
session_start();
require_once '/xampp/htdocs/Parcial2/incluye/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    die("No se ha iniciado sesión.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario_id = $_SESSION['usuario_id'];

    // Sanitizar y asignar variables
    $cedula = trim($_POST['cedula'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $estado_civil = $_POST['estado_civil'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $tipo_sangre = $_POST['tipo_sangre'] ?? '';
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $nacionalidad = trim($_POST['nacionalidad'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $residencia = trim($_POST['residencia'] ?? '');
    $correo_contacto = trim($_POST['email'] ?? '');

    // Validaciones con expresiones regulares
    $cedulaValida = preg_match('/^\d{1,2}-\d{1,4}-\d{1,6}$/', $cedula);
    $nombreValido = preg_match('/^[a-zA-ZÀ-ÿ\s]{1,40}$/', $nombre);
    $apellidoValido = preg_match('/^[a-zA-ZÀ-ÿ\s]{1,40}$/', $apellido);
    $telefonoValido = preg_match('/^\d{7,14}$/', $telefono);
    $emailValido = filter_var($correo_contacto, FILTER_VALIDATE_EMAIL);

    if (
        empty($nombre) || !$nombreValido ||
        empty($apellido) || !$apellidoValido ||
        empty($cedula) || !$cedulaValida ||
        empty($telefono) || !$telefonoValido ||
        empty($correo_contacto) || !$emailValido
    ) {
        die("Datos inválidos. Por favor revisa los campos ingresados.");
    }

    // Consulta preparada
    $sql = "UPDATE aspirantes SET 
                cedula_pasaporte = ?, nombre = ?, apellido = ?, estado_civil = ?, genero = ?, 
                tipo_sangre = ?, fecha_nacimiento = ?, nacionalidad = ?, telefono = ?, residencia = ?, 
                correo_contacto = ?
            WHERE usuario_id = ?";

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt->bind_param(
        "sssssssssssi",
        $cedula, $nombre, $apellido, $estado_civil, $genero, $tipo_sangre,
        $fecha_nacimiento, $nacionalidad, $telefono, $residencia,
        $correo_contacto, $usuario_id
    );

    if ($stmt->execute()) {
        header("Location: /Parcial2/pantallas/InfoAsp.html");
        exit;
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Método no válido.";
}

$conexion->close();
?>
