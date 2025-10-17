<?php 
require_once 'modelo/DesarrolloModelo.php';
require_once 'modelo/procesarModelo.php';
class DesarrolloControl {
    public static function lista (){
        $resultado = Desarrollo::mostrar_lista();
        $notificaciones = Desarrollo::notificaciones();
        $notificacion = $notificaciones['exito'] ? $notificaciones['datos'] : [];
        // Agrupar notificaciones por categoría
        $notificacionAgrupada = [];
        foreach ($notificacion as $item) {
            $tipo = $item['categoria'] ?? 'general';
            $notificacionAgrupada[$tipo][] = $item;
        }
            if($resultado['exito']){
                $datos = $resultado['datos'];
            }
            require_once 'vistas/solicitudes_desarrollo.php';
        }

    public static function buscar_desarrollo(){
        require_once 'vistas/solicitudes_desarrollo_buscar.php';
    }
    public static function formulario_desarrollo() {
        $ci = $_POST['ci'] ?? null;
        if ($ci) {
            $res = Desarrollo::verificar_solicitudes($ci);
            if($res['exito']){
                $msj = 'Se han encontrado solicitudes anteriores de esta persona';
                $datos = $res['datos'];
                require_once 'vistas/solicitud_desarrollo_encontrada.php';
            }
            else{
                $data = self::obtenerDatosBeneficiario($ci);
                extract($data); // crea $data_exists, $datos_beneficiario, etc.
                // Verificar si el solicitante existe para bloquear edición
                $readonly = !empty($datos_beneficiario['solicitante']['nombre']);
                require_once 'vistas/solicitudes_desarrollo_formulario.php';
            }
        }
    }

    public static function registrar(){
        $ci = $_POST['ci'] ?? null;
        if($ci){
            $ci = $_POST['ci'];
            $data = self::obtenerDatosBeneficiario($ci);
            extract($data); // crea $data_exists, $datos_beneficiario, etc.
            $readonly = !empty($datos_beneficiario['solicitante']['nombre']);
            require_once 'vistas/solicitudes_desarrollo_formulario.php';
        }
    }

    private static function obtenerDatosBeneficiario($ci) {
        $data = [
            'datos_beneficiario' => null
        ];

        $resultado = Desarrollo::cargar_datos_solicitantes($ci);
        if ($resultado['exito']) {
            $data['datos_beneficiario'] = $resultado['mostrar'];
        }
        return $data;
    }

    public static function enviar_formulario_desarrollo() {
            date_default_timezone_set('America/Caracas');
            $_POST['fecha'] = date('Y-m-d H:i:s');
            $_POST['ci_user'] = $_SESSION['ci'];
            $resultado = Desarrollo::enviar_formulario($_POST);
            if ($resultado['exito']) {
                header('Location: ' . BASE_URL . '/felicidades_desarrollo');
                $fecha = $_POST['fecha'];
                $accion = 'Registró solicitud en Desarrollo Social';
                $id_des = $resultado['id_des'];
                Procesar::registrarReporte($id_des,$fecha,$accion,$_SESSION['ci']);
                exit;
            } else {
                $msj = "Error al registrar la solicitud: " . $resultado['error'];
                $ci = $_POST['ci'] ?? null;
                $data_exists = false;
                $datos_beneficiario = $_POST; // mantener datos del intento fallido
                // Rebuscar datos si es necesario
                if ($ci) {
                    $data = self::obtenerDatosBeneficiario($ci);
                    extract($data);
                }
                // Verificar si el solicitante existe para bloquear edición
                $readonly = !empty($datos_beneficiario['solicitante']['nombre']);
                require_once 'vistas/solicitudes_desarrollo_formulario.php';
            }
        }

        public static function felicidades_desarrollo(){
            require_once 'vistas/felicidades_desarrollo.php';
        }

        public static function procesar(){
        if(isset($_GET['id_des'])){
            $id_des = $_GET['id_des'];
            $estado = $_GET['estado'];
            switch($estado){
                case 'En espera del documento físico para ser procesado 0/2':
                    $estado_new = 'En Proceso 1/2';
                    $accion = 'Aprobó la solicitud para su procedimiento (Desarrollo Social)';
                    break;
                case 'En Proceso 1/2':
                    $estado_new = 'En Proceso 2/2 (Sin entregar)';
                    $accion = 'Envió la solicitud a Administración. (Desarrollo Social)';
                    break;
                case 'En Proceso 2/2 (Sin entregar)':
                    $estado_new = 'Solicitud Finalizada (Ayuda Entregada)';
                    $accion = 'Confirmó que se entregó la ayuda. (Desarrollo Social)';
                    break;
                case 'Solicitud Finalizada (Ayuda Entregada)':
                    $estado_new = 'En Proceso 1/2';
                    $accion = 'Reinició la solicitud. (Desarrollo Social)';
                    break;
                default:
                    $msj = 'Ocurrió un error!';
            }
            if(Procesar::desarrollo($id_des,$estado_new)){
                header('Location: '.BASE_URL.'/solicitudes_desarrollo');
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d H:i:s');
                Procesar::registrarReporte($id_des,$fecha,$accion,$_SESSION['ci']);
                exit;
            }
            else{
                $msj = 'Ocurrió un error ejecutando la consulta de procesamiento';
            }
        }
        else{
            $msj = 'Faltan parámetros en la solicitud';
        }
        require_once 'vistas/solicitudes_desarrollo.php';
        }

    public static function inhabilitar_vista(){
        if(isset($_GET['id_des'])){
            $id_des = $_GET['id_des'];
        }
        require_once 'vistas/solicitud_desarrollo_inhabilitar.php';
    }

    public static function inhabilitar(){
        if(isset($_POST['id_des'])){
            $invalido = 1;
            $id_des = $_POST['id_des'];
            $razon = $_POST['razon'];
            if(Procesar::inhabilitarDesarrollo($id_des,$invalido,$razon)){
                header('Location: '.BASE_URL.'/desarrollo_invalidos');
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d H:i:s');
                $accion = 'Inhabilitó la solicitud razón: '.$razon;
                Procesar::registrarReporte($id_des,$fecha,$accion,$_SESSION['ci']);
                exit;
            }
            else{
                $msj = 'Ocurrió un error inesperado';
            }
        }
    }

    public static function inhabilitados_lista(){
        $resultado = Desarrollo::mostrar_inhabilitados();
        if($resultado['exito']){
            $datos = $resultado['datos'];
        }
        require_once 'vistas/solicitud_desarrollo_invalidos.php';
    }

    public static function habilitar(){
        if(isset($_GET['id_des'])){
            $id_des = $_GET['id_des'];
            if(Procesar::habilitar_desarrollo($id_des)){
                header('Location: '.BASE_URL.'/solicitudes_desarrollo');
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d H:i:s');
                $accion = 'Habilitó la solicitud';
                Procesar::registrarReporte($id_des,$fecha,$accion,$_SESSION['ci']);
                exit;
            }
            else{
                $msj = 'Ocurrió un error inesperado';
            }
        }
    }

    public static function editar(){
        if(isset($_GET['id_des'])){
            $id_des = $_GET['id_des'];
            $resultado = Desarrollo::edicion_vista($id_des);
            if($resultado){
                $datos = $resultado['datos'];
            }
            else{
                $msj = 'Ocurrió un  en el precesamiento del id_des';
            }
        }
        require_once 'vistas/solicitudes_desarrollo_editar.php';
    }

    public static function editar_solicitud(){
        if(isset($_POST['id_des'])){
        date_default_timezone_set('America/Caracas');
        $_POST['fecha'] = date('Y-m-d H:i:s');
        $_POST['ci_user'] = $_SESSION['ci'];
        $id_des = $_POST['id_des'];
        $resultado = Desarrollo::editar($_POST);
            if($resultado['exito']){
                header('Location: '.BASE_URL.'/solicitudes_desarrollo');
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d H:i:s');
                $accion = 'Editó la solicitud de Desarrollo Social';
                Procesar::registrarReporte($id_des,$fecha,$accion,$_SESSION['ci']);
            }
            else{
                $msj = "Error" . $resultado['error'];
                $id_des = $_POST['id_des'] ?? null;
                if($id_des){
                    $resultado = Desarrollo::edicion_vista($id_des);
                    if($resultado){
                        $datos=$resultado['datos'];
                    }
                }
                require_once 'vistas/solicitudes_desarrollo_editar.php';
            }               
        }
        else{
            $msj = 'Surgió un error obteniendo datos (POST)';
        }
    }

    public static function filtrar_desarrollo(){
        if(isset($_GET['filtro'])){
            $filtro = $_GET['filtro'];
            $res = Desarrollo::filtrar($filtro);
            if($res['exito']){

            }
            else{
                $msj = 'Ocurrió un error: '.$res['error'];
            }
        }
        require_once 'vistas/solicitudes_desarrollo.php';
    }

    public static function filtrar_fecha_desarrollo(){
        if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_final']) && isset($_POST['estado'])) {
            $resultado = Desarrollo::fecha_filtro($_POST);
                if ($resultado['exito']) {
                    $datos = $resultado['datos'];
                    $fecha_inicio = $_POST['fecha_inicio'];
                    $fecha_final = $_POST['fecha_final'];
                    $estado = $_POST['estado'];
            }
            else{
                $msj = 'Ocurrió un error: '.$resultado['error'];
            }
        }
        else{
            $msj = 'Ocurrió un error con el envío de datos (POST)';
        }
    }

    public static function mostrar_noti_urgencia(){
        if(isset($_GET['id_doc'])){
            $id_doc = $_GET['id_doc'];
            $res = Desarrollo::mostrar_urgencia($id_doc);
            if($res['exito']){
                $datos = $res['datos'];
            }
            else{
                $msj = 'Ocurrió un error: '.$res['error'];
            }

        }
        require_once 'vistas/solicitud_desarrollo_urgencia.php';
    }
}
?>