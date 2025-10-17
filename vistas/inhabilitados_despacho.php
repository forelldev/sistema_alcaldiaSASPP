<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Solicitudes inhabilitadas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">Solicitudes inhabilitadas</div>
        <div class="header-right">
            <a href="<?=BASE_URL?>/main"><button class="nav-btn"><i class="fa fa-home"></i> Inicio</button></a>
            <a href="<?=BASE_URL?>/despacho_list"><button class="nav-btn"><i class="fa fa-eye"></i> Ver Solicitudes Habilitadas</button></a>
        </div>
    </header>
    <main>
        <section class="solicitudes-lista">
            <?php if (!empty($datos)):  ?>
                <?php foreach ($datos as $fila): ?>
                    <div class="solicitud-card">
                        <div class="solicitud-header">
                            <span class="solicitud-estado inhabilitada">
                                <?= htmlspecialchars($fila['estado'] ?? '') ?>
                            </span>
                            <div><strong>Fecha:</strong> <?= htmlspecialchars(date('d-m-Y', strtotime($fila['fecha'])))?></div>
                        </div>
                        <div class="solicitud-info">
                            <div><strong>Asunto:</strong> <?= htmlspecialchars($fila['asunto']) ?></div>
                            <div><strong>ID Manual:</strong> <?= htmlspecialchars($fila['id_manual']) ?></div>
                            <div><strong>Cédula de Identidad:</strong> <?= htmlspecialchars($fila['ci'] ?? '') ?></div>
                            <div><strong>Creador:</strong> <?= htmlspecialchars($fila['creador'] ?? '') ?></div>
                        </div>
                        <div class="solicitud-actions">
                            <a href="<?= BASE_URL ?>/" class="aprobar-btn">Ver Información del beneficiario</a>
                            <a href="<?= BASE_URL.'/editarDespacho?id_doc='.$fila['id_doc']  ?>" class="aprobar-btn">Editar</a>
                            <a href="<?= BASE_URL.'/habilitarDespacho?id_doc='.$fila['id_doc'] ?>" class="aprobar-btn">Habilitar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="solicitud-card">
                    <div class="solicitud-header">
                        <span class="solicitud-estado">Sin información</span>
                    </div>
                    <div class="solicitud-info">
                        No hay información disponible.
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</html>