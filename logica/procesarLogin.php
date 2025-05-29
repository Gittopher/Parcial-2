<?php
session_start();
require_once '../incluye/conexion.php'; // Conexión a la base de datos

// Manejo de Registro de Usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['registro'])) { // Si es un registro
        $usuario = trim($_POST['usuario']);
        $correo = trim($_POST['correo']);
        $password = trim($_POST['password']);
        $confirmarPassword = trim($_POST['confirmar_password']); // Captura la confirmación de contraseña
        $rol = $_POST['rol'] ?? 'aspirante';

        // Verificar que las contraseñas coincidan
        if ($password !== $confirmarPassword) {
            echo json_encode(["error" => "Las contraseñas no coinciden"]);
            exit;
        }

        // Validaciones adicionales
        if (empty($usuario) || empty($correo) || empty($password)) {
            echo json_encode(["error" => "Todos los campos son obligatorios"]);
            exit;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["error" => "El correo no es válido"]);
            exit;
        }

        if (strlen($password) < 6) {
            echo json_encode(["error" => "La contraseña debe tener al menos 6 caracteres"]);
            exit;
        }

        // Verificar si el usuario ya existe
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE nombre_usuario = ? OR correo = ?");
        $stmt->bind_param("ss", $usuario, $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            echo json_encode(["error" => "El usuario o correo ya está registrado"]);
            exit;
        }

        // Encriptar la contraseña antes de guardar
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Insertar usuario en la base de datos
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $usuario, $correo, $passwordHash, $rol);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Usuario registrado correctamente"]);
        } else {
            echo json_encode(["error" => "Error al registrar usuario"]);
        }

        $stmt->close();
        $conexion->close();
        exit;
    }

    // Lógica de Inicio de Sesión
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    if (empty($usuario) || empty($password)) {
        echo json_encode(["error" => "Por favor, complete todos los campos."]);
        exit;
    }

    // Buscar el usuario en la tabla `usuarios` por su nombre_usuario y recuperar la contraseña
    $stmt = $conexion->prepare("SELECT id, nombre_usuario, contrasena, rol FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        echo json_encode(["error" => "No se encontró el usuario en la base de datos"]);
        exit;
    }

    $usuarioDB = $resultado->fetch_assoc();
    $usuario_id = $usuarioDB['id'];

    // ✅ Verificar contraseña correctamente
    if (!password_verify($password, $usuarioDB['contrasena'])) {
        echo json_encode(["error" => "Contraseña incorrecta."]);
        exit;
    }

    // Establecer sesión
    $_SESSION['usuario_id'] = $usuario_id;
    $_SESSION['usuario'] = $usuarioDB['nombre_usuario'];
    $_SESSION['rol'] = $usuarioDB['rol'];

    // Redirección según el rol
    if ($usuarioDB['rol'] === 'rh') {
        header("Location: ../pantallas/dashboard_rh.html");
        exit;
    }

    $stmt = $conexion->prepare("SELECT id FROM aspirantes WHERE usuario_id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        header("Location: ../pantallas/InfoAsp.html");
        exit;
    } else {
        header("Location: ../pantallas/RegisterHR.html");
        exit;
    }

    $stmt->close();
    $conexion->close();
}
?>