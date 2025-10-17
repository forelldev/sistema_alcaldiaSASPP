<?php
require_once 'config/config.php';
// index.php
// Primero, carga la clase Router
require_once 'config/router.php';
// Luego, carga la definición de tus rutas
require_once 'routes.php';
// Finalmente, ejecuta el router para que maneje la petición
Router::dispatch();
?>