<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Solicitud</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/reportes.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/new_style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
  <header class="header">
    <div class="titulo-header">Formulario de Registro de Casos</div>
    <div class="header-right">
      <a href="<?= BASE_URL ?>/casos_lista">
        <button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver</button>
      </a>
    </div>
  </header>

  <form action="<?= BASE_URL ?>/casos_enviar" method="POST" id="form_caso" class="formulario-ayuda" enctype="multipart/form-data">
      <h2 class="form-titulo"><i class="fa fa-folder-plus"></i> Nuevo Caso</h2>

      <div class="titulo-seccion"><i class="fa fa-user"></i> Datos del Solicitante</div>
      <div class="fila-formulario">

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" placeholder="Nombre del solicitante"
          value="<?= htmlspecialchars($datos_beneficiario['solicitante']['nombre'] ?? '') ?>" required>

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" id="apellido" placeholder="Apellido del solicitante"
          value="<?= htmlspecialchars($datos_beneficiario['solicitante']['apellido'] ?? '') ?>" required>

          <label for="correo">Correo:</label>
        <input type="email" name="correo" id="correo" placeholder="Ingrese su correo"
          value="<?= htmlspecialchars($datos_beneficiario['solicitante']['correo'] ?? '') ?>" required>


          <label for="ci">Télefono:</label>
          <input type="text" name="telefono" id="telefono" placeholder="Télefono del solicitante"
          value="<?= htmlspecialchars($datos_beneficiario['info']['telefono'] ?? '') ?>" required  oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                    <label for="comunidad">Comunidad:</label>
                    <select name="comunidad" id="comunidad">
                        <option value="">Seleccione su comunidad...</option>
                            <?php
                                  $res = Solicitud::traer_comunidades();
                                    if ($res['exito']) {
                                        foreach ($res['datos'] as $comunidad) {
                                            $nombre = $comunidad['comunidad'] ?? '';
                                            $selected = (($datos_beneficiario['comunidad']['comunidad'] ?? '') === $nombre) ? 'selected' : '';
                                            echo '<option ' . $selected . '>' . htmlspecialchars($nombre) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">Ocurrió un error al cargar las comunidades</option>';
                                    }
                                  ?>

                        </select>

        <label for="ci">Cédula:</label>
        <input type="text" name="ci" id="ci" placeholder="Cédula"
          value="<?= htmlspecialchars($datos_beneficiario['solicitante']['ci'] ?? $ci) ?>" required  oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="10">
      </div>

      <div class="titulo-seccion"><i class="fa fa-file-alt"></i> Datos del Caso</div>
      <div class="fila-formulario">

        <label for="direccion" id="label-direccion">Oficina a la que se dirige:</label>
        <select name="direccion" id="direccion" required>
          <option value="">Seleccione</option> 
          <!-- Rellenar dinamicamente -->
        </select>

       <!-- <div id="campos-dinamicos"></div> -->
        <label for="descripcion">Descripción del Caso:</label>
        <input type="text" name="descripcion" id="descripcion">

        <label for="carta">Carta:</label>
        <input type="file" name="carta" id="carta" required>
      </div>

      <div class="form-boton-contenedor">
        <input type="submit" value="Registrar Caso" class="btn-principal">
      </div>
    </form>

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