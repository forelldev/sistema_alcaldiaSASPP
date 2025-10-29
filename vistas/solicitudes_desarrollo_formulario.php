<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Solicitud</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/reportes.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/new_style.css?v=<?php echo time(); ?>">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
       <div class="titulo-header">
            Continuar Caso de la persona: 
            <?= htmlspecialchars($datos_beneficiario['solicitante']['nombre'] ?? '') . ' ' . htmlspecialchars($datos_beneficiario['solicitante']['apellido'] ?? '').' '.htmlspecialchars($datos_beneficiario['solicitante']['ci']) ?>
        </div>
                <div class="header-right">
            <a href="<?= BASE_URL ?>/solicitudes_desarrollo">
                <button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver</button>
            </a>
        </div>
    </header>

    <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>

    <form action="<?= BASE_URL ?>/caso_continuar" method="POST" id="form_solicitud" class="formulario-ayuda">
        <h2 class="form-titulo"><i class="fa fa-hands-helping"></i> Solicitud de Ayuda Desarrollo Social</h2>

        <div class="titulo-seccion"><i class="fa fa-user"></i> Datos de la Solicitud</div>
        <div class="fila-formulario">
            <label for="tipo_ayuda">Categoría:</label>
            <select id="tipo_ayuda" name="categoria" required>
                <option value="">Seleccione...</option>
                <option value="Medicamentos">Medicamentos</option>
                <option value="Laboratorio">Laboratorio</option>
                <option value="Ayudas Técnicas">Ayudas Técnicas</option>
                <option value="Ayuda Económica">Ayuda Económica</option>
                <option value="Enseres">Enseres</option>
            </select>

            <input type="hidden" name="tipo_ayuda" value="Otros">
            <input type="hidden" name="id_caso" value="<?= $id_caso ?? '' ?>">
            <input type="hidden" name="direccion" value="<?=$direccion ?? ''?>">
        </div>

        <div class="form-boton-contenedor">
            <input type="submit" value="Registrar Solicitud" class="btn-principal">
        </div>
        </form>

    <script>
        const BASE_PATH = "<?php echo BASE_PATH; ?>";
    </script>
    <script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
    <script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
    <script src="<?= BASE_URL ?>/public/js/solicitud_urgencia.js"></script>
</body>
</html>
