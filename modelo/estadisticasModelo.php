<?php
require_once 'conexiondb.php';
class EstadisticasModelo{
    public static function estadisticas_mostrar(){
        $conexion = DB::conectar();
        try {
            $stmt = $conexion->prepare("
                SELECT estado, COUNT(*) AS total
                FROM solicitud_ayuda
                WHERE estado IN (
                    'En espera del documento físico para ser procesado 0/3',
                    'En Proceso 1/3',
                    'En Proceso 2/3',
                    'En Proceso 3/3 (Sin Entregar)',
                    'Solicitud Finalizada (Ayuda Entregada)'
                )
                GROUP BY estado
            ");

            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'exito' => true,
                'datos' => $resultados
            ];
        } catch (Exception $e) {
            error_log("Error al obtener estadísticas de solicitud_ayuda: " . $e->getMessage());
            return [
                'exito' => false,
                'error' => $e->getMessage()
            ];
        }
    }

}

?>