<?php
require_once '../incluye/conexion.php'; // Archivo donde creas $conexion (PDO)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y sanear datos
    $usuario = trim($_POST["usuario"] ?? '');
    $password = $_POST["password"] ?? '';
    $confirmar = $_POST["confirmar_password"] ?? '';

    // Validar campos vacíos
    if (empty($usuario) || empty($password) || empty($confirmar)) {
        echo "Por favor, complete todos los campos.";
        exit;
    }

    // Validar que las contraseñas coincidan
    if ($password !== $confirmar) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    // Validar que el usuario no exista ya en la base de datos
    $sql_check = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conexion->prepare($sql_check);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "El nombre de usuario ya está en uso, elija otro.";
        exit;
    }
    $stmt->close();

    // Hashear la contraseña antes de guardar
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar nuevo usuario en la base de datos
    $sql_insert = "INSERT INTO usuarios (nombre_usuario, contrasena) VALUES (?, ?)";
    $stmt_insert = $conexion->prepare($sql_insert);
    $stmt_insert->bind_param("ss", $usuario, $password_hash);

    if ($stmt_insert->execute()) {
        echo "Usuario registrado con éxito.";
        header("Location: /Parcial-2/pantallas/RegisterHR.php"); // Redirigir a la pantalla de registro de RH
        // Puedes redirigir a login u otra página aquí:
        // header("Location: ../pantallas/Login.html");
        // exit;
    } else {
        echo "Error al registrar usuario: " . $conexion->error;
    }

    $stmt_insert->close();
    $conexion->close();
} else {
    echo "Método no permitido.";
}
?>
