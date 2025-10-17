<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de acciones</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/reportes.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
    <div class="titulo-header">Registro de acciones</div>
    <div class="header-right">
      <a href="<?= BASE_URL ?>/main"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
    </div>
  </header>
  <main class="auditoria-main">
        <div class="auditoria-header">
        <h1><i class="fa fa-clipboard-list"></i> Auditoría de Actividades</h1>
        <p>Consulta las acciones realizadas por los usuarios y administradores del sistema.</p>
    </div>

    <div class="auditoria-filtros-bar">
        <form action="filtro_acciones" method="POST">
        <!-- <input type="text" id="filtro-nombre" class="auditoria-filtro" placeholder="Buscar por nombre..." required> POR AHORA INNECESARIO IGUAL QUE EN REPORTES-->
            <select id="filtro-rol" class="auditoria-filtro" name="id_rol" required>
                <option value="0" <?= ($id_rol ?? '') === '0' ? 'selected' : '' ?>>Todos los roles</option>
                <option value="1" <?= ($id_rol ?? '') === '1' ? 'selected' : '' ?>>Promotor Social</option>
                <option value="2" <?= ($id_rol ?? '') === '2' ? 'selected' : '' ?>>Despacho</option>
                <option value="3" <?= ($id_rol ?? '') === '3' ? 'selected' : '' ?>>Administración</option>
                <option value="4" <?= ($id_rol ?? '') === '4' ? 'selected' : '' ?>>Admin</option>
            </select>
            <input type="date" id="filtro-fecha" class="auditoria-filtro" required name="fecha" value="<?php echo isset($fecha) ? $fecha : ''; ?>">
        <button type="submit" class="filtrar-btn"><i class="fa fa-search"></i> Filtrar</button>
        </form>
    </div>
    <button class="filtrar-btn" id="btnPDF" onclick="generarPDF()"><i class="fa fa-file-excel" ></i> Exportar PDF</button>
  <section class="auditoria-tabla-card">
    <div class="tabla-responsive">
    <table class="auditoria-tabla"  id="exportarPDF">
        <thead>
            <tr>
                <th>Número</th>
                <th>CI</th>
                <th>Nombre</th>
                <th>Fecha de la acción</th>
                <th>Hora de la acción</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($datos)):  ?>
                <?php foreach ($datos as $fila): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['id']) ?></td>
                        <td><?= htmlspecialchars($fila['ci']) ?></td>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td class="auditoria-fecha"><?= date('d-m-Y', strtotime($fila['fecha'])) ?></td>
                        <td class="auditoria-fecha"><?= date('H:i', strtotime($fila['fecha'])) ?></td>
                        <td><?= htmlspecialchars($fila['accion']) ?></td>
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
</main>
</body>
<script src="<?= BASE_URL ?>/libs/jspdf.umd.min.js"></script>
<script src="<?= BASE_URL ?>/libs/jspdf.plugin.autotable.min.js"></script>
<script src="<?= BASE_URL ?>/public/js/exportarPDF.js"></script>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</html>