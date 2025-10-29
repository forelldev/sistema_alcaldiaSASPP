<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Despacho</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">Formulario de despacho</div>
        <div class="header-right">
            <a href="<?=BASE_URL?>/despacho_list"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
        </div>
    </header>
    <main>
        <form action="despacho_enviarForm" method="POST" class="formulario-ayuda" autocomplete="off">
            <h2><i class="fa fa-truck"></i> Solicitud de Despacho</h2>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="id_manual">Número de documento:</label>
                    <input type="text" id="id_manual" name="id_manual" placeholder="00004578" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div class="campo-formulario">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= $datos_beneficiario['solicitante']['nombre'] ?? '' ?>" required>
                </div>
            </div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="<?= $datos_beneficiario['solicitante']['apellido'] ?? '' ?>" required>
                </div>
                <div class="campo-formulario">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?= $datos_beneficiario['info']['telefono'] ?? '' ?>" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
            </div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="ci">Cedula de Identidad:</label>
                    <input type="text" id="ci" name="ci" placeholder="Ejem: V-12345678" value="<?= htmlspecialchars($datos_beneficiario['solicitante']['ci'] ?? '') ?>" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div class="campo-formulario">
                    <label for="direc_habita">Dirección:</label>
                    <input type="text" id="direc_habita" name="direc_habita" value="<?= $datos_beneficiario['comunidad']['direc_habita'] ?? '' ?>" required>
                </div>
            </div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="categoria">Categoría:</label>
                    <select name="categoria" id="categoria" required>
                        <option value="">Seleccione</option>
                        <option value="Salud">Salud</option>
                        <option value="Ayuda Económica">Ayuda Económica</option>
                        <option value="Materiales de Construcción">Materiales de Construcción</option>
                        <option value="Varios">Varios</option>
                    </select>
                </div>
            </div>
            <div class="fila-formulario" id="tipoAyudaContainer">
                <div class="campo-formulario">
                    <label for="tipo_ayuda">Tipo de ayuda:</label>
                    <select name="tipo_ayuda" id="tipo_ayuda" required></select>
                </div>
            </div>
            <div class="fila-formulario" id="prioridadContainer">
                <div class="campo-formulario" >
                    <label for="prioridad">Prioridad:</label>
                    <select name="prioridad" id="prioridad">
                        <option value="">Seleccione</option>
                        <option value="Alta">Alta</option>
                        <option value="Media">Media</option>
                        <option value="Baja">Baja</option>
                    </select>
                </div>
            </div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" id="descripcion" name="descripcion" required placeholder="Descripción específica de la ayuda (Ejemplo: 3 Sacos de cemento para la Alcaldía de Peña)">
                </div>
            </div>
            <button type="submit" class="boton-enviar-ayuda"><i class="fa fa-paper-plane"></i> Enviar</button>
        </form>
    </main>
</body>
<script src="<?= BASE_URL ?>/public/js/msj.js"></script>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
    <?php if (isset($msj)):?> 
        mostrarMensaje("<?= htmlspecialchars($msj) ?>", "info", 6500);
    <?php endif; ?>
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
<script src="<?= BASE_URL ?>/public/js/formulario_despacho.js"></script>
</html>