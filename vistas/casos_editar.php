<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edición de caso</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/reportes.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/new_style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
  <header class="header">
    <div class="titulo-header">Editar caso</div>
    <div class="header-right">
      <a href="<?= BASE_URL ?>/main">
        <button class="nav-btn"><i class="fa fa-home"></i> Inicio</button>
      </a>
      <a href="<?= BASE_URL ?>/casos_lista">
        <button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button>
      </a>
    </div>
  </header>

  <main>

    <form action="<?= BASE_URL ?>/editar_caso_enviar" method="POST" class="registro-card form-user" autocomplete="off" enctype="multipart/form-data">
      <h2><i class="fa fa-edit"></i> Editar caso</h2>

      <!-- ID oculto del caso -->
      <input type="hidden" name="id_caso" value="<?= htmlspecialchars($datos['id_caso'] ?? '') ?>">

      <!-- Oficina -->
      <div class="campo-user">
        <label for="direccion">Oficina a la que se dirige:</label>
        <select name="direccion" id="direccion" required>
          <option value="">Seleccione</option>
          <option value="Desarrollo Social" <?= ($datos['direccion'] ?? '') === 'Desarrollo Social' ? 'selected' : '' ?>>Desarrollo Social</option>
          <option value="Infraestructura" <?= ($datos['direccion'] ?? '') === 'Infraestructura' ? 'selected' : '' ?>>Infraestructura</option>
          <option value="Salud" <?= ($datos['direccion'] ?? '') === 'Salud' ? 'selected' : '' ?>>Salud</option>
          <option value="Educación" <?= ($datos['direccion'] ?? '') === 'Educación' ? 'selected' : '' ?>>Educación</option>
        </select>
      </div>

      <!-- Descripción -->
      <div class="campo-user">
        <label for="descripcion">Descripción del caso:</label>
        <input type="text" id="descripcion" name="descripcion" placeholder="Describa brevemente el caso"
          value="<?= htmlspecialchars($datos['descripcion'] ?? '') ?>" required>
      </div>

      <!-- Carta -->
      <div class="campo-user">
        <label for="carta">Carta (opcional si ya está cargada):</label>
        <input type="file" name="carta" id="carta">
      </div>

      <!-- Botón de envío -->
      <button type="submit" class="boton-enviar-ayuda">
        <i class="fa fa-save"></i> Guardar cambios
      </button>
    </form>
  </main>
</body>


<script src="<?= BASE_URL ?>/public/js/casos_formulario.js"></script>
<script src="<?= BASE_URL ?>/public/js/msj.js"></script>
<?php
        $mensaje = $msj ?? $_GET['msj'] ?? null;
        if ($mensaje):
        ?>
            <script>
                mostrarMensaje("<?= htmlspecialchars($mensaje) ?>", "info", 6500);
            </script>
    <?php endif; ?>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</html>