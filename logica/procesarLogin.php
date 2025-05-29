<?php
session_start();
require_once '../incluye/conexion.php';

// Inicializar contador de intentos fallidos (protección contra fuerza bruta)
if (!isset($_SESSION['intentos'])) {
    $_SESSION['intentos'] = 0;
}

if ($_SESSION['intentos'] >= 5) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Demasiados intentos fallidos. Intenta en 5 minutos."]);
    exit;
}

// Función para limpiar los datos recibidos
function limpiarEntrada($valor) {
    return htmlspecialchars(trim($valor));
}

// Configurar la respuesta en JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // **Registro**
    if (isset($_POST['registro'])) {
        $usuario = limpiarEntrada($_POST['nombre'] ?? '');
        $correo = limpiarEntrada($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmarPassword = $_POST['password2'] ?? '';

        // Validaciones
        if ($password !== $confirmarPassword) {
            echo json_encode(["error" => "Las contraseñas no coinciden."]);
            exit;
        }

        if (empty($usuario) || empty($correo) || empty($password)) {
            echo json_encode(["error" => "Todos los campos son obligatorios."]);
            exit;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["error" => "Correo electrónico no válido."]);
            exit;
        }

        // Validación de contraseña segura
        if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).{6,15}$/', $password)) {
            echo json_encode(["error" => "La contraseña debe tener entre 6 y 15 caracteres, incluir letras, números y símbolos."]);
            exit;
        }
        
        // Evitar duplicados en la base de datos
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE nombre_usuario = ? OR correo = ?");
        $stmt->bind_param("ss", $usuario, $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            echo json_encode(["error" => "El usuario o correo ya está registrado."]);
            exit;
        }

        // Encriptar contraseña antes de guardarla
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Aquí asumimos que el rol por defecto es 'aspirante' (o lo que quieras)
        $rol = 'aspirante';

        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $usuario, $correo, $passwordHash, $rol);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Usuario registrado correctamente."]);
        } else {
            echo json_encode(["error" => "Error al registrar usuario."]);
        }

        $stmt->close();
        $conexion->close();
        exit;
    }

    // **Login**
    $usuario = limpiarEntrada($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($usuario) || empty($password)) {
        echo json_encode(["error" => "Por favor, complete todos los campos."]);
        exit;
    }

    // Buscar usuario en la base de datos
    $stmt = $conexion->prepare("SELECT id, nombre_usuario, contrasena, rol FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        $_SESSION['intentos']++;
        echo json_encode(["error" => "Usuario no encontrado."]);
        exit;
    }

    $usuarioDB = $resultado->fetch_assoc();
    $usuario_id = $usuarioDB['id'];

    // Verificación de la contraseña
    if (!password_verify($password, $usuarioDB['contrasena'])) {
        $_SESSION['intentos']++;
        echo json_encode(["error" => "Contraseña incorrecta."]);
        exit;
    }

    // Login exitoso, se guardan los datos de sesión
    $_SESSION['usuario_id'] = $usuario_id;
    $_SESSION['usuario'] = $usuarioDB['nombre_usuario'];
    $_SESSION['rol'] = $usuarioDB['rol'];
    $_SESSION['intentos'] = 0; // Reset de intentos fallidos

    // Definir la URL de redirección según el rol
    $urlRedireccion = "dashboard.php"; // por defecto
    if ($usuarioDB['rol'] === 'RH') {
        $urlRedireccion = "dashboardrh.php";
    } else if ($usuarioDB['rol'] === 'Admin') {
        $urlRedireccion = "dashboardadmin.php";
    }
    // Puedes agregar más condiciones según roles

    echo json_encode([
        "success" => "Login exitoso.",
        "rol" => $usuarioDB['rol'],
        "redirect" => $urlRedireccion
    ]);

    exit;
}
?>
