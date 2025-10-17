<?php 
$acciones = [
                            'En espera del documento físico para ser procesado 0/2' => 'Aprobar para su procedimiento',
                            'En Proceso 1/2' => 'Aprobar Ayuda',
                            'En Proceso 2/2 (Sin entregar)' => 'Finalizar Solicitud (Se entregó la ayuda)',
                            'Solicitud Finalizada (Ayuda Entregada)' => 'Reiniciar en caso de algún error'
        ];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Solicitudes de Desarrollo Social</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">Lista de solicitudes de Desarrollo Social</div>
        <div class="header-right">
         <?php if($_SESSION['id_rol'] == 1 || $_SESSION['id_rol'] == 4){?>
            <a href="<?=BASE_URL?>/buscar_desarrollo"><button class="principal-btn"><i class="fa fa-plus"></i> Rellenar Formulario</button></a>
        <?php } ?>
        <?php if($_SESSION['id_rol'] == 1 || $_SESSION['id_rol'] == 4){?>
            <a href="<?=BASE_URL?>/desarrollo_invalidos"><button class="nav-btn"><i class="fa fa-eye-slash"></i> Ver Solicitudes Inhabilitadas</button></a>
        <?php } ?>
      <a href="<?= BASE_URL ?>/main"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
      
          <button class="notificaciones-btn" id="btn-notificaciones">
                <i class="fas fa-bell"></i> Notificaciones de Urgencia
                <?php
                        $notificaciones = Desarrollo::notificacion_urgencia();
                        $notificacion = $notificaciones['exito'] ? $notificaciones['datos'] : [];
                        $notificacionAgrupada = [];
                        foreach ($notificacion as $item) {
                            $tipo = $item['categoria'] ?? 'general';
                            $notificacionAgrupada[$tipo][] = $item;
                        }
                    $total = 0;
                    foreach ($notificacionAgrupada as $grupo) {
                        // Si es un mensaje plano, no es un array de notificaciones
                        if (isset($grupo['mensaje'])) {
                            continue;
                        }
                        $total += count($grupo);
                    }
                    ?>

                    <?php if ($total > 0): ?>
                        <span class="badge"><?= $total ?></span>
                    <?php endif; ?>
                    </button>

                    <div id="barra-notificaciones" class="barra-notificaciones oculto">
                        <ul id="lista-notificaciones" class="notificaciones-lista">
                            <?php if ($total > 0): ?>
                                <?php foreach ($notificacionAgrupada as $tipo => $notificaciones): ?>
                                    <?php foreach ($notificaciones as $noti): ?>
                                        <li class="notificacion-item">
                                            <strong><?= ucfirst($tipo) ?>:</strong>
                                            <a href="<?= htmlspecialchars(BASE_URL.'/mostrar_noti_urgencia?id_doc='.urlencode($noti['id_des'])) ?>"><?= htmlspecialchars($noti['descripcion'] ?? 'Sin mensaje') ?><br>
                                            <?= htmlspecialchars($noti['estado'] ?? 'Sin mensaje') ?>
                                            <span class="fecha"><?= date('d/m/Y H:i', strtotime($noti['fecha'])) ?></span></a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="notificacion-item">
                                    <strong>Info:</strong> No hay notificaciones disponibles
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
    </div>
  </header>

    <main>
    <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>
    <section class="filtros-card">
        <form class="filtros-form" action="filtrar_fechaDesarrollo" method="POST">
            <label>
                Desde
                <input type="date" name="fecha_inicio" value="<?php echo isset($fecha_inicio) ? $fecha_inicio : ''; ?>" required>
            </label>
            <label>
                Hasta
                <input type="date" name="fecha_final" value="<?php echo isset($fecha_final) ? $fecha_final : ''; ?>" required>
            </label>
            <label>
                Seleccione un Estado:
                <select name="estado" required>
                    <option value="">Seleccione</option>
                    <option value="En espera del documento físico para ser procesado 0/2" <?= ($estado ?? '') == 'En espera del documento físico para ser procesado 0/2' ? 'selected' : '' ?>>
                        En espera del documento físico para ser procesado 0/2
                    </option>
                    <option value="En Proceso 1/2" <?= ($estado ?? '') == 'En Proceso 1/2' ? 'selected' : '' ?>>
                        En Proceso 1/2
                    </option>
                    <option value="En Proceso 2/2 (Sin entregar)" <?= ($estado ?? '') == 'En Proceso 2/2 (Sin entregar)' ? 'selected' : '' ?>>
                        En Proceso 2/2 (Sin entregar)
                    </option>
                    <option value="Solicitud Finalizada (Ayuda Entregada)" <?= ($estado ?? '') == 'Solicitud Finalizada (Ayuda Entregada)' ? 'selected' : '' ?>>
                        Solicitud Finalizada (Ayuda Entregada)
                    </option>
                </select>

            </label>
            <button type="submit" name="btn_filtro" value="Filtrar" class="filtrar-btn">
                <i class="fa fa-filter"></i> <span>Filtrar</span>
            </button>
        </form>

    </section>
    <nav class="filtros-categorias">
        <a href="<?= BASE_URL ?>/filtrar_desarrollo?filtro=recientes" class="filtro-btn" name="recientes"><i class="fa fa-clock"></i> Más recientes</a>
        <a href="<?= BASE_URL ?>/filtrar_desarrollo?filtro=antiguos" class="filtro-btn" name="antiguos"><i class="fa fa-clock"></i> Más antiguos</a>
        <a href="<?= BASE_URL ?>/filtrar_desarrollo?filtro=urgentes" class="filtro-btn" name="urgentes"><i class="fa fa-exclamation-circle"></i> Más urgentes</a>
        <a href="<?= BASE_URL ?>/filtrar_desarrollo?filtro=medicinas" class="filtro-btn" name="medicinas"><i class="fa fa-medkit"></i> Medicinas</a>
        <!-- <a href="/filtrar_desarrollo?filtro=ayuda_tecnica" class="filtro-btn" name="ayudas técnicas"><i class="fa fa-wheelchair"></i> Ayudas técnicas</a> -->
        <a href="<?= BASE_URL ?>/filtrar_desarrollo?filtro=laboratorio" class="filtro-btn" name="laboratorio"><i class="fa fa-flask"></i> Laboratorio</a>
        <!-- <a href="/filtrar_desarrollo?filtro=enseres" class="filtro-btn" name="enseres"><i class="fa fa-couch"></i> Enseres</a> -->
    </nav>
    <section class="solicitudes-lista">
        <?php if (!empty($datos)): ?>
            <?php foreach ($datos as $fila): ?>
                <div class="solicitud-card">
                    <div class="solicitud-header">
                        <span class="solicitud-estado 
                            <?php
                                $estado = htmlspecialchars($fila['estado'] ?? '');
                                if ($estado == 'En espera del documento físico para ser procesado 0/3') echo 'pendiente';
                                else if ($estado == 'En Proceso 1/3') echo 'activo1';
                                else if ($estado == 'En Proceso 2/2 (Sin entregar)') echo 'activo2';
                                else if ($estado == 'Solicitud Finalizada (Ayuda Entregada)') echo 'finalizada';
                                else if ($estado == 'Documento inválido') echo 'invalido';
                            ?>">
                            <?= $estado ?>
                        </span>
                        <div><strong>Fecha:</strong> <?= htmlspecialchars(date('d-m-Y', strtotime($fila['fecha']))) ?></div>
                    </div>
                    <div class="solicitud-info">
                        <div><strong>Descripción:</strong> <?= htmlspecialchars($fila['descripcion'] ?? '') ?></div>
                        <div><strong>Categoría:</strong> <?= htmlspecialchars($fila['categoria'] ?? '') ?></div>
                        <?php if ($fila['categoria'] === 'Laboratorio'): ?>
                            <div><strong>Exámenes:</strong> <?= htmlspecialchars($fila['examenes'] ?? '') ?></div>
                        <?php endif; ?>
                        <div><strong>ID Manual:</strong> <?= htmlspecialchars($fila['id_manual'] ?? '') ?></div>
                        <div><strong>CI:</strong> <?= htmlspecialchars($fila['ci'] ?? '') ?></div>
                        <div><strong>Remitente:</strong> 
                        <?= htmlspecialchars(($fila['remitente_nombre'] ?? '') . ' ' . ($fila['remitente_apellido'] ?? '')) ?>
                        </div>
                        <div><strong>Promotor Social:</strong> <?= htmlspecialchars($fila['creador'] ?? '')?></div>

                    </div>
                    <div class="solicitud-actions">
                        <a href="<?= BASE_URL ?>/informacion_beneficiario?ci=<?= $fila['ci']?>" class="aprobar-btn">Ver Información del beneficiario</a>
                        <?php if ($_SESSION['id_rol'] == 1 || $_SESSION['id_rol'] == 4): ?>
                        <a href="<?= BASE_URL.'/editarDesarrollo?id_des='.$fila['id_des'] ?>" class="aprobar-btn">Editar</a>
                        <?php endif; ?>
                        <?php if ($_SESSION['id_rol'] == 1 || $_SESSION['id_rol'] == 4): ?>
                            <a href="<?= BASE_URL.'/inhabilitarDesarrollo?id_des='.$fila['id_des'] ?>" class="rechazar-btn">Inhabilitar</a>
                        <?php endif; ?>
                        <a href="<?= BASE_URL.'/procesarDesarrollo?id_des='.$fila['id_des'].'&estado='.$fila['estado'] ?>" class="aprobar-btn">
                            <?= isset($acciones[$fila['estado']]) ? $acciones[$fila['estado']] : 'Acción desconocida'; ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
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
    </section>
</main>
</body>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
<script src="<?= BASE_URL ?>/public/js/notificacionAdministrador.js"></script>
</html>