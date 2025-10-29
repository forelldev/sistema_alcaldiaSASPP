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
        <div class="titulo-header">Formulario de solicitud Desarrollo Social</div>
        <div class="header-right">
            <a href="<?= BASE_URL ?>/solicitudes_desarrollo">
                <button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver</button>
            </a>
        </div>
    </header>

    <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>

    <form action="<?= BASE_URL ?>/enviar_formulario_desarrollo" method="POST" id="form_solicitud" class="formulario-ayuda">
        <h2 class="form-titulo"><i class="fa fa-hands-helping"></i> Solicitud de Ayuda Desarrollo Social</h2>

        <div class="titulo-seccion"><i class="fa fa-user"></i> Datos de la Solicitud</div>
        <div class="fila-formulario">
            <label for="tipo_ayuda">Categoría:</label>
            <select id="tipo_ayuda" name="categoria" required>
            <option value="">Seleccione...</option>
            <option value="Medicamentos">Medicamentos</option>
            <option value="Laboratorio">Laboratorio</option>
            </select>

            <div id="subcategoria_container" style="display: none;" class="campo-dinamico">
            <label for="subcategoria">Seleccione tipo de examen:</label>
            <select id="subcategoria" name="subcategoria">
                <option value="">Seleccione...</option>
                <option value="Ecosonograma">Ecosonograma</option>
                <option value="Eco-Doppler">Eco-Doppler</option>
                <option value="Exámenes de Laboratorio">Exámenes de Laboratorio</option>
            </select>
            </div>

            <div id="campo_examen" class="campo-dinamico" style="display: none;">
            <!-- Se llenará dinámicamente -->
            </div>

            <label for="id_manual">Número de documento:</label>
            <input type="text" name="id_manual" id="id_manual" placeholder="Ingrese el número de documento">

            <label for="descripcion">Descripción:</label>
            <input type="text" name="descripcion" placeholder="Descripción específica de la ayuda" required>

            <label for="correo">Correo:</label>
            <input type="text" name="correo" placeholder="Ingrese su correo"
            value="<?= htmlspecialchars($datos_beneficiario['solicitante']['correo'] ?? '') ?>" required>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" placeholder="Nombre del Paciente"
            value="<?= htmlspecialchars($datos_beneficiario['solicitante']['nombre'] ?? '') ?>" required>

            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" placeholder="Apellido del Paciente"
            value="<?= htmlspecialchars($datos_beneficiario['solicitante']['apellido'] ?? '') ?>" required>

            <label for="cedula">Cédula:</label>
            <input type="text" name="ci" placeholder="Cédula"
            value="<?= htmlspecialchars($datos_beneficiario['solicitante']['ci'] ?? '') ?>"
            required oninput="this.value = this.value.replace(/[^0-9]/g, '')">

            <input type="hidden" name="tipo_ayuda" value="Otros">
        </div>

        <div class="form-boton-contenedor">
            <input type="submit" value="Registrar Solicitud" class="btn-principal">
        </div>
        </form>

    <script>
        const BASE_PATH = "<?php echo BASE_PATH; ?>";
    </script>
    <script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
    <script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
    <script src="<?= BASE_URL ?>/public/js/solicitud_urgencia.js"></script>
</body>
</html>
