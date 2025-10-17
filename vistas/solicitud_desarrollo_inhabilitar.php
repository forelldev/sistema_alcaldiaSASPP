<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inhabilitar</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">Inhabilitar solicitud</div>
        <div class="header-right">
            <a href="<?= BASE_URL ?>/main"><button class="nav-btn"><i class="fa fa-home"></i> Inicio</button></a>
            <a href="<?= BASE_URL ?>/solicitudes_list"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
        </div>
    </header>
    <main>
            <form action="inhabilitar_desarrollo" method="POST" class="registro-card form-user" autocomplete="off">
                <h2><i class="fa fa-ban"></i> Inhabilitar solicitud</h2>
                <input type="hidden" name="id_des" value="<?= $id_des ?? 'No hay ID' ?>">
                <div class="campo-user">
                    <label for="razon">Razón por la cual será inhabilitada esta solicitud</label>
                    <input type="text" name="razon" id="razon" placeholder="Razón por la cual será inhabilitada esta solicitud">
                </div>
                <button type="submit" class="boton-enviar-ayuda"><i class="fa fa-check"></i> Aceptar</button>
            </form>
  </main>
</body>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</html>