<?php
require_once 'modelo/AtencionModelo.php';
require_once 'modelo/procesarModelo.php';
class AtencionControl {
    public static function casos_lista(){
        $resultado = AtencionModelo::mostrar_casos();
        if($resultado['exito']){
            $datos = $resultado['datos'];
        }
        else{
            $msj = 'Ocurrió un error: '.$resultado['error'];
        }
        require_once 'vistas/casos_lista.php';
    }

    public static function casos_ci_busqueda(){
        require_once 'vistas/casos_busqueda.php';
    }

    private static function obtenerDatosBeneficiario($ci) {
        $data = [
            'datos_beneficiario' => null
        ];

        $resultado = AtencionModelo::cargar_datos($ci);
        if ($resultado['exito']) {
            $data['datos_beneficiario'] = $resultado['mostrar'];
        }
        return $data;
    }
    public static function casos_formulario(){
        if(isset($_POST['ci'])){
            $ci = $_POST['ci'];
            $resultado = AtencionModelo::verificar_casos($ci);
            if($resultado['exito']){
                $msj = 'Se han encontrado casos anteriores de esta persona';
                $datos = $resultado['datos'];
                require_once 'vistas/casos_anteriores.php';
            }
            else{
                $res = AtencionModelo::verificar_solicitante($ci);
                if($res['exito']){
                    $msj = 'El solicitante ya está registrado!';
                    $data = self::obtenerDatosBeneficiario($ci);
                    extract($data); // crea $data_exists, $datos_beneficiario, etc.
                    require_once 'vistas/casos_formulario_cargado.php';
                    
                }
                else{
                    $msj = 'Registra al Solicitante!';
                    require_once 'vistas/casos_formulario.php';
                }
            }
        }
        else{
            $msj = 'Algo anda mal... (Fallo en recibir datos)';
            require_once 'vistas/casos_formulario.php';
        }
    }

    public static function casos_anteriores(){
        if(isset($_POST['ci'])){
            $ci = $_POST['ci'];
            $data = self::obtenerDatosBeneficiario($ci);
            extract($data); // crea $data_exists, $datos_beneficiario, etc.
            $msj = 'El solicitante ya está registrado!';
            require_once 'vistas/casos_formulario_cargado.php';
        }
        else{
            $msj = 'Ocurrió un error o el Solicitante no existe';
            require_once 'vistas/casos_formulario.php';
        }
    }

    public static function casos_enviar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            date_default_timezone_set('America/Caracas');
            $_POST['fecha'] = date('Y-m-d H:i:s');
            $_POST['ci_user'] = $_SESSION['ci'];
            $_POST['estado'] = 'Sin Atender';
            $_POST['descripcion'] = 'Solicitud de '.$_POST['tipo_ayuda'].' para '.$_POST['nombre']. ' '.$_POST['apellido'].' en oficina de '.$_POST['direccion'];
            $resultado = AtencionModelo::registrar_caso($_POST);
            if ($resultado['exito']) {
                $accion = 'Creó un nuevo caso.';
                $id_doc = $resultado['id_caso'];
                Procesar::registrarReporte($id_doc, $_POST['fecha'], $accion, $_SESSION['ci']);
                header('Location: ' . BASE_URL . '/felicidades_casos');
                exit;
            } else {
                $msj = "Error al registrar la solicitud: " . $resultado['error'];
                $ci = $_POST['ci'] ?? null;
                $datos_beneficiario = $_POST;
                // Si deseas recuperar datos del beneficiario:
                if ($ci) {
                    $data = self::obtenerDatosBeneficiario($ci);
                    extract($data);
                }
                require_once 'vistas/casos_formulario_cargado.php';
            }
        } else {
            $msj = "Solicitud inválida. No se recibieron datos. (Método POST)";
            require_once 'vistas/casos_formulario.php';
        }
    }

    public static function felicidades_casos (){
        require_once 'vistas/felicidades_casos.php';
    }

}
?>