<?php
require_once 'modelo/estadisticasModelo.php';
class EstadisticasControl {
    public static function estadisticas() {
        $respuesta = EstadisticasModelo::estadisticas_mostrar();

        // Inicializar arreglo con los 5 estados
        $datos = [
            'En espera del documento físico para ser procesado 0/3' => 0,
            'En Proceso 1/3' => 0,
            'En Proceso 2/3' => 0,
            'En Proceso 3/3 (Sin Entregar)' => 0,
            'Solicitud Finalizada (Ayuda Entregada)' => 0
        ];

        if ($respuesta['exito']) {
            foreach ($respuesta['datos'] as $fila) {
                $estado = $fila['estado'];
                $total = $fila['total'];
                if (isset($datos[$estado])) {
                    $datos[$estado] = $total;
                }
            }
        } else {
            error_log("Error al obtener estadísticas: " . $respuesta['error']);
        }

        // Pasar datos a la vista
        require_once 'vistas/estadisticas_solicitudes.php';
    }
}

?>
