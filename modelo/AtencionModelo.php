<?php 
require_once 'conexiondb.php';
class AtencionModelo{
    public static function mostrar_casos($direccion) {
        try {
            $conexion = DB::conectar();

            // Si la dirección es "Todos", no se filtra por dirección
            if ($direccion === 'Todos') {
                $sql = "
                    SELECT c.*, ci.*, cf.*, cc.*, sol.*
                    FROM casos c 
                    LEFT JOIN casos_info ci ON c.id_caso = ci.id_caso
                    LEFT JOIN casos_fecha cf ON c.id_caso = cf.id_caso
                    LEFT JOIN casos_categoria cc ON c.id_caso = cc.id_caso
                    LEFT JOIN solicitantes sol ON c.ci = sol.ci
                    ORDER BY (c.estado = 'Sin Atender') DESC, c.id_caso DESC
                ";
                $consulta = $conexion->prepare($sql);
                $consulta->execute();
            } else {
                $sql = "
                    SELECT c.*, ci.*, cf.*, cc.*, sol.*
                    FROM casos c 
                    LEFT JOIN casos_info ci ON c.id_caso = ci.id_caso
                    LEFT JOIN casos_fecha cf ON c.id_caso = cf.id_caso
                    LEFT JOIN casos_categoria cc ON c.id_caso = cc.id_caso
                    LEFT JOIN solicitantes sol ON c.ci = sol.ci
                    WHERE c.direccion = :direccion
                    ORDER BY (c.estado = 'Sin Atender') DESC, c.id_caso DESC
                ";
                $consulta = $conexion->prepare($sql);
                $consulta->bindParam(':direccion', $direccion, PDO::PARAM_STR);
                $consulta->execute();
            }

            $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

            return !empty($datos)
                ? ['exito' => true, 'datos' => $datos]
                : ['exito' => false, 'error' => 'No se encontraron casos.'];

        } catch (PDOException $e) {
            return [
                'exito' => false,
                'error' => 'Error en la consulta: ' . $e->getMessage()
            ];
        }
    }



    public static function cargar_datos($ci){
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

    public static function verificar_casos($ci) {
        try {
            $conexion = DB::conectar();
            $consulta = "
                SELECT c.*, ci.*,cf.*, cc.*, sol.*
                FROM casos c 
                LEFT JOIN casos_info ci ON c.id_caso = ci.id_caso
                LEFT JOIN casos_fecha cf ON c.id_caso = cf.id_caso
                LEFT JOIN casos_categoria cc ON c.id_caso = cc.id_caso
                LEFT JOIN solicitantes sol ON c.ci = sol.ci
                WHERE c.ci = :ci
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
            }
            else {
                return [
                    'exito' => false
                ];
            }
        } catch (PDOException $e) {
            return [
                'exito' => false,
                'error' => 'Error al consultar casos: ' . $e->getMessage()
            ];
        }
    }

     public static function verificar_solicitante($ci) {
    // Validar que el CI no esté vacío
        if (empty($ci)) {
            return [
                'exito' => false,
                'error' => 'La cédula no puede estar vacía.'
            ];
        }

        try {
            $conexion = DB::conectar();
            $consulta = "SELECT s.*, sc.*, sco.*, se.*, si.*, sin.*, sp.*, st.*
                        FROM solicitantes s
                        LEFT JOIN solicitantes_comunidad sc ON s.id_solicitante = sc.id_solicitante
                        LEFT JOIN solicitantes_conocimiento sco ON s.id_solicitante = sco.id_solicitante
                        LEFT JOIN solicitantes_extra se ON s.id_solicitante = se.id_solicitante
                        LEFT JOIN solicitantes_info si ON s.id_solicitante = si.id_solicitante
                        LEFT JOIN solicitantes_ingresos sin ON s.id_solicitante = sin.id_solicitante
                        LEFT JOIN solicitantes_patologia sp ON s.id_solicitante = sp.id_solicitante
                        LEFT JOIN solicitantes_trabajo st ON s.id_solicitante = st.id_solicitante
                        WHERE s.ci = :ci";

            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(':ci', $ci, PDO::PARAM_STR);
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Validar si se encontraron datos
            if (!$datos || count($datos) === 0) {
                return [
                    'exito' => false,
                    'error' => 'No se encontró ningún solicitante con esa cédula.'
                ];
            }

            return [
                'exito' => true,
                'datos' => $datos
            ];
        } catch (PDOException $e) {
            return [
                'exito' => false,
                'error' => 'Error de conexión: ' . $e->getMessage()
            ];
        }
    }


    public static function registrar_caso($data){
        $db = DB::conectar();
        $db->beginTransaction();

        try {
            // if (empty($data['prioridad'])) {
            //     $data['prioridad'] = 'Baja';
            // }
            // Validar campos obligatorios
            $camposObligatorios = [ 'ci', 'descripcion', 'fecha', 'ci_user', 
            'nombre', 'apellido', 'correo', 'estado'];

            foreach ($camposObligatorios as $campo) {
                if (!isset($data[$campo]) || $data[$campo] === '') {
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


            // Verificar si el id ya existe en casos
            // $checkStmt = $db->prepare("SELECT COUNT(*) FROM casos WHERE id = :id");
            // $checkStmt->execute([':id' => $data['id']]);
            // $exists = $checkStmt->fetchColumn();

            // if ($exists > 0) {
            //     throw new Exception("❌ El número de documento ya está registrado.");
            // }


            // Obtener datos del promotor
            $stmt = $db->prepare("SELECT nombre, apellido FROM usuarios_info WHERE ci = :ci");
            $stmt->execute([':ci' => $data['ci_user']]);
            $promotor = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$promotor) {
                throw new Exception("No se encontró el promotor con CI: " . $data['ci_user']);
            }

            $creador = $promotor['nombre'] . ' ' . $promotor['apellido'];

            // Insertar en tabla casos
            $stmt = $db->prepare("
                INSERT INTO casos (
                    ci, estado, direccion
                ) VALUES (
                    :ci, :estado, :direccion
                )
            ");
            $stmt->execute([
                ':ci' => $data['ci'],
                ':estado' => $data['estado'],
                ':direccion' => $data['direccion']
            ]);

            $id_caso = $db->lastInsertId();

            // Insertar en tabla casos_info
            $stmt = $db->prepare("
                INSERT INTO casos_info (
                    id_caso, descripcion, creador
                ) VALUES (
                    :id_caso, :descripcion, :creador
                )
            ");
            $stmt->execute([
                ':id_caso' => $id_caso,
                ':descripcion' => $data['descripcion'],
                ':creador' => $creador
            ]);

            // Insertar en tabla casos_fecha
            $stmt = $db->prepare("
                INSERT INTO casos_fecha (
                    id_caso, fecha, visto
                ) VALUES (
                    :id_caso, :fecha, :visto
                )
            ");
            $stmt->execute([
                ':id_caso' => $id_caso,
                ':fecha' => $data['fecha'],
                ':visto' => 0
            ]);

            $stmt = $db->prepare("
                INSERT INTO casos_categoria (
                    id_caso, tipo_ayuda, categoria
                ) VALUES (
                    :id_caso, :tipo_ayuda, :categoria
                )
            ");

            $stmt->execute([
                ':id_caso' => $id_caso,
                ':tipo_ayuda' => 'Sin Registrar',
                ':categoria' => 'Sin Registrar'
            ]);

            

            $db->commit();

            return [
                'exito' => true,
                'id_caso' => $id_caso
            ];

        } catch (Exception $e) {
            $db->rollBack();
            return [
                'exito' => false,
                'error' => $e->getMessage()
            ];
        }
        }
    public static function datos_caso($id_caso) {
        try {
            $conexion = DB::conectar();
            $consulta = $conexion->prepare("
                SELECT c.*, ci.*, cf.*, cc.*, sol.*
                FROM casos c 
                LEFT JOIN casos_info ci ON c.id_caso = ci.id_caso
                LEFT JOIN casos_fecha cf ON c.id_caso = cf.id_caso
                LEFT JOIN casos_categoria cc ON c.id_caso = cc.id_caso
                LEFT JOIN solicitantes sol ON c.ci = sol.ci
                WHERE c.id_caso = :id_caso
            ");
            $consulta->bindParam(':id_caso', $id_caso, PDO::PARAM_STR);
            $consulta->execute();
            $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

            if ($datos && count($datos) > 0) {
                return [
                    'exito' => true,
                    'datos' => $datos
                ];
            } else {
                return [
                    'exito' => false,
                    'error' => 'No se encontraron casos.'
                ];
            }
        } catch (PDOException $e) {
            return [
                'exito' => false,
                'error' => 'Error en la consulta: ' . $e->getMessage()
            ];
        }
    }

    public static function marcar_vistas() {
        try {
            $conexion = DB::conectar();
            $consulta = "UPDATE casos_fecha SET visto = 1 WHERE visto = 0";
            $busqueda = $conexion->prepare($consulta);

            if ($busqueda->execute()) {
                $filas_afectadas = $busqueda->rowCount();

                if ($filas_afectadas > 0) {
                    return [
                        'exito' => true,
                        'datos' => ['filas_actualizadas' => $filas_afectadas]
                    ];
                } else {
                    return [
                        'exito' => false,
                        'mensaje' => 'No hay vistas por marcar'
                    ];
                }
            } else {
                return [
                    'exito' => false,
                    'mensaje' => 'Ocurrió un error realizando la actualización'
                ];
            }
        } catch (PDOException $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error en la consulta: ' . $e->getMessage()
            ];
        }
    }

    public static function filtrar_caso($filtro) {
        try {
            $conexion = DB::conectar();

            $baseQuery = "
                SELECT 
                    sa.*, 
                    saf.*,
                    sac.*,
                    sc.*,
                    sd.*,
                    sol.nombre AS nombre,
                    sol.apellido AS apellido
                FROM solicitud_ayuda sa
                LEFT JOIN solicitud_ayuda_fecha saf ON sa.id_doc = saf.id_doc
                LEFT JOIN solicitud_ayuda_correo sac ON sa.id_doc = sac.id_doc
                LEFT JOIN solicitud_categoria sc ON sa.id_doc = sc.id_doc
                LEFT JOIN solicitud_descripcion sd ON sa.id_doc = sd.id_doc
                LEFT JOIN solicitantes sol ON sa.ci = sol.ci
                WHERE sa.invalido = 0
            ";

            $order = "DESC";
            $categoria = null;
            $estado = null;

            switch ($filtro) {
                case "economica":
                    $categoria = "Economica";
                    break;
                case "otros":
                    $categoria = "Otros";
                    break;
                case "sin_atender":
                    $estado = "Sin Atender";
                    break;
                case "atendidos":
                    $estado = "Atendido";
                    break;
                case "antiguos":
                    $order = "ASC";
                    break;
                case "recientes":
                default:
                    // No se modifica categoría ni orden
                    break;
            }

            if ($categoria !== null) {
                $baseQuery .= " AND sc.categoria = :categoria";
            }

            if ($estado !== null) {
                $baseQuery .= " AND sa.estado = :estado";
            }

            $baseQuery .= " ORDER BY saf.fecha $order";

            $stmt = $conexion->prepare($baseQuery);

            if ($categoria !== null) {
                $stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);
            }

            if ($estado !== null) {
                $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al filtrar solicitud: " . $e->getMessage());
            return [];
        }
    }

    public static function marcar_visto_caso($id_caso){
        $db = DB::conectar();
        $marcar_visto = $db->prepare("UPDATE casos_fecha SET visto = 1 WHERE id_caso = :id_caso");
        $marcar_visto->execute([
                ':id_caso' => $id_caso
            ]);
    }

    public static function caso_continuar($data) {
    try {
        $conexion = DB::conectar();

        // Validar campos obligatorios
        if (empty($data['id_caso'])) {
            throw new Exception("Falta el ID del caso.");
        }
        if (empty($data['categoria'])) {
            throw new Exception("Falta la categoría.");
        }

        // Actualizar la categoría en la tabla casos_categoria
        $sqlCategoria = "UPDATE casos_categoria SET categoria = :categoria WHERE id_caso = :id_caso";
        $stmtCategoria = $conexion->prepare($sqlCategoria);
        $stmtCategoria->execute([
            ':categoria' => $data['categoria'],
            ':id_caso' => $data['id_caso']
        ]);

        // (Opcional) Actualizar estado del caso en tabla principal
        $sqlEstado = "UPDATE casos SET estado = 'En Proceso', fecha_modificacion = NOW() WHERE id_caso = :id_caso";
        $stmtEstado = $conexion->prepare($sqlEstado);
        $stmtEstado->execute([
            ':id_caso' => $data['id_caso']
        ]);

        return ['exito' => true];

    } catch (Exception $e) {
        error_log("Error al continuar caso: " . $e->getMessage());
        return ['exito' => false, 'error' => $e->getMessage()];
    }
}




    }

?>