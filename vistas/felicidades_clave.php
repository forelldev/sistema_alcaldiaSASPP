<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felicidades!</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">¡Contraseña cambiada con éxito!</div>
    </header>
    <main>
        <div class="registro-card form-user" style="text-align:center;">
            <h1><i class="fa fa-check-circle" style="color:#3b4cca"></i> Contraseña cambiada con éxito!</h1>
            <a href="<?= BASE_URL ?>/">Ingresar</a>
        </div>
    </main>
</body>
</html>