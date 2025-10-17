<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de solicitudes</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/estadisticas.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">Estadísticas de solicitudes</div>
        <div class="header-right">
            <a href="<?= BASE_URL ?>/main"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
        </div>
    </header>
    <main>
        <div class="estadisticas-card">
            <div class="estadisticas-header">
                <h2><i class="fa fa-chart-bar"></i> Resumen de solicitudes</h2>
            </div>
            <div class="estadisticas-contenido">
                <div class="estadisticas-resumen">
                    <div class="resumen-item espera">
                        <span class="resumen-label"><i class="fa fa-clock"></i> En Espera del Documento</span>
                        <span class="resumen-valor"><?= $datos['En espera del documento físico para ser procesado 0/3'] ?></span>
                    </div>
                    <div class="resumen-item proceso">
                        <span class="resumen-label"><i class="fa fa-spinner"></i> En proceso 1/3</span>
                        <span class="resumen-valor"><?= $datos['En Proceso 1/3'] ?></span>
                    </div>
                    <div class="resumen-item proceso">
                        <span class="resumen-label"><i class="fa fa-spinner"></i> En proceso 2/3</span>
                        <span class="resumen-valor"><?= $datos['En Proceso 2/3'] ?></span>
                    </div>
                    <div class="resumen-item proceso">
                        <span class="resumen-label"><i class="fa fa-spinner"></i> En proceso 3/3</span>
                        <span class="resumen-valor"><?= $datos['En Proceso 3/3 (Sin Entregar)'] ?></span>
                    </div>
                    <div class="resumen-item finalizados">
                        <span class="resumen-label"><i class="fa fa-check-circle"></i> Solicitudes finalizadas</span>
                        <span class="resumen-valor"><?= $datos['Solicitud Finalizada (Ayuda Entregada)'] ?></span>
                    </div>
                </div>
                <div class="estadisticas-grafico">
                    <canvas id="graficaSolicitudes"></canvas>
                </div>
            </div>
        </div>
        
    </main>
</body>
<script src="<?= BASE_URL ?>/libs/Chart.min.js"></script>
<script>
  window.solicitudesData = [
    <?= $datos['En espera del documento físico para ser procesado 0/3'] ?>,
    <?= $datos['En Proceso 1/3'] ?>,
    <?= $datos['En Proceso 2/3'] ?>,
    <?= $datos['En Proceso 3/3 (Sin Entregar)'] ?>,
    <?= $datos['Solicitud Finalizada (Ayuda Entregada)'] ?>
  ];
</script>
<script src="<?= BASE_URL ?>/public/js/grafica.js"></script>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</html>