<?php
require_once "../config/database.php";

class TareasModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
        date_default_timezone_set('America/Bogota');
    }

    // Obtener todas las tareas
    public function obtenerTareas() {
        $sql = "SELECT * FROM tareasDiarias ORDER BY id ASC";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Agregar tarea
    public function agregarTarea($nombre, $cantidad) {
        $stmt = $this->db->prepare("INSERT INTO tareasDiarias (nombre, cantidad_usuarios) VALUES (?, ?)");
        $stmt->bind_param("si", $nombre, $cantidad);
        return $stmt->execute();
    }
    public function eliminarTarea($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM tareasDiarias WHERE id = ?");
            if (!$stmt) {
                return ['ok' => false, 'msg' => 'Error al preparar: ' . $this->db->error];
            }

            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                return ['ok' => true, 'msg' => 'Tarea eliminada correctamente'];
            } else {
                return ['ok' => false, 'msg' => 'Error al ejecutar: ' . $stmt->error];
            }
        } catch (Exception $e) {
            return ['ok' => false, 'msg' => $e->getMessage()];
        }
    }

    // Obtener operadores (opcional por sede)
    public function obtenerOperadores($sede = '') {

        // Fecha de hoy (formato YYYY-MM-DD)
        $hoy = date('Y-m-d');

        if ($sede !== '') {

            $stmt = $this->db->prepare("
                SELECT 
                    idusuarios AS id,
                    usu_nombre AS nombre,
                    usu_identificacion AS cedula,
                    usu_celular AS celular,
                    usu_mail AS correo
                FROM usuarios
                WHERE usu_idsede = ?
                AND idusuarios NOT IN (
                    SELECT operador_id 
                    FROM asignaciones 
                    WHERE fecha = ?
                )
            ");

            $stmt->bind_param("is", $sede, $hoy);
            $stmt->execute();
            $res = $stmt->get_result();
            return $res->fetch_all(MYSQLI_ASSOC);
        }

        // Si no filtra por sede
        $stmt = $this->db->prepare("
            SELECT 
                idusuarios AS id,
                usu_nombre AS nombre,
                usu_identificacion AS cedula,
                usu_celular AS celular,
                usu_mail AS correo
            FROM usuarios
            WHERE idusuarios NOT IN (
                SELECT operador_id 
                FROM asignaciones 
                WHERE fecha = ?
            )
            ORDER BY nombre
        ");

        $stmt->bind_param("s", $hoy);
        $stmt->execute();
        $res = $stmt->get_result();

        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerUsuarios($fecha = '') {
        $fecha= date('Y-m-d');

        $sql = " SELECT `idusuarios`,concat_ws(' / ',usu_nombre,zon_nombre) as nombre FROM  seguimiento_user inner join zonatrabajo on seg_idzona=idzonatrabajo  inner join  `usuarios` on idusuarios=seg_idusuario  WHERE `roles_idroles` in (3) and seg_fechaalcohol='$fecha' and (usu_estado=1 or usu_filtro=1)and usu_idsede='1'"; 
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        return $usuarios;
    }

    // Obtener asignaciones de una semana (receives Monday date or current week)
    public function obtenerAsignacionesSemana($fecha_inicio = '') {
        if (empty($fecha_inicio)) {
            $inicio_semana = date('Y-m-d', strtotime('monday this week'));
            $fin_semana = date('Y-m-d', strtotime('sunday this week'));
        } else {
            $inicio_semana = date('Y-m-d', strtotime($fecha_inicio));
            $fin_semana = date('Y-m-d', strtotime($inicio_semana . ' +6 days'));
        }

        $sql = "SELECT a.id, a.fecha, o.idusuarios as operador_id, o.usu_nombre as operador, o.usu_identificacion as cedula, t.id as tarea_id, t.nombre as tarea
                FROM asignaciones a
                JOIN usuarios o ON o.idusuarios = a.operador_id
                JOIN tareasDiarias t ON t.id = a.tarea_id
                WHERE a.fecha BETWEEN ? AND ?
                ORDER BY a.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $inicio_semana, $fin_semana);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

// Lógica sorteo: asigna tareas del día actual sin repetir la misma tarea para un operador en la semana
public function sortearTareasDelDia($fecha = '', $operadoresSeleccionados, $tarea_id = null) {
    $logFile = __DIR__ . '/debug_sorteo.log';
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] === INICIO sortearTareasDelDia() ===\n", FILE_APPEND);

    // Si no viene fecha, usamos la actual
    $fecha = date('Y-m-d');

    $inicio_semana = date('Y-m-d', strtotime('monday this week'));
    $fin_semana = date('Y-m-d', strtotime('sunday this week'));
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Fecha: $fecha | Semana: $inicio_semana - $fin_semana\n", FILE_APPEND);

    // Validar que haya operadores seleccionados
    if (empty($operadoresSeleccionados) || !is_array($operadoresSeleccionados)) {
        file_put_contents($logFile, "No se recibieron operadores seleccionados\n", FILE_APPEND);
        return ['ok' => false, 'msg' => 'No se recibieron operadores seleccionados'];
    }

    // 1️⃣ Traer solo los operadores seleccionados
    $ids = implode(',', array_map('intval', $operadoresSeleccionados));

    $opsRes = $this->db->query("SELECT idusuarios FROM usuarios WHERE idusuarios IN ($ids)");
    if (!$opsRes) file_put_contents($logFile, "ERROR SQL operadores: " . $this->db->error . "\n", FILE_APPEND);
    $operadores = $opsRes ? $opsRes->fetch_all(MYSQLI_ASSOC) : [];
    file_put_contents($logFile, "Operadores encontrados: " . count($operadores) . "\n", FILE_APPEND);

    // 2️⃣ Traer tareas (todas o una sola si se seleccionó)
    if (!empty($tarea_id)) {
        $tareasRes = $this->db->query("SELECT * FROM tareasDiarias WHERE id = " . intval($tarea_id));
        file_put_contents($logFile, "🔸 Modo tarea específica: ID=$tarea_id\n", FILE_APPEND);
    } else {
        $tareasRes = $this->db->query("SELECT * FROM tareasDiarias");
        file_put_contents($logFile, "🔹 Modo todas las tareas\n", FILE_APPEND);
    }

    if (!$tareasRes) file_put_contents($logFile, "ERROR SQL tareas: " . $this->db->error . "\n", FILE_APPEND);
    $tareas = $tareasRes ? $tareasRes->fetch_all(MYSQLI_ASSOC) : [];
    file_put_contents($logFile, "Tareas encontradas: " . count($tareas) . "\n", FILE_APPEND);

    if (empty($operadores) || empty($tareas)) {
        file_put_contents($logFile, "No hay operadores o tareas configuradas\n", FILE_APPEND);
        return ['ok' => false, 'msg' => 'No hay operadores o tareas configuradas'];
    }

    // Mezclar para sorteo aleatorio
    shuffle($operadores);

    // Guardar los que ya fueron asignados hoy (para no repetir dentro del sorteo)
    $operadoresAsignadosHoy = [];

    foreach ($tareas as $tarea) {
        $tareaId = $tarea['id'];
        $cantidad = (int)$tarea['cantidad_usuarios'];
        file_put_contents($logFile, "Procesando tarea ID=$tareaId (cantidad: $cantidad)\n", FILE_APPEND);

        // 3️⃣ Operadores que ya hicieron esta tarea en la semana
        $stmt = $this->db->prepare("SELECT operador_id FROM asignaciones WHERE tarea_id = ? AND fecha BETWEEN ? AND ?");
        if (!$stmt) {
            file_put_contents($logFile, "ERROR prepare stmt (tarea_id=$tareaId): " . $this->db->error . "\n", FILE_APPEND);
            continue;
        }

        $stmt->bind_param("iss", $tareaId, $inicio_semana, $fin_semana);
        $stmt->execute();
        $r = $stmt->get_result();
        if (!$r) {
            file_put_contents($logFile, "ERROR get_result (tarea_id=$tareaId): " . $stmt->error . "\n", FILE_APPEND);
            continue;
        }

        $rows = $r->fetch_all(MYSQLI_ASSOC);
        $ids_asignados_semana = array_column($rows, 'operador_id');
        file_put_contents($logFile, "Operadores ya asignados a esta tarea esta semana: " . count($ids_asignados_semana) . "\n", FILE_APPEND);

        // 4️⃣ Filtrar operadores disponibles para esta tarea
        $disponibles = array_values(array_filter($operadores, function($op) use ($ids_asignados_semana, $operadoresAsignadosHoy) {
            $id = $op['idusuarios'];
            // No debe haber hecho esta tarea en la semana ni tener ya tarea hoy
            return !in_array($id, $ids_asignados_semana) && !in_array($id, $operadoresAsignadosHoy);
        }));

        file_put_contents($logFile, "Disponibles para tarea $tareaId: " . count($disponibles) . "\n", FILE_APPEND);

        // Si no hay suficientes disponibles, usar todos los que no repitan la tarea semanal
        if (count($disponibles) < $cantidad) {
            file_put_contents($logFile, "⚠️ No hay suficientes disponibles, usando todos los no repetidos semanalmente\n", FILE_APPEND);
            $disponibles = array_values(array_filter($operadores, function($op) use ($ids_asignados_semana) {
                return !in_array($op['idusuarios'], $ids_asignados_semana);
            }));
        }
        // 🚨 Último fallback: todos ya hicieron esta tarea esta semana
        if (empty($disponibles)) {
            file_put_contents(
                $logFile,
                "🚨 Sin operadores disponibles sin repetir tarea semanal. Se permite repetir tarea.\n",
                FILE_APPEND
            );

            // Intentar primero no repetir operador en el día
            $disponibles = array_values(array_filter($operadores, function($op) use ($operadoresAsignadosHoy) {
                return !in_array($op['idusuarios'], $operadoresAsignadosHoy);
            }));

            // Si igual no hay, usar todos
            if (empty($disponibles)) {
                $disponibles = $operadores;
            }
        }

        shuffle($disponibles);
        $seleccionados = array_slice($disponibles, 0, $cantidad);
        file_put_contents($logFile, "Seleccionados para tarea $tareaId: " . json_encode(array_column($seleccionados, 'idusuarios')) . "\n", FILE_APPEND);

        // 5️⃣ Insertar asignaciones
        $ins = $this->db->prepare("INSERT INTO asignaciones (operador_id, tarea_id, fecha) VALUES (?, ?, ?)");
        if (!$ins) {
            file_put_contents($logFile, "ERROR prepare INSERT (tarea_id=$tareaId): " . $this->db->error . "\n", FILE_APPEND);
            continue;
        }

        foreach ($seleccionados as $op) {
            $operadorId = $op['idusuarios'];

            // Verificar si ya tiene tarea hoy (por seguridad)
            $chkDia = $this->db->prepare("SELECT id FROM asignaciones WHERE operador_id = ? AND fecha = ?");
            $chkDia->bind_param("is", $operadorId, $fecha);
            $chkDia->execute();
            $resChkDia = $chkDia->get_result();
            if ($resChkDia && $resChkDia->fetch_assoc()) {
                file_put_contents($logFile, "⏭️ Operador $operadorId ya tiene tarea hoy, se omite\n", FILE_APPEND);
                continue;
            }

            // Insertar si cumple las condiciones
            $ins->bind_param("iis", $operadorId, $tareaId, $fecha);
            if ($ins->execute()) {
                file_put_contents($logFile, "✅ Insertado operador $operadorId en tarea $tareaId\n", FILE_APPEND);
                $operadoresAsignadosHoy[] = $operadorId; // Marcar como asignado hoy
            } else {
                file_put_contents($logFile, "❌ ERROR insert (op=$operadorId, tarea=$tareaId): " . $ins->error . "\n", FILE_APPEND);
            }
        }
    }

    file_put_contents($logFile, "=== FIN sortearTareasDelDia() ===\n\n", FILE_APPEND);
    return ['ok' => true];
}




    // Método util: verificar asignación existente por semana/tarea/operador (opcional)
    public function operadorTieneTareaEnSemana($operador_id, $tarea_id, $inicio_semana, $fin_semana) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as c FROM asignaciones WHERE operador_id = ? AND tarea_id = ? AND fecha BETWEEN ? AND ?");
        $stmt->bind_param("iiss", $operador_id, $tarea_id, $inicio_semana, $fin_semana);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_assoc();
        return (int)$r['c'] > 0;
    }


    public function eliminarAsignacion($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM asignaciones WHERE id = ?");
            if (!$stmt) {
                return ['ok' => false, 'msg' => 'Error al preparar: ' . $this->db->error];
            }

            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                return ['ok' => true, 'msg' => 'Asignación eliminada correctamente'];
            } else {
                return ['ok' => false, 'msg' => 'Error al ejecutar: ' . $stmt->error];
            }

        } catch (Exception $e) {
            return ['ok' => false, 'msg' => $e->getMessage()];
        }
    }
}
