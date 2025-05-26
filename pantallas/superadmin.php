<?php
// Datos simulados (puedes modificar o agregar más)
$personal = [
    ['id' => 1, 'nombre' => 'Ana López', 'correo' => 'ana@empresa.com', 'acceso' => 1],
    ['id' => 2, 'nombre' => 'Carlos Ruiz', 'correo' => 'carlos@empresa.com', 'acceso' => 0],
    ['id' => 3, 'nombre' => 'María Pérez', 'correo' => 'maria@empresa.com', 'acceso' => 1],
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Super Admin - Gestión RRHH</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 1200px; margin: 30px auto; }
        .tarjetas { display: flex; flex-wrap: wrap; gap: 20px; }
        .tarjeta {
            background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001;
            padding: 20px; width: 300px; position: relative;
        }
        .tarjeta h3 { margin: 0 0 10px; }
        .tarjeta .acciones { margin-top: 15px; }
        .tarjeta form { display: inline; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-editar { background: #2196f3; color: #fff; }
        .btn-quitar { background: #f44336; color: #fff; }
        .btn-otorgar { background: #4caf50; color: #fff; }
        .btn-guardar { background: #ff9800; color: #fff; }
        .agregar-form { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 2px 8px #0001; }
    </style>
</head>
<body>
<div class="container">
    <h1>Gestión de Personal RRHH</h1>

    <!-- Formulario para agregar personal (solo visual, no funcional) -->
    <div class="agregar-form">
        <h2>Agregar Personal</h2>
        <form>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button class="btn btn-guardar" type="button">Agregar</button>
        </form>
    </div>

    <div class="tarjetas">
        <?php foreach ($personal as $p): ?>
            <div class="tarjeta">
                <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                <p><strong>Correo:</strong> <?= htmlspecialchars($p['correo']) ?></p>
                <p><strong>Acceso:</strong> <?= $p['acceso'] ? 'Concedido' : 'Denegado' ?></p>
                <div class="acciones">
                    <a href="#" class="btn btn-editar">Editar</a>
                    <?php if ($p['acceso']): ?>
                        <button class="btn btn-quitar" type="button">Quitar Acceso</button>
                    <?php else: ?>
                        <button class="btn btn-otorgar" type="button">Otorgar Acceso</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
