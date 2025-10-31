<?php 
require_once 'modelo/loginModel.php';
require_once 'modelo/notificacionesModelo.php';
class LoginControl {
    public function ingresar() {
        // Verifica si la sesión ya está iniciada antes de llamar a session_start
        $ci = $_POST['ci'] ?? null;
        $clave = $_POST['clave'] ?? null;
        $respuesta = UserModel::verificarCredenciales($ci, $clave);
        if ($respuesta['status'] === 'ok') {
            $usuarioDatos = $respuesta['usuario'];
            $_SESSION['ci'] = $usuarioDatos['ci'];
            $_SESSION['id_rol'] = $usuarioDatos['id_rol'];
            $_SESSION['rol'] = UserModel::rol($_SESSION['id_rol']);
            $_SESSION['sesion'] = $usuarioDatos['sesion'];
            date_default_timezone_set('America/Caracas');
            $fecha_entrada = date('Y-m-d H:i:s');
            $_SESSION['id_sesion'] = UserModel::registrarEntrada($usuarioDatos['ci'],$fecha_entrada);
            header('Location: '.BASE_URL.'/main');
            exit;
        } else {
            // Redirigir con msj personalizado
            $msj = urlencode($respuesta['msj']);
            header('Location: '.BASE_URL.'/?msj=' . $msj);
            exit;
        }
    }

    public function index() {
        if (isset($_SESSION['ci'])) {
            header('Location: '.BASE_URL.'/main');
            exit;
        }
        require_once 'vistas/login.php';
    }

    public function main() {
        if (!isset($_SESSION['ci'])) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
        require_once 'vistas/main.php';
    }

    public function casos_ajax() {
    if (!isset($_SESSION['ci'])) {
        echo json_encode(['exito' => false, 'mensaje' => 'Sesión no iniciada']);
        return;
    }
        $notificaciones = Notificaciones::mostrar_notificaciones_casos();
        echo json_encode($notificaciones);
    }



    public function logout() {
        if (isset($_SESSION['ci'])) {
            date_default_timezone_set('America/Caracas');
            $ci = $_SESSION['ci'];
            $id = $_SESSION['id_sesion'] ?? null;
            $fecha_salida = date('Y-m-d H:i:s');
            // Registrar salida si hay ID de sesión
            if ($id) {
                UserModel::registrarSalida($id, $fecha_salida);
            }
            // Registrar cierre de sesión
            UserModel::logOut($ci);
            // Cerrar sesión de forma segura
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();      // Elimina todas las variables de sesión
                session_destroy();    // Destruye la sesión actual
                session_write_close(); // Libera el archivo de sesión
            }
            // Redirigir al inicio
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

public function validarSesionAjax() {
    header('Content-Type: application/json');
    if (isset($_SESSION['ci'])) {
        $activa = UserModel::sesionValidar($_SESSION['ci']);

        if ($activa) {
            echo json_encode(['sesionActiva' => true]);
        } else {
            date_default_timezone_set('America/Caracas');
            $fecha_salida = date('Y-m-d H:i:s');
            $id = $_SESSION['id_sesion'];
            UserModel::registrarSalida($id,$fecha_salida);
            // ⚠️ Sesión marcada como inactiva en la base de datos
            session_unset();      // Elimina variables de sesión
            session_destroy();    // Destruye la sesión
            setcookie(session_name(), '', time() - 3600, '/'); // Elimina cookie
            echo json_encode(['sesionActiva' => false]);
        }
    } else {
        // No hay sesión en PHP
        session_destroy();
        echo json_encode(['sesionActiva' => false]);
    }
}
            // MÉTODOS DE REGISTRO
    public static function registro() {
        $ci = $_POST['ci'] ?? null;
        $clave = $_POST['clave'] ?? null;
        $nombre = $_POST['nombre'] ?? null;
        $apellido = $_POST['apellido'] ?? null;
        $id_rol = $_POST['id_rol'] ?? null;
        $correo = $_POST['correo'] ?? null;
        $sesion = 'False'; // Valor inicial de sesión

        $msj = '';

        // Lista de campos requeridos con nombres legibles
        $campos = [
            'ci' => 'Cédula',
            'clave' => 'Clave',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'id_rol' => 'Rol',
            'correo' => 'Correo'
        ];

        // Verificar campos faltantes
        $faltantes = [];
        foreach ($campos as $claveCampo => $nombreCampo) {
            if (!isset($_POST[$claveCampo]) || $_POST[$claveCampo] === '') {
                $faltantes[] = $nombreCampo;
            }
        }

        if (empty($faltantes)) {
            $claveHash = password_hash($clave, PASSWORD_DEFAULT);
            $resultado = UserModel::crearCuenta($ci, $claveHash, $nombre, $apellido, $id_rol, $sesion);

            if (str_starts_with($resultado, 'error_sql:')) {
                $msj = "❌ Error SQL: " . substr($resultado, strlen('error_sql:'));
            } else {
                switch ($resultado) {
                    case 'exito':
                        $msj = "✅ Usuario registrado correctamente.";
                        break;
                    case 'usuario_existente':
                        $msj = "❌ Error: el usuario con esta Cédula de Identidad ya existe.";
                        break;
                    case 'limite_superado':
                        $msj = "🚫 Error: se ha alcanzado el límite de usuarios para este rol.";
                        break;
                    case 'rol_invalido':
                        $msj = "⚠️ Error: el rol seleccionado no es válido.";
                        break;
                    default:
                        $msj = "❌ Error desconocido al registrar el usuario.";
                        break;
                }
            }
        } else {
            $msj = "⚠️ Faltan los siguientes campos: " . implode(', ', $faltantes) . ".";
        }

        require_once 'vistas/registro.php';
    }



        public static function registroIndex() {
            if (!isset($_SESSION['ci'])) {
            header('Location: '.BASE_URL.'/');
            exit;
        }
            require_once 'vistas/registro.php';
        }

        public static function solicitud_notificacion(){
            if(isset($_GET['id_doc'])){
                $id_doc = $_GET['id_doc'];
                $id_name = $_GET['id_name'];
                $tabla = $_GET['tabla'];
                $notificaciones = Notificaciones::mostrar_notis($id_doc);
                if($notificaciones['exito']){
                    $datos = $notificaciones['datos'];
                    if($datos['visto'] == 0){
                        $marcar_vista = Notificaciones::marcar_vista_uno($id_doc,$id_name,$tabla);
                    }
                }
                // Validar si hubo error
                else {
                    $msj = 'Ocurrió un error '.$notificaciones['mensaje'];
                }
                // Extraer los datos si la búsqueda fue exitosa
                switch($id_name){
                    case 'id_doc':
                        $acciones = [
                            'En espera del documento físico para ser procesado 0/3' => 'Aprobar para su procedimiento',
                            'En Proceso 1/3' => 'Enviar a despacho',
                            'En Proceso 2/3' => 'Enviar a Administración',
                            'En Proceso 3/3 (Sin entregar)' => 'Finalizar Solicitud (Se Entregó la ayuda)',
                            'Solicitud Finalizada (Ayuda Entregada)' => 'Reiniciar en caso de algún error'
                        ];
                        break;
                    case 'id_des':
                        $acciones = [
                            'En espera del documento físico para ser procesado 0/2' => 'Aprobar para su procedimiento',
                            'En Proceso 1/2' => 'Aprobar Ayuda',
                            'En Proceso 2/2 (Sin entregar)' => 'Finalizar Solicitud (Se entregó la ayuda)',
                            'Solicitud Finalizada (Ayuda Entregada)' => 'Reiniciar en caso de algún error'
                        ];
                        break;
                    case 'id_despacho':
                        $acciones = [
                            'En Revisión 1/2' => 'Enviar a Administración',
                            'En Proceso 2/2 (Sin entregar)' => 'Finalizar Solicitud (Se entregó la ayuda)',
                            'Solicitud Finalizada (Ayuda Entregada)' => 'Reiniciar en caso de algún error'
                        ];
                        break;
                }
                // Pasar los datos a la vista
            }else{
                $msj = 'Ocurrió un error al recibir los datos (GET)';
            }
            require_once 'vistas/solicitud.php';
        }
        public static function marcar_vistas(){
            Notificaciones::marcar_vista();
            // Capturar el resultado de las notificaciones
            $notificaciones = Notificaciones::mostrarNotificaciones($_SESSION['id_rol']);
            
            // Validar si hubo error
            if ($notificaciones === false || !isset($notificaciones['exito'])) {
                echo 'false error';
                return;
            }
            // Extraer los datos si la búsqueda fue exitosa
            $datos = $notificaciones['exito'] ? $notificaciones['datos'] : [];
            require_once 'vistas/main.php';
        }

        public static function marcar_vistasDespacho(){
            Notificaciones::marcar_vistaDespacho();
            // Capturar el resultado de las notificaciones
            $notificaciones = Notificaciones::mostrarNotificaciones($_SESSION['id_rol']);

            // Validar si hubo error
            if ($notificaciones === false || !isset($notificaciones['exito'])) {
                echo 'false error';
                return;
            }
            // Extraer los datos si la búsqueda fue exitosa
            $datos = $notificaciones['exito'] ? $notificaciones['datos'] : [];
            require_once 'vistas/main.php';
        }

        public static function recuperacion_clave(){
            require_once 'vistas/recuperacion_clave.php';
        }

        public static function recuperar_clave(){
            if (isset($_POST['correo'])) {
                $correo = $_POST['correo'];
                $resultado = UserModel::verificar_correo($correo);
                if ($resultado && $resultado['existe']) {
                    $_SESSION['ci_recuperacion'] = $resultado['ci_recuperacion']; // Guardar la CI si quieres usarla después
                    $generar_codigo = UserModel::generar_codigo($_SESSION['ci_recuperacion']);
                    if(isset($generar_codigo['retorno']) && $generar_codigo['retorno'] == 'retorno'){
                        $msj = $generar_codigo['msj'];
                        require_once 'vistas/recuperar_clave.php';
                    }
                    else if($generar_codigo['success']){
                        $msj = $generar_codigo['msj'];
                        require_once 'vistas/recuperar_clave.php';
                    }
                    else{
                        $msj = $generar_codigo['msj'] ?? 'Error desconocido.';
                        require_once 'vistas/recuperacion_clave.php';
                    }
                } else {
                    $msj = 'Este correo no existe en ninguna cuenta!';
                    require_once 'vistas/recuperacion_clave.php';
                }
            }
        }


        public static function nueva_clave(){
            if(isset($_POST['codigo'])){
                $codigo = $_POST['codigo'];
                $resultado = UserModel::verificar_codigo($codigo,$_SESSION['ci_recuperacion']);
                    if($resultado['success']){
                        require_once 'vistas/nueva_clave.php';
                    }
                    else if(isset($resultado['reset']) && $resultado['reset'] == 'reset'){
                        unset($_SESSION['ci_recuperacion']);
                        $msj = $resultado['msj'];
                        header('Location: '.BASE_URL.'/?msj='.$msj);
                    }
                    else{
                        $msj = $resultado['msj'];
                        require_once 'vistas/recuperar_clave.php';
                    }
            }
            }
        

        public static function actualizar_clave(){
            if(isset($_POST['clave_nueva']) && isset($_POST['confirmar_clave'])){
                $clave_nueva = $_POST['clave_nueva'];
                $confirmar_clave = $_POST['confirmar_clave'];
                if($clave_nueva === $confirmar_clave){
                    UserModel::cambiar_clave($clave_nueva,$_SESSION['ci_recuperacion']);
                    unset($_SESSION['ci_recuperacion']);
                    require_once 'vistas/felicidades_clave.php';
                }
                else{
                    $msj = 'Las contraseñas no coinciden, vuelve a intentarlo';
                    require_once 'vistas/nueva_clave.php';
                }
            }
        }
    }
?>