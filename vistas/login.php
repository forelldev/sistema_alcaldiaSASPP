<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Sesión</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="login-body">
    <section class="login-container">
        <img src="assets/body.png" alt="Logo">
        <h1>Bienvenido</h1>
    <form action="<?= BASE_PATH ?>/login" method="POST">
            <div class="input-group">
            <i class="fa fa-id-card"></i>
            <input type="text" name="ci" required placeholder="CI" autocomplete="off">
            </div>
        <div class="input-group">
            <i class="fa fa-lock"></i>
            <input type="password" name="clave" id="password" required placeholder="Contraseña">
            <button class="password-toggle" id="toggle-password" type="button" onclick="togglePasswordVisibility()">
                <i class="fa fa-eye"></i>
            </button>
        </div>
        <button type="submit" class="login-btn">Iniciar Sesión</button>
    </form>
    <br>
    <a href="recuperacion_clave">Recuperación de Cuenta</a>
    </section>
</body>
<!-- Copiar y pegar para los mensajes de error, se puede cambiar el "error" por "info" -->
<script src="<?= BASE_URL ?>/public/js/msj.js"></script>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
    <?php if (isset($_GET['msj'])): ?> mostrarMensaje("<?= htmlspecialchars($_GET['msj']) ?>", "info", 6500);
    <?php endif; ?>
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/contra.js"></script>
</html>