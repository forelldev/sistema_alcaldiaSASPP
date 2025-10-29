<?php
// Configuración de la base de datos
$host = 'localhost';
$usuario = 'root';
$contrasena = '';
$base_datos = 'sistema_alcaldia';

// Carpeta donde guardar el respaldo
$carpeta = __DIR__;
$nombre_archivo = "sistema_alcaldia_respaldo.sql";
$ruta_respaldo = $carpeta . "/$nombre_archivo";

// Validar ruta de mysqldump
$mysqldump_path = trim(shell_exec("which mysqldump"));
if (!$mysqldump_path) {
    $mysqldump_path = "C:\\xampp\\mysql\\bin\\mysqldump.exe"; // Ruta típica en Windows con XAMPP
}

// Comando mysqldump
$comando = "\"$mysqldump_path\" --user=\"$usuario\" --password=\"$contrasena\" --host=\"$host\" \"$base_datos\" > \"$ruta_respaldo\"";

// Ejecutar
exec($comando, $output, $resultado);

// Resultado
if ($resultado === 0 && file_exists($ruta_respaldo)) {
    echo "✅ Respaldo actualizado: $nombre_archivo";
} else {
    echo "❌ Error al crear el respaldo. Verifica credenciales, ruta de mysqldump y permisos.";
}
?>
