<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Constancia</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/solicitud.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../css/registro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>../font/css/all.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
    <style>
        .registro-constancia-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(48,82,255,0.09);
            max-width: 500px;
            margin: 3rem auto 2rem auto;
            padding: 2.5rem 2rem;
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .registro-constancia-card h1 {
            text-align: center;
            color: #3b4cca;
            font-weight: 700;
            margin-bottom: 2rem;
        }
        .registro-constancia-form {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }
        .registro-constancia-form input,
        .registro-constancia-form select {
            border: 1px solid #e0e3e8;
            border-radius: 8px;
            padding: 0.7rem 1rem;
            font-size: 1rem;
            background: #f5f7fa;
            margin-bottom: 0.5rem;
        }
        .registro-constancia-form input[type="submit"] {
            background: #3b4cca;
            color: #fff;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 1rem;
        }
        .registro-constancia-form input[type="submit"]:hover {
            background: #232946;
            color: #ffd600;
        }
        .volver-btn {
            display: inline-block;
            background: #3b4cca;
            color: #fff;
            border-radius: 8px;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: background 0.2s;
            margin: 1.5rem auto 0 auto;
            text-align: center;
        }
        .volver-btn:hover {
            background: #232946;
            color: #ffd600;
        }
        @media (max-width: 600px) {
            .registro-constancia-card {
                padding: 1rem 0.3rem;
                max-width: 99vw;
            }
            .registro-constancia-form input,
            .registro-constancia-form select {
                font-size: 0.97rem;
                padding: 0.5rem 0.7rem;
            }
        }
    </style>
</head>
<body class="solicitud-body">
    <div class="registro-constancia-card">
        <h1 class="mensaje"><?= isset($msj) ? htmlspecialchars($msj) : 'Registrar Constancia' ?></h1>
        <form action="<?= BASE_URL ?>/registrar_constancia" method="POST" class="registro-constancia-form">
            <input type="text" name="id_manual" placeholder="Número de documento" required>
            <select name="tipo" id="tipo" required>
                <option value="Fé de vida">Fé de vida</option>
                <option value="Constancia de Soltería">Constancia de Soltería</option>
                <option value="Asiento Permanente">Asiento Permanente</option>
                <option value="Permisos de Mudanza">Permisos de Mudanza</option>
            </select>
            <input type="text" name="ci" placeholder="Cédula" required>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <input type="submit" value="Registrar Constancia">
        </form>
        <a href="<?= BASE_URL ?>/constancias" class="volver-btn"><i class="fa fa-arrow-left"></i> Volver</a>
    </div>
    <script>
        const BASE_PATH = "<?php echo BASE_PATH; ?>";
    </script>
    <script src="<?= BASE_URL ?>/public/js/sesionReload.js"></script>
    <script src="<?= BASE_URL ?>/public/js/validarSesion.js"></script>
</body>
</html>