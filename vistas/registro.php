<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
    <div class="titulo-header">Registro de usuario</div>
    <div class="header-right">
      <a href="<?= BASE_URL ?>/main"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
    </div>
  </header>
  <main>
    <form action="<?=BASE_URL?>/registro" method="POST" class="registro-card form-user" autocomplete="off">
        <h2><i class="fa fa-user-plus"></i> Registro de usuario</h2>
        <div class="campo-user">
            <label for="nombre">Nombre</label>
        <input type="text" name="nombre" placeholder="Nombre" required>
        </div>
        <div class="campo-user">
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" placeholder="Apellido" required>
        </div>
        <div class="campo-user">
            <label for="ci">Cédula de Identidad</label>
            <input type="text" name="ci" placeholder="Cédula de Identidad" required>
        </div>
        <div class="campo-user">
            <label for="clave">Contraseña</label>
            <input type="password" name="clave" placeholder="Contraseña" required>
        </div>
        <div class="campo-user">
            <label for="id_rol">Rol</label>
            <select name="id_rol">
                <option value="1">Promotor Social</option>
                <option value="2">Despacho</option>
                <option value="3">Administración</option>
                <option value="4">Administrador Principal</option>
            </select>
        </div>
        <button type="submit" class="boton-enviar-ayuda"><i class="fa fa-user-plus"></i>Registrar Persona</button>
    </form>
    </main>
    <!-- Copiar y pegar si vas a poner un mensaje de error etc atte david -->
    <script src="<?= BASE_URL ?>/public/js/msj.js"></script>
    <script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
    <?php if (isset($msj) && !empty($msj)) : ?>
        mostrarMensaje("<?= htmlspecialchars($msj) ?>", "info", 3500);
    <?php endif; ?>
    </script>
</body>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</html>