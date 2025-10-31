<?php
require_once 'modelo/AtencionModelo.php';
require_once 'modelo/procesarModelo.php';
require_once 'modelo/solicitudModelo.php';
class AtencionControl {
    // MOSTRAR CASOS, LISTA
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

    // VISTA DE BUSQUEDA DE CI:

    public static function casos_ci_busqueda(){
        require_once 'vistas/casos_busqueda.php';
    }

    // PRECARGA DE DATOS:

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


    // CARGA AL FORMULARIO:

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


    // SI SE ENCUENTRAN CASOS ANTERIORES:

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

    // REGISTRAR EL CASO:

    public static function casos_enviar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            date_default_timezone_set('America/Caracas');
            $_POST['fecha'] = date('Y-m-d H:i:s');
            $_POST['ci_user'] = $_SESSION['ci'];
            $_POST['estado'] = 'Sin Atender';
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


    // REDIRIGIR AL SER EXITOSO EL REGISTRO:

    public static function felicidades_casos (){
        require_once 'vistas/felicidades_casos.php';
    }
    

    // ATENDER EL CASO:

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

    // CONTINUAR EL CASO:

    public static function continuar_caso(){
        if(isset($_GET['id_caso']) && isset($_GET['direccion']) && isset($_GET['ci'])){
            $id_caso = $_GET['id_caso'];
            $direccion = $_GET['direccion'];
            $ci = $_GET['ci'];
            AtencionModelo::actualizar_oficina($direccion,$id_caso);
            switch($direccion){
                case 'Desarrollo Social':
                        $data = self::obtenerDatosBeneficiario($ci);
                        extract($data); // crea $data_exists, $datos_beneficiario, etc.
                        require_once 'vistas/caso_desarrollo.php';
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

    // MARCAR VISTAS DE LOS CASOS EN NOTIFICACIONES MAIN

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

    // FILTRAR CASO

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

    // CASO CONTINUAR, CARGAR FORMULARIO DE CADA OFICINA: 

    public static function caso_continuar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            $direccion = $_POST['direccion'];
            $id_caso = $_POST['id_caso'];
            $ci = $_POST['ci'];
                switch($direccion){
                    case 'Desarrollo Social':
                        $res = AtencionModelo::caso_continuar($_POST);
                        $oficina = 'Despacho';
                        AtencionModelo::actualizar_oficina($oficina,$id_caso);
                        if($res['exito']){
                            header("Location: ".BASE_URL."/felicidades_caso_continuado");
                        }
                        else{
                            $msj = 'Ocurrió un error '.$res['error'];
                            $data = self::obtenerDatosBeneficiario($ci);
                            extract($data); // crea $data_exists, $datos_beneficiario, etc.
                            require_once 'vistas/caso_desarrollo.php';
                        }
                        break;
                }

        }
        else{
            $msj = 'Ocurrio un error (POST), Intenta volver a enviar la solicitud';
            require_once 'vistas/caso_desarrollo.php';
        }
    }

    // REDIRIGIR AL ENVIAR EL FORMULARIO DE CADA OFICINA 

    public static function felicidades_caso_continuado(){
        require_once 'vistas/felicidades_caso_continuado.php';
    }

    // LISTA DE CASOS EN PROCESOS:

    public static function casos_procesos_lista(){
        $id_rol = $_SESSION['id_rol'];
        $res = AtencionModelo::casos_procesos($id_rol);
        if($res['exito']){
            $datos = $res['datos'] ?? null;
            if(isset($res['error'])){
                $msj = $res['error'];
            }
       }
       else{
        $msj = 'Ocurrió un error mostrando los casos en proceso';
       }
        require_once 'vistas/casos_procesos_lista.php';
    }

    // ACCIONES DE DESPACHO BOTONES:

    public static function accion(){
        if(isset($_GET['id_caso']) && isset($_GET['accion'])){
            $id_caso = $_GET['id_caso'];
            $accion = $_GET['accion'];
            switch($accion){
                case 'informado':
                    $accion_new = 'Informado';
                    break;
                case 'negar':
                    $accion_new = 'Informado - Negado';
                    break;
                case 'aprobar':
                    $accion_new = 'Informado - Aprobado';
                    break;
                case 'diferir':
                    $accion_new = 'Informado - Diferido';
                    break;
            }
            $res = AtencionModelo::actualizar_caso($id_caso,$accion_new);
            if($res['exito']){
                header("Location:".BASE_URL."/casos_procesos_lista?msj=Caso actualizado con éxito");
            }
            else{
                $msj = 'Ocurrió un error actualizando el caso '.$res['error'];
            }
        }
        else{
            $msj = 'Ocurrió un error, no se recibió la acción';
        }
        require_once 'vistas/casos_procesos_lista.php';
    }

    // VISTA CON SU CARGA DE DATOS PARA EDITAR UN CASO

    public static function editar_caso(){
        if(isset($_GET['id_caso'])){
            $id_caso = $_GET['id_caso'];
            $resultado = AtencionModelo::edicion_vista($id_caso);
            if($resultado){
                $datos = $resultado['datos'];
            }
            else{
                $msj = 'Ocurrió un  en el precesamiento del id_des';
            }
        }
        require_once 'vistas/casos_editar.php';
    }

    public static function editar_caso_enviar(){
        if(isset($_POST['id_caso'])){
        date_default_timezone_set('America/Caracas');
        $_POST['fecha'] = date('Y-m-d H:i:s');
        $_POST['ci_user'] = $_SESSION['ci'];
        $id_caso = $_POST['id_caso'];
        $resultado = AtencionModelo::edicion_enviar($_POST);
            if($resultado['exito']){
                header('Location: '.BASE_URL.'/casos_lista?msj=Caso editado con éxito!');
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d H:i:s');
                $accion = 'Editó información del caso';
                Procesar::registrarReporte($id_caso,$fecha,$accion,$_SESSION['ci']);
            }
            else{
                $msj = "Error" . $resultado['error'];
                $id_caso = $_POST['id_caso'] ?? null;
                if($id_caso){
                    $resultado = AtencionModelo::edicion_vista($id_caso);
                    if($resultado){
                        $datos=$resultado['datos'];
                    }
                }
                require_once 'vistas/casos_editar.php';
            }               
        }
        else{
            $msj = 'Surgió un error obteniendo datos (POST)';
        }
    }

}
?>