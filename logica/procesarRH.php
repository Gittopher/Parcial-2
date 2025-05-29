<?php

session_start();
$_SESSION['usuario_rol'] = 'admin';

require_once '../incluye/conexion.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'obtener':
        $sql = "
            SELECT u.id, u.nombre_usuario, u.correo, pr.nombre_completo
            FROM usuarios u
            INNER JOIN personal_rh pr ON pr.usuario_id = u.id
            WHERE u.rol = 'rh'
        ";
        $resultado = $conexion->query($sql);
        $data = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $data[] = $fila;
            }
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Error en la consulta']);
        }
        break;

    case 'procesar':
        $usuario = trim($_POST['usuario'] ?? '');
        $correo = filter_var(trim($_POST['correo'] ?? ''), FILTER_SANITIZE_EMAIL);
        $contrasena = $_POST['contrasena'] ?? '';
        $nombre_completo = trim($_POST['nombre_completo'] ?? '');

        if (empty($usuario) || empty($correo) || empty($contrasena) || empty($nombre_completo)) {
            echo "❌ Todos los campos son obligatorios.";
            exit();
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo "❌ Correo no válido.";
            exit();
        }

        // Ajusta la validación según tu política de contraseñas
        if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{8,}$/', $contrasena)) {
            echo "❌ Contraseña insegura. Debe tener mínimo 8 caracteres, incluir letras, números y símbolos.";
            exit();
        }

        $conexion->begin_transaction();

        try {
            // Validar si ya existe usuario o correo
            $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE nombre_usuario = ? OR correo = ?");
            $stmt->bind_param("ss", $usuario, $correo);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                throw new Exception("Usuario o correo ya existe.");
            }
            $stmt->close();

            $hash = password_hash($contrasena, PASSWORD_BCRYPT);

            // Insertar en usuarios
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol) VALUES (?, ?, ?, 'rh')");
            $stmt->bind_param("sss", $usuario, $correo, $hash);
            $stmt->execute();
            $usuario_id = $conexion->insert_id;
            $stmt->close();

            // Insertar en personal_rh
            $stmt = $conexion->prepare("INSERT INTO personal_rh (usuario_id, nombre_completo) VALUES (?, ?)");
            $stmt->bind_param("is", $usuario_id, $nombre_completo);
            $stmt->execute();
            $stmt->close();

            $conexion->commit();
            echo "✅ Personal RH registrado.";
        } catch (Exception $e) {
            $conexion->rollback();
            echo "❌ Error: " . htmlspecialchars($e->getMessage());
        }

        break;

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);

        $conexion->begin_transaction();

        try {
            $stmt = $conexion->prepare("DELETE FROM personal_rh WHERE usuario_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            $conexion->commit();
            echo "✅ Eliminado correctamente.";
        } catch (Exception $e) {
            $conexion->rollback();
            echo "❌ Error al eliminar.";
        }
        break;

    default:
        echo "❌ Acción no válida.";
}



