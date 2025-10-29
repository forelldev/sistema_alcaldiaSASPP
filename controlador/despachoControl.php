<?php 
    require_once 'modelo/despachoModelo.php';
    require_once 'modelo/procesarModelo.php';
    class DespachoControl{
        public static function despacho_list(){
            $resultado = Despacho::buscarLista();
            if($resultado['exito']){
                $datos = $resultado['datos'];
            }
            require_once 'vistas/despacho_list.php';
        }

    public static function buscar() {
        $ci = $_POST['ci'] ?? null;
        if ($ci) {
            $data = self::obtenerDatosBeneficiario($ci);
            extract($data); // crea $data_exists, $datos_beneficiario, etc.

            require_once 'vistas/despacho_formulario.php';
        }
}
    private static function obtenerDatosBeneficiario($ci) {
        $data = [
            'datos_beneficiario' => null
        ];

        $resultado = Despacho::buscarCi($ci);
        if ($resultado['exito']) {
            $data['datos_beneficiario'] = $resultado['mostrar'];
        }

        return $data;
    }

    public static function enviarFormulario() {
            date_default_timezone_set('America/Caracas');
            $_POST['fecha'] = date('Y-m-d H:i:s');
            $_POST['ci_user'] = $_SESSION['ci'];
            $resultado = Despacho::enviarForm($_POST);
            if ($resultado['exito']) {
                header('Location: ' . BASE_URL . '/felicidades_despacho');
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d H:i:s');
                $accion = 'Registró solicitud de despacho';
                $id_doc = $_POST['id_despacho'];
                Procesar::registrarReporte($id_doc,$fecha,$accion,$_SESSION['ci']);
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
                require_once 'vistas/despacho_formulario.php';
            }
        }

        public static function procesar(){
        if(isset($_GET['id_despacho'])){
            $id_despacho = $_GET['id_despacho'];
            $estado = $_GET['estado'];
            switch($estado){
                case 'En Revisión 1/2':
                    $estado_new = 'En Proceso 2/2 (Sin entregar)';
                    $accion = 'Envió la solicitud a Administración. (Despacho)';
                    break;
                case 'En Proceso 2/2 (Sin entregar)':
                    $estado_new = 'Solicitud Finalizada (Ayuda Entregada)';
                    $accion = 'Confirmó que se entregó la ayuda. (Despacho)';
                    break;
                case 'Solicitud Finalizada (Ayuda Entregada)':
                    $estado_new = 'En Revisión 1/2';
                    $accion = 'Reinició la solicitud. (Despacho)';
                    break;
                default:
                    $msj = 'Ocurrió un error!';
            }
            if(Procesar::despacho($id_despacho,$estado_new)){
                header('Location: '.BASE_URL.'/despacho_list');
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d H:i:s');
                $id_doc = $id_despacho;
                Procesar::registrarReporte($id_doc,$fecha,$accion,$_SESSION['ci']);
                exit;
            }
            else{
                $msj = 'Ocurrió un error ejecutando la consulta de procesamiento';
            }
        }
        else{
            $msj = 'Faltan parámetros en la solicitud';
        }
        require_once 'vistas/despacho_list.php';
        }

    public static function inhabilitar(){
        if(isset($_GET['id_doc'])){
            $id_doc = $_GET['id_doc'];
        }
        require_once 'vistas/inhabilitar.php';
    }
    public static function inhabilitar_solicitud(){
        if(isset($_POST['id_doc'])){
            $estado = 'Inhabilitado';
            $id_doc = $_POST['id_doc'];
            $razon = $_POST['razon'];
            if(Procesar::inhabilitarDespacho($id_doc,$estado,$razon)){
                header('Location: '.BASE_URL.'/inhabilitados_despacho');
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d H:i:s');
                $accion = 'Inhabilitó la solicitud razón: '.$razon;
                Procesar::registrarReporte($id_doc,$fecha,$accion,$_SESSION['ci']);
                exit;
            }
            else{
                $msj = 'Ocurrió un error inesperado';
            }
        }
    }

    public static function inhabilitados_lista(){
        $resultado = Procesar::inhabilitados_listaDespacho();
        if($resultado['exito']){
            $datos = $resultado['datos'];
        }
        require_once 'vistas/inhabilitados_despacho.php';
    }

    public static function habilitar(){
        if(isset($_GET['id_doc'])){
            $estado = 'En Revisión 1/2';
            $id_doc = $_GET['id_doc'];
            $razon = '';
            if(Procesar::habilitar_solicitudDespacho($id_doc,$estado,$razon)){
                header('Location: '.BASE_URL.'/despacho_list');
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d H:i:s');
                $accion = 'Habilitó la solicitud';
                Procesar::registrarReporte($id_doc,$fecha,$accion,$_SESSION['ci']);
                exit;
            }
            else{
                $msj = 'Ocurrió un error inesperado';
            }
        }
    }

    public static function editar(){
        if(isset($_GET['id_doc'])){
            $id_doc = $_GET['id_doc'];
            $resultado = Procesar::edit_vistaDespacho($id_doc);
            if($resultado){
                $datos = $resultado['datos'];
            }
            else{
                $msj = 'Ocurrió un  en el precesamiento del id_doc';
            }
        }
        require_once 'vistas/editarDespacho.php';
    }

    public static function editar_solicitud(){
        date_default_timezone_set('America/Caracas');
        $direccion = 'despacho_list';
        $_POST['fecha'] = date('Y-m-d H:i:s');
        $_POST['ci_user'] = $_SESSION['ci'];
        $resultado = Procesar::editar_consultaDespacho($_POST);
            if($resultado['exito']){
                header('Location: '.BASE_URL.'/'.$direccion);
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d H:i:s');
                $accion = 'Editó la solicitud de Despacho';
                $id_doc = $_POST['id_despacho'];
                Procesar::registrarReporte($id_doc,$fecha,$accion,$_SESSION['ci']);
            }
            else{
                $msj = "Error" . $resultado['error'];
            }
    }

    public static function despacho_busqueda(){
        require_once 'vistas/despacho_busqueda.php';
    }

    public static function felicidades_despacho(){
        require_once 'vistas/felicidadeS_despacho.php';
    }
        
    }
?>