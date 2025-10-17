<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
</head>
<body>
    <a href="<?=BASE_URL?>/">Volver</a>
    <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>
    <form action="<?=BASE_URL?>/recuperar_clave" method="POST">
        <input type="text" name="correo" placeholder="Ingresa tu correo electrónico" required>
        <input type="submit" value="Recuperar">
    </form>
</body>
</html>