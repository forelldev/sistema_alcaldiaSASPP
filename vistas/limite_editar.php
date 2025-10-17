<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Límite</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">Editar Límite</div>
        <div class="header-right">
            <a href="<?= BASE_URL ?>/main"><button class="nav-btn"><i class="fa fa-home"></i> Inicio</button></a>
            <a href="<?= BASE_URL ?>/limites"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
        </div>
    </header>

    <main>
    <form action="consulta_limite" method="POST" class="registro-card form-user" autocomplete="off">
        <h2><i class="fa fa-pencil-alt"></i> Edita el límite</h2>
        <div class="campo-user">
            <label for="nombre_rol">Nombre del rol:</label>
            <input type="text" id="nombre_rol" name="nombre_rol" placeholder="Nombre del rol" required value="<?= htmlspecialchars($datos['nombre_rol'] ?? '') ?>" readonly>

        </div>
        <div class="campo-user">
            <label for="limite">Límite:</label>
            <input type="text" id="limite" name="limite" value="<?= htmlspecialchars($datos['limite'] ?? '') ?>" placeholder="Límite de rol deseado" required>

            <input type="hidden" name="id_rol" value="<?= htmlspecialchars($datos['id_rol'] ?? '') ?>">
            <button type="submit" value="Cambiar" class="boton-enviar-ayuda"><i class="fa fa-check"></i> Cambiar</button>
        </div>
    </form>
    
    <?php if (!empty($excedentes)): ?>
        <div class="auditoria-header">
            <h2><i class="fa fa-users"></i> Usuarios excedentes para el rol</h2>
            <p>Debes eliminar <?= count($excedentes) ?> usuario(s) para cumplir con el nuevo límite.</p>
        </div>
        
<section class="auditoria-tabla-card">
    <div class="tabla-responsive">
        <table class="auditoria-tabla">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
            <?php foreach ($excedentes as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['ci']) ?></td>
                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                <td>
                    <form method="POST" action="<?= BASE_URL ?>/eliminar_usuario">
                        <input type="hidden" name="ci" value="<?= $usuario['ci'] ?>">
                        <input type="submit" value="Eliminar" class="boton-enviar-ayuda" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
<?php endif; ?>
    </div>
    </section>
    </main>

</body>
<!-- Copiar y pegar para los mensajes de error, se puede cambiar el "error" por "info" -->
<script src="<?= BASE_URL ?>/public/js/msj.js"></script>
    <script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
    <?php if (isset($msj) && !empty($msj)) : ?>
        mostrarMensaje("<?= htmlspecialchars($msj) ?>", "info", 5500);
    <?php endif; ?>
    </script>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</html>