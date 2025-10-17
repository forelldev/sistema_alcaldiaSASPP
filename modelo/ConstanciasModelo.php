<?php 
require_once 'conexiondb.php';
class ConstanciasModelo{
    public static function muestra() {
        try {
            $conexion = DB::conectar();
            $stmt = $conexion->prepare("SELECT * FROM constancias ");
            $stmt->execute(); 
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
              if ($resultado) {
                    // Usuario encontrado, devolver datos
                    return [
                        'exito' => true,
                        'datos' => $resultado
                    ];
                } else {
                    // No se encontró el usuario
                    return [
                        'exito' => false,
                        'mensaje' => 'Ocurrió un error realizando la búsqueda'
                    ];
                }
        } catch (PDOException $e) {
            error_log("Error al actualizar solicitud: " . $e->getMessage());
            return false;
        }
    }

    public static function registrar_constancia($data) {
    $conexion = DB::conectar();
        try {
            // Validar campos obligatorios
            $camposObligatorios = ['id_manual', 'tipo', 'ci', 'nombre', 'apellido'];
            foreach ($camposObligatorios as $campo) {
                if (!isset($data[$campo]) || $data[$campo] === '') {
                    throw new Exception("Falta el campo obligatorio: $campo");
                }
            }

            // Verificar si ya existe una constancia con ese id_manual
            $verificar = $conexion->prepare("SELECT COUNT(*) FROM constancias WHERE id_manual = ?");
            $verificar->execute([$data['id_manual']]);
            $existe = $verificar->fetchColumn();

            if ($existe > 0) {
                return [
                    'exito' => false,
                    'error' => 'Ya existe una constancia con ese ID.'
                ];
            }

            // Insertar nueva constancia
            $stmt = $conexion->prepare("INSERT INTO constancias (id_manual, tipo, ci, nombre, apellido, fecha) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['id_manual'],
                $data['tipo'],
                $data['ci'],
                $data['nombre'],
                $data['apellido'],
                $data['fecha']
            ]);
            $idInsertado = $conexion->lastInsertId();
            return ['exito' => true,
                    'id' => $idInsertado];
        } catch (Exception $e) {
            error_log("Error al insertar el reporte: " . $e->getMessage());
            return ['exito' => false, 'error' => $e->getMessage()];
        }
}
}
?>