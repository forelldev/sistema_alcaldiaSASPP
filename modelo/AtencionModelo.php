<?php 
require_once 'conexiondb.php';
class AtencionModelo{
    public static function mostrar_casos() {
        try {
            $conexion = DB::conectar();
            $consulta = $conexion->prepare("
                SELECT c.*, ci.*,cf.*,cc.*, sol.*
                FROM casos c 
                LEFT JOIN casos_info ci ON c.id_caso = ci.id_caso
                LEFT JOIN casos_fecha cf ON c.id_caso = cf.id_caso
                LEFT JOIN casos_categoria cc ON c.id_caso = cc.id_caso
                LEFT JOIN solicitantes sol ON c.ci = sol.ci
                ORDER BY (c.estado = 'Sin Atender') DESC, c.id_caso DESC
            ");
            $consulta->execute();
            $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($datos)) {
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
        } catch (Exception $e) {
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

            return [
                'exito' => true,
                'datos' => $datos
            ];
        } catch (PDOException $e) {
            return [
                'exito' => false,
                'error' => $e->getMessage()
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
            $camposObligatorios = ['id_manual', 'ci', 'descripcion', 'fecha', 'ci_user', 
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


            // Verificar si el id_manual ya existe en casos
            $checkStmt = $db->prepare("SELECT COUNT(*) FROM casos WHERE id_manual = :id_manual");
            $checkStmt->execute([':id_manual' => $data['id_manual']]);
            $exists = $checkStmt->fetchColumn();

            if ($exists > 0) {
                throw new Exception("❌ El número de documento ya está registrado.");
            }


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
                    id_manual, ci, estado, direccion
                ) VALUES (
                    :id_manual, :ci, :estado, :direccion
                )
            ");
            $stmt->execute([
                ':id_manual' => $data['id_manual'],
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
                ':tipo_ayuda' => $data['tipo_ayuda'],
                ':categoria' => $data['categoria']
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
    
}
?>