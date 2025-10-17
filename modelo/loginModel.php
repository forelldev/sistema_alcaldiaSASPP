<?php 
require_once 'conexiondb.php';
require_once 'correoModelo.php';
class UserModel {
    public static function verificarCredenciales($ci, $clave){
        $conexion = DB::conectar();
        // Verificar si ese usuario ya tiene sesión activa
        $sqlSesion = "SELECT sesion FROM usuarios WHERE ci = :ci";
        $stmtSesion = $conexion->prepare($sqlSesion);
        $stmtSesion->bindParam(':ci', $ci);
        $stmtSesion->execute();
        $estadoSesion = $stmtSesion->fetchColumn();
        if ($estadoSesion === 'True') {
            // Cerrar sesión si ya estaba activa
            $cerrarSesion = "UPDATE usuarios SET sesion = 'False' WHERE ci = :ci";
            $stmtCerrar = $conexion->prepare($cerrarSesion);
            $stmtCerrar->bindParam(':ci', $ci);
            $stmtCerrar->execute();
                $actualizarSalida = "
                    UPDATE reportes_entradas
                    SET fecha_salida = NOW()
                    WHERE ci = :ci AND fecha_salida = '0000-00-00 00:00:00'
                ";
                $stmtSalida = $conexion->prepare($actualizarSalida);
                $stmtSalida->bindParam(':ci', $ci);
                $stmtSalida->execute();
            return ['status' => 'cerrada', 'msj' => '¡Había una sesión activa, y se cerró correctamente!'];
        }
        // Verificar credenciales
        $sql = 'SELECT * FROM usuarios WHERE ci = :ci';
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':ci', $ci);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado && password_verify($clave, $resultado['clave'])) {
            // Iniciar sesión
            $sesion = 'True';
            $consulta = "UPDATE usuarios SET sesion = :sesion WHERE ci = :ci";
            $cons = $conexion->prepare($consulta);
            $cons->bindParam(':sesion', $sesion);    
            $cons->bindParam(':ci', $ci);
            $cons->execute();

            return ['status' => 'ok', 'usuario' => $resultado];
        }
        return ['status' => 'error', 'msj' => '¡Credenciales incorrectas!'];
    }

    public static function crearCuenta($ci, $claveHash, $nombre, $apellido, $id_rol, $sesion) {
        $conexion = DB::conectar();

        // Verificar si el usuario ya existe
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE ci = :ci");
        $stmt->bindParam(':ci', $ci);
        $stmt->execute();

        if ($stmt->fetch()) {
            return 'usuario_existente';
        }

        // Verificar límite de usuarios para el rol
        $stmtLimite = $conexion->prepare("SELECT limite FROM roles WHERE id_rol = :id_rol");
        $stmtLimite->bindParam(':id_rol', $id_rol);
        $stmtLimite->execute();
        $limiteData = $stmtLimite->fetch(PDO::FETCH_ASSOC);

        if (!$limiteData) {
            return 'rol_invalido';
        }

        $limite = (int)$limiteData['limite'];

        // Contar usuarios actuales con ese rol
        $stmtContador = $conexion->prepare("SELECT COUNT(*) as total FROM usuarios WHERE id_rol = :id_rol");
        $stmtContador->bindParam(':id_rol', $id_rol);
        $stmtContador->execute();
        $totalUsuarios = (int)$stmtContador->fetch(PDO::FETCH_ASSOC)['total'];

        if ($totalUsuarios >= $limite) {
            return 'limite_superado';
        }

        try {
            // Iniciar transacción
            $conexion->beginTransaction();

            // Insertar en tabla usuarios
            $sqlUsuarios = "INSERT INTO usuarios (ci, clave, id_rol, sesion) 
                            VALUES (:ci, :clave, :id_rol, :sesion)";
            $stmtUsuarios = $conexion->prepare($sqlUsuarios);
            $stmtUsuarios->bindParam(':ci', $ci);
            $stmtUsuarios->bindParam(':clave', $claveHash);
            $stmtUsuarios->bindParam(':id_rol', $id_rol);
            $stmtUsuarios->bindParam(':sesion', $sesion);
            $stmtUsuarios->execute();

            // Insertar en tabla usuarios_info
            $sqlInfo = "INSERT INTO usuarios_info (ci, nombre, apellido) 
                        VALUES (:ci, :nombre, :apellido)";
            $stmtInfo = $conexion->prepare($sqlInfo);
            $stmtInfo->bindParam(':ci', $ci);
            $stmtInfo->bindParam(':nombre', $nombre);
            $stmtInfo->bindParam(':apellido', $apellido);
            $stmtInfo->execute();

            // Confirmar transacción
            $conexion->commit();
            return 'exito';

        } catch (PDOException $e) {
            $conexion->rollBack();
            error_log("Error al registrar usuario: " . $e->getMessage());
            return 'error_sql: ' . $e->getMessage(); // TEMPORAL para depuración
            }
    }
    
    public static function logOut($ci){
            $conexion = DB::conectar();
            $sesion = 'False';
            $consulta = "UPDATE usuarios SET sesion = :sesion WHERE ci = :ci";
            $cons = $conexion->prepare($consulta);
            $cons->bindParam(':sesion',$sesion);
            $cons ->bindParam(':ci',$ci);
            $cons->execute();
    }

    public static function sesionValidar($ci){
        $conexion = DB::conectar();
        $consulta = "SELECT sesion FROM usuarios WHERE ci = :ci";
        $cons = $conexion->prepare($consulta);
        $cons ->bindParam(':ci',$ci);
        $cons->execute();
        $estado = $cons->fetchColumn();
        // Retornar true si la sesión está activa
        return $estado === 'True';
    }

    public static function rol($id_rol){
        $conexion = DB::conectar();
        $consulta = "SELECT nombre_rol FROM roles WHERE id_rol = :id_rol";
        $cons = $conexion->prepare($consulta);
        $cons->bindParam(':id_rol',$id_rol);
        $cons->execute();
        $rol = $cons->fetchColumn();
        return $rol;
    }

    public static function registrarEntrada($ci,$fecha_entrada){
        $conexion = DB::conectar();
        $consulta = "INSERT INTO reportes_entradas (ci,fecha_entrada) VALUES (:ci,:fecha_entrada)";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':ci',$ci);
        $stmt->bindParam(':fecha_entrada',$fecha_entrada);
        $stmt->execute();
        $id = $conexion->lastInsertId();
        return $id;
    }

    public static function registrarSalida($id,$fecha_salida){
        $conexion = DB::conectar();
        $consulta = "UPDATE reportes_entradas SET fecha_salida = :fecha_salida WHERE id = :id";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':fecha_salida',$fecha_salida);
        $stmt->execute();
    }

    public static function verificar_correo($correo){
        $conexion = DB::conectar();
        $consulta = "SELECT ci FROM usuarios_info WHERE correo = :correo";
        $cons = $conexion->prepare($consulta);
        $cons->bindParam(':correo', $correo);
        $cons->execute();

        if ($cons->rowCount() > 0) {
            $resultado = $cons->fetch(PDO::FETCH_ASSOC);
            return ['existe' => true, 'ci_recuperacion' => $resultado['ci']];
        } else {
            return ['existe' => false];
        }
    }



    public static function generar_codigo($ci){
        $conexion = DB::conectar();
        // Verificar si ya existe un intento de recuperación
        $verificar = $conexion->prepare("SELECT intentos FROM usuarios_recuperacion WHERE ci = :ci");
        $verificar->bindParam(':ci', $ci);
        $verificar->execute();

        if ($verificar->rowCount() > 0) {
            $datos = $verificar->fetch(PDO::FETCH_ASSOC);
            $intentos = $datos['intentos'];
            $msj = "Ya hay un intento de recuperación! te quedan $intentos intentos.";
            return ['success' => false, 'msj' => $msj, 'retorno' => 'retorno'];
        }

        // Obtener correo y nombre desde usuarios_info
        $datos_usuario = self::obtener_datos_por_ci($ci);
        if (!$datos_usuario) {
            return ['success' => false, 'msj' => 'No se encontró el usuario con esa CI.'];
        }
        $correo = $datos_usuario['correo'];
        $nombre = $datos_usuario['nombre'];

        // Generar código y registrar intento
        $codigo = rand(100000, 999999);
        $intentos = 3;
        $insertar = $conexion->prepare("INSERT INTO usuarios_recuperacion (ci, codigo, intentos) VALUES (:ci, :codigo, :intentos)");
        $insertar->bindParam(':ci', $ci);
        $insertar->bindParam(':codigo', $codigo);
        $insertar->bindParam(':intentos', $intentos);
        $insertar->execute();

        // Enviar el código por correo
        $res = Correo::correoClave($correo, $nombre, $codigo);

        if ($res) {
            return ['success' => true, 'msj' => 'Se ha enviado un código a tu correo!'];
        } else {
            return ['success' => false, 'msj' => 'Error al enviar el correo.'];
        }
    }


    public static function obtener_datos_por_ci($ci){
        $conexion = DB::conectar();
        $consulta = "SELECT correo, nombre FROM usuarios_info WHERE ci = :ci";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':ci', $ci);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public static function verificar_codigo($codigo, $ci){
        $conexion = DB::conectar();

        // Verificar si el código existe para ese CI
        $consulta = "SELECT * FROM usuarios_recuperacion WHERE codigo = :codigo AND ci = :ci";
        $cons = $conexion->prepare($consulta);
        $cons->bindParam(':codigo', $codigo);
        $cons->bindParam(':ci', $ci);
        $cons->execute();

        if ($cons->rowCount() > 0) {
            // Código válido, eliminar el intento
            $eliminar = $conexion->prepare("DELETE FROM usuarios_recuperacion WHERE ci = :ci");
            $eliminar->bindParam(':ci', $ci);
            $eliminar->execute();

            return ['success' => true];
        } else {
            // Buscar intentos restantes por CI
            $buscar = $conexion->prepare("SELECT intentos FROM usuarios_recuperacion WHERE ci = :ci");
            $buscar->bindParam(':ci', $ci);
            $buscar->execute();

            if ($buscar->rowCount() > 0) {
                $datos = $buscar->fetch(PDO::FETCH_ASSOC);
                $intentos = $datos['intentos'];

                // Si los intentos son 0, eliminar el registro
                if ($intentos <= 1) {
                    $borrar = $conexion->prepare("DELETE FROM usuarios_recuperacion WHERE ci = :ci");
                    $borrar->bindParam(':ci', $ci);
                    $borrar->execute();

                    return ['success' => false, 'msj' => 'Se agotaron los intentos. Debes iniciar una nueva recuperación.', 'reset' => 'reset'];
                }

                // Reducir intentos en 1
                $nuevo_intento = $intentos - 1;
                $actualizar = $conexion->prepare("UPDATE usuarios_recuperacion SET intentos = :nuevo_intento WHERE ci = :ci");
                $actualizar->bindParam(':nuevo_intento', $nuevo_intento);
                $actualizar->bindParam(':ci', $ci);
                $actualizar->execute();

                $msj = ($nuevo_intento == 1)
                    ? 'No es el código, te queda un último intento'
                    : "No es el código, te quedan $nuevo_intento intentos.";

                return ['success' => false, 'msj' => $msj];
            } else {
                return ['success' => false, 'msj' => 'No hay intento de recuperación activo.'];
            }
        }
    }

    public static function cambiar_clave($clave, $ci){
        $conexion = DB::conectar();

        // Hashear la nueva contraseña
        $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $consulta = "UPDATE usuarios SET clave = :clave WHERE ci = :ci";
        $cons = $conexion->prepare($consulta);
        $cons->bindParam(':clave', $clave_hash);
        $cons->bindParam(':ci', $ci);
        $cons->execute();

        // Eliminar cualquier intento de recuperación activo
        $borrar = $conexion->prepare("DELETE FROM usuarios_recuperacion WHERE ci = :ci");
        $borrar->bindParam(':ci', $ci);
        $borrar->execute();
    }
}
?>