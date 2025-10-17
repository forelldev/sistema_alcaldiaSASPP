<?php 
$acciones = [
                    'En Revisión 1/2' => 'Enviar a Administración',
                    'En Proceso 2/2 (Sin entregar)' => 'Finalizar Solicitud (Se entregó la ayuda)',
                    'Solicitud Finalizada (Ayuda Entregada)' => 'Reiniciar en caso de algún error'
                ];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes Internas de Despacho</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">Solicitudes internas de despacho</div>
        <div class="header-right">
            <a href="<?=BASE_URL?>/despacho_busqueda"><button class="nav-btn principal-btn"><i class="fa fa-plus"></i> Rellenar Formulario</button></a>
            <a href="<?=BASE_URL?>/main"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
            <a href="<?=BASE_URL?>/inhabilitados_despacho"><button class="nav-btn"><i class="fa fa-eye-slash"></i> Ver Solicitudes Inhabilitadas (Despacho)</button></a>
        </div>
    </header>
    <main>
        <section class="solicitudes-lista">
            <?php if (!empty($datos)):  ?>
                <?php foreach ($datos as $fila): ?>
                    <div class="solicitud-card">
                        <div class="solicitud-header">
                            <span class="solicitud-estado 
                                <?php
                                    $estado = htmlspecialchars($fila['estado'] ?? '');
                                    if ($estado == 'En espera del documento físico para ser procesado 0/3') echo 'pendiente';
                                    else if ($estado == 'En Revisión 1/2') echo 'activo1';
                                    else if ($estado == 'En Proceso 2/2 (Sin entregar)') echo 'activo2';
                                    else if ($estado == 'En Proceso 3/3 (Sin entregar)') echo 'activo3';
                                    else if ($estado == 'Solicitud Finalizada (Ayuda Entregada)') echo 'finalizada';
                                    else if ($estado == 'Documento inválido') echo 'invalido';
                                ?>">
                                <?= $estado ?>
                            </span>
                            <div><strong>Fecha de creación:</strong> <?= htmlspecialchars(date('d-m-Y', strtotime($fila['fecha'])))?></div>
                        </div>
                        <div class="solicitud-info">
                            <div><strong>Descripción:</strong> <?= htmlspecialchars($fila['descripcion']) ?></div>
                            <div><strong>ID Manual:</strong> <?= htmlspecialchars($fila['id_manual'] ?? '') ?></div>
                            <div><strong>Cédula de Identidad:</strong> <?= htmlspecialchars($fila['ci'] ?? '') ?></div>
                            <div><strong>Prioridad:</strong> <?= htmlspecialchars($fila['prioridad'] ?? '') ?></div>
                            <div><strong>Categoría:</strong> <?= htmlspecialchars($fila['categoria'] ?? '') ?></div>
                            <div><strong>Tipo de Ayuda:</strong> <?= htmlspecialchars($fila['tipo_ayuda'] ?? '') ?></div>
                            <div><strong>Creador:</strong> <?= htmlspecialchars($fila['creador'] ?? '') ?></div>
                            <div><strong>Remitente:</strong> <?= htmlspecialchars(($fila['nombre'] ?? '') . ' ' . ($fila['apellido'] ?? ''))?></div>
                        </div>
                        <div class="solicitud-actions">
                            <a href="<?= BASE_URL ?>/informacion_beneficiario?ci=<?= $fila['ci']?>" class="aprobar-btn">Ver Información del beneficiario</a>
                            <?php if($estado == 'En Revisión 1/2' || $estado == 'Solicitud Finalizada (Ayuda Entregada)'){ ?>
                            <a href="<?= BASE_URL.'/editarDespacho?id_despacho='.$fila['id_despacho']  ?>" class="aprobar-btn">Editar</a>
                            <a href="<?= BASE_URL.'/inhabilitarDespacho?id_despacho='.$fila['id_despacho'] ?>" class="rechazar-btn">Inhabilitar</a>
                                <a href="<?= BASE_URL.'/procesarDespacho?id_despacho='.$fila['id_despacho'].'&estado='.$fila['estado'] ?>" class="aprobar-btn">
                                    <?= $accion = isset($acciones[$fila['estado']]) ? $acciones[$fila['estado']] : 'Acción desconocida'; ?>
                                </a>
                            <?php } ?>
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