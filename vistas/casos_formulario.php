<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Solicitud</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/reportes.css?v=<?php echo time(); ?>">
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

  <form action="<?= BASE_URL ?>/casos_enviar" method="POST" id="form_caso" class="formulario-ayuda">
    <h2><i class="fa fa-folder-plus"></i> Nuevo Caso</h2>

    <div class="titulo-seccion"><i class="fa fa-user"></i> Datos del Solicitante</div>
    <div class="fila-formulario">

      <label for="id_manual">Número de documento:</label>
      <input type="text" name="id_manual" id="id_manual" placeholder="Ingrese el número de documento" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">

      <label for="descripcion">Descripción del caso:</label>
      <input type="text" name="descripcion" id="descripcion" placeholder="Descripción específica del caso" required>

      <label for="correo">Correo:</label>
      <input type="email" name="correo" id="correo" placeholder="Ingrese su correo" required>

      <label for="nombre">Nombre:</label>
      <input type="text" name="nombre" id="nombre" placeholder="Nombre del solicitante" required>

      <label for="apellido">Apellido:</label>
      <input type="text" name="apellido" id="apellido" placeholder="Apellido del solicitante" required>

      <label for="ci">Cédula:</label>
      <input type="text" name="ci" id="ci" placeholder="Cédula" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">

      <input type="submit" value="Registrar Caso">
    </div>
  </form>
</body>
<script src="<?= BASE_URL ?>/public/js/msj.js"></script>
<?php if (isset($msj)): ?>
        <script>
            mostrarMensaje("<?= htmlspecialchars($msj) ?>", "info", 3000);
        </script>
<?php endif; ?>
<script></script>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</html>