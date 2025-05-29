<?php
session_start();
require_once '../incluye/conexion1.php'; // conexión mysqli procedural

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // REGISTRO DE USUARIO
    if (isset($_POST['registro'])) {
        $usuario = trim($_POST['usuario']);
        $correo = trim($_POST['correo']);
        $password = trim($_POST['password']);
        $confirmarPassword = trim($_POST['confirmar_password']);
        $rol = $_POST['rol'] ?? 'aspirante';

        if ($password !== $confirmarPassword) {
            echo json_encode(["error" => "Las contraseñas no coinciden"]);
            exit;
        }

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

        $query = "SELECT id FROM usuarios WHERE nombre_usuario = ? OR correo = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "ss", $usuario, $correo);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo json_encode(["error" => "El usuario o correo ya está registrado"]);
            exit;
        }

        mysqli_stmt_close($stmt);

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $usuario, $correo, $passwordHash, $rol);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(["success" => "Usuario registrado correctamente"]);
        } else {
            echo json_encode(["error" => "Error al registrar usuario"]);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
        exit;
    }

    // INICIO DE SESIÓN
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    if (empty($usuario) || empty($password)) {
        echo json_encode(["error" => "Por favor, complete todos los campos."]);
        exit;
    }

    $query = "SELECT id, nombre_usuario, contrasena, rol FROM usuarios WHERE nombre_usuario = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) === 0) {
        echo json_encode(["error" => "No se encontró el usuario"]);
        exit;
    }

    $usuarioDB = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);

    if (!password_verify($password, $usuarioDB['contrasena'])) {
        echo json_encode(["error" => "Contraseña incorrecta."]);
        exit;
    }

    $_SESSION['usuario_id'] = $usuarioDB['id'];
    $_SESSION['usuario'] = $usuarioDB['nombre_usuario'];
    $_SESSION['rol'] = $usuarioDB['rol'];

    // Verificar si el usuario ya tiene datos en `aspirantes`
    $usuario_id = $usuarioDB['id'];
    $query = "SELECT id FROM aspirantes WHERE usuario_id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $usuario_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        header("Location: ../pantallas/InfoAsp.html");
        exit;
    } else {
        header("Location: ../pantallas/RegisterHR.html");
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}
?>
