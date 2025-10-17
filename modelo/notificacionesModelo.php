<?php
require_once 'conexiondb.php';
  class Notificaciones {
    public static function mostrarNotificaciones($rol) {
        try {
            $conexion = DB::conectar();
            $resultadoFinal = [];
            // Definir estados según el rol
            switch ($rol) {
                case 1:
                    $estado = ['En espera del documento físico para ser procesado 0/3', 'En Proceso 1/3'];
                    break;
                case 2:
                    $estado = ['En Proceso 2/3'];
                    break;
                case 3:
                    $estado = ['En Proceso 3/3 (Sin entregar)'];
                    break;
                case 4:
                    $estado = [
                        'En espera del documento físico para ser procesado 0/3',
                        'En Proceso 1/3',
                        'En Proceso 2/3',
                        'En Proceso 3/3',
                        'Solicitud Finalizada (Ayuda Entregada)'
                    ];
                    break;
                default:
                    return [
                        'exito' => false,
                        'mensaje' => 'Rol no válido'
                    ];
            }

            // Consulta de solicitud de ayuda con detalles
            if (!empty($estado)) {
                $placeholders = implode(',', array_fill(0, count($estado), '?'));
                $consultaAyuda = "
                    SELECT 
                        sa.*, 
                        saf.fecha, saf.fecha_modificacion, saf.visto,
                        sc.correo_enviado,
                        cat.tipo_ayuda, cat.categoria,
                        des.descripcion, des.promotor, des.observaciones
                    FROM solicitud_ayuda sa
                    LEFT JOIN solicitud_ayuda_fecha saf ON sa.id_doc = saf.id_doc
                    LEFT JOIN solicitud_ayuda_correo sc ON sa.id_doc = sc.id_doc
                    LEFT JOIN solicitud_categoria cat ON sa.id_doc = cat.id_doc
                    LEFT JOIN solicitud_descripcion des ON sa.id_doc = des.id_doc
                    WHERE saf.visto = 0 
                    AND sa.estado IN ($placeholders)
                    AND sa.invalido = 0
                    ORDER BY saf.fecha DESC
                ";

                $busquedaAyuda = $conexion->prepare($consultaAyuda);
                $busquedaAyuda->execute($estado);
                $resultadoAyuda = $busquedaAyuda->fetchAll(PDO::FETCH_ASSOC);

                if ($resultadoAyuda) {
                    $resultadoFinal['ayuda'] = [
                        'tabla' => 'solicitud_ayuda_fecha',
                        'id_name' => 'id_doc',
                        'datos' => $resultadoAyuda
                    ];
                }
            }

            // Respuesta final
            if (!empty($resultadoFinal)) {
                return [
                    'exito' => true,
                    'datos' => $resultadoFinal
                ];
            } else {
                return [
                    'exito' => false,
                    'mensaje' => 'No se encontraron notificaciones'
                ];
            }
        } catch (Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al obtener notificaciones: ' . $e->getMessage()
            ];
        }
    }


    public static function mostrar_notificaciones_despacho() {
        try {
            $conexion = DB::conectar();
            $resultadoFinal = [];

            $consultaDespacho = "
                SELECT 
                    d.*, 
                    di.*, 
                    df.*,
                    dc.*
                FROM despacho d
                LEFT JOIN despacho_info di ON d.id_despacho = di.id_despacho
                LEFT JOIN despacho_fecha df ON d.id_despacho = df.id_despacho
                LEFT JOIN despacho_categoria dc ON d.id_despacho = dc.id_despacho
                WHERE df.visto = 0
                AND d.invalido = 0
                ORDER BY df.fecha DESC
            ";

            $busquedaDespacho = $conexion->prepare($consultaDespacho);
            $busquedaDespacho->execute();
            $resultadoDespacho = $busquedaDespacho->fetchAll(PDO::FETCH_ASSOC);

            if ($resultadoDespacho) {
                // Asignar id_doc a cada notificación individual
                foreach ($resultadoDespacho as &$item) {
                    $item['id_doc'] = $item['id_despacho'];
                }
                unset($item); // buena práctica para evitar referencias residuales

                $resultadoFinal['despacho'] = [
                    'tabla' => 'despacho_fecha',
                    'id_name' => 'id_despacho',
                    'datos' => $resultadoDespacho
                ];
            }


            if (!empty($resultadoFinal)) {
                return [
                    'exito' => true,
                    'datos' => $resultadoFinal
                ];
            } else {
                return [
                    'exito' => false,
                    'mensaje' => 'No se encontraron notificaciones'
                ];
            }

        } catch (Exception $e) {
            error_log("Error al obtener notificaciones de despacho: " . $e->getMessage());
            return [
                'exito' => false,
                'mensaje' => 'Error al obtener notificaciones: ' . $e->getMessage()
            ];
        }
    }

    public static function mostrar_notificaciones_desarrollo() {
        try {
            $conexion = DB::conectar();
            $resultadoFinal = [];

            $consultaDesarrollo = "
                SELECT 
                    sd.*, 
                    sdi.descripcion, sdi.creador,
                    sdt.categoria,
                    sdl.examen,
                    sdf.fecha, sdf.fecha_modificacion, sdf.visto
                FROM solicitud_desarrollo sd
                LEFT JOIN solicitud_desarrollo_info sdi ON sd.id_des = sdi.id_des
                LEFT JOIN solicitud_desarrollo_tipo sdt ON sd.id_des = sdt.id_des
                LEFT JOIN solicitud_desarrollo_laboratorio sdl ON sd.id_des = sdl.id_des
                LEFT JOIN solicitud_desarrollo_fecha sdf ON sd.id_des = sdf.id_des
                WHERE sdf.visto = 0
                AND sd.invalido = 0
                ORDER BY sdf.fecha DESC
            ";

            $busquedaDesarrollo = $conexion->prepare($consultaDesarrollo);
            $busquedaDesarrollo->execute();
            $resultadoDesarrollo = $busquedaDesarrollo->fetchAll(PDO::FETCH_ASSOC);

            if ($resultadoDesarrollo) {
            // Asignar id_doc a cada notificación individual
            foreach ($resultadoDesarrollo as &$item) {
                $item['id_doc'] = $item['id_des'];
            }
            unset($item); // buena práctica para evitar referencias residuales

            $resultadoFinal['desarrollo'] = [
                'tabla' => 'solicitud_desarrollo_fecha',
                'id_name' => 'id_des',
                'datos' => $resultadoDesarrollo
            ];
        }


            if (!empty($resultadoFinal)) {
                return [
                    'exito' => true,
                    'datos' => $resultadoFinal
                ];
            } else {
                return [
                    'exito' => false,
                    'mensaje' => 'No se encontraron notificaciones'
                ];
            }

        } catch (Exception $e) {
            error_log("Error al obtener notificaciones de desarrollo: " . $e->getMessage());
            return [
                'exito' => false,
                'mensaje' => 'Error al obtener notificaciones: ' . $e->getMessage()
            ];
        }
    }



   public static function mostrar_notis($id_doc) {
        try {
            $conexion = DB::conectar();

            $consulta = "
                SELECT 
                    sa.*, 
                    saf.fecha, saf.fecha_modificacion, saf.visto,
                    sc.correo_enviado,
                    cat.tipo_ayuda, cat.categoria,
                    des.descripcion, des.promotor, des.observaciones,
                    sol.nombre AS nombre,
                    sol.apellido AS apellido
                FROM solicitud_ayuda sa
                LEFT JOIN solicitud_ayuda_fecha saf ON sa.id_doc = saf.id_doc
                LEFT JOIN solicitud_ayuda_correo sc ON sa.id_doc = sc.id_doc
                LEFT JOIN solicitud_categoria cat ON sa.id_doc = cat.id_doc
                LEFT JOIN solicitud_descripcion des ON sa.id_doc = des.id_doc
                LEFT JOIN solicitantes sol ON sa.ci = sol.ci
                WHERE sa.id_doc = :id_doc
            ";


            $busqueda = $conexion->prepare($consulta);
            $busqueda->bindParam(':id_doc', $id_doc, PDO::PARAM_STR);
            $busqueda->execute();
            $resultado = $busqueda->fetchAll(PDO::FETCH_ASSOC);

            if ($resultado) {
                return [
                    'exito' => true,
                    'datos' => $resultado
                ];
            } else {
                return [
                    'exito' => false,
                    'mensaje' => 'No se encontraron datos para el ID proporcionado.'
                ];
            }
        } catch (PDOException $e) {
            error_log("Error en mostrar_notis: " . $e->getMessage());
            return [
                'exito' => false,
                'mensaje' => 'Error al realizar la búsqueda.'
            ];
        }
    }

    public static function mostrar_notis_desarrollo($id_doc) {
        try {
            $conexion = DB::conectar();

            $consulta = "
                SELECT 
                    sa.*, 
                    saf.fecha, saf.fecha_modificacion, saf.visto,
                    sc.correo_enviado,
                    cat.tipo_ayuda, cat.categoria,
                    des.descripcion, des.promotor, des.observaciones,
                    sol.nombre AS nombre,
                    sol.apellido AS apellido
                FROM solicitud_ayuda sa
                LEFT JOIN solicitud_ayuda_fecha saf ON sa.id_doc = saf.id_doc
                LEFT JOIN solicitud_ayuda_correo sc ON sa.id_doc = sc.id_doc
                LEFT JOIN solicitud_categoria cat ON sa.id_doc = cat.id_doc
                LEFT JOIN solicitud_descripcion des ON sa.id_doc = des.id_doc
                LEFT JOIN solicitantes sol ON sa.ci = sol.ci
                WHERE sa.id_doc = :id_doc
            ";


            $busqueda = $conexion->prepare($consulta);
            $busqueda->bindParam(':id_doc', $id_doc, PDO::PARAM_STR);
            $busqueda->execute();
            $resultado = $busqueda->fetchAll(PDO::FETCH_ASSOC);

            if ($resultado) {
                return [
                    'exito' => true,
                    'datos' => $resultado
                ];
            } else {
                return [
                    'exito' => false,
                    'mensaje' => 'No se encontraron datos para el ID proporcionado.'
                ];
            }
        } catch (PDOException $e) {
            error_log("Error en mostrar_notis: " . $e->getMessage());
            return [
                'exito' => false,
                'mensaje' => 'Error al realizar la búsqueda.'
            ];
        }
    }


    public static function marcar_vista() {
    $conexion = DB::conectar();
    $consulta = "UPDATE solicitud_ayuda_fecha SET visto = 1 WHERE visto = 0";
    $busqueda = $conexion->prepare($consulta);
    if ($busqueda->execute()) {
        $filas_afectadas = $busqueda->rowCount();
        return [
            'exito' => true,
            'datos' => ['filas_actualizadas' => $filas_afectadas]
        ];
    } else {
        return [
            'exito' => false,
            'mensaje' => 'Ocurrió un error realizando la actualización'
        ];
    }
}

    public static function marcar_vistaDespacho() {
    $conexion = DB::conectar();

    $consulta1 = "UPDATE solicitud_ayuda_fecha SET visto = 1 WHERE visto = 0";
    $consulta2 = "UPDATE despacho_fecha SET visto = 1 WHERE visto = 0";

    try {
        // Ejecutar primera consulta
        $stmt1 = $conexion->prepare($consulta1);
        $stmt1->execute();
        $filas1 = $stmt1->rowCount();

        // Ejecutar segunda consulta
        $stmt2 = $conexion->prepare($consulta2);
        $stmt2->execute();
        $filas2 = $stmt2->rowCount();

        return [
            'exito' => true,
            'datos' => [
                'solicitud_ayuda_actualizadas' => $filas1,
                'despacho_actualizadas' => $filas2,
                'total_actualizadas' => $filas1 + $filas2
            ]
        ];
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'mensaje' => 'Error en la actualización: ' . $e->getMessage()
        ];
    }
}
    public static function marcar_vista_uno($id_doc,$id_name,$tabla){
        try{
            $conexion = DB::conectar();
            $consulta = "UPDATE $tabla SET visto = 1 WHERE $id_name = $id_doc";
            $busqueda = $conexion->prepare($consulta);
            if ($busqueda->execute()) {
                $filas_afectadas = $busqueda->rowCount();
                return [
                    'exito' => true,
                    'datos' => ['filas_actualizadas' => $filas_afectadas]
                ];
            } else {
                return [
                    'exito' => false,
                    'mensaje' => 'Ocurrió un error realizando la actualización'
                ];
            }
        }
        catch(Exception $e){
            return [
            'exito' => false,
            'mensaje' => 'Error en la actualización: ' . $e->getMessage()
        ];
        }
    }

    public static function mostrar_notis_caso($id_caso) {
    try {
        $conexion = DB::conectar();

        $consulta = "
            SELECT 
                c.id_manual, c.ci, c.estado,
                cf.fecha, cf.visto,
                cc.tipo_ayuda, cc.categoria,
                ci.descripcion, ci.creador,
                sol.nombre AS nombre,
                sol.apellido AS apellido
            FROM casos c
            LEFT JOIN casos_fecha cf ON c.id_caso = cf.id_caso
            LEFT JOIN casos_categoria cc ON c.id_caso = cc.id_despacho
            LEFT JOIN casos_info ci ON c.id_caso = ci.id_caso
            LEFT JOIN solicitantes sol ON c.ci = sol.ci
            WHERE c.id_caso = :id_caso
        ";

        $busqueda = $conexion->prepare($consulta);
        $busqueda->bindParam(':id_caso', $id_caso, PDO::PARAM_INT);
        $busqueda->execute();
        $resultado = $busqueda->fetchAll(PDO::FETCH_ASSOC);

        if ($resultado) {
            return [
                'exito' => true,
                'datos' => $resultado
            ];
        } else {
            return [
                'exito' => false,
                'mensaje' => 'No se encontraron datos para el ID proporcionado.'
            ];
        }
    } catch (PDOException $e) {
        error_log("Error en mostrar_notis_desarrollo: " . $e->getMessage());
        return [
            'exito' => false,
            'mensaje' => 'Error al realizar la búsqueda.'
        ];
    }
}

    public static function mostrar_notificaciones_casos() {
    try {
        $conexion = DB::conectar();
        $resultadoFinal = [];

        // Determinar dirección según el rol del usuario
        $direccion = null;
        switch ($_SESSION['id_rol']) {
            case 1:
                $direccion = 'Desarrollo Social';
                break;
            case 2:
                $direccion = 'Despacho';
                break;
            case 3:
                $direccion = 'Administracion';
                break;
            case 4:
                $direccion = null; // master: sin filtro de dirección
                break;
            default:
                return [
                    'exito' => false,
                    'mensaje' => 'Rol no autorizado para ver notificaciones.'
                ];
        }

        // Construir consulta base
        $consulta = "
            SELECT 
                c.*, 
                ci.*, 
                cc.*, 
                cf.*
            FROM casos c
            LEFT JOIN casos_info ci ON c.id_caso = ci.id_caso
            LEFT JOIN casos_categoria cc ON c.id_caso = cc.id_caso
            LEFT JOIN casos_fecha cf ON c.id_caso = cf.id_caso
            WHERE c.estado = 'Sin Atender'
              AND cf.visto = 0
        ";

        // Agregar filtro por dirección si no es master
        if ($direccion !== null) {
            $consulta .= " AND c.direccion = :direccion";
        }

        $consulta .= " ORDER BY cf.fecha DESC";

        $stmt = $conexion->prepare($consulta);

        if ($direccion !== null) {
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
        }

        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resultado) {
            foreach ($resultado as &$item) {
                $item['id_doc'] = $item['id_caso']; // compatibilidad con vistas
            }
            unset($item);

            $resultadoFinal['casos'] = [
                'tabla' => 'casos_fecha',
                'id_name' => 'id_caso',
                'datos' => $resultado
            ];
        }

        if (!empty($resultadoFinal)) {
            return [
                'exito' => true,
                'datos' => $resultadoFinal
            ];
        } else {
            return [
                'exito' => false,
                'mensaje' => 'No se encontraron notificaciones sin atender.'
            ];
        }

    } catch (Exception $e) {
        error_log("Error al obtener notificaciones de casos: " . $e->getMessage());
        return [
            'exito' => false,
            'mensaje' => 'Error al obtener notificaciones: ' . $e->getMessage()
        ];
    }
}


  }
?>