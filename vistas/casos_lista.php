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
        <div class="titulo-header">Lista de Casos</div>
        <div class="header-right">
            <a href="<?=BASE_URL?>/casos_ci_busqueda"><button class="principal-btn"><i class="fa fa-plus"></i> Registrar Nuevo Caso</button></a>
      <a href="<?= BASE_URL ?>/main"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
      </div>
  </header>
    <main>
    <section class="filtros-card">
        <form class="filtros-form" action="filtrar_fecha" method="POST">
            <label>
                Desde
                <input type="date" name="fecha_inicio" value="<?php echo isset($fecha_inicio) ? $fecha_inicio : ''; ?>" required>
            </label>
            <label>
                Hasta
                <input type="date" name="fecha_final" value="<?php echo isset($fecha_final) ? $fecha_final : ''; ?>" required>
            </label>
            <label>
                Seleccione un Estado:
                <select name="estado" required>
                    <option value="">Seleccione</option>
                    <option value="Sin Atender" <?= ($estado ?? '') == 'Sin Atender' ? 'selected' : '' ?>>Sin Atender</option>
                    <option value="Atendidas" <?= ($estado ?? '') == 'Atendidas' ? 'selected' : '' ?>>Atendidas</option>
                </select>
            </label>
            <button type="submit" name="btn_filtro" value="Filtrar" class="filtrar-btn">
                <i class="fa fa-filter"></i> <span>Filtrar</span>
            </button>
        </form>
    </section>
    <nav class="filtros-categorias">
        <a href="<?= BASE_URL ?>/filtrar?filtro=recientes" class="filtro-btn">
            <i class="fa fa-clock"></i> Más recientes
        </a>
        <a href="<?= BASE_URL ?>/filtrar?filtro=antiguos" class="filtro-btn">
            <i class="fa fa-history"></i> Más antiguos
        </a>
        <a href="<?= BASE_URL ?>/filtrar?filtro=sin_atender" class="filtro-btn">
            <i class="fa fa-hourglass-start"></i> Sin atender
        </a>
        <a href="<?= BASE_URL ?>/filtrar?filtro=atendidos" class="filtro-btn">
            <i class="fa fa-check-circle"></i> Atendidos
        </a>
    </nav>

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
                        <div><strong>Fecha:</strong> <?= htmlspecialchars(date('d-m-Y', strtotime($fila['fecha']))) ?></div>
                    </div>
                    <div class="solicitud-info">
                        <div><strong>Resumen:</strong> <?= htmlspecialchars($fila['descripcion']) ?></div>
                        <div><strong>Número de documento:</strong> <?= htmlspecialchars($fila['id_manual'] ?? '') ?></div>
                        <div><strong>Cédula del Beneficiario:</strong> <?= htmlspecialchars($fila['ci'] ?? '') ?></div>
                        <div><strong>Remitente:</strong> <?= htmlspecialchars(($fila['nombre'] ?? '') . ' ' . ($fila['apellido'] ?? ''))?></div>
                        <div><strong>Creador del caso:</strong> <?= htmlspecialchars($fila['creador'] ?? '') ?></div>
                        <div><strong>Dirección a la que se dirige:</strong> <?= htmlspecialchars($fila['direccion'] ?? '') ?></div>
                    </div>
                    <div class="solicitud-actions">
                        <a href="<?= BASE_URL ?>/informacion_beneficiario?ci=<?= $fila['ci']?>" class="aprobar-btn">Ver Información del beneficiario</a>
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
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
<script src="<?= BASE_URL ?>/public/js/notificacionAdministrador.js"></script>
</html>