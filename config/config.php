<?php
if (session_status() === PHP_SESSION_NONE) {
                session_start();
    }
// Define rutas base
define('BASE_URL', dirname($_SERVER['SCRIPT_NAME']));
// define('BASE_PATH', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/'); // Para JavaScript
// Detecta la IP del servidor automáticamente
// $ip_servidor = $_SERVER['SERVER_ADDR'] ?? gethostbyname(gethostname());
// define('BASE_URL', ''); // Ruta relativa
define('BASE_PATH', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/');
?>