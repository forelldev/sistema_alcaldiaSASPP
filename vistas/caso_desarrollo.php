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
            <?= htmlspecialchars($datos_beneficiario['solicitante']['nombre'] ?? '') . ' ' . htmlspecialchars($datos_beneficiario['solicitante']['apellido'] ?? '').' Cédula: '.htmlspecialchars($datos_beneficiario['solicitante']['ci']) ?>
        </div>
                <div class="header-right">
            <a href="<?= BASE_URL ?>/casos_lista">
                <button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver</button>
            </a>
        </div>
    </header>

    <form action="<?= BASE_URL ?>/caso_continuar" method="POST" id="form_solicitud" class="formulario-ayuda">
        <h2 class="form-titulo"><i class="fa fa-hands-helping"></i> Solicitud de Ayuda Desarrollo Social</h2>

        <div class="titulo-seccion"><i class="fa fa-user"></i> Datos de la Solicitud</div>
        <div class="fila-formulario">
            <label for="categoria">Categoría:</label>
            <select id="categoria" name="categoria" required>
                <option value="">Seleccione...</option>
                <option value="Medicamentos">Medicamentos</option>
                <option value="Laboratorio">Laboratorio</option>
                <option value="Ayudas Técnicas">Ayudas Técnicas</option>
                <option value="Ayuda Económica">Ayuda Económica</option>
                <option value="Enseres">Enseres</option>
            </select>


            <input type="hidden" name="id_caso" value="<?= $id_caso ?? '' ?>">
            <input type="hidden" name="direccion" value="<?=$direccion ?? ''?>">
            <input type="hidden" name="ci" value="<?=$ci ?? ''?>">
        </div>

        <div id="formulario-socioeconomico" style="margin-top:20px;"></div>


        <div class="form-boton-contenedor">
            <input type="submit" value="Procesar Caso" class="btn-principal">
        </div>
        </form>
<script src="<?= BASE_URL ?>/public/js/msj.js"></script>
    <script>
        const BASE_PATH = "<?php echo BASE_PATH; ?>";
    </script>
    <?php
        $mensaje = $msj ?? $_GET['msj'] ?? null;
        if ($mensaje):
        ?>
            <script>
                mostrarMensaje("<?= htmlspecialchars($mensaje) ?>", "info", 6500);
            </script>
    <?php endif; ?>
    <script src="<?= BASE_URL?>/public/js/form_economico.js"></script>
    <script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
    <script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</body>
</html>
