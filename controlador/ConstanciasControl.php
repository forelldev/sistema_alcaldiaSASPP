<?php 
require_once 'modelo/ConstanciasModelo.php';
require_once 'modelo/procesarModelo.php';
Class ConstanciasControl {
    public static function mostrar() {
    $resultado = ConstanciasModelo::muestra();
    if($resultado['exito']){
        $datos = $resultado['datos'];
    }
    require_once 'vistas/constancias_desarrollo.php';
    }

    public static function registro_constancia() {
        require_once 'vistas/constancias_desarrollo_registro.php';
    }

    public static function registrar_constancia() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
        date_default_timezone_set('America/Caracas');
        $fecha = date('Y-m-d H:i:s');
        $_POST['fecha'] = $fecha;
        $resultado = ConstanciasModelo::registrar_constancia($_POST);
        if ($resultado['exito']) {
            header('Location: ' . BASE_URL . '/felicidades_constancia');
            $id = $resultado['id'];
            $accion = 'Creó una nueva constancia.';
            Procesar::registrarReporte($id, $fecha, $accion, $_SESSION['ci']);
            exit;
        } else {
            $msj = "Error al registrar la solicitud: " . $resultado['error'];
            require_once 'vistas/constancias_desarrollo_registro.php';
            }
        } else {
            // Manejo si no es POST o está vacío
            $msj = "Solicitud inválida. No se recibieron datos.";
            require_once 'vistas/constancias_desarrollo_registro.php';
        }
    }

    public static function felicidades_constancia(){
        require_once 'vistas/felicidades_constancia.php';
    }
}
?>