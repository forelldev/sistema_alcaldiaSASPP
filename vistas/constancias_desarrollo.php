<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancias</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/estadisticas.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">Lista de Constancias</div>
        <div class="header-right">
            <a href="<?= BASE_URL ?>/registro_constancia"><button class="principal-btn"><i class="fa fa-file-alt"></i> Registrar Constancia</button></a>
            <a href="<?= BASE_URL ?>/main"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
        </div>
    </header>
    <div class="constancia-card">
        <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>
        <?php if (!empty($datos)): ?>
        <table class="constancia-table">
            <tr>
                <th>Constancia tipo</th>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Ver Documento</th>
            </tr>
            <?php foreach ($datos as $fila): ?>
            <tr>
                <td><?= htmlspecialchars($fila['tipo']) ?></td>
                <td><?= htmlspecialchars($fila['ci']) ?></td>
                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                <td><?= htmlspecialchars($fila['apellido']) ?></td>
                <td><?= htmlspecialchars(date('d-m-Y', strtotime($fila['fecha'])))?></td>
                <td><?= htmlspecialchars(date('H:i:s', strtotime($fila['fecha'])))?></td>
                <td>
                    <button class="generar-word"
                            data-tipo="<?= htmlspecialchars($fila['tipo']) ?>"
                            data-ci="<?= htmlspecialchars($fila['ci']) ?>"
                            data-nombre="<?= htmlspecialchars($fila['nombre']) ?>"
                            data-apellido="<?= htmlspecialchars($fila['apellido']) ?>">
                        Generarlo en Word
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
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
      
    </div>
    <script>
        const BASE_PATH = "<?php echo BASE_PATH; ?>";
    </script>
    <script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
    <script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
    <script src="<?= BASE_URL ?>/libs/html-docx.js"></script>
    <script>
    document.querySelectorAll('.generar-word').forEach(btn => {
      btn.addEventListener('click', () => {
        const tipo = btn.dataset.tipo;
        const ci = btn.dataset.ci;
        const nombre = btn.dataset.nombre;
        const apellido = btn.dataset.apellido;

        const html = `
          <!DOCTYPE html>
          <html>
            <head>
              <meta charset="utf-8">
              <title>Constancia</title>
              <style>
                body { font-family: Arial, sans-serif; }
                h2 { color: #2E86C1; }
                p { font-size: 14px; }
              </style>
            </head>
            <body>
              <h2>Constancia de Identificación</h2>
              <p><strong>Tipo:</strong> ${tipo}</p>
              <p><strong>Cédula:</strong> ${ci}</p>
              <p><strong>Nombre:</strong> ${nombre}</p>
              <p><strong>Apellido:</strong> ${apellido}</p>
            </body>
          </html>
        `;

        const blob = htmlDocx.asBlob(html);
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `constancia_${ci}.docx`;
        link.click();
      });
    });
    </script>
</body>
</html>