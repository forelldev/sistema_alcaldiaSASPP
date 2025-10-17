<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Beneficiario (Persona)</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
</head>
<body class="solicitud-body">
    <header class="header">
        <div class="titulo-header">Registro de Beneficiario</div>
        <div class="header-right">
            <a href="<?= BASE_URL ?>/beneficiarios_lista"><button class="nav-btn"><i class="fa fa-arrow-left"></i> Volver atrás</button></a>
        </div>
    </header>
    <main>
        <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : '' ?></h1>
        <form method="POST" action="<?= BASE_URL ?>/registrar_beneficiario" class="formulario-ayuda" autocomplete="off">
            <h2><i class="fa fa-user"></i> Datos Personales</h2>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="" required>
                </div>
                <div class="campo-formulario">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="" required>
                </div>
                <div class="campo-formulario">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" value="" required>
                </div>
                <div class="campo-formulario">
                    <label for="ci">Cédula:</label>
                    <input type="text" id="ci" name="ci" value="" required>
                </div>
            </div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="" required>
                    <input type="hidden" id="edad" name="edad">
                </div>
                <div class="campo-formulario">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
            </div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="lugar_nacimiento">Lugar de Nacimiento:</label>
                    <input type="text" id="lugar_nacimiento" name="lugar_nacimiento" value="" required>
                </div>
                <div class="campo-formulario">
                    <label for="estado_civil">Estado Civil:</label>
                    <select name="estado_civil" id="estado_civil" required>
                        <option value="Soltero/a">Soltero/a</option>
                        <option value="Casado/a">Casado/a</option>
                        <option value="Viudo/a">Viudo/a</option>
                    </select>
                </div>
            </div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="codigo_patria">Código de Patria:</label>
                    <input type="text" id="codigo_patria" name="codigo_patria" value="" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div class="campo-formulario">
                    <label for="serial_patria">Serial de Patria:</label>
                    <input type="text" id="serial_patria" name="serial_patria" value="" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
            </div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="nivel_instruc">Nivel de Instrucción:</label>
                    <select name="nivel_instruc" id="nivel_instruc" required>
                        <option value="Primaria">Primaria</option>
                        <option value="Secundaria">Secundaria</option>
                        <option value="Universidad">Universidad</option>
                    </select>
                </div>
                <div class="campo-formulario">
                    <label for="profesion">Profesión:</label>
                    <input type="text" id="profesion" name="profesion" value="" required>
                </div>
            </div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="trabajo1">¿Trabaja?</label>
                    <select name="trabajo1" id="trabajo1" required onchange="mostrarCampoTrabajo()">
                        <option value="">Seleccione</option>
                        <option value="Si">Sí</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="campo-formulario" id="campoTrabajo" style="display:none; margin-top:10px;">
                    <label for="trabajo">Trabajo:</label>
                    <input type="text" id="trabajo" name="trabajo" value="">
                    <label for="direccion_trabajo">Dirección de Trabajo:</label>
                    <input type="text" id="direccion_trabajo" name="direccion_trabajo" value="">
                    <label for="trabaja_public">¿Trabaja en el sector público?</label>
                    <select name="trabaja_public" id="trabaja_public" onchange="mostrarInstitucion()">
                        <option value="">Seleccione</option>
                        <option value="Si">Sí</option>
                        <option value="No">No</option>
                    </select>
                    <div id="campoInstitucion" style="display:none; margin-top:10px;">
                        <label for="nombre_insti">Nombre de la Institución:</label>
                        <input type="text" id="nombre_insti" name="nombre_insti" value="">
                    </div>
                </div>
            </div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="comunidad">Comunidad:</label>
                    <select name="comunidad" id="comunidad" required>
                        <option value="plus">Agregar Comunidad</option>
                    </select>
                </div>
                <div class="campo-formulario">
                    <label for="direc_habita">Dirección de Habitación:</label>
                    <input type="text" id="direc_habita" name="direc_habita" value="" required>
                </div>
            </div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="estruc_base">Estructura Base:</label>
                    <input type="text" id="estruc_base" name="estruc_base" value="" required>
                </div>
            </div>
            <div class="titulo-seccion"><i class="fa fa-home"></i> Datos Físicos Ambientales</div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="propiedad">Propiedad</label>
                    <select name="propiedad" id="propiedad" required>
                        <option value="Casa">Casa</option>
                        <option value="Apartamento">Apartamento</option>
                        <option value="Rancho">Rancho</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="campo-formulario">
                    <label for="propiedad_est">Estado de Propiedad</label>
                    <select name="propiedad_est" id="propiedad_est" required>
                        <option value="Propia">Propia</option>
                        <option value="Prestada">Prestada</option>
                        <option value="Alquiler">Alquiler</option>
                    </select>
                </div>
            </div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="observaciones">Observaciones:</label>
                    <input type="text" id="observaciones" name="observaciones_propiedad" placeholder="Detalles adicionales relevantes (Opcional)" value="">
                </div>
            </div>
            <div class="titulo-seccion"><i class="fa fa-money-bill"></i> Datos Socio-Económicos</div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="nivel_ingreso">Nivel de Ingresos:</label>
                    <input type="text" id="nivel_ingreso" name="nivel_ingreso" placeholder="Ejem: 500 Bs" required oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="">
                </div>
                <div class="campo-formulario">
                    <label for="pension">¿Recibe Bonos?</label>
                    <select name="pension" id="pension" required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="campo-formulario">
                    <label for="bono">¿Recibe Pensiones?</label>
                    <select name="bono" id="bono" required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>
            <div class="titulo-seccion"><i class="fa fa-medkit"></i> Datos de Asistencia Médica</div>
            <div class="fila-formulario">
                <div class="campo-formulario">
                    <label for="tienePatologia">¿Tiene familiares con patología?</label>
                    <select id="tienePatologia" name="tienePatologia" onchange="mostrarNumeroFamiliares()" required>
                        <option value="">Seleccione</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="campo-formulario" id="numeroFamiliaresContainer" style="display:none; margin-top:10px;">
                    <label for="numeroFamiliares" name="numeroFamiliares">¿Cuántos familiares?</label>
                    <select id="numeroFamiliares" onchange="generarCamposFamiliares()" required>
                        <option value="">Seleccione</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div id="camposFamiliares" style="margin-top:10px;"></div>
            <button type="submit" class="boton-enviar-ayuda"><i class="fa fa-paper-plane"></i> Enviar</button>
        </form>
    </main>
</body>
<script>
    const BASE_PATH = "<?php echo BASE_PATH; ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
<script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
<script src="<?= BASE_URL ?>/public/js/trabajo.js"></script>
<script src="<?= BASE_URL ?>/public/js/edad.js"></script>
<script>
    const tiposPatologiaGuardados = "<?= $tiposJS ?>".split('|');
    const nombresPatologiaGuardados = "<?= $nombresJS ?>".split('|');
    const data_exists = "<?= $data_exists ? '1' : '0' ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/patologia.js"></script>
</html>