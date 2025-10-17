<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se han encontrado otras solicitudes</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="titulo-header">Antecedentes de Casos</div>
      <a href="<?= BASE_URL ?>/casos_ci_busqueda"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
      </div>
  </header>
    <section class="solicitudes-lista">
    <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>
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
                        <div><strong>Descripción:</strong> <?= htmlspecialchars($fila['descripcion']) ?></div>
                        <div><strong>Número de documento:</strong> <?= htmlspecialchars($fila['id_manual'] ?? '') ?></div>
                        <div><strong>Cédula del Beneficiario:</strong> <?= htmlspecialchars($fila['ci'] ?? '') ?></div>
                        <div><strong>Remitente:</strong> <?= htmlspecialchars(($fila['nombre'] ?? '') . ' ' . ($fila['apellido'] ?? ''))?></div>
                        <div><strong>Creador del caso:</strong> <?= htmlspecialchars($fila['creador'] ?? '') ?></div>
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
    <form action="casos_anteriores" method="POST">
        <input type="submit" value="Registrar Solicitud">
        <input type="hidden" name="ci" value="<?= $ci ?>">
    </form>
    <a href="<?=BASE_URL?>/busqueda">Volver (No registrar)</a>
</body>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</html>