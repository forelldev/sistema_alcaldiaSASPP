<?php 
require_once 'conexiondb.php';
require_once 'correoModelo.php';
class Desarrollo {
    public static function mostrar_lista() {
        $conexion = DB::conectar();
        $consulta = "
                    SELECT 
                        sd.id_des,
                        sd.id_manual,
                        sd.ci,
                        sd.estado,

                        sdi.descripcion,
                        sdi.creador,

                        sdt.categoria,

                        GROUP_CONCAT(sl.examen SEPARATOR ', ') AS examenes,

                        sdf.fecha,
                        sdf.fecha_modificacion,
                        sdf.visto,

                        s.nombre AS remitente_nombre,
                        s.apellido AS remitente_apellido

                    FROM solicitud_desarrollo sd
                    LEFT JOIN solicitud_desarrollo_info sdi ON sd.id_des = sdi.id_des
                    LEFT JOIN solicitud_desarrollo_tipo sdt ON sd.id_des = sdt.id_des
                    LEFT JOIN solicitud_desarrollo_laboratorio sl ON sd.id_des = sl.id_des
                    LEFT JOIN solicitud_desarrollo_fecha sdf ON sd.id_des = sdf.id_des
                    LEFT JOIN solicitantes s ON sd.ci = s.ci

                    WHERE sd.invalido = 0
                    GROUP BY sd.id_des
                    ORDER BY sdf.fecha DESC
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
                'mensaje' => 'No se encontraron solicitudes válidas'
            ];
        }
    }

   public static function notificaciones() {
        $conexion = DB::conectar();
        try {
            // Estados que requieren seguimiento (excluyendo solicitudes finalizadas)
            $estadoFiltro = "
                sd.estado IN (
                    'En espera del documento físico para ser procesado 0/2',
                    'En Proceso 1/2',
                    'En Proceso 2/2 (Sin entregar)'
                )
            ";

            // Consulta principal sobre las nuevas tablas
            $stmt = $conexion->prepare("
                SELECT 
                    sd.id_des,
                    sd.id_manual,
                    sd.ci,
                    sd.estado,

                    sdf.fecha,
                    sdf.fecha_modificacion,
                    sdt.categoria,
                    sdi.nombre,
                    sdi.apellido,
                    sdi.descripcion
                FROM solicitud_desarrollo sd
                LEFT JOIN solicitud_desarrollo_fecha sdf ON sd.id_des = sdf.id_des
                LEFT JOIN solicitud_desarrollo_tipo sdt ON sd.id_des = sdt.id_des
                LEFT JOIN solicitud_desarrollo_info sdi ON sd.id_des = sdi.id_des
                WHERE sdt.categoria = 'Medicamentos'
                AND $estadoFiltro
                AND sd.invalido = 0
                AND sdf.fecha <= DATE_SUB(NOW(), INTERVAL 5 DAY)
            ");
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $fila) {
                if (!empty($fila['ci'])) {
                    $ci = $fila['ci'];

                    // Obtener correo del solicitante
                    $stmtSolicitante = $conexion->prepare("
                        SELECT nombre, correo FROM solicitantes WHERE ci = :ci
                    ");
                    $stmtSolicitante->execute(['ci' => $ci]);
                    $solicitante = $stmtSolicitante->fetch(PDO::FETCH_ASSOC);

                    if ($solicitante) {
                        $correo = $solicitante['correo'];
                        $nombre = $solicitante['nombre'];

                        // Verificar si ya se envió el correo
                        $stmtCheck = $conexion->prepare("
                            SELECT COUNT(*) FROM solicitud_desarrollo_correo 
                            WHERE id_des = :id_des AND correo_enviado = 1
                        ");
                        $stmtCheck->execute(['id_des' => $fila['id_des']]);
                        $yaEnviado = $stmtCheck->fetchColumn();

                        if ($yaEnviado == 0) {
                            $enviado = Correo::correoVencido($correo, $nombre);

                            if ($enviado) {
                                $stmtUpdate = $conexion->prepare("
                                    INSERT INTO solicitud_desarrollo_correo (id_des, correo_enviado) 
                                    VALUES (:id_des, 1)
                                ");
                                $stmtUpdate->execute(['id_des' => $fila['id_des']]);
                            }
                        }
                    }
                }
            }

            return [
                'exito' => true,
                'datos' => $resultados
            ];
        } catch (Exception $e) {
            error_log("Error al filtrar solicitudes urgentes: " . $e->getMessage());
            return [
                'exito' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public static function cargar_datos_solicitantes($ci){
        $db = DB::conectar();

        // Buscar el solicitante principal
        $stmt = $db->prepare("SELECT * FROM solicitantes WHERE ci = :ci");
        $stmt->bindParam(':ci', $ci);
        $stmt->execute();
        $solicitante = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$solicitante) {
            return ['exito' => false];
        }

        $id = $solicitante['id_solicitante'];

        $patologias = self::buscarTodos($db, 'solicitantes_patologia', $id);
        $cantidad = count($patologias);
        // Buscar datos relacionados
        $datos = [
            'solicitante' => $solicitante,
            'comunidad' => self::buscarUno($db, 'solicitantes_comunidad', $id),
            'conocimiento' => self::buscarUno($db, 'solicitantes_conocimiento', $id),
            'extra' => self::buscarUno($db, 'solicitantes_extra', $id),
            'info' => self::buscarUno($db, 'solicitantes_info', $id),
            'propiedad' => self::buscarUno($db, 'solicitantes_propiedad', $id),
            'trabajo' => self::buscarUno($db, 'solicitantes_trabajo', $id),
            'ingresos' => self::buscarUno($db,'solicitantes_ingresos',$id),
            'patologia' => $patologias,
            'cantidad' => $cantidad
        ];

        return ['exito' => true, 'mostrar' => $datos];
    }

    private static function buscarUno($db, $tabla, $id) {
        $stmt = $db->prepare("SELECT * FROM $tabla WHERE id_solicitante = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private static function buscarTodos($db, $tabla, $id) {
        $stmt = $db->prepare("SELECT * FROM $tabla WHERE id_solicitante = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function enviar_formulario($data) {
        $db = DB::conectar();
        $db->beginTransaction();

        try {
            // Validar campos obligatorios
            $camposObligatorios = [
                'id_manual', 'ci', 'descripcion', 'fecha',
                'categoria', 'ci_user', 'nombre', 'apellido', 'correo'
            ];
            foreach ($camposObligatorios as $campo) {
                if (!isset($data[$campo]) || trim($data[$campo]) === '') {
                    throw new Exception("Falta el campo obligatorio: $campo");
                }
            }

            // Verificar si el solicitante ya existe
                $checkSolicitante = $db->prepare("SELECT COUNT(*) FROM solicitantes WHERE ci = :ci");
                $checkSolicitante->execute([':ci' => $data['ci']]);
                $existeSolicitante = $checkSolicitante->fetchColumn();

                if ($existeSolicitante == 0) {
                    // Insertar nuevo solicitante
                    $insertSolicitante = $db->prepare("INSERT INTO solicitantes (ci, nombre, apellido, correo, fecha_creacion) 
                        VALUES (:ci, :nombre, :apellido, :correo, :fecha_creacion)");
                    $insertSolicitante->execute([
                        ':ci' => $data['ci'],
                        ':nombre' => $data['nombre'],
                        ':apellido' => $data['apellido'],
                        ':correo' => $data['correo'],
                        ':fecha_creacion' => $data['fecha']
                    ]);
                }


            // Verificar si el id_manual ya existe
            $check = $db->prepare("SELECT COUNT(*) FROM solicitud_desarrollo WHERE id_manual = :id_manual");
            $check->execute([':id_manual' => $data['id_manual']]);
            if ($check->fetchColumn() > 0) {
                throw new Exception("El número de documento ya está registrado.");
            }

            // Insertar en solicitud_desarrollo
            $insertSD = $db->prepare("INSERT INTO solicitud_desarrollo 
                (id_manual, ci, estado, invalido) 
                VALUES (:id_manual, :ci, 'En espera del documento físico para ser procesado 0/2', 0)");
            $insertSD->execute([
                ':id_manual' => $data['id_manual'],
                ':ci' => $data['ci'],
            ]);
            $id_des = $db->lastInsertId();

            $creador = self::obtenerNombreCreador($data['ci_user']);

            // Insertar en solicitud_desarrollo_info
            $insertInfo = $db->prepare("INSERT INTO solicitud_desarrollo_info 
                (id_des, descripcion, creador) 
                VALUES (:id_des, :descripcion, :creador)");
            $insertInfo->execute([
                ':id_des' => $id_des,
                ':descripcion' => $data['descripcion'],
                ':creador' => $creador
            ]);

            // Insertar en solicitud_desarrollo_tipo
            $insertTipo = $db->prepare("INSERT INTO solicitud_desarrollo_tipo 
                (id_des, categoria) 
                VALUES (:id_des, :categoria)");
            $insertTipo->execute([
                ':id_des' => $id_des,
                ':categoria' => $data['categoria']
            ]);

            // Insertar en solicitud_desarrollo_laboratorio solo si la categoría es Laboratorio
                if ($data['categoria'] === 'Laboratorio' && !empty($data['examen'])) {
                    $examenes = is_array($data['examen']) ? $data['examen'] : [$data['examen']];
                    $insertLab = $db->prepare("INSERT INTO solicitud_desarrollo_laboratorio 
                        (id_des, examen) 
                        VALUES (:id_des, :examen)");
                    foreach ($examenes as $examen) {
                        $insertLab->execute([
                            ':id_des' => $id_des,
                            ':examen' => $examen
                        ]);
                    }
                } elseif (in_array($data['categoria'], ['Ecosonograma', 'Eco-Doppler'])) {
                    // Tratar estas categorías como exámenes individuales
                    $insertLab = $db->prepare("INSERT INTO solicitud_desarrollo_laboratorio 
                        (id_des, examen) 
                        VALUES (:id_des, :examen)");
                    $insertLab->execute([
                        ':id_des' => $id_des,
                        ':examen' => $data['categoria']
                    ]);
}



            // Insertar en solicitud_fecha
            $insertFecha = $db->prepare("INSERT INTO solicitud_desarrollo_fecha 
                (id_des, fecha, fecha_modificacion,fecha_renovacion, visto) 
                VALUES (:id_des, :fecha, :fecha_modificacion, :fecha_renovacion, 0)");
            $insertFecha->execute([
                ':id_des' => $id_des,
                ':fecha' => $data['fecha'],
                ':fecha_modificacion' => $data['fecha'],
                ':fecha_renovacion' => $data['fecha']
            ]);

            $insertCorreo = $db->prepare("INSERT INTO solicitud_desarrollo_correo (id_des,correo_enviado) VALUES (:id_des,0)");
            $insertCorreo->execute([
                ':id_des' => $id_des
            ]);

            $marcar_visto_caso = $db->prepare("UPDATE casos SET estado = 'Atendido' WHERE id_caso = :id_caso");
            $marcar_visto_caso->execute([':id_caso' => $data['id_caso']]);

            $db->commit();
            return ['exito' => true, 'id_des' => $id_des];

        } catch (Exception $e) {
            $db->rollBack();
            error_log("Error al registrar solicitud de desarrollo: " . $e->getMessage());
            return ['exito' => false, 'error' => $e->getMessage()];
        }
    }

    private static function obtenerNombreCreador($ci_user) {
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT nombre, apellido FROM usuarios_info WHERE ci = :ci");
        $stmt->execute([':ci' => $ci_user]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            return $usuario['nombre'] . ' ' . $usuario['apellido'];
        } else {
            return 'Desconocido';
        }
    }

    public static function verificar_solicitudes($ci) {
    try {
        $conexion = DB::conectar();
        $consulta = "
                SELECT 
                    sd.id_des,
                    sd.id_manual,
                    sd.ci,
                    sd.estado,
                    sd.invalido,

                    sdi.descripcion,
                    sdi.creador,
                    sdt.categoria,
                    GROUP_CONCAT(sl.examen SEPARATOR ', ') AS examenes,
                    sdf.fecha,
                    sdf.fecha_modificacion,
                    sdf.visto,

                    sol.nombre AS remitente_nombre,
                    sol.apellido AS remitente_apellido

                FROM solicitud_desarrollo sd
                LEFT JOIN solicitud_desarrollo_info sdi ON sd.id_des = sdi.id_des
                LEFT JOIN solicitud_desarrollo_tipo sdt ON sd.id_des = sdt.id_des
                LEFT JOIN solicitud_desarrollo_laboratorio sl ON sd.id_des = sl.id_des
                LEFT JOIN solicitud_desarrollo_fecha sdf ON sd.id_des = sdf.id_des
                LEFT JOIN solicitantes sol ON sd.ci = sol.ci

                WHERE sd.ci = :ci
                GROUP BY sd.id_des
                ORDER BY sdf.fecha DESC
            ";


        $cons = $conexion->prepare($consulta);
        $cons->bindParam(':ci', $ci);
        $cons->execute();
        $datos = $cons->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($datos)) {
            return [
                'exito' => true,
                'datos' => $datos
            ];
        } else {
            return [
                'exito' => false
            ];
        }
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'error' => 'Error al consultar solicitudes de desarrollo: ' . $e->getMessage()
        ];
    }
}

    public static function mostrar_inhabilitados() {
        try {
            $conexion = DB::conectar();
            $consulta = "
                SELECT 
                    sd.id_des,
                    sd.id_manual,
                    sd.ci,
                    sd.estado,
                    sd.invalido,

                    sdi.descripcion,
                    sdi.creador,
                    sdt.categoria,
                    GROUP_CONCAT(sl.examen SEPARATOR ', ') AS examenes,
                    sdf.fecha,
                    sdf.fecha_modificacion,
                    sdf.visto,

                    sdi2.razon AS razon_invalidez,
                    sol.nombre AS remitente_nombre,
                    sol.apellido AS remitente_apellido

                FROM solicitud_desarrollo sd
                LEFT JOIN solicitud_desarrollo_info sdi ON sd.id_des = sdi.id_des
                LEFT JOIN solicitud_desarrollo_tipo sdt ON sd.id_des = sdt.id_des
                LEFT JOIN solicitud_desarrollo_laboratorio sl ON sd.id_des = sl.id_des
                LEFT JOIN solicitud_desarrollo_fecha sdf ON sd.id_des = sdf.id_des
                LEFT JOIN solicitud_desarrollo_invalido sdi2 ON sd.id_des = sdi2.id_des
                LEFT JOIN solicitantes sol ON sd.ci = sol.ci

                WHERE sd.invalido = 1
                GROUP BY sd.id_des
                ORDER BY sdf.fecha DESC
            ";

            $stmt = $conexion->prepare($consulta);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultado) {
                return [
                    'exito' => true,
                    'datos' => $resultado
                ];
            } else {
                return [
                    'exito' => false,
                    'mensaje' => 'No se encontraron solicitudes inhabilitadas'
                ];
            }
        } catch (PDOException $e) {
            return [
                'exito' => false,
                'error' => 'Error al consultar solicitudes inhabilitadas: ' . $e->getMessage()
            ];
        }
    }

    public static function edicion_vista($id_des) {
        $conexion = DB::conectar();

        try {
            $stmt = $conexion->prepare("
                SELECT 
                    sd.*, 
                    sdf.fecha, sdf.fecha_modificacion, sdf.visto,
                    sdc.correo_enviado,
                    sdt.categoria,
                    sdi.descripcion, sdi.creador,
                    GROUP_CONCAT(sl.examen SEPARATOR ', ') AS examenes,
                    sdinv.razon AS razon_invalidez
                FROM solicitud_desarrollo sd
                LEFT JOIN solicitud_desarrollo_fecha sdf ON sd.id_des = sdf.id_des
                LEFT JOIN solicitud_desarrollo_correo sdc ON sd.id_des = sdc.id_des
                LEFT JOIN solicitud_desarrollo_tipo sdt ON sd.id_des = sdt.id_des
                LEFT JOIN solicitud_desarrollo_info sdi ON sd.id_des = sdi.id_des
                LEFT JOIN solicitud_desarrollo_laboratorio sl ON sd.id_des = sl.id_des
                LEFT JOIN solicitud_desarrollo_invalido sdinv ON sd.id_des = sdinv.id_des
                WHERE sd.id_des = ?
                GROUP BY sd.id_des
            ");
            $stmt->execute([$id_des]);
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
            error_log("Error al obtener solicitud de desarrollo: " . $e->getMessage());
            return [
                'exito' => false,
                'error' => $e->getMessage()
            ];
        }
    }


    public static function editar($data) {
        $conexion = DB::conectar();

        try {
            $conexion->beginTransaction();

            $camposObligatorios = [
                'id_des', 'id_manual', 'ci', 'descripcion','categoria'
            ];

            foreach ($camposObligatorios as $campo) {
                if (!isset($data[$campo]) || $data[$campo] === '') {
                    throw new Exception("Falta el campo obligatorio: $campo");
                }
            }

            // Actualizar solicitud_desarrollo
            $stmt1 = $conexion->prepare("
                UPDATE solicitud_desarrollo 
                SET id_manual = ?, ci = ? 
                WHERE id_des = ?
            ");
            $stmt1->execute([
                $data['id_manual'],
                $data['ci'],
                $data['id_des']
            ]);

            // Actualizar solicitud_desarrollo_info
            $stmt2 = $conexion->prepare("
                UPDATE solicitud_desarrollo_info 
                SET descripcion = ?
                WHERE id_des = ?
            ");
            $stmt2->execute([
                $data['descripcion'],
                $data['id_des']
            ]);

            // Actualizar solicitud_desarrollo_tipo
            $stmt3 = $conexion->prepare("
                UPDATE solicitud_desarrollo_tipo 
                SET categoria = ? 
                WHERE id_des = ?
            ");
            $stmt3->execute([
                $data['categoria'],
                $data['id_des']
            ]);

            $conexion->commit();
            return ['exito' => true];

        } catch (Exception $e) {
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            error_log("Error al editar solicitud de desarrollo: " . $e->getMessage());
            return ['exito' => false, 'error' => $e->getMessage()];
        }
    }

    public static function filtrar($filtro){
        try {
            $conexion = DB::conectar();

            $baseQuery = "
                SELECT 
                    sd.*, 
                    sdf.fecha, sdf.fecha_modificacion, sdf.visto,
                    sdc.correo_enviado,
                    sdt.categoria,
                    sdi.descripcion, sdi.creador,
                    sdl.examen,
                    sol.nombre AS nombre,
                    sol.apellido AS apellido
                FROM solicitud_desarrollo sd
                LEFT JOIN solicitud_desarrollo_fecha sdf ON sd.id_des = sdf.id_des
                LEFT JOIN solicitud_desarrollo_correo sdc ON sd.id_des = sdc.id_des
                LEFT JOIN solicitud_desarrollo_tipo sdt ON sd.id_des = sdt.id_des
                LEFT JOIN solicitud_desarrollo_info sdi ON sd.id_des = sdi.id_des
                LEFT JOIN solicitud_desarrollo_laboratorio sdl ON sd.id_des = sdl.id_des
                LEFT JOIN solicitantes sol ON sd.ci = sol.ci
                WHERE sd.invalido = 0
            ";

            $order = "DESC";
            $categoria = null;

            switch ($filtro) {
                case "economica":
                    $categoria = "Economica";
                    break;
                case "otros":
                    $categoria = "Otros";
                    break;
                case "medicinas":
                    $categoria = "Medicamentos";
                    break;
                case "laboratorio":
                    $categoria = "Laboratorio";
                    break;
                case "ayuda_tecnica":
                    $categoria = "Ayudas Técnicas";
                    break;
                case "enseres":
                    $categoria = "Enseres";
                    break;
                case "urgentes":
                    $categoria = "Urgentes";
                    break;
                case "antiguos":
                    $order = "ASC";
                    break;
                case "recientes":
                default:
                    // No se modifica categoría ni orden (DESC por defecto)
                    break;
            }

            if ($categoria !== null) {
                $baseQuery .= " AND sdt.categoria = :categoria";
            }

            $baseQuery .= " ORDER BY sdf.fecha $order";

            $stmt = $conexion->prepare($baseQuery);

            if ($categoria !== null) {
                $stmt->bindParam(':categoria', $categoria);
            }

            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $resultados;

        } catch (PDOException $e) {
            error_log("Error al filtrar solicitud_desarrollo: " . $e->getMessage());
            return [];
        }
    }

    public static function fecha_filtro($datos) {
        $conexion = DB::conectar();
        $fecha_inicio = $datos['fecha_inicio'];
        $fecha_final = $datos['fecha_final'];
        $estado = $datos['estado'];
        try {
            $stmt = $conexion->prepare("
                    SELECT 
                        sd.*, 
                        sdf.fecha, sdf.fecha_modificacion, sdf.visto,
                        sdc.correo_enviado,
                        sdt.categoria,
                        sdi.descripcion, sdi.creador,
                        sdl.examen,
                        sol.nombre AS nombre,
                        sol.apellido AS apellido
                    FROM solicitud_desarrollo sd
                    LEFT JOIN solicitud_desarrollo_fecha sdf ON sd.id_des = sdf.id_des
                    LEFT JOIN solicitud_desarrollo_correo sdc ON sd.id_des = sdc.id_des
                    LEFT JOIN solicitud_desarrollo_tipo sdt ON sd.id_des = sdt.id_des
                    LEFT JOIN solicitud_desarrollo_info sdi ON sd.id_des = sdi.id_des
                    LEFT JOIN solicitud_desarrollo_laboratorio sdl ON sd.id_des = sdl.id_des
                    LEFT JOIN solicitantes sol ON sd.ci = sol.ci
                    WHERE DATE(sdf.fecha) >= :fecha_inicio
                    AND DATE(sdf.fecha) <= :fecha_final 
                    AND sd.estado = :estado
                    AND sd.invalido = 0
                    ORDER BY sdf.fecha DESC
                ");

            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_final', $fecha_final);
            $stmt->bindParam(':estado', $estado);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'exito' => true,
                'datos' => $resultados
            ];
        } catch (Exception $e) {
            error_log("Error al filtrar solicitudes por fecha: " . $e->getMessage());
            return [
                'exito' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public static function notificacion_urgencia() {
            $conexion = DB::conectar();
            // $rol = $_SESSION['id_rol']; esta linea era para separar por roles, pero como esta función solo es de rol 1 y accesible solo para rol 1 y 4(que es el usuario master)
            try {
                    $stmt = $conexion->prepare("
                        SELECT 
                            sd.*, 
                            sdf.*,
                            sdc.correo_enviado,
                            sdt.categoria,
                            sdi.*
                        FROM solicitud_desarrollo sd
                        LEFT JOIN solicitud_desarrollo_fecha sdf ON sd.id_des = sdf.id_des
                        LEFT JOIN solicitud_desarrollo_correo sdc ON sd.id_des = sdc.id_des
                        LEFT JOIN solicitud_desarrollo_tipo sdt ON sd.id_des = sdt.id_des
                        LEFT JOIN solicitud_desarrollo_info sdi ON sd.id_des = sdi.id_des
                        WHERE sdt.categoria IN ('Medicamentos', 'Laboratorio')
                        AND sd.invalido = 0
                        AND sdf.fecha_renovacion <= DATE_SUB(NOW(), INTERVAL 5 DAY)
                        ORDER BY 
                            CASE sdt.categoria
                                WHEN 'Medicamentos' THEN 0
                                WHEN 'Laboratorio' THEN 1
                                ELSE 2
                            END,
                            sdf.fecha ASC
                    ");
                $stmt->execute();
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $fila) {
                    if (!empty($fila['ci']) && $fila['correo_enviado'] == 0) {
                        $ci = $fila['ci'];
                        $stmtSolicitante = $conexion->prepare("
                            SELECT nombre, correo FROM solicitantes WHERE ci = :ci
                        ");
                        $stmtSolicitante->execute(['ci' => $ci]);
                        $solicitante = $stmtSolicitante->fetch(PDO::FETCH_ASSOC);

                        if ($solicitante) {
                            $correo = $solicitante['correo'];
                            $nombre = $solicitante['nombre'];

                            $enviado = Correo::correoVencido($correo, $nombre);

                            if ($enviado) {
                                $stmtUpdate = $conexion->prepare("
                                    UPDATE solicitud_desarrollo_correo 
                                    SET correo_enviado = 1 
                                    WHERE id_des = :id_des
                                ");
                                $stmtUpdate->execute(['id_des' => $fila['id_des']]);
                            }
                        }
                    }
                }
                return [
                    'exito' => true,
                    'datos' => $resultados
                ];
            } catch (Exception $e) {
                error_log("Error al filtrar solicitudes por categoría y fecha: " . $e->getMessage());
                return [
                    'exito' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

      public static function mostrar_urgencia($id_des){
            try {
                $conexion = DB::conectar();

                $sql = "
                    SELECT 
                        sd.*,
                        sdi.*,
                        sdt.*,
                        GROUP_CONCAT(sl.examen SEPARATOR ', ') AS examenes,
                        sdf.*,
                        s.nombre AS nombre,
                        s.apellido AS apellido
                    FROM solicitud_desarrollo sd
                    LEFT JOIN solicitud_desarrollo_info sdi ON sd.id_des = sdi.id_des
                    LEFT JOIN solicitud_desarrollo_tipo sdt ON sd.id_des = sdt.id_des
                    LEFT JOIN solicitud_desarrollo_laboratorio sl ON sd.id_des = sl.id_des
                    LEFT JOIN solicitud_desarrollo_fecha sdf ON sd.id_des = sdf.id_des
                    LEFT JOIN solicitantes s ON sd.ci = s.ci
                    WHERE sd.id_des = :id_des
                    GROUP BY sd.id_des
                ";

                $stmt = $conexion->prepare($sql);
                $stmt->bindParam(':id_des', $id_des);
                $stmt->execute();
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return [
                    'exito' => true,
                    'datos' => $resultados
                ];
            } catch (Exception $e) {
                error_log("Error al mostrar la solicitud urgente: " . $e->getMessage());
                return [
                    'exito' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

}





?>