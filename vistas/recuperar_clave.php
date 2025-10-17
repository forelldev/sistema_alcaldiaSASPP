<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar Código</title>
</head>
<body>
    <a href="<?=BASE_URL?>/">Volver</a>
    <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>
    <form action="<?=BASE_URL?>/nueva_clave" method="POST">
        <input type="text" name="codigo" placeholder="Ingresa el código enviado a tu correo" required>
        <input type="submit" value="Verificar Código">
    </form>
</body>
</html>