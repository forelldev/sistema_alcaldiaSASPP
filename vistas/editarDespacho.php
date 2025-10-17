<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Solicitud</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">Editar solicitud de despacho</div>
        <div class="header-right">
            <a href="<?=BASE_URL?>/main"><button class="nav-btn"><i class="fa fa-home"></i> Inicio</button></a>
            <a href="<?=BASE_URL?>/despacho_list"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
        </div>
    </header>
    <main>
        <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>
        <form action="editar_solicitudDespacho" method="POST" class="registro-card form-user" autocomplete="off">
            <h2><i class="fa fa-edit"></i> Editar solicitud de despacho</h2>
            <div class="campo-user">
                <label for="id_manual">Número de documento:</label>
                <input type="text" id="id_manual" name="id_manual" placeholder="00004578" required value="<?= htmlspecialchars($datos['id_manual'] ?? '') ?>">
            </div>
            <div class="campo-user">
                <label for="asunto">Asunto:</label>
                <input type="text" id="asunto" name="asunto" value="<?= htmlspecialchars($datos['asunto'] ?? '') ?>" placeholder="Ejem: Ayuda para silla de ruedas" required>
            </div>
            <input type="hidden" name="id_despacho" value="<?= htmlspecialchars($datos['id_despacho'] ?? '') ?>">
            <button type="submit" class="boton-enviar-ayuda"><i class="fa fa-save"></i> Guardar cambios</button>
        </form>
    </main>
</body>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</html>