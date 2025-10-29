<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Solicitudes</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">Lista de Casos Alcaldía(Oficina: <?= $_SESSION['rol']; ?>)</div>
        <div class="header-right">
            <?php if($_SESSION['id_rol'] == 0 || $_SESSION['id_rol'] == 4){?>
                <a href="<?=BASE_URL?>/casos_ci_busqueda"><button class="principal-btn"><i class="fa fa-plus"></i> Registrar Nuevo Caso</button></a>
            <?php } ?>
      <a href="<?= BASE_URL ?>/main"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>

      </div>
  </header>
    <main>
    <section class="solicitudes-lista">
        <?php if (!empty($datos)): ?>
            <?php foreach ($datos as $fila): ?>
                <div class="solicitud-card">
                    <div class="solicitud-header">
                        <span class="solicitud-estado 
                            <?php
                                $estado = htmlspecialchars($fila['estado'] ?? '');
                                if ($estado == 'Sin Atender') echo 'pendiente';
                                else if ($estado == 'Atendidas') echo 'activo1';
                            ?>">
                            <?= $estado ?>
                        </span>
                        <div><strong>Fecha:</strong>
                            <?php
                                $fecha_caso = date('Y-m-d', strtotime($fila['fecha']));
                                $hoy = date('Y-m-d');
                            ?>
                            <?= $fecha_caso === $hoy ? 'Hoy' : htmlspecialchars(date('d-m-Y', strtotime($fila['fecha']))) ?>
                        </div>
                        <div><strong>Hora:</strong> <?= htmlspecialchars(date('g:i A', strtotime($fila['fecha']))) ?></div>
                    </div>
                    <div class="solicitud-info">
                        <div><strong>Descripción:</strong> <?= htmlspecialchars($fila['descripcion']) ?></div>
                        <div><strong>Número de documento:</strong> <?= htmlspecialchars($fila['id_caso'] ?? '') ?></div>
                        <div><strong>Cédula del Beneficiario:</strong> <?= htmlspecialchars($fila['ci'] ?? '') ?></div>
                        <div><strong>Remitente:</strong> <?= htmlspecialchars(($fila['nombre'] ?? '') . ' ' . ($fila['apellido'] ?? ''))?></div>
                        <div><strong>Creador del caso:</strong> <?= htmlspecialchars($fila['creador'] ?? '') ?></div>
                        <div><strong>Dirección a la que se dirige:</strong> <?= htmlspecialchars($fila['direccion'] ?? '') ?></div>
                        </div>
                        
                    <div class="solicitud-actions">
                        <?php if(!$_SESSION['id_rol'] == 0){ ?>
                        <a href="<?= BASE_URL ?>/atender_caso?id_caso=<?= $fila['id_caso']?>" class="aprobar-btn">Atender Caso</a>
                        <?php } ?>
                        <?php if($fila['estado'] == 'Sin Atender'){ ?>
                        <?php if ($_SESSION['id_rol'] == 0 || $_SESSION['id_rol'] == 4): ?>
                            <a href="<?= BASE_URL.'/editar?id_caso='.$fila['id_caso'] ?>" class="aprobar-btn">Editar</a>
                        <?php endif; ?>
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
<script src="<?= BASE_URL ?>/public/js/msj.js"></script>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
    <?php if (isset($msj)): ?> mostrarMensaje("<?= htmlspecialchars($msj) ?>", "info", 6500);
            <?php endif; ?>
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
<script src="<?= BASE_URL ?>/public/js/notificacionAdministrador.js"></script>
</html>