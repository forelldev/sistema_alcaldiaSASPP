<?php 
require_once 'modelo/beneficiarioModelo.php';
Class BeneficiarioControl {
    public static function mostrar() {
    if (isset($_GET['ci']) && !empty($_GET['ci'])) {
        $ci = $_GET['ci'];
        $resultado = BeneficiarioModelo::muestra($ci);
        if ($resultado['exito']) {
            $beneficiario = $resultado['datos'];
        } else {
            $error = $resultado['mensaje'] ?? 'No se encontró el beneficiario.';
        }
    } else {
        $error = 'No se proporcionó una cédula válida.';
    }

    require_once 'vistas/informacion_beneficiario.php';
}

    public static function beneficiarios_list() {
    $resultado = BeneficiarioModelo::lista();
    if($resultado['exito']){
        $datos = $resultado['datos'];
    }
    require_once 'vistas/beneficiario_list.php';
    }

    public static function registro_beneficiario(){
        require_once 'vistas/beneficiario_registro.php';
    }

    public static function registrar_beneficiario(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d H:i:s');
                $_POST['fecha'] = $fecha;
                $resultado = BeneficiarioModelo::registrar_beneficiario($_POST);
                if ($resultado['exito']) {
                    header('Location: ' . BASE_URL . '/felicidades_beneficiario');
                    $id_solicitante = $resultado['id_solicitante'];
                    $accion = 'Registró un nuevo beneficiario.';
                    Procesar::registrarReporte($id_solicitante, $fecha, $accion, $_SESSION['ci']);
                    exit;
                } else {
                    $msj = "Error al registrar la solicitud: " . $resultado['error'];
                    require_once 'vistas/beneficiario_registro.php';
                    }
                } else {
                    // Manejo si no es POST o está vacío
                    $msj = "Solicitud inválida. No se recibieron datos.";
                    require_once 'vistas/beneficiario_registro.php';
                }
    }

    public static function felicidades_beneficiario(){
        require_once 'vistas/felicidades_beneficiario.php';
    }

    public static function buscar_beneficiario(){
         if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            $res = BeneficiarioModelo::busqueda_beneficiario($_POST);
            if($res['exito']){
                $datos = $res['datos'];
            }
            else{
                $msj = "Ocurrió un error".$res['error'];
            }
        }
        else{
            $msj = 'Hubo error pasando datos';
        }
        require_once 'vistas/beneficiario_list.php';
    }

    public static function solicitudes_beneficiario(){
        if(isset($_GET['ci'])){
            $ci = $_GET['ci'];
            $res = BeneficiarioModelo::mostrar_solicitudes($ci);
            if($res['exito']){
                $datos = $res['datos'];
            }
            else{
                $msj = 'Ocurrió un error: '.$res['error'];
            }
        }
        else{
            $msj = 'Error al recibir "CI"';
        }
        require_once 'vistas/beneficiario_solicitudes.php';
    }

}
?>