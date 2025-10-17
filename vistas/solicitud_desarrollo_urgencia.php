<?php 
$acciones = [
                            'En espera del documento físico para ser procesado 0/2' => 'Aprobar para su procedimiento',
                            'En Proceso 1/2' => 'Aprobar Ayuda',
                            'En Proceso 2/2 (Sin entregar)' => 'Finalizar Solicitud (Se entregó la ayuda)',
                            'Solicitud Finalizada (Ayuda Entregada)' => 'Reiniciar en caso de algún error'
        ];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">Solicitud de urgencia (Desarrollo Social)</div>
        <div class="header-right">
            <a href="<?= BASE_URL ?>/solicitudes_desarrollo"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
        </div>
    </header>
    <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>
     <main class="solicitudes-main">
<section class="solicitudes-lista">
    <?php if (!empty($datos)): ?>
            <?php foreach ($datos as $fila): ?>
                <div class="solicitud-card">
                    <div class="solicitud-header">
                        <span class="solicitud-estado
                            <?php
                                $estado = htmlspecialchars($fila['estado'] ?? '');
                                if ($estado == 'En espera del documento físico para ser procesado 0/3') echo 'pendiente';
                                else if ($estado == 'En Proceso 1/3') echo 'activo1';
                                else if ($estado == 'En Proceso 2/3') echo 'activo2';
                                else if ($estado == 'En Proceso 3/3 (Sin entregar)') echo 'activo3';
                                else if ($estado == 'Solicitud Finalizada (Ayuda Entregada)') echo 'finalizada';
                                else if ($estado == 'Documento inválido') echo 'invalido';
                            ?>">
                            <?= $estado ?>
                        </span>
                        <div><strong>Fecha:</strong> <?= htmlspecialchars(date('d-m-Y', strtotime($fila['fecha']))) ?></div>
                    </div>
                    <div class="solicitud-info">
                        <div><strong>Descripción:</strong> <?= htmlspecialchars($fila['descripcion']) ?></div>
                        <div><strong>Categoría:</strong> <?= htmlspecialchars($fila['categoria'] ?? '') ?></div>
                        <div><strong>ID Manual:</strong> <?= htmlspecialchars($fila['id_manual'] ?? '') ?></div>
                        <div><strong>CI:</strong> <?= htmlspecialchars($fila['ci'] ?? '') ?></div>
                        <div><strong>Remitente:</strong> <?= htmlspecialchars(($fila['nombre'] ?? '') . ' ' . ($fila['apellido'] ?? ''))?></div>
                        <?php if($fila['categoria'] == 'Laboratorio'){ ?>
                            <div><strong>Examenes:</strong> <?= htmlspecialchars($fila['examenes'] ?? '') ?></div>
                        <?php } ?>
                    </div>
                    <div class="solicitud-actions">
                        <a href="<?= BASE_URL ?>/informacion_beneficiario?ci=<?= $fila['ci']?>" class="aprobar-btn">Ver Información del beneficiario</a>
                        <?php if ($_SESSION['id_rol'] == 1 || $_SESSION['id_rol'] == 4): ?>
                        <a href="<?= BASE_URL.'/editarDesarrollo?id_des='.$fila['id_des'] ?>" class="aprobar-btn">Editar</a>
                        <?php endif; ?>
                        <?php if ($_SESSION['id_rol'] == 1 || $_SESSION['id_rol'] == 4): ?>
                            <a href="<?= BASE_URL.'/inhabilitarDesarrollo?id_des='.$fila['id_des'] ?>" class="rechazar-btn">Inhabilitar</a>
                        <?php endif; ?>
                        <a href="<?= BASE_URL.'/procesarDesarrollo?id_des='.$fila['id_des'].'&estado='.$fila['estado'] ?>" class="aprobar-btn">
                            <?= isset($acciones[$fila['estado']]) ? $acciones[$fila['estado']] : 'Acción desconocida'; ?>
                        </a>
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
</body>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</html>