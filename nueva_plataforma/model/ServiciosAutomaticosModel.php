<?php
require_once "../config/database.php";

class serviciosAuto {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function obtenerSerProgramados($filtroFecha = '', $filtroCliente = '') {

        // Establecer zona horaria de Bogotá
        date_default_timezone_set('America/Bogota');
        // Si no se pasa una fecha, usa la fecha actual

        
        $sql = "SELECT 
                sa.aut_id,
                c.ciu_nombre AS ciudad_origen,
                COALESCE(cr.cre_nombre, 'EXTERNO') AS cliente,
                sa.aut_dias,
                sa.aut_telefono,
                sa.aut_direccion,
                sa.aut_ciudad_origen,
                aut_fecha
            FROM servicios_automaticos sa
            JOIN ciudades c ON sa.aut_ciudad_origen = c.idciudades
            LEFT JOIN creditos cr ON sa.aut_cliente = cr.idcreditos";

        // ✅ Guardar consulta en log para depuración
        $logPath = __DIR__ . '/log_consultas.txt'; // puedes cambiar el nombre/ruta si quieres
        $logMessage = "[" . date("Y-m-d H:i:s") . "] SQL: $sql\n";
        file_put_contents($logPath, $logMessage, FILE_APPEND);
        
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerRoles() {
        $sql = "SELECT idroles, rol_nombre FROM roles ORDER BY rol_nombre";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function actualizarCampo($id, $campo, $valor) {
        $permitidos = ['usu_filtro', 'usu_ver_nomina', 'usu_estado'];
        if (!in_array($campo, $permitidos)) return;

        $sql = "UPDATE usuarios SET $campo = ? WHERE idusuarios = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $valor, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function obtenerCiudades() {
        $sql = "SELECT `idciudades`, `ciu_nombre` FROM `ciudades`  where inner_estados=1 ";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public function obtenerClientes() {
        $sql = "SELECT idcreditos,cre_nombre FROM  creditos  where idcreditos>0 ";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public function crearServicioAutomatico($cliente, $ciudad, $dias,$telefono,$direccion,$hora)
    {
        // Ruta del archivo de logs
        $logFile = __DIR__ . '/servicios_automaticos.log';

        // Convertimos el array de días a JSON
        $direccion = str_replace(' ', '&', $direccion);
        $dias_json = json_encode($dias);
        if ($dias_json === false) {
            error_log("[" . date("Y-m-d H:i:s") . "] Error al convertir días a JSON: " . json_last_error_msg() . PHP_EOL, 3, $logFile);
            return false;
        }

        $telefono_json = json_encode($telefono);
        if ($telefono_json === false) {
            error_log("[" . date("Y-m-d H:i:s") . "] Error al convertir telefonos a JSON: " . json_last_error_msg() . PHP_EOL, 3, $logFile);
            return false;
        }

        // Preparar consulta
        $sql = "INSERT INTO servicios_automaticos (aut_cliente, aut_ciudad_origen, aut_dias,aut_telefono,aut_direccion,aut_fecha) VALUES (?, ?, ?,?,?,?)";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log("[" . date("Y-m-d H:i:s") . "] Error al preparar SQL: " . $this->db->error . PHP_EOL, 3, $logFile);
            return false;
        }

        // Log de datos que se van a insertar
        error_log("[" . date("Y-m-d H:i:s") . "] Insertando servicio automático - Cliente: $cliente, Ciudad: $ciudad, Días JSON: $dias_json, Direccion JSON: $direccion, HORA: $hora" . PHP_EOL, 3, $logFile);

        // Vincular parámetros
        $stmt->bind_param("ssssss", $cliente, $ciudad, $dias_json,$telefono_json,$direccion,$hora);

        // Ejecutar
        $resultado = $stmt->execute();

        if (!$resultado) {
            error_log("[" . date("Y-m-d H:i:s") . "] Error al ejecutar SQL: " . $stmt->error . PHP_EOL, 3, $logFile);
        } else {
            error_log("[" . date("Y-m-d H:i:s") . "] Servicio automático insertado exitosamente." . PHP_EOL, 3, $logFile);
        }

        // Cerrar statement
        $stmt->close();

        return $resultado;
    }

    public function eliminarServicio($id) {
        $sql = "DELETE FROM servicios_automaticos WHERE aut_id = ?";
        
        if (!$stmt = $this->db->prepare($sql)) {
            $this->logError("Error al preparar SQL (ID $id): " . $this->db->errno . " - " . $this->db->error);
            return false;
        }

        if (!$stmt->bind_param("i", $id)) {
            $this->logError("Error al bind_param (ID $id): " . $stmt->errno . " - " . $stmt->error);
            return false;
        }

        if (!$stmt->execute()) {
            $this->logError("Error al ejecutar DELETE (ID $id): " . $stmt->errno . " - " . $stmt->error);
            return false;
        }

        return true;
    }

    // Función para registrar logs
    private function logError($mensaje) {
        $fecha = date("Y-m-d H:i:s");
        $logMessage = "[$fecha] $mensaje" . PHP_EOL;
        file_put_contents(__DIR__ . "/logs_servicios_auto.log", $logMessage, FILE_APPEND);
    }
    private function logSql($mensaje) {
        $logFile = __DIR__ . '/serviciosCreadosAutomaticos.log';
        $fecha = date("Y-m-d H:i:s");
        file_put_contents($logFile, "[$fecha] $mensaje\n", FILE_APPEND);
    }
    public function insertarServicio($nombre,$telefono,$ciudad,$direccion) {
        $fechatiempo = date("Y-m-d H:i:s");
        $conn = $this->db;
        // Si es un array, tomar el primer elemento
        if (is_array($telefono) && !empty($telefono)) {
            $telefono = reset($telefono); // o $telefono[0]
        }

        try {
            $this->logSql("🚀 Iniciando proceso de inserción con búsqueda de remitente");

            // 🔍 Buscar datos del remitente
            $stmtR = $conn->prepare("
                SELECT cli_nombre, cli_telefono, cli_idciudad, cli_direccion, cli_idclientes
                FROM clientesdir
                WHERE cli_telefono = ?
                LIMIT 1
            ");
            if (!$stmtR) throw new Exception("Error prepare stmtR: " . $conn->error);

            $stmtR->bind_param("s", $telefono);
            if (!$stmtR->execute()) throw new Exception("Error execute stmtR: " . $stmtR->error);

            $datosR = $stmtR->get_result()->fetch_assoc();
            $stmtR->close();

            if ($datosR) {
                $this->logSql("📇 Remitente encontrado: " . json_encode($datosR));
                $nombre = $datosR['cli_nombre'];
                $telefono = $datosR['cli_telefono'];
                // $ciudad = $datosR['cli_idciudad'];
                // $direccion = $datosR['cli_direccion'];
                $idcliente = $datosR['cli_idclientes'];
            } else {
                $this->logSql("⚠️ No se encontró remitente con teléfono $telefono, usando datos vacíos");
                $nombre = '';
                // lo dejamos aunque no exista en BD
                $ciudad = 0;
                $direccion = '';
                $idcliente = 0;
            }

            // 📌 Insertar en clientesservicios
            $stmt1 = $conn->prepare("
                INSERT INTO clientesservicios (
                    cli_nombre, cli_telefono, cli_idciudad, cli_direccion, cli_idclientes, cli_principal
                ) VALUES (?, ?, ?, ?, ?, 1)
            ");
            if (!$stmt1) throw new Exception("Error prepare stmt1: " . $conn->error);

            $stmt1->bind_param("ssisi", $nombre, $telefono, $ciudad, $direccion, $idcliente);
            if (!$stmt1->execute()) throw new Exception("Error execute stmt1: " . $stmt1->error);
            $idcliservicios = $conn->insert_id;
            $stmt1->close();

            $this->logSql("✅ clientesservicios creado ID: $idcliservicios");

            // 📌 Insertar en servicios (destinatario vacío)
            $telefonoDesti = '';
            $nombreDesti = '';
            $direccionDesti = '';
            $ciudadDesti = '304';

            $stmt2 = $conn->prepare("
                INSERT INTO servicios (
                    ser_iddocumento, ser_telefonocontacto, ser_destinatario, ser_direccioncontacto,
                    ser_ciudadentrega, ser_tipopaquete, ser_paquetedescripcion, ser_fechaentrega,
                    ser_prioridad, ser_valorprestamo, ser_valorabono, ser_valorseguro,
                    ser_fecharegistro, ser_clasificacion, ser_img_whatsapp
                ) VALUES ('', ?, ?, ?, ?, '', '', '', '', 0, 0, '100000', ?, '', '')
            ");
            if (!$stmt2) throw new Exception("Error prepare stmt2: " . $conn->error);

            $stmt2->bind_param("sssss", $telefonoDesti, $nombreDesti, $direccionDesti, $ciudadDesti, $fechatiempo);
            if (!$stmt2->execute()) throw new Exception("Error execute stmt2: " . $stmt2->error);
            $idser = $conn->insert_id;
            $stmt2->close();

            $this->logSql("✅ servicio creado ID: $idser");

            // 📌 rel_sercli
            $stmt3 = $conn->prepare("INSERT INTO rel_sercli (ser_idclientes, ser_idservicio, ser_fechaingreso) VALUES (?, ?, ?)");
            if (!$stmt3) throw new Exception("Error prepare stmt3: " . $conn->error);

            $stmt3->bind_param("iis", $idcliservicios, $idser, $fechatiempo);
            if (!$stmt3->execute()) throw new Exception("Error execute stmt3: " . $stmt3->error);
            $stmt3->close();

            $this->logSql("🔗 rel_sercli OK");

            // 📌 rel_sercre vacío
            $nombrecredito = '';
            $stmtCred = $conn->prepare("INSERT INTO rel_sercre (idservicio, rel_nom_credito) VALUES (?, ?)");
            if (!$stmtCred) throw new Exception("Error prepare stmtCred: " . $conn->error);

            $stmtCred->bind_param("is", $idser, $nombrecredito);
            if (!$stmtCred->execute()) throw new Exception("Error execute stmtCred: " . $stmtCred->error);
            $stmtCred->close();

            $this->logSql("💳 rel_sercre insert OK");

            // 📌 guias
            $stmt4 = $conn->prepare("INSERT INTO guias (gui_idservicio, gui_idusuario, gui_usucreado, gui_fechacreacion, gui_tiposervicio) VALUES (?, '1919', 'whatsapp', ?, '')");
            if (!$stmt4) throw new Exception("Error prepare stmt4: " . $conn->error);

            $stmt4->bind_param("is", $idser, $fechatiempo);
            if (!$stmt4->execute()) throw new Exception("Error execute stmt4: " . $stmt4->error);
            $stmt4->close();

            $this->logSql("📦 guias insert OK");

            return $idser;

        } catch (Exception $e) {
            $this->logSql("❌ ERROR CRÍTICO: " . $e->getMessage());
            return null;
        }
    }
    public function enviarAlertaWhat($telefono, $tipo) {
        $url = "https://www.transmillas.com/ChatbotTransmillas/alertas.php";

        $data = array(
            "telefono" => $telefono,
            "tipo_alerta" => $tipo,

        );

        $data_json = json_encode($data);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer MiSuperToken123'
            ),
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            error_log("Error en alerta WhatsApp: $error");
        } else {
            $response_data = json_decode($response, true);
            error_log("WhatsApp enviado: " . print_r($response_data, true));
        }

        curl_close($curl);
    }
}
