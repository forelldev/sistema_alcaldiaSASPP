<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Límite de cuentas por roles</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/reportes.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
    <div class="titulo-header">Limites de cuentas por roles</div>
    <div class="header-right">
      <a href="<?= BASE_URL ?>/main"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
    </div>
  </header>
  <main class="auditoria-main">
    <section class="auditoria-tabla-card">
        <div class="tabla-responsive">
    <table class="auditoria-tabla">
        <thead>
            <tr>
                <th>Rol</th>
                <th>Número de cuentas con este rol</th>
                <th>Límite de cuentas</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody> 
        <?php if (!empty($datos)):  ?>
            <?php foreach ($datos as $fila): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['nombre_rol']) ?></td>
                    <td><?= htmlspecialchars($fila['num_cuentas']) ?></td>
                    <td><?= htmlspecialchars($fila['limite']) ?></td>
                    <td><a href="<?= BASE_URL ?>/limite_editar?id_rol=<?= htmlspecialchars($fila['id_rol'])?>" class="usuario-btn">Cambiar Límite</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No hay información disponible.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
    </section>
</main>
</body>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</html>