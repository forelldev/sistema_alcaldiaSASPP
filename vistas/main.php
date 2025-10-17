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
        <div class="titulo-header">Sistema de Solicitud de Ayudas</div> 
        <div class="header-right">
            <div class="rol">Rol: <?= $_SESSION['rol'] ?></div>
            <?php if ($_SESSION['id_rol'] == 1 || $_SESSION['id_rol'] == 4) { ?>
            <a href="<?= BASE_URL ?>/busqueda" class="nueva-solicitud-btn"><i class="fas fa-plus"></i> Nueva Solicitud</a>
            <?php } ?>
          <div class="notification-dropdown">
                <button class="notificaciones-btn" id="btn-notificaciones">
                    <i class="fas fa-bell"></i> Notificaciones
                    <?php
                    $total = 0;
                    foreach ($datos as $grupo) {
                        if (isset($grupo['datos']) && is_array($grupo['datos'])) {
                            $total += count($grupo['datos']);
                        }
                    }
                    ?>
                    <?php if ($total > 0): ?>
                        <span class="badge"><?= $total ?></span>
                    <?php endif; ?>
                </button>
                <div id="barra-notificaciones" class="barra-notificaciones oculto">
                    <ul id="lista-notificaciones" class="notificaciones-lista">
                        <?php if ($total > 0): ?>
                            <?php foreach ($datos as $tipo => $grupo): ?>
                            <?php foreach ($grupo['datos'] as $noti): ?>
                                <li class="notificacion-item">
                                    <strong><?= ucfirst($tipo) ?>:</strong>
                                    <a href="<?= BASE_URL ?>/noti?id_doc=<?= htmlspecialchars($noti['id_doc']) ?>&tabla=<?= urlencode($grupo['tabla']) ?>&id_name=<?= urlencode($grupo['id_name']) ?>">
                                        <?= htmlspecialchars($noti['descripcion']) ?><br>
                                        <?= htmlspecialchars($noti['estado'] ?? 'Sin mensaje') ?>
                                        <span class="fecha"><?= date('d/m/Y H:i', strtotime($noti['fecha'])) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                            <a href="<?= $_SESSION['id_rol'] == 2 ? 'marcar_vistasDespacho' : 'marcar_vistas' ?>">Marcar todas como vistas</a>
                        <?php else: ?>
                            <li class="notificacion-item">No hay notificaciones nuevas.</li>
                        <?php endif; ?>
                    </ul>
                </div>
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
        </div>
    </div>
    <div class="dropdown">
        <button class="nav-btn dropdown-toggle" aria-label="Menú" id="menuDropdownBtn">
            <i class="fas fa-chart-bar"></i> Estadísticas
        </button>
        <div class="dropdown-menu" id="menuDropdown">
            <a href="<?= BASE_URL ?>/estadisticas"><i class="fas fa-chart-bar"></i> Estadísticas de Solicitudes</a>
            <!-- <a href="">Estadísticas de Usuarios</a> -->
        </div>
    </div>
    <?php } ?>
    <div class="dropdown">
        <button class="nav-btn dropdown-toggle" aria-label="Menú" id="menuDropdownBtn">
            <i class="fas fa-folder-open"></i> Solicitudes
        </button>
        <div class="dropdown-menu" id="menuDropdown">
            <a href="<?= BASE_URL ?>/solicitudes_list"><i class="fas fa-folder-open"></i> Solicitudes de Ayuda</a>
            <?php if ($_SESSION['id_rol'] == 4 || $_SESSION['id_rol'] == 1 ) { ?>
                <a href="<?= BASE_URL ?>/solicitudes_desarrollo"><i class="fas fa-folder-open"></i> Solicitudes de Desarrollo Social</a>
            <?php } ?>
            <?php if ($_SESSION['id_rol'] == 2 || $_SESSION['id_rol'] == 4) { ?>
                <a href="<?= BASE_URL ?>/despacho_list"><i class="fas fa-folder-open"></i> Solicitudes Despacho</a>
            <?php } ?>
        </div>
    </div>
    <?php if ($_SESSION['id_rol'] == 1 || $_SESSION['id_rol'] == 4) { ?>
    <div class="dropdown">
        <button class="nav-btn dropdown-toggle" aria-label="Menú" id="menuDropdownBtn">
            <i class="fas fa-file-alt"></i> Constancias
        </button>
        <div class="dropdown-menu" id="menuDropdown">
            <a href="<?= BASE_URL ?>/constancias"><i class="fas fa-file-alt"></i> Constancias</a>
    <?php } ?>
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
            <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>
            <div class="card desc-section">
                <h1>Descripción del Programa</h1>
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
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
<script src="<?= BASE_URL ?>/public/js/dropdown.js"></script>
<script src="<?= BASE_URL ?>/public/js/notificacionAdministrador.js"></script>
</html>