<?php
require_once 'modelo/AtencionModelo.php';
require_once 'modelo/procesarModelo.php';
class AtencionControl {
   public static function casos_lista() {
    $rol = $_SESSION['id_rol'] ?? null;

    // Mapeo de roles a direcciones
    $direcciones = [
        0 => 'Todos',
        1 => 'Desarrollo Social',
        2 => 'Despacho',
        3 => 'Administracion',
        4 => 'Todos'
    ];

    $direccion = $direcciones[$rol] ?? 'Todos';

    $resultado = AtencionModelo::mostrar_casos($direccion);

    if ($resultado['exito']) {
        $datos = $resultado['datos'];
    } else {
        $msj = $resultado['error'];
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
                    $msj = 'El solicitante ya está registrado! (Se han cargado los datos)';
                    $data = self::obtenerDatosBeneficiario($ci);
                    extract($data); // crea $data_exists, $datos_beneficiario, etc.
                    require_once 'vistas/casos_formulario_cargado.php';
                    
                }
                else{
                    $msj = 'Registra al Solicitante!';
                    require_once 'vistas/casos_formulario_cargado.php';
                }
            }
        }
        else{
            $msj = 'Algo anda mal... (Fallo en recibir datos)';
            require_once 'vistas/casos_formulario_cargado.php';
        }
    }

    public static function casos_anteriores(){
        if(isset($_POST['ci'])){
            $ci = $_POST['ci'];
            $data = self::obtenerDatosBeneficiario($ci);
            extract($data); // crea $data_exists, $datos_beneficiario, etc.
            $msj = 'El solicitante ya está registrado! (Se han cargado los datos)';
            require_once 'vistas/casos_formulario_cargado.php';
        }
        else{
            $msj = 'Ocurrió un error o el Solicitante no existe';
            require_once 'vistas/casos_formulario_cargado.php';
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

    public static function atender_caso(){
        if(isset($_GET['id_caso'])){
            $id_caso = $_GET['id_caso'];
            $resultado = AtencionModelo::datos_caso($id_caso);
                if($resultado['exito']){
                    $datos = $resultado['datos'];
                    $marcar_visto = AtencionModelo::marcar_visto_caso($id_caso);
                }
                else{
                    $msj = $resultado['error'];
                }
        }
        else{
            $msj = 'Ocurrió un error al recibir la ci';
        }
        require_once 'vistas/atender_caso.php';
    }

    public static function generar_solicitud(){
        if(isset($_GET['id_caso']) && isset($_GET['direccion']) && isset($_GET['categoria']) && isset($_GET['ci'])){
            $id_caso = $_GET['id_caso'];
            $direccion = $_GET['direccion'];
            $categoria = $_GET['categoria'];
            $ci = $_GET['ci'];
            switch($direccion){
                case 'Desarrollo Social':
                    if($categoria == 'Ayuda Económica'){
                        $msj = 'Rellena el estudio socioeconómico! (Se han cargado datos del solicitante)';
                        $data = self::obtenerDatosBeneficiario($ci);
                        extract($data); // crea $data_exists, $datos_beneficiario, etc.
                        require_once 'vistas/solicitud_formulario_cargado.php';
                        exit;
                    }
                    else{
                        require_once 'vistas/solicitudes_desarrollo_formulario.php';
                    }
                break;
                case 'Despacho':
                    $msj = 'Rellene el formulario! (Se han cargado datos del solicitante)';
                    $data = self::obtenerDatosBeneficiario($ci);
                    extract($data); // crea $data_exists, $datos_beneficiario, etc.
                    require_once 'vistas/despacho_formulario.php';
                    break;
                default:
                $msj = 'No se encontró la oficina buscada, intentelo de nuevo';
                require_once 'vistas/casos_lista.php';
                break;
            }
        }

    }

    public static function marcar_vistas_new(){
        $resultado = AtencionModelo::marcar_vistas();
        if($resultado['exito']){
            $msj = 'Marcadas como vistas con éxito';
        }
        else{
            $msj = $resultado['mensaje'];
        }
        header('Location: '.BASE_URL.'/main?msj='.$msj);
    }

    public static function filtrar_caso() {
        if (isset($_GET['filtro'])) {
            $filtro = $_GET['filtro'];
            $resultado = AtencionModelo::filtrar_caso($filtro);

            if (!empty($resultado)) {
                $datos = $resultado;
            } else {
                $msj = "No se encontraron solicitudes para el filtro: " . htmlspecialchars($filtro);
            }
        } else {
            $msj = 'No se recibió ningún filtro por GET.';
        }

        require_once 'vistas/casos_lista.php';
    }

}
?>