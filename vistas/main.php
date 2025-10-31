<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="body-main">
    <header class="header">
    <div class="titulo-header">SASPP</div> 
    <div class="rol">Oficina: <?= htmlspecialchars($_SESSION['rol']) ?></div>
    <div class="notification-dropdown">
    <button class="notificaciones-btn" id="btn-notificaciones">
        <i class="fas fa-bell"></i> Casos
        <span class="badge" id="noti-total" style="display:none;"></span>
    </button>

    <div id="barra-notificaciones" class="barra-notificaciones oculto">
        <ul id="lista-notificaciones" class="notificaciones-lista">
            <li class="notificacion-item">Cargando notificaciones...</li>
        </ul>
        <a href="<?= BASE_URL ?>/marcar_vistas_new" id="marcar-vistas" style="display:none;">Marcar todos los casos como vistos</a>
    </div>
</div>
</header>
    <nav class="navbar" aria-label="Menú principal">
        <?php if ($_SESSION['id_rol'] == 4) { ?>
            <div class="dropdown">
                <button class="nav-btn dropdown-toggle" aria-label="Menú" id="menuDropdownBtn">
                <i class="fas fa-bars"></i> Menú
            </button>
            <div class="dropdown-menu" id="menuDropdown">
                    <a href="<?= BASE_URL ?>/beneficiarios_lista"><i class="fas fa-users"></i> Lista de beneficiarios</a>
                    <a href="<?= BASE_URL ?>/registro"><i class="fas fa-user-plus"></i> Registrar Usuario</a>
                    <a href="<?= BASE_URL ?>/reportes_acciones"><i class="fas fa-file-alt"></i> Reportes de Acciones</a>
                    <a href="<?= BASE_URL ?>/reportes"><i class="fas fa-chart-bar"></i> Reportes</a>
                    <a href="<?= BASE_URL ?>/limites"><i class="fas fa-user-shield"></i> Límite por rol</a>
                    <a href="<?= BASE_URL ?>/estadisticas"><i class="fas fa-chart-bar"></i> Estadísticas de Solicitudes</a>
            </div>
        </div>
    <?php } ?>

    <div class="dropdown">
        <button class="nav-btn dropdown-toggle" aria-label="Menú" id="menuDropdownBtn">
            <i class="fas fa-folder-open"></i> Casos
        </button>
        <div class="dropdown-menu" id="menuDropdown">
            <a href="<?= BASE_URL ?>/casos_lista"><i class="fas fa-folder-open"></i> Lista de casos sin atender</a>
            <?php if($_SESSION['id_rol'] == 4){?>
                <a href="<?= BASE_URL.'/solicitudes_list'?>"><i class="fas fa-folder-open"></i> Lista de Solicitudes Generales</a>
                <a href="<?= BASE_URL.'/despacho_list'?>"><i class="fas fa-folder-open"></i> Lista de Solicitudes de Oficina Despacho</a>
                <a href="<?= BASE_URL.'/solicitudes_desarrollo'?>"><i class="fas fa-folder-open"></i> Lista de Solicitudes de Oficina Desarrollo Social</a>
            <?php } else if (!$_SESSION['id_rol'] == 0){ ?>
                <a href="<?= BASE_URL.'/casos_procesos_lista'?>"><i class="fas fa-folder-open"></i> Lista de Casos en Proceso (<?= $_SESSION['rol']; ?>)</a>
            <?php } ?>
        </div>
    </div>
        </div>
    </div>
    <div class="dropdown">
        <button class="nav-btn dropdown-toggle" aria-label="Usuario" id="usuarioDropdownBtn">
            <i class="fas fa-user"></i> Usuario
        </button>
        <div class="dropdown-menu" id="usuarioDropdown">
            <a href="<?= BASE_URL ?>/logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
    </div>
    
    </nav>
    
    <main>
        <section class="main-content">
            <div class="card desc-section">
                <h1>SASPP</h1>
                <p>
                    Este sistema permite gestionar solicitudes de ayuda de manera eficiente, proporcionando herramientas para la administración de usuarios, generación de reportes y estadísticas. Además, facilita la visualización de solicitudes pendientes y su estado, permitiendo a los administradores priorizar y atender las solicitudes de manera oportuna.<br><br>
                    Con una interfaz intuitiva, los usuarios pueden navegar fácilmente por las diferentes secciones del sistema, como la gestión de usuarios, la configuración de perfiles y la consulta de datos relevantes para la toma de decisiones estratégicas.
                </p>
            </div>
            <div class="card img-section">
                <img src="<?= BASE_URL ?>/assets/iss.avif" alt="Ilustración sistema">
            </div>
        </section>
        <section class="novedades">
            <h2>¿Qué hay de nuevo?</h2>
            <ul>
                <li>Se agregó la funcionalidad para marcar solicitudes como vistas.</li>
                <li>Mejoras en la generación de reportes y estadísticas.</li>
                <li>Optimización de la interfaz para dispositivos móviles.</li>
            </ul>
        </section>
    </main>
</body>
<script src="<?= BASE_URL ?>/public/js/msj.js"></script>
<script src="<?= BASE_URL ?>/public/js/casos_ajax.js"></script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
    const BASE_URL = "<?php echo BASE_URL; ?>";
    <?php
        $mensaje = $msj ?? $_GET['msj'] ?? null;
        if ($mensaje):
        ?>
            mostrarMensaje("<?= htmlspecialchars($mensaje) ?>", "info", 6500);
    <?php endif; ?>

</script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
<script src="<?= BASE_URL ?>/public/js/dropdown.js"></script>
<script src="<?= BASE_URL ?>/public/js/notificacionAdministrador.js"></script>
</html>