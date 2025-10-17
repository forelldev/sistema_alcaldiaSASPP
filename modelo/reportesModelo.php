<?php 
require_once 'conexiondb.php';
class reportesModelo{

    public static function mostrarReportes($pagina = 1, $porPagina = 10) {
        try {
            $conexion = DB::conectar();
            $offset = ($pagina - 1) * $porPagina;

            // Obtener total de registros
            $totalConsulta = $conexion->query("SELECT COUNT(*) FROM reportes_acciones");
            $totalRegistros = $totalConsulta->fetchColumn();
            $consulta = "
                SELECT r.*, ui.nombre
                FROM reportes_entradas r
                INNER JOIN usuarios_info ui ON r.ci = ui.ci
                ORDER BY r.fecha_entrada DESC
                LIMIT :limite OFFSET :offset
            ";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindValue(':limite', $porPagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return [
                'exito' => true,
                'datos' => $datos,
                'total' => $totalRegistros,
                'pagina' => $pagina,
                'porPagina' => $porPagina
            ];
        } catch (PDOException $e) {
            return ['exito' => false, 'mensaje' => $e->getMessage()];
        }
    }

    public static function mostrarReportesAcciones($pagina = 1, $porPagina = 10) {
        try {
            $conexion = DB::conectar();
            $offset = ($pagina - 1) * $porPagina;

            // Obtener total de registros
            $totalConsulta = $conexion->query("SELECT COUNT(*) FROM reportes_acciones");
            $totalRegistros = $totalConsulta->fetchColumn();

            // Obtener registros paginados
            $consulta = "
                SELECT ra.*, ui.nombre
                FROM reportes_acciones ra
                INNER JOIN usuarios_info ui ON ra.ci = ui.ci
                ORDER BY ra.fecha DESC
                LIMIT :limite OFFSET :offset
            ";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindValue(':limite', $porPagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'exito' => true,
                'datos' => $datos,
                'total' => $totalRegistros,
                'pagina' => $pagina,
                'porPagina' => $porPagina
            ];
        } catch (PDOException $e) {
            return ['exito' => false, 'mensaje' => $e->getMessage()];
        }
    }


    public static function mostrarLimites() {
        try {
        $conexion = DB::conectar();
        $consulta = "SELECT * FROM roles";
        $stmt = $conexion->prepare($consulta);
        $stmt->execute();
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agregar número de cuentas por cada rol
        foreach ($roles as &$rol) {
            $id_rol = $rol['id_rol'];
            $consultaUsuarios = "SELECT COUNT(*) AS num_cuentas FROM usuarios WHERE id_rol = :id_rol";
            $stmtUsuarios = $conexion->prepare($consultaUsuarios);
            $stmtUsuarios->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
            $stmtUsuarios->execute();
            $resultadoUsuarios = $stmtUsuarios->fetch(PDO::FETCH_ASSOC);
            $rol['num_cuentas'] = $resultadoUsuarios['num_cuentas'] ?? 0;
        }

        return [
            'exito' => !empty($roles),
            'datos' => $roles,
            'mensaje' => empty($roles) ? 'No se encontraron roles' : null
        ];
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'mensaje' => 'Error en la consulta: ' . $e->getMessage()
        ];
    }
    }

    public static function cargarDatos($id_rol){
        $conexion = DB::conectar();
            $stmt =$conexion->prepare("SELECT * FROM roles WHERE id_rol = ?");
            $stmt->execute([$id_rol]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($resultado) {
                    // Usuario encontrado, devolver datos
                    return [
                        'exito' => true,
                        'datos' => $resultado
                    ];
                } else {
                    // No se encontró el usuario
                    return [
                        'exito' => false,
                        'mensaje' => 'Ocurrió un error realizando la búsqueda'
                    ];
                }
        }

        public static function actualizarLimite($id_rol, $nuevo_limite) {
            try {
                $conexion = DB::conectar();
                $consulta = "UPDATE roles SET limite = :limite WHERE id_rol = :id_rol";
                $stmt = $conexion->prepare($consulta);
                $stmt->bindParam(':limite', $nuevo_limite, PDO::PARAM_INT);
                $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
                $stmt->execute();

                return ['exito' => true];
            } catch (PDOException $e) {
                return ['exito' => false, 'mensaje' => $e->getMessage()];
            }
        }

        public static function contarUsuariosPorRol($id_rol) {
            try {
                $conexion = DB::conectar();
                $consulta = "SELECT COUNT(*) AS total FROM usuarios WHERE id_rol = :id_rol";
                $stmt = $conexion->prepare($consulta);
                $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
                $stmt->execute();
                $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return ['exito' => true, 'datos' => $datos];
            } catch (PDOException $e) {
                return ['exito' => false, 'mensaje' => $e->getMessage()];
            }
    }

        public static function obtenerUsuariosPorRol($id_rol) {
            try {
                $conexion = DB::conectar();
                $consulta = "
                    SELECT u.*, ui.nombre 
                    FROM usuarios u
                    INNER JOIN usuarios_info ui ON u.ci = ui.ci
                    WHERE u.id_rol = :id_rol
                    ORDER BY u.ci DESC
                ";
                $stmt = $conexion->prepare($consulta);
                $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
                $stmt->execute();
                $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return ['exito' => true, 'datos' => $datos];
            } catch (PDOException $e) {
                return ['exito' => false, 'mensaje' => $e->getMessage()];
            }
        }


        public static function eliminarUsuario($ci) {
            try {
                $conexion = DB::conectar();
                $consulta = "DELETE FROM usuarios WHERE ci = :ci";
                $stmt = $conexion->prepare($consulta);
                $stmt->bindParam(':ci', $ci, PDO::PARAM_INT);
                $stmt->execute();

                return ['exito' => true];
            } catch (PDOException $e) {
                return ['exito' => false, 'mensaje' => $e->getMessage()];
            }
        }

        public static function fecha_filtro($datos) {
            $conexion = DB::conectar();
            $fecha_inicio = $datos['fecha_inicio'];
            $fecha_final = $datos['fecha_final'];
            try {
                $stmt = $conexion->prepare("
                    SELECT re.*, ui.nombre 
                    FROM reportes_entradas re
                    INNER JOIN usuarios_info ui ON re.ci = ui.ci
                    WHERE re.fecha_entrada >= :fecha_inicio 
                    AND re.fecha_salida <= :fecha_final
                    ORDER BY re.fecha_entrada DESC, re.fecha_salida DESC
                ");

                $stmt->bindParam(':fecha_inicio', $fecha_inicio);
                $stmt->bindParam(':fecha_final', $fecha_final);
                $stmt->execute();
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return [
                    'exito' => true,
                    'datos' => $resultados
                ];
            } catch (Exception $e) {
                error_log("Error al filtrar reportes por fecha: " . $e->getMessage());
                return [
                    'exito' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

        public static function filtro_acciones($fecha,$id_rol) {
            $conexion = DB::conectar();
            $fecha_inicio = $fecha . ' 00:00:00';
            $fecha_fin = $fecha . ' 23:59:59';
            try {
                $stmt = $conexion->prepare("
                    SELECT ra.*, u.id_rol, ui.nombre
                    FROM reportes_acciones ra
                    INNER JOIN usuarios u ON ra.ci = u.ci
                    INNER JOIN usuarios_info ui ON ra.ci = ui.ci
                    WHERE ra.fecha BETWEEN :fecha_inicio AND :fecha_fin
                    AND (:id_rol = 0 OR u.id_rol = :id_rol)
                ");

                $stmt->bindParam(':fecha_inicio', $fecha_inicio);
                $stmt->bindParam(':fecha_fin', $fecha_fin);
                $stmt->bindParam(':id_rol', $id_rol);
                $stmt->execute();
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return [
                    'exito' => true,
                    'datos' => $resultados
                ];
            } catch (Exception $e) {
                error_log("Error al filtrar reportes por fecha: " . $e->getMessage());
                return [
                    'exito' => false,
                    'error' => $e->getMessage()
                ];
            }
        }


}

?>
