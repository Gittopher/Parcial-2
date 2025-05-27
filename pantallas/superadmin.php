<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión RH</title>
    <link rel="stylesheet" href="../assets/estilosRH.css">
</head>
<body>
    <div class="form-container">
        <h2>Agregar Personal RH</h2>
        <section class="formulario-rh">
            <form id="formAgregarRH" action="../logica/procesarRH.php" method="POST">
                <input type="hidden" name="action" value="procesar">
                <input type="text" name="nombre_usuario" placeholder="Usuario" required>
                <input type="email" name="correo" placeholder="Correo" required>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                <input type="text" name="nombre_completo" placeholder="Nombre completo" required>
                <button type="submit">Agregar</button>
            </form>

        </section>
        <div id="mensaje"></div>
    </div>

    <div class="cards-container">
        <h3>Personal Registrado</h3>
        <div class="cards" id="tarjetas"></div>
    </div>

    <script src="../scripts/gestionRH.js"></script>
</body>
</html>
