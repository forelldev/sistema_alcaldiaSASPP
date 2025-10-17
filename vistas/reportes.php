<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/reportes.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
    <div class="titulo-header">Reportes</div>
    <div class="header-right">
      <a href="<?= BASE_URL ?>/main"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
    </div>
  </header>
    <section class="reportes-filtros">
      <form class="reportes-form" action="fecha_reportes" method="POST">
          <div class="filtro-fecha">
              <label for="desde"><i class="fa fa-calendar"></i> Desde</label>
              <input type="date" id="desde" name="fecha_inicio" value="<?php echo isset($fecha_inicio) ? $fecha_inicio : ''; ?>" required>
          </div>
          <div class="filtro-fecha">
              <label for="hasta"><i class="fa fa-calendar"></i> Hasta</label>
              <input type="date" id="hasta" name="fecha_final" value="<?php echo isset($fecha_final) ? $fecha_final : ''; ?>" required>
          </div>
        <button type="submit" class="buscar-btn"><i class="fa fa-search"></i> Buscar</button>
      </form>
    </section>
    <!-- Agregar un botón de exportar y búsqueda -->
        <div class="reportes-busqueda">
            <!-- LO VI INNECESARIO EN CASO DE IMPLEMENTAR QUITAR ETIQUETA Y ESTE TEXTO <input type="text" placeholder="Buscar CI o nombre..." class="buscar-input">
            <button class="buscar-btn"><i class="fa fa-search"></i> Buscar</button> -->
            <button class="buscar-btn" id="btnPDF" onclick="generarPDF()"><i class="fa fa-file-excel"></i> Exportar PDF</button>
        </div>
    <section class="reportes-tabla-card">
    <div class="tabla-responsive">
    <table class="reportes-tabla" id="exportarPDF">
        <thead>
            <tr>
                <th>Número</th>
                <th>Cédula de Identidad</th>
                <th>Fecha de Entrada</th>
                <th>Hora de Entrada</th>
                <th>Fecha de Salida</th>
                <th>Hora de Salida</th>
                <th>Nombre</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($datos)):  ?>
                <?php foreach ($datos as $fila): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['id']) ?></td>
                        <td><?= htmlspecialchars($fila['ci']) ?></td>
                        <td><?= date('d-m-Y', strtotime($fila['fecha_entrada'])) ?></td>
                        <td><?= date('H:i', strtotime($fila['fecha_entrada'])) ?></td>
                        <td><?= ($fila['fecha_salida'] === '0000-00-00 00:00:00' || empty($fila['fecha_salida'])) ? 'En Línea' : date('d-m-Y', strtotime($fila['fecha_salida'])) ?></td>
                        <td><?= ($fila['fecha_salida'] === '0000-00-00 00:00:00' || empty($fila['fecha_salida'])) ? 'En Línea' : date('H:i', strtotime($fila['fecha_salida'])) ?></td>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                <td colspan="7">No hay información disponible.</td>
            </tr>
        <?php endif; ?>
    </table>
    </div>
    <div class="paginacion">
        <?php if ($paginaActual > 1): ?>
            <a href="?pagina=<?= $paginaActual - 1 ?>" class="paginacion-btn">&laquo;</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?pagina=<?= $i ?>" class="paginacion-btn <?= $i == $paginaActual ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($paginaActual < $totalPaginas): ?>
            <a href="?pagina=<?= $paginaActual + 1 ?>" class="paginacion-btn">&raquo;</a>
        <?php endif; ?>
    </div>
    </section>
</body>
<script src="<?= BASE_URL ?>/libs/jspdf.umd.min.js"></script>
<script src="<?= BASE_URL ?>/libs/jspdf.plugin.autotable.min.js"></script>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
<script src="<?= BASE_URL ?>/public/js/exportarPDF.js"></script>
</html>