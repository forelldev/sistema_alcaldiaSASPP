<?php 
require_once 'conexiondb.php';
require_once 'correoModelo.php';
class Procesar{
public static function solicitud($id_doc, $estado) {
    try {
        $conexion = DB::conectar();
        date_default_timezone_set('America/Caracas');
        $fecha = date('Y-m-d H:i:s');

        // Actualizar estado en solicitud_ayuda
        $stmt = $conexion->prepare("
            UPDATE solicitud_ayuda 
            SET estado = ? 
            WHERE id_doc = ?
        ");
        $stmt->execute([$estado, $id_doc]);

        // Actualizar visto y fecha_modificacion en solicitud_ayuda_fecha
        $stmtFecha = $conexion->prepare("
            UPDATE solicitud_ayuda_fecha 
            SET visto = ?, fecha_modificacion = ? 
            WHERE id_doc = ?
        ");
        $stmtFecha->execute([0, $fecha, $id_doc]);

        // Obtener correo_enviado desde solicitud_ayuda_correo y ci desde solicitud_ayuda
        $stmt2 = $conexion->prepare("
            SELECT sa.ci, sc.correo_enviado
            FROM solicitud_ayuda sa
            LEFT JOIN solicitud_ayuda_correo sc ON sa.id_doc = sc.id_doc
            WHERE sa.id_doc = ?
        ");
        $stmt2->execute([$id_doc]);
        $fila = $stmt2->fetch(PDO::FETCH_ASSOC);

        // Si existe el registro y correo_enviado es distinto de 0, enviar correo y reiniciar
        if ($fila && $fila['correo_enviado'] != 0 && !empty($fila['ci'])) {
            $ci = $fila['ci'];

            // Obtener nombre y correo del solicitante
            $stmt3 = $conexion->prepare("
                SELECT nombre, correo FROM solicitantes 
                WHERE ci = ?
            ");
            $stmt3->execute([$ci]);
            $info = $stmt3->fetch(PDO::FETCH_ASSOC);

            if ($info) {
                $nombre = $info['nombre'];
                $correo = $info['correo'];

                // Enviar correo de renovación
                Correo::correoRenovado($correo, $nombre);

                // Reiniciar correo_enviado en solicitud_ayuda_correo
                $stmt4 = $conexion->prepare("
                    UPDATE solicitud_ayuda_correo 
                    SET correo_enviado = 0 
                    WHERE id_doc = ?
                ");
                $stmt4->execute([$id_doc]);
                $stmt5 = $conexion->prepare("UPDATE solicitud_ayuda_fecha SET fecha_renovacion = ? WHERE id_des = ?");
                $stmt5->execute([$fecha,$id_doc]);
            }
        }

        return true;
    } catch (Exception $e) {
        error_log("Error al actualizar solicitud: " . $e->getMessage());
        return false;
    }
}

    public static function desarrollo($id_des, $estado) {
        try {
            $conexion = DB::conectar();
            date_default_timezone_set('America/Caracas');
            $fecha = date('Y-m-d H:i:s');

            // Actualizar estado en solicitud_desarrollo
            $stmt = $conexion->prepare("
                UPDATE solicitud_desarrollo 
                SET estado = ? 
                WHERE id_des = ?
            ");
            $stmt->execute([$estado, $id_des]);

            // Actualizar visto y fecha_modificacion en solicitud_desarrollo_fecha
            $stmtFecha = $conexion->prepare("
                UPDATE solicitud_desarrollo_fecha 
                SET visto = ?, fecha_modificacion = ? 
                WHERE id_des = ?
            ");
            $stmtFecha->execute([0, $fecha, $id_des]);

            // Obtener correo_enviado desde solicitud_desarrollo_correo y ci desde solicitud_desarrollo
            $stmt2 = $conexion->prepare("
                SELECT sd.ci, sdc.correo_enviado
                FROM solicitud_desarrollo sd
                LEFT JOIN solicitud_desarrollo_correo sdc ON sd.id_des = sdc.id_des
                WHERE sd.id_des = ?
            ");
            $stmt2->execute([$id_des]);
            $fila = $stmt2->fetch(PDO::FETCH_ASSOC);

            // Si existe el registro y correo_enviado es distinto de 0, enviar correo y reiniciar
            if ($fila && $fila['correo_enviado'] != 0 && !empty($fila['ci'])) {
                $ci = $fila['ci'];

                // Obtener nombre y correo del solicitante
                $stmt3 = $conexion->prepare("
                    SELECT nombre, correo FROM solicitantes 
                    WHERE ci = ?
                ");
                $stmt3->execute([$ci]);
                $info = $stmt3->fetch(PDO::FETCH_ASSOC);

                if ($info) {
                    $nombre = $info['nombre'];
                    $correo = $info['correo'];
                    // Enviar correo de renovación
                    Correo::correoRenovado($correo, $nombre);

                    // Reiniciar correo_enviado en solicitud_desarrollo_correo
                    $stmt4 = $conexion->prepare("
                        UPDATE solicitud_desarrollo_correo 
                        SET correo_enviado = 0 
                        WHERE id_des = ?
                    ");
                    $stmt4->execute([$id_des]);
                    $stmt5 = $conexion->prepare("UPDATE solicitud_desarrollo_fecha SET fecha_renovacion = ? WHERE id_des = ?");
                    $stmt5->execute([$fecha,$id_des]);
                }
            }

            return true;
        } catch (Exception $e) {
            error_log("Error al actualizar solicitud desarrollo: " . $e->getMessage());
            return false;
        }
    }

    public static function inhabilitarDesarrollo($id_des, $invalido, $razon) {
        try {
            $conexion = DB::conectar();

            // Actualizar el campo invalido en solicitud_desarrollo
            $stmt = $conexion->prepare("
                UPDATE solicitud_desarrollo 
                SET invalido = ? 
                WHERE id_des = ?
            ");
            $stmt->execute([$invalido, $id_des]);

            // Verificar si ya existe una entrada en solicitud_desarrollo_invalido
            $stmtCheck = $conexion->prepare("
                SELECT COUNT(*) FROM solicitud_desarrollo_invalido 
                WHERE id_des = ?
            ");
            $stmtCheck->execute([$id_des]);
            $existe = $stmtCheck->fetchColumn();

            if ($existe) {
                // Si existe, actualizar la razón
                $stmtUpdate = $conexion->prepare("
                    UPDATE solicitud_desarrollo_invalido 
                    SET razon = ? 
                    WHERE id_des = ?
                ");
                $stmtUpdate->execute([$razon, $id_des]);
            } else {
                // Si no existe, insertar nueva razón
                $stmtInsert = $conexion->prepare("
                    INSERT INTO solicitud_desarrollo_invalido (id_des, razon) 
                    VALUES (?, ?)
                ");
                $stmtInsert->execute([$id_des, $razon]);
            }

            return true;
        } catch (PDOException $e) {
            error_log("Error al inhabilitar solicitud de desarrollo: " . $e->getMessage());
            return false;
        }
    }

    public static function habilitar_desarrollo($id_des) {
        try {
            $conexion = DB::conectar();

            // Marcar la solicitud como válida
            $stmt = $conexion->prepare("
                UPDATE solicitud_desarrollo 
                SET invalido = 0 
                WHERE id_des = ?
            ");
            $stmt->execute([$id_des]);

            // Eliminar la razón de invalidez si existe
            $stmtDelete = $conexion->prepare("
                DELETE FROM solicitud_desarrollo_invalido 
                WHERE id_des = ?
            ");
            $stmtDelete->execute([$id_des]);

            return true;
        } catch (PDOException $e) {
            error_log("Error al habilitar solicitud de desarrollo: " . $e->getMessage());
            return false;
        }
    }


    public static function inhabilitar($id_doc, $invalido, $razon) {
    try {
            $conexion = DB::conectar();

            // Actualizar el campo invalido en solicitud_ayuda
            $stmt = $conexion->prepare("
                UPDATE solicitud_ayuda 
                SET invalido = ? 
                WHERE id_doc = ?
            ");
            $stmt->execute([$invalido, $id_doc]);

            // Verificar si ya existe una entrada en solicitud_ayuda_invalido
            $stmtCheck = $conexion->prepare("
                SELECT COUNT(*) FROM solicitud_ayuda_invalido 
                WHERE id_doc = ?
            ");
            $stmtCheck->execute([$id_doc]);
            $existe = $stmtCheck->fetchColumn();

            if ($existe) {
                // Si existe, actualizar la razón
                $stmtUpdate = $conexion->prepare("
                    UPDATE solicitud_ayuda_invalido 
                    SET razon = ? 
                    WHERE id_doc = ?
                ");
                $stmtUpdate->execute([$razon, $id_doc]);
            } else {
                // Si no existe, insertar nueva razón
                $stmtInsert = $conexion->prepare("
                    INSERT INTO solicitud_ayuda_invalido (id_doc, razon) 
                    VALUES (?, ?)
                ");
                $stmtInsert->execute([$id_doc, $razon]);
            }

            return true;
        } catch (PDOException $e) {
            error_log("Error al inhabilitar solicitud: " . $e->getMessage());
            return false;
        }
    }


    public static function habilitar_solicitud($id_doc, $invalido) {
        try {
            $conexion = DB::conectar();

            // Actualizar el campo invalido en solicitud_ayuda
            $stmt = $conexion->prepare("
                UPDATE solicitud_ayuda 
                SET invalido = ? 
                WHERE id_doc = ?
            ");
            $stmt->execute([$invalido, $id_doc]);

            // Eliminar la razón asociada en solicitud_ayuda_invalido
            $stmtDelete = $conexion->prepare("
                DELETE FROM solicitud_ayuda_invalido 
                WHERE id_doc = ?
            ");
            $stmtDelete->execute([$id_doc]);

            return true;
        } catch (PDOException $e) {
            error_log("Error al habilitar solicitud: " . $e->getMessage());
            return false;
        }
    }

        public static function inhabilitados_lista() {
            $conexion = DB::conectar();

            try {
                $consulta = "
                            SELECT 
                                sa.*, 
                                saf.fecha, saf.fecha_modificacion, saf.visto,
                                sc.correo_enviado,
                                cat.tipo_ayuda, cat.categoria,
                                des.descripcion, des.promotor, des.observaciones,
                                inv.razon,
                                sol.nombre AS nombre,
                                sol.apellido AS apellido
                            FROM solicitud_ayuda sa
                            LEFT JOIN solicitud_ayuda_fecha saf ON sa.id_doc = saf.id_doc
                            LEFT JOIN solicitud_ayuda_correo sc ON sa.id_doc = sc.id_doc
                            LEFT JOIN solicitud_categoria cat ON sa.id_doc = cat.id_doc
                            LEFT JOIN solicitud_descripcion des ON sa.id_doc = des.id_doc
                            LEFT JOIN solicitud_ayuda_invalido inv ON sa.id_doc = inv.id_doc
                            LEFT JOIN solicitantes sol ON sa.ci = sol.ci
                            WHERE sa.invalido = 1
                            ORDER BY saf.fecha DESC
                        ";


                $busqueda = $conexion->prepare($consulta);
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
                        'mensaje' => 'No se encontraron solicitudes inhabilitadas.'
                    ];
                }
            } catch (PDOException $e) {
                error_log("Error al obtener solicitudes inhabilitadas: " . $e->getMessage());
                return [
                    'exito' => false,
                    'error' => $e->getMessage()
                ];
            }
        }


        public static function edit_vista($id_doc) {
            $conexion = DB::conectar();

            try {
                $stmt = $conexion->prepare("
                    SELECT 
                        sa.*, 
                        saf.fecha, saf.fecha_modificacion, saf.visto,
                        sc.correo_enviado,
                        cat.tipo_ayuda, cat.categoria,
                        des.descripcion, des.promotor, des.observaciones,
                        inv.razon,
                        sol.*
                    FROM solicitud_ayuda sa
                    LEFT JOIN solicitud_ayuda_fecha saf ON sa.id_doc = saf.id_doc
                    LEFT JOIN solicitud_ayuda_correo sc ON sa.id_doc = sc.id_doc
                    LEFT JOIN solicitud_categoria cat ON sa.id_doc = cat.id_doc
                    LEFT JOIN solicitud_descripcion des ON sa.id_doc = des.id_doc
                    LEFT JOIN solicitud_ayuda_invalido inv ON sa.id_doc = inv.id_doc
                    LEFT JOIN solicitantes sol ON sa.ci = sol.ci
                    WHERE sa.id_doc = ?
                ");
                $stmt->execute([$id_doc]);
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($resultado) {
                    return [
                        'exito' => true,
                        'datos' => $resultado
                    ];
                } else {
                    return [
                        'exito' => false,
                        'mensaje' => 'No se encontró la solicitud.'
                    ];
                }
            } catch (PDOException $e) {
                error_log("Error al obtener solicitud: " . $e->getMessage());
                return [
                    'exito' => false,
                    'error' => $e->getMessage()
                ];
            }
        }


        public static function editar_consulta($data) {
            $conexion = DB::conectar();

            try {
                $conexion->beginTransaction();

                $camposObligatorios = [
                    'id_doc', 'id_manual', 'ci', 'descripcion','observaciones', 'tipo_ayuda', 'categoria'
                ];

                foreach ($camposObligatorios as $campo) {
                    if (!isset($data[$campo]) || $data[$campo] === '') {
                        throw new Exception("Falta el campo obligatorio: $campo");
                    }
                }

                // Actualizar solicitud_ayuda
                $stmt1 = $conexion->prepare("
                    UPDATE solicitud_ayuda 
                    SET id_manual = ?, ci = ? 
                    WHERE id_doc = ?
                ");
                $stmt1->execute([
                    $data['id_manual'],
                    $data['ci'],
                    $data['id_doc']
                ]);

                // Actualizar solicitud_descripcion
                $stmt2 = $conexion->prepare("
                    UPDATE solicitud_descripcion 
                    SET descripcion = ?,observaciones = ? 
                    WHERE id_doc = ?
                ");
                $stmt2->execute([
                    $data['descripcion'],
                    $data['observaciones'],
                    $data['id_doc']
                ]);

                // Actualizar solicitud_categoria
                $stmt3 = $conexion->prepare("
                    UPDATE solicitud_categoria 
                    SET tipo_ayuda = ?, categoria = ? 
                    WHERE id_doc = ?
                ");
                $stmt3->execute([
                    $data['tipo_ayuda'],
                    $data['categoria'],
                    $data['id_doc']
                ]);

                $conexion->commit();
                return ['exito' => true];

            } catch (Exception $e) {
                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
                error_log("Error al editar la solicitud: " . $e->getMessage());
                return ['exito' => false, 'error' => $e->getMessage()];
            }
        }



        public static function registrarReporte($id_doc,$fecha,$accion,$ci){
            $conexion = DB::conectar();
            try{
                $stmt = $conexion->prepare("INSERT INTO reportes_acciones (id_doc,fecha,accion,ci) VALUES (?, ?, ?, ?)");
                $stmt->execute([$id_doc,$fecha,$accion,$ci]);
                return ['exito' => true];
            } catch(Exception $e){
                $conexion->rollBack();
                error_log("Error al insertar el reporte: " . $e->getMessage());
                return ['exito' => false, 'error' => $e->getMessage()];
            }
        }

        public static function despacho($id_despacho,$estado){
        try {
            $conexion = DB::conectar();
            $stmt = $conexion->prepare("UPDATE despacho SET estado = ? WHERE id_despacho = ?");
            $stmt->execute([$estado, $id_despacho]); 
            return true;
        } catch (PDOException $e) {
            error_log("Error al actualizar solicitud: " . $e->getMessage());
            return false;
        };
    }

    public static function inhabilitarDespacho($id_despacho, $estado, $razon) {
        try {
            $conexion = DB::conectar();

            // Actualizar estado en despacho
            $stmt = $conexion->prepare("
                UPDATE despacho 
                SET estado = ?, invalido = 1 
                WHERE id_despacho = ?
            ");
            $stmt->execute([$estado, $id_despacho]);

            // Verificar si ya existe una razón en despacho_invalido
            $stmtCheck = $conexion->prepare("
                SELECT COUNT(*) FROM despacho_invalido 
                WHERE id_despacho = ?
            ");
            $stmtCheck->execute([$id_despacho]);
            $existe = $stmtCheck->fetchColumn();

            if ($existe) {
                // Actualizar razón existente
                $stmtUpdate = $conexion->prepare("
                    UPDATE despacho_invalido 
                    SET razon = ? 
                    WHERE id_despacho = ?
                ");
                $stmtUpdate->execute([$razon, $id_despacho]);
            } else {
                // Insertar nueva razón
                $stmtInsert = $conexion->prepare("
                    INSERT INTO despacho_invalido (id_despacho, razon) 
                    VALUES (?, ?)
                ");
                $stmtInsert->execute([$id_despacho, $razon]);
            }

            return true;
        } catch (PDOException $e) {
            error_log("Error al inhabilitar despacho: " . $e->getMessage());
            return false;
        }
    }


    public static function habilitar_solicitudDespacho($id_despacho, $estado) {
            try {
                $conexion = DB::conectar();

                // Actualizar estado e invalido en despacho
                $stmt = $conexion->prepare("
                    UPDATE despacho 
                    SET estado = ?, invalido = 0 
                    WHERE id_despacho = ?
                ");
                $stmt->execute([$estado, $id_despacho]);

                // Eliminar la razón en despacho_invalido
                $stmtDelete = $conexion->prepare("
                    DELETE FROM despacho_invalido 
                    WHERE id_despacho = ?
                ");
                $stmtDelete->execute([$id_despacho]);

                return true;
            } catch (PDOException $e) {
                error_log("Error al habilitar despacho: " . $e->getMessage());
                return false;
            }
        }

        

        public static function inhabilitados_listaDespacho() {
            $conexion = DB::conectar();

            try {
                $consulta = "
                    SELECT 
                        d.*, 
                        dd.asunto, dd.creador,
                        df.fecha, df.fecha_modificacion, df.visto,
                        di.razon
                    FROM despacho d
                    LEFT JOIN despacho_descripcion dd ON d.id_despacho = dd.id_despacho
                    LEFT JOIN despacho_fecha df ON d.id_despacho = df.id_despacho
                    LEFT JOIN despacho_invalido di ON d.id_despacho = di.id_despacho
                    WHERE d.invalido = 1
                    ORDER BY df.fecha DESC
                ";

                $busqueda = $conexion->prepare($consulta);
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
                        'mensaje' => 'No se encontraron despachos inhabilitados.'
                    ];
                }
            } catch (PDOException $e) {
                error_log("Error al obtener despachos inhabilitados: " . $e->getMessage());
                return [
                    'exito' => false,
                    'error' => $e->getMessage()
                ];
            }
        }


        public static function edit_vistaDespacho($id_despacho) {
            $conexion = DB::conectar();

            try {
                $stmt = $conexion->prepare("
                    SELECT 
                        d.*, 
                        dd.asunto, dd.creador,
                        df.fecha, df.fecha_modificacion, df.visto,
                        di.razon
                    FROM despacho d
                    LEFT JOIN despacho_descripcion dd ON d.id_despacho = dd.id_despacho
                    LEFT JOIN despacho_fecha df ON d.id_despacho = df.id_despacho
                    LEFT JOIN despacho_invalido di ON d.id_despacho = di.id_despacho
                    WHERE d.id_despacho = ?
                ");
                $stmt->execute([$id_despacho]);
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($resultado) {
                    return [
                        'exito' => true,
                        'datos' => $resultado
                    ];
                } else {
                    return [
                        'exito' => false,
                        'mensaje' => 'No se encontró el despacho.'
                    ];
                }
            } catch (PDOException $e) {
                error_log("Error al obtener despacho: " . $e->getMessage());
                return [
                    'exito' => false,
                    'error' => $e->getMessage()
                ];
            }
        }


        public static function editar_consultaDespacho($data) {
            $conexion = DB::conectar();

            try {
                $conexion->beginTransaction();

                $camposObligatorios = [
                    'id_despacho', 'id_manual', 'ci', 'asunto', 'creador'
                ];

                foreach ($camposObligatorios as $campo) {
                    if (!isset($data[$campo]) || $data[$campo] === '') {
                        throw new Exception("Falta el campo obligatorio: $campo");
                    }
                }

                // Actualizar despacho
                $stmt1 = $conexion->prepare("
                    UPDATE despacho 
                    SET id_manual = ?, ci = ? 
                    WHERE id_despacho = ?
                ");
                $stmt1->execute([
                    $data['id_manual'],
                    $data['ci'],
                    $data['id_despacho']
                ]);

                // Actualizar despacho_descripcion
                $stmt2 = $conexion->prepare("
                    UPDATE despacho_descripcion 
                    SET asunto = ?, creador = ? 
                    WHERE id_despacho = ?
                ");
                $stmt2->execute([
                    $data['asunto'],
                    $data['creador'],
                    $data['id_despacho']
                ]);

                // (Opcional) Actualizar fecha_modificacion en despacho_fecha
                if (!empty($data['actualizar_fecha'])) {
                    date_default_timezone_set('America/Caracas');
                    $fecha = date('Y-m-d H:i:s');

                    $stmt3 = $conexion->prepare("
                        UPDATE despacho_fecha 
                        SET fecha_modificacion = ? 
                        WHERE id_despacho = ?
                    ");
                    $stmt3->execute([$fecha, $data['id_despacho']]);
                }

                $conexion->commit();
                return ['exito' => true];

            } catch (Exception $e) {
                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
                error_log("Error al editar el despacho: " . $e->getMessage());
                return ['exito' => false, 'error' => $e->getMessage()];
            }
        }

}
?>