<?php
session_start();
require_once '../incluye/conexion.php'; // Usa tu conexión MySQLi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($usuario) || empty($password)) {
        echo "Por favor, complete todos los campos.";
        exit;
    }

    // Preparar la consulta usando MySQLi
    $stmt = $conexion->prepare("SELECT id, nombre_usuario, contrasena, rol FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuarioDB = $resultado->fetch_assoc();

        if (password_verify($password, $usuarioDB['contrasena'])) {
            $_SESSION['usuario_id'] = $usuarioDB['id'];
            $_SESSION['usuario'] = $usuarioDB['nombre_usuario'];
            $_SESSION['rol'] = $usuarioDB['rol'];

            // Redirigir según el rol
            switch ($usuarioDB['rol']) {
                case 'aspirante':
                    header("Location: ../pantallas/perfil_aspirante.php");
                    break;
                case 'rh':
                    header("Location: ../pantallas/panel_rh.php");
                    break;
                case 'admin':
                    header("Location: ../pantallas/superadmin.php");
                    break;
                default:
                    echo "Rol no reconocido.";
                    exit;
            }
            exit;
        } else {
            echo "Contraseña incorrecta.";
            exit;
        }
    } else {
        echo "El usuario no existe.";
        exit;
    }

    $stmt->close();
    $conexion->close();
}
?>
