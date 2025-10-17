<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Beneficiarios Registrados</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/reportes.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
    <div class="titulo-header">Lista de beneficiarios</div>
    <div class="header-right">
      <a href="<?= BASE_URL ?>/registro_beneficiario"><button class="principal-btn"><i class="fa fa-plus"></i>Registrar Beneficiario</button></a>
      <a href="<?= BASE_URL ?>/main"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
    </div>
  </header>
    <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>
    <section class="filtros-card"> 
        <h2>Búsqueda Rápida:</h2>
        <form action="<?= BASE_URL?>/buscar_beneficiario"  method="POST" autocomplete="off">
            <input type="search" name="filtro_busqueda" placeholder="Búsqueda de beneficiarios" required value=<?= htmlspecialchars($_POST['filtro_busqueda'] ?? '') ?>>
            <input type="submit" value="Buscar" class="filtro-btn">
        </form>
    </section>
    <!-- En caso de que exista la busqueda a través de get osea que ingresó a una pues se le pone boton de exportar en word o pdf, en caso de que no pues no existe este botón -->
     <main class="auditoria-main">
    <section class="auditoria-tabla-card">
        <div class="tabla-responsive">
     <table class="auditoria-tabla">
        <?php if (!empty($datos)): ?>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Cédula</th>
            <th>Ver Información Completa</th>
        </tr>
            <?php foreach ($datos as $fila): ?>
        <tr>
            <td><?= htmlspecialchars($fila['nombre']) ?></td>
            <td><?= htmlspecialchars($fila['apellido']) ?></td>
            <td><?= htmlspecialchars($fila['ci']) ?></td>
            <td><a href="<?= BASE_URL ?>/informacion_beneficiario?ci=<?= $fila['ci']; ?>" class="usuario-btn">Ver información Completa</a></td>
        </tr>
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