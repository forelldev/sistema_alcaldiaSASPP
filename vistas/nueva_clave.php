<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña</title>
</head>
<body>
    <a href="<?=BASE_URL?>/">Volver</a>
    <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>
    <form id="formClave" action="<?=BASE_URL?>/actualizar_clave" method="POST">
        <input type="password" name="clave_nueva" id="clave_nueva" placeholder="Nueva contraseña" required>
        <button type="button" onclick="togglePassword('clave_nueva')">👁️</button>
        <input type="password" name="confirmar_clave" id="confirmar_clave" placeholder="Confirmar contraseña" required>
        <button type="button" onclick="togglePassword('confirmar_clave')">👁️</button>
        <input type="submit" value="Cambiar Contraseña">
        <p id="error" style="color: red;"></p>
    </form>
</body>
 <script>
        function togglePassword(id) {
            const campo = document.getElementById(id);
            campo.type = campo.type === "password" ? "text" : "password";
        }
    </script>
</html>