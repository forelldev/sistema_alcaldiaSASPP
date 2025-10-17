<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Solicitud</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">Editar solicitud</div>
        <div class="header-right">
            <a href="<?=BASE_URL?>/main"><button class="nav-btn"><i class="fa fa-home"></i> Inicio</button></a>
            <a href="<?=BASE_URL?>/solicitudes_list"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
        </div>
    </header>
    <main>
        <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>
        <form action="editar_solicitud" method="POST" class="registro-card form-user">
            <h2><i class="fa fa-edit"></i> Editar solicitud</h2>
            <div class="campo-user">
                <label for="id_manual">Número de documento:</label>
                <input type="text" id="id_manual" name="id_manual" placeholder="00004578" required value="<?= htmlspecialchars($datos['id_manual'] ?? '') ?>">
            </div>
            <div class="campo-user">
                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" value="<?= htmlspecialchars($datos['descripcion'] ?? '') ?>" placeholder="Ejem: Ayuda para silla de ruedas" required>
            </div>
            <div class="campo-user">
                <label for="ci">Cedula de Identidad:</label>
                <input type="text" id="ci" name="ci" placeholder="Ejem: V-12345678" value="<?= htmlspecialchars($datos['ci'] ?? '') ?>" required>
            </div>
            <div class="campo-user">
                <label for="tipo_ayuda">Tipo de Ayuda:</label>
                <select name="tipo_ayuda" id="tipo_ayuda">
                    <option value="Silla de Ruedas" <?= ($datos['tipo_ayuda'] ?? '') === 'Silla de Ruedas' ? 'selected' : '' ?>>Silla de Ruedas</option>
                    <option value="Silla de Ruedas(Niño)" <?= ($datos['tipo_ayuda'] ?? '') === 'Silla de Ruedas(Niño)' ? 'selected' : '' ?>>Silla de Ruedas(Niño)</option>
                    <option value="Andadera" <?= ($datos['tipo_ayuda'] ?? '') === 'Andadera' ? 'selected' : '' ?>>Andadera</option>
                    <option value="Andadera (Niño)" <?= ($datos['tipo_ayuda'] ?? '') === 'Andadera (Niño)' ? 'selected' : '' ?>>Andadera (Niño)</option>
                    <option value="Bastón 1 Punta" <?= ($datos['tipo_ayuda'] ?? '') === 'Bastón 1 Punta' ? 'selected' : '' ?>>Bastón 1 Punta</option>
                    <option value="Bastón 3 Puntas" <?= ($datos['tipo_ayuda'] ?? '') === 'Bastón 3 Puntas' ? 'selected' : '' ?>>Bastón 3 Puntas</option>
                    <option value="Bastón 4 Puntas" <?= ($datos['tipo_ayuda'] ?? '') === 'Bastón 4 Puntas' ? 'selected' : '' ?>>Bastón 4 Puntas</option>
                    <option value="Muletas" <?= ($datos['tipo_ayuda'] ?? '') === 'Muletas' ? 'selected' : '' ?>>Muletas</option>
                    <option value="Muletas (Niño)" <?= ($datos['tipo_ayuda'] ?? '') === 'Muletas (Niño)' ? 'selected' : '' ?>>Muletas (Niño)</option>
                    <option value="Collarín" <?= ($datos['tipo_ayuda'] ?? '') === 'Collarín' ? 'selected' : '' ?>>Collarín</option>
                    <option value="Colchón Anti-escaras" <?= ($datos['tipo_ayuda'] ?? '') === 'Colchón Anti-escaras' ? 'selected' : '' ?>>Colchón Anti-escaras</option>
                    <option value="Otros" <?= ($datos['tipo_ayuda'] ?? '') === 'Otros' ? 'selected' : '' ?>>Otros</option>
                </select>
            </div>
            <div class="campo-user">
                <label for="categoria">Categoría</label>
                <select name="categoria" id="categoria">
                    <option value="Ayudas técnicas" <?= ($datos['categoria'] ?? '') === 'Ayudas técnicas' ? 'selected' : '' ?>>Ayudas técnicas</option>
                    <option value="Medicamentos" <?= ($datos['categoria'] ?? '') === 'Medicamentos' ? 'selected' : '' ?>>Medicamentos</option>
                    <option value="Laboratorio" <?= ($datos['categoria'] ?? '') === 'Laboratorio' ? 'selected' : '' ?>>Laboratorio</option>
                    <option value="Enseres" <?= ($datos['categoria'] ?? '') === 'Enseres' ? 'selected' : '' ?>>Enseres</option>
                    <option value="Otros" <?= ($datos['categoria'] ?? '') === 'Otros' ? 'selected' : '' ?>>Otros</option>
                </select>
            </div>
            <div class="campo-user">
                <label for="remitente">Remitente:</label>
                <input type="text" id="remitente" name="remitente" placeholder="Ejem: María González" value="<?= htmlspecialchars(($datos['nombre'] ?? '') . ' ' . ($datos['apellido'] ?? '')) ?>" readonly>
            </div>
            <div class="campo-user">
                <label for="promotor">Promotor:</label>
                <input type="text" id="promotor" name="promotor" placeholder="Ejem: María González" value="<?= htmlspecialchars($datos['promotor'] ?? '') ?>" readonly>
            </div>
            <div class="campo-user">
                <label for="observaciones">Observaciones:</label>
                <input type="text" id="observaciones_ayuda" name="observaciones" placeholder="Detalles relevantes (Opcional)" value="<?= htmlspecialchars($datos['observaciones'] ?? '') ?>" required>
            </div>
            <?php $_SESSION['id_doc'] = $datos['id_doc'] ?? '' ?>
            <button type="submit" class="boton-enviar-ayuda"><i class="fa fa-save"></i> Guardar cambios</button>
        </form>
    </main>
</body>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</html>