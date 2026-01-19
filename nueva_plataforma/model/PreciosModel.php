<?php
require_once "../config/database.php";
require_once '../../PHPMailer/src/PHPMailer.php';
require_once '../../PHPMailer/src/SMTP.php';
require_once '../../PHPMailer/src/Exception.php';

// Importar clases
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Precios {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }
    public function logPreciosModel($msg) {
        $logFile = __DIR__ . "/logs_Precios.log";
        $fecha = date("Y-m-d H:i:s");
        file_put_contents($logFile, "[$fecha] $msg\n", FILE_APPEND);
    }
    public function obtenerPrecios($Origen="", $Destino="",$Creditos="",$Servicio="",$Estado="") {

        // Log de entrada a la función
        $this->logPreciosModel("Inicio obtenerPrecios | Origen=$Origen | Destino=$Destino | Creditos=$Creditos | Servicio=$Servicio | Estado=$Estado");

        $sql = "SELECT 
            pc.pre_estado,
            pc.idprecios,
            pc.pre_idciudadori,
            ori.ciu_nombre AS ciudad_origen,
            pc.pre_idciudaddes,
            des.ciu_nombre AS ciudad_destino,
            pc.pre_kilo,
            pc.pre_adicional,
            pc.pre_tiposervicio,
            pc.pre_fecha_inicial,
            pc.pre_fecha_final,

            CASE 
                WHEN pc.pre_tiposervicio = 0 THEN 'Carga vía terrestre'
                ELSE s.tip_nom
            END AS tip_nom,

            MAX(CASE WHEN ck.con_idprecios = 1 THEN ck.con_precios END) AS precio_6_20,
            MAX(CASE WHEN ck.con_idprecios = 2 THEN ck.con_precios END) AS precio_21_50,
            MAX(CASE WHEN ck.con_idprecios = 3 THEN ck.con_precios END) AS precio_51_100,
            MAX(CASE WHEN ck.con_idprecios = 4 THEN ck.con_precios END) AS precio_101_150,
            MAX(CASE WHEN ck.con_idprecios = 5 THEN ck.con_precios END) AS precio_151_200,
            MAX(CASE WHEN ck.con_idprecios = 6 THEN ck.con_precios END) AS precio_201_250

        FROM precios pc
        LEFT JOIN ciudades ori ON ori.idciudades = pc.pre_idciudadori
        LEFT JOIN ciudades des ON des.idciudades = pc.pre_idciudaddes
        LEFT JOIN tiposervicio s ON s.idtiposervicio = pc.pre_tiposervicio
        LEFT JOIN configuracionkilos ck
            ON ck.con_idprecioskilos = pc.idprecios
            AND ck.con_tipo = 'normal'
        WHERE pc.idprecios > 0";

        if (!empty($Origen) && !empty($Destino)) {
            $sql .= " AND (
                (pc.pre_idciudadori = '$Origen' AND pc.pre_idciudaddes = '$Destino')
                OR
                (pc.pre_idciudadori = '$Destino' AND pc.pre_idciudaddes = '$Origen')
            )";
        } else {
            if (!empty($Origen)) {
                $sql .= " AND pc.pre_idciudadori = '$Origen'";
            }
            if (!empty($Destino)) {
                $sql .= " AND pc.pre_idciudaddes = '$Destino'";
            }
        }

        // if ($Creditos !== '') {
        //     $sql .= " AND pre_idcredito = '$Creditos'";
        // }

        if ($Servicio !== '') {
            $sql .= " AND pre_tiposervicio = '$Servicio'";
        }

        if ($Estado !== '') {
            $sql .= " AND pre_estado = '$Estado'";
        }

        $sql .= " GROUP BY pc.idprecios
                ORDER BY pc.idprecios ASC";

        // Log del SQL final
        $this->logPreciosModel("SQL generado: $sql");

        $result = $this->db->query($sql);

        if (!$result) {
            // Log de error SQL
            $this->logPreciosModel("ERROR MySQL: " . $this->db->error);
            return [];
        }

        $total = $result->num_rows;
        $this->logPreciosModel("Consulta OK | Registros encontrados: $total");

        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function obtenerRoles() {
        $sql = "SELECT idroles, rol_nombre FROM roles ORDER BY rol_nombre";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public function obtenerCiudades() {
        $sql = "SELECT `idciudades`, `ciu_nombre` FROM `ciudades`  where inner_estados=1 order by idciudades";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    public function obtenerCreditos() {
        $sql = "SELECT idcreditos,cre_nombre FROM  creditos  where idcreditos>0 ";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function tiposDeServicios() {
        $sql = "SELECT idtiposervicio,tip_nom FROM  tiposervicio Where  LOWER(tip_estado_final) = 'activo' ";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerPrecioCreditoPorId($id)
    {
        $id = intval($id);

        $sql = "SELECT 
                    pc.*,

                    MAX(CASE WHEN ck.con_idprecios = 1 THEN ck.con_precios END) AS precio_6_20,
                    MAX(CASE WHEN ck.con_idprecios = 2 THEN ck.con_precios END) AS precio_21_50,
                    MAX(CASE WHEN ck.con_idprecios = 3 THEN ck.con_precios END) AS precio_51_100,
                    MAX(CASE WHEN ck.con_idprecios = 4 THEN ck.con_precios END) AS precio_101_150,
                    MAX(CASE WHEN ck.con_idprecios = 5 THEN ck.con_precios END) AS precio_151_200,
                    MAX(CASE WHEN ck.con_idprecios = 6 THEN ck.con_precios END) AS precio_201_250

                FROM precios pc
                LEFT JOIN configuracionkilos ck
                        ON ck.con_idprecioskilos = pc.idprecios
                       AND ck.con_tipo = 'normal'
                WHERE pc.idprecios = $id
                GROUP BY pc.idprecios";

        $result = $this->db->query($sql);
        if (!$result) {
            error_log("[ERROR SQL] " . $this->db->error);
            return [];
        }
        return $result->fetch_assoc();
    }

   public function actualizarPrecioCredito($data)
    {
        $this->logPreciosModel("==== INICIO actualizarPrecioCredito ====");
        $this->logPreciosModel("DATA RECIBIDA: " . json_encode($data));

        $id = intval($data['id']);
        $editActual  = $this->db->real_escape_string($data['editar_precio']);

        $this->logPreciosModel("Modo edición: editar_precio = {$editActual}");

        if ($data['editar_precio'] == 1) {

            $this->logPreciosModel("➡️ NUEVO PRECIO (insertar)");

            $credito  = $this->db->real_escape_string($data['pre_idcredito']);
            $origen   = $this->db->real_escape_string($data['pre_idciudadori']);
            $destino  = $this->db->real_escape_string($data['pre_idciudades']);
            $primeros = $this->db->real_escape_string($data['pre_preciokilo']);
            $servicio = $this->db->real_escape_string($data['pre_tiposervicio']);
            $fechaInicial = $this->db->real_escape_string($data['pre_fecha_ini']);
            $fechaFinal = $this->db->real_escape_string($data['pre_fecha_fin']);

            $rangos = [
                1 => $data['precio_6_20']    ?? 0,
                2 => $data['precio_21_50']   ?? 0,
                3 => $data['precio_51_100']  ?? 0,
                4 => $data['precio_101_150'] ?? 0,
                5 => $data['precio_151_200'] ?? 0,
                6 => $data['precio_201_250'] ?? 0
            ];

            $this->logPreciosModel("Origen: {$origen} | Destino: {$destino} | Crédito: {$credito}");
            $this->logPreciosModel("Rangos: " . json_encode($rangos));

            // 🔄 Desactivar precios activos anteriores
            $idsActivos = $this->obtenerIdsPreciosCreditoActivos($origen, $destino,$servicio);

            if (!empty($idsActivos)) {
                $this->logPreciosModel("IDs activos encontrados: " . implode(',', $idsActivos));
                foreach ($idsActivos as $idPrecio) {
                    $this->actualizarCampo($idPrecio, 'pre_estado', 0);
                    $this->logPreciosModel("Precio ID {$idPrecio} desactivado");
                }
            } else {
                $this->logPreciosModel("No se encontraron precios activos previos");
            }

            // 1️⃣ Guardar origen → destino
            $ok1 = $this->insertarPrecioCredito(
                $credito, $origen, $destino, $primeros, $servicio, $rangos, $fechaInicial, $fechaFinal
            );
            $this->logPreciosModel("Insertar ORIGEN→DESTINO: " . ($ok1 ? "OK" : "ERROR"));

            // 2️⃣ Guardar destino → origen
            $ok2 = $this->insertarPrecioCredito(
                $credito, $destino, $origen, $primeros, $servicio, $rangos, $fechaInicial, $fechaFinal
            );
            $this->logPreciosModel("Insertar DESTINO→ORIGEN: " . ($ok2 ? "OK" : "ERROR"));

            if ($ok1 && $ok2) {
                $this->logPreciosModel("✅ PROCESO COMPLETADO CORRECTAMENTE");
                return true;
            } else {
                $this->logPreciosModel("❌ ERROR AL INSERTAR PRECIOS");
            }

        } else {

            $this->logPreciosModel("✏️ EDICIÓN DE PRECIO EXISTENTE");

            $pre_idcredito    = $this->db->real_escape_string($data['pre_idcredito']);
            $pre_idciudadori  = $this->db->real_escape_string($data['pre_idciudadori']);
            $pre_idciudades   = $this->db->real_escape_string($data['pre_idciudades']);
            $pre_preciokilo   = $this->db->real_escape_string($data['pre_preciokilo']);
            $pre_tiposervicio = $this->db->real_escape_string($data['pre_tiposervicio']);
            $fechaInicial = $this->db->real_escape_string($data['pre_fecha_ini']);
            $fechaFinal = $this->db->real_escape_string($data['pre_fecha_fin']);

            $rangos = [
                1 => $data['precio_6_20'] ?? 0,
                2 => $data['precio_21_50'] ?? 0,
                3 => $data['precio_51_100'] ?? 0,
                4 => $data['precio_101_150'] ?? 0,
                5 => $data['precio_151_200'] ?? 0,
                6 => $data['precio_201_250'] ?? 0
            ];

            $this->logPreciosModel("Editando precios Origen: {$pre_idciudadori} | Destino: {$pre_idciudades}");
            $this->logPreciosModel("Rangos nuevos: " . json_encode($rangos));

            // $idsActivos = $this->obtenerIdsPreciosCreditoActivos($pre_idciudadori, $pre_idciudades);
              $ok1  = $this->editarPrecioCredito($id,$pre_idcredito,$pre_idciudadori,$pre_idciudades,$pre_preciokilo,$pre_tiposervicio,$rangos,$fechaInicial,$fechaFinal  );

            // if (!empty($idsActivos)) {
            //     $this->logPreciosModel("IDs a editar: " . implode(',', $idsActivos));
            //     foreach ($idsActivos as $idPrecio) {
            //         $ok1 = $this->editarPrecioCredito(
            //             $idPrecio,
            //             $pre_idcredito,
            //             $pre_idciudadori,
            //             $pre_idciudades,
            //             $pre_preciokilo,
            //             $pre_tiposervicio,
            //             $rangos,
            //             $fechaInicial,
            //             $fechaFinal
            //         );
            //         $this->logPreciosModel("Editar Precio ID {$idPrecio}: " . ($ok1 ? "OK" : "ERROR"));
            //     }
            // } else {
            //     $this->logPreciosModel("⚠️ No se encontraron precios activos para editar");
            // }

            if ($ok1) {
                $this->logPreciosModel("✅ EDICIÓN COMPLETADA");
                return true;
            } else {
                $this->logPreciosModel("❌ ERROR EN EDICIÓN");
            }
        }

        $this->logPreciosModel("==== FIN actualizarPrecioCredito ====");
        return false;
    }

    private function obtenerIdsPreciosCreditoActivos($origen, $destino, $tipoServicio)
    {
        $this->logPreciosModel(
            "🔎 INICIO obtenerIdsPreciosCreditoActivos | Origen: {$origen} | Destino: {$destino} | Servicio: {$tipoServicio}"
        );

        $ids = [];

        $sql = "SELECT idprecios
                FROM precios
                WHERE pre_estado = 1
                AND (
                        (pre_idciudadori = '$origen' AND pre_idciudaddes = '$destino')
                    OR  (pre_idciudadori = '$destino' AND pre_idciudaddes = '$origen')
                )
                AND pre_tiposervicio = '$tipoServicio'";

        $this->logPreciosModel("SQL Ejecutado: {$sql}");

        $result = $this->db->query($sql);

        if (!$result) {
            $this->logPreciosModel("❌ ERROR SQL obtenerIdsPreciosCreditoActivos | " . $this->db->error);
            return [];
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ids[] = $row['idprecios'];
            }
            $this->logPreciosModel("✅ IDs activos encontrados: " . implode(',', $ids));
        } else {
            $this->logPreciosModel("⚠️ No se encontraron precios activos");
        }

        $this->logPreciosModel("⬅️ FIN obtenerIdsPreciosCreditoActivos");
        return $ids;
    }
    private function logEditarPrecioCredito($mensaje, $nivel = 'INFO')
    {
        $rutaLog = __DIR__ . '/logs/editar_precio_credito.log';

        // Crear carpeta si no existe
        if (!file_exists(dirname($rutaLog))) {
            mkdir(dirname($rutaLog), 0777, true);
        }

        $fecha = date('Y-m-d H:i:s');
        $linea = "[$fecha][$nivel] $mensaje" . PHP_EOL;

        file_put_contents($rutaLog, $linea, FILE_APPEND);
    }

    private function editarPrecioCredito(
        $idPrecioCredito,
        $credito,
        $origen,
        $destino,
        $primeros,
        $servicio,
        $rangos,
        $fechaInicial,
        $fechaFinal
    ) {
        $this->logEditarPrecioCredito("INICIO edición precio crédito | ID: $idPrecioCredito");

        // SQL principal
        $sql = "UPDATE precios_credito SET
                    pre_idcredito     = '$credito',
                    pre_idciudadori   = '$origen',
                    pre_idciudades    = '$destino',
                    pre_preciokilo    = '$primeros',
                    pre_tiposervicio  = '$servicio',
                    pre_fecha_inicial = '$fechaInicial',
                    pre_fecha_final   = '$fechaFinal'
                WHERE idprecioscredito  = '$idPrecioCredito'";

        $this->logEditarPrecioCredito("SQL UPDATE precios_credito: $sql");

        if (!$this->db->query($sql)) {
            $this->logEditarPrecioCredito(
                "ERROR UPDATE precios_credito | MySQL: " . $this->db->error,
                "ERROR"
            );
            return false;
        }

        $this->logEditarPrecioCredito("UPDATE precios_credito ejecutado correctamente");

        // Procesar rangos
        foreach ($rangos as $idRango => $valor) {
            $valor = $this->db->real_escape_string($valor);

            $this->logEditarPrecioCredito(
                "Procesando rango | ID Rango: $idRango | Valor: $valor"
            );

            $check = "SELECT idconfiguracionkilos 
                    FROM configuracionkilos
                    WHERE con_idprecioskilos = '$idPrecioCredito'
                    AND con_idprecios = '$idRango'
                    AND con_tipo = 'Credito'";

            $this->logEditarPrecioCredito("SQL CHECK rango: $check");

            $result = $this->db->query($check);

            if ($result && $result->num_rows > 0) {

                $sqlR = "UPDATE configuracionkilos SET
                            con_precios = '$valor'
                        WHERE con_idprecioskilos = '$idPrecioCredito'
                        AND con_idprecios = '$idRango'
                        AND con_tipo = 'Credito'";

                $this->logEditarPrecioCredito("SQL UPDATE rango: $sqlR");

            } else {

                $sqlR = "INSERT INTO configuracionkilos
                            (con_idprecioskilos, con_idprecios, con_precios, con_tipo)
                        VALUES
                            ('$idPrecioCredito', '$idRango', '$valor', 'Credito')";

                $this->logEditarPrecioCredito("SQL INSERT rango: $sqlR");
            }

            if (!$this->db->query($sqlR)) {
                $this->logEditarPrecioCredito(
                    "ERROR rango ID $idRango | MySQL: " . $this->db->error,
                    "ERROR"
                );
            } else {
                $this->logEditarPrecioCredito(
                    "Rango ID $idRango procesado correctamente"
                );
            }
        }

        $this->logEditarPrecioCredito("FIN edición precio crédito | ID: $idPrecioCredito");

        return true;
    }


    public function agregarPrecioCredito($d)
    {
        $credito  = $this->db->real_escape_string($d['credito']);
        $origen   = $this->db->real_escape_string($d['origen']);
        $destino  = $this->db->real_escape_string($d['destino']);
        $primeros = $this->db->real_escape_string($d['primeros']);
        $servicio = $this->db->real_escape_string($d['servicio']);
        $fechaInicial = $this->db->real_escape_string($d['FechaInicial']);
        $fechaFinal = $this->db->real_escape_string($d['FechaFinal']);


        $rangos = [
            1 => $d['precio_6_20']    ?? 0,
            2 => $d['precio_21_50']   ?? 0,
            3 => $d['precio_51_100']  ?? 0,
            4 => $d['precio_101_150'] ?? 0,
            5 => $d['precio_151_200'] ?? 0,
            6 => $d['precio_201_250'] ?? 0
        ];

        // 1️⃣ Guardar origen → destino
        $ok1 = $this->insertarPrecioCredito(
            $credito, $origen, $destino, $primeros, $servicio, $rangos, $fechaInicial, $fechaFinal
        );

        // 2️⃣ Guardar destino → origen (INVERSO)
        $ok2 = $this->insertarPrecioCredito(
            $credito, $destino, $origen, $primeros, $servicio, $rangos, $fechaInicial, $fechaFinal
        );

        return $ok1 && $ok2;
    }
    private function insertarPrecioCredito(
        $credito,
        $origen,
        $destino,
        $primeros,
        $servicio,
        $rangos,
        $fechaInicial,
        $fechaFinal
    ) {
        $this->logPreciosModel("➡️ INICIO insertarPrecioCredito | Origen: {$origen} | Destino: {$destino}");

        $sql = "INSERT INTO precios
                (pre_idciudadori, pre_idciudaddes, pre_kilo, pre_tiposervicio, pre_fecha_inicial, pre_fecha_final,pre_estado)
                VALUES 
                ('$origen', '$destino', '$primeros', '$servicio', '$fechaInicial', '$fechaFinal',1)";

        if (!$this->db->query($sql)) {
            $this->logPreciosModel("❌ ERROR INSERT precios | SQL ERROR: " . $this->db->error);
            return false;
        }

        $idNuevo = $this->db->insert_id;
        $this->logPreciosModel("✅ Precio insertado correctamente | ID: {$idNuevo}");

        foreach ($rangos as $idRango => $valor) {

            $valor = $this->db->real_escape_string($valor);

            $sqlR = "INSERT INTO configuracionkilos
                    (con_idprecioskilos, con_idprecios, con_precios, con_tipo)
                    VALUES ($idNuevo, $idRango, '$valor', 'normal')";

            if (!$this->db->query($sqlR)) {
                $this->logPreciosModel(
                    "❌ ERROR INSERT configuracionkilos | PrecioID: {$idNuevo} | Rango: {$idRango} | ERROR: " . $this->db->error
                );
            } else {
                $this->logPreciosModel(
                    "✔️ Rango guardado | PrecioID: {$idNuevo} | Rango: {$idRango} | Valor: {$valor}"
                );
            }
        }

        $this->logPreciosModel("⬅️ FIN insertarPrecioCredito | ID: {$idNuevo}");
        return true;
    }

    public function existePrecioCredito($d)
    {
        $this->logPreciosModel("🔎 INICIO existePrecioCredito");

        $credito  = $this->db->real_escape_string($d['credito']);
        $origen   = $this->db->real_escape_string($d['origen']);
        $destino  = $this->db->real_escape_string($d['destino']);
        $servicio = $this->db->real_escape_string($d['servicio']);
        $FechaInicial = $this->db->real_escape_string($d['FechaInicial']);
        $FechaFinal   = $this->db->real_escape_string($d['FechaFinal']);

        $this->logPreciosModel(
            "Parámetros | Crédito: {$credito} | Origen: {$origen} | Destino: {$destino} | Servicio: {$servicio} | Fechas: {$FechaInicial} - {$FechaFinal}"
        );

        $sql = "SELECT idprecios
                FROM precios
                WHERE pre_idciudadori  = '$origen'
                AND pre_idciudaddes  = '$destino'
                AND pre_tiposervicio = '$servicio'
                AND '$FechaInicial' >= pre_fecha_inicial
                AND '$FechaFinal'   <= pre_fecha_final
                LIMIT 1";

        $this->logPreciosModel("SQL Ejecutado: {$sql}");

        $res = $this->db->query($sql);

        if (!$res) {
            $this->logPreciosModel("❌ ERROR SQL existePrecioCredito | " . $this->db->error);
            return false;
        }

        if ($res->num_rows > 0) {
            $this->logPreciosModel("✅ Existe precio crédito");
            return true;
        }

        $this->logPreciosModel("⚠️ No existe precio crédito");
        return false;
    }

    public function eliminarPrecioCredito($id)
    {
        $id = intval($id);

        // Primero borramos configuracionkilos asociada
        $sql1 = "DELETE FROM configuracionkilos WHERE con_idprecioskilos = $id AND con_tipo = 'normal'";
        if (!$this->db->query($sql1)) {
            error_log("[ERROR SQL DELETE configuracionkilos] " . $this->db->error);
        }

        // Luego el registro principal
        $sql2 = "DELETE FROM precios WHERE idprecios = $id";
        if (!$this->db->query($sql2)) {
            error_log("[ERROR SQL DELETE precios] " . $this->db->error);
            return false;
        }

        return true;
    }

    public function buscarReferencia($origen, $destino, $servicio)
{

            date_default_timezone_set('America/Bogota');
        $this->logPreciosModel("=== INICIO buscarReferencia() ===");


    $origen   = $this->db->real_escape_string($origen);
    $destino  = $this->db->real_escape_string($destino);
    $servicio = $this->db->real_escape_string($servicio);

    $sql = "SELECT 
                pc.idprecios,
                pc.pre_kilo,
                

                MAX(CASE WHEN ck.con_idprecios = 1 THEN ck.con_precios END) AS precio_6_20,
                MAX(CASE WHEN ck.con_idprecios = 2 THEN ck.con_precios END) AS precio_21_50,
                MAX(CASE WHEN ck.con_idprecios = 3 THEN ck.con_precios END) AS precio_51_100,
                MAX(CASE WHEN ck.con_idprecios = 4 THEN ck.con_precios END) AS precio_101_150,
                MAX(CASE WHEN ck.con_idprecios = 5 THEN ck.con_precios END) AS precio_151_200,
                MAX(CASE WHEN ck.con_idprecios = 6 THEN ck.con_precios END) AS precio_201_250

            FROM precios pc
            LEFT JOIN configuracionkilos ck
                    ON ck.con_idprecioskilos = pc.idprecios
                   AND ck.con_tipo = 'normal'
            WHERE pc.pre_idciudadori = '$origen'
              AND pc.pre_idciudaddes  = '$destino'
              AND pc.pre_tiposervicio = '$servicio'
              AND pc.pre_estado = 1
            GROUP BY pc.idprecios";

    $res = $this->db->query($sql);

    if (!$res) {
        error_log("[ERROR SQL buscarReferencia] " . $this->db->error);
        return [];
    }

    return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function existeRegistroCompleto($d)
    {
        $credito  = $this->db->real_escape_string($d['credito']);
        $origen   = $this->db->real_escape_string($d['origen']);
        $destino  = $this->db->real_escape_string($d['destino']);
        $servicio = $this->db->real_escape_string($servicio = $d['servicio']);

        $primeros = $this->db->real_escape_string($d['primeros']);

        $p6   = $this->db->real_escape_string($d['precio_6_20']);
        $p21  = $this->db->real_escape_string($d['precio_21_50']);
        $p51  = $this->db->real_escape_string($d['precio_51_100']);
        $p101 = $this->db->real_escape_string($d['precio_101_150']);
        $p151 = $this->db->real_escape_string($d['precio_151_200']);
        $p201 = $this->db->real_escape_string($d['precio_201_250']);

        $sql = "SELECT pc.idprecios
                FROM precios pc
                LEFT JOIN configuracionkilos ck1  ON ck1.con_idprecioskilos = pc.idprecios AND ck1.con_idprecios = 1 AND ck1.con_tipo = 'normal'
                LEFT JOIN configuracionkilos ck2  ON ck2.con_idprecioskilos = pc.idprecios AND ck2.con_idprecios = 2 AND ck2.con_tipo = 'normal'
                LEFT JOIN configuracionkilos ck3  ON ck3.con_idprecioskilos = pc.idprecios AND ck3.con_idprecios = 3 AND ck3.con_tipo = 'normal'
                LEFT JOIN configuracionkilos ck4  ON ck4.con_idprecioskilos = pc.idprecios AND ck4.con_idprecios = 4 AND ck4.con_tipo = 'normal'
                LEFT JOIN configuracionkilos ck5  ON ck5.con_idprecioskilos = pc.idprecios AND ck5.con_idprecios = 5 AND ck5.con_tipo = 'normal'
                LEFT JOIN configuracionkilos ck6  ON ck6.con_idprecioskilos = pc.idprecios AND ck6.con_idprecios = 6 AND ck6.con_tipo = 'normal'
                WHERE  pc.pre_idciudadori = '$origen'
                AND pc.pre_idciudaddes  = '$destino'
                AND pc.pre_tiposervicio = '$servicio'
                AND pc.pre_kilo   = '$primeros'
                AND IFNULL(ck1.con_precios,0) = '$p6'
                AND IFNULL(ck2.con_precios,0) = '$p21'
                AND IFNULL(ck3.con_precios,0) = '$p51'
                AND IFNULL(ck4.con_precios,0) = '$p101'
                AND IFNULL(ck5.con_precios,0) = '$p151'
                AND IFNULL(ck6.con_precios,0) = '$p201'
                LIMIT 1";

        $res = $this->db->query($sql);

        if (!$res) {
            error_log("[ERROR SQL existeRegistroCompleto] " . $this->db->error);
            return false;
        }

        return $res->num_rows > 0;
    }
    public function actualizarCampo($id, $campo, $valor) {
        $permitidos = ['pre_estado'];
        if (!in_array($campo, $permitidos)) return;

        $sql = "UPDATE precios SET $campo = ? WHERE idprecios = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $valor, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function enviarPreciosCorreo($idCredito, $correo, $rutaExcel = null, $info = [])
    {
        try {


                // ✉️ PHPMailer
            // $mail = new PHPMailer(true);

            // $mail->isSMTP();
            // // $mail->Host       = 'smtp.gmail.com';
            // $mail->Host       = 'smtp.hostinger.com';

            // $mail->SMTPAuth   = true;
            // // $mail->Username   = 'facturaciontransmillas@gmail.com';
            // $mail->Username   = 'comunicacion@transmillas.com';

            // // $mail->Password   = 'qxlh uxsh ilgp xojp';
            // $mail->Password   = 'Transmillas2026@';

            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // // $mail->Port       = 587;
            // $mail->Port       = 465;


            // // Remitente
            // // $mail->setFrom(
            // //     'facturaciontransmillas@gmail.com',
            // //     'TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A.S.'
            // // );
            // $mail->setFrom(
            //     'comunicacion@transmillas.com',
            //     'TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A.S.'
            // );

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'facturacion@transmillas.com';
            $mail->Password   = 'Transmillas2026@';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->CharSet = 'UTF-8';

            $mail->setFrom(
                'facturacion@transmillas.com',
                'Transmillas Logística'
            );

            $mail->addReplyTo(
                'facturacion@transmillas.com',
                'Transmillas Logística'
            );

            // Destinatario
            $mail->addAddress($correo);

            // Adjuntar Excel
            if (!empty($rutaExcel) && file_exists($rutaExcel)) {
                $mail->addAttachment($rutaExcel);
            } else {
                throw new Exception('El archivo Excel no existe');
            }

            $mail->Subject = "Tabla de precios – Crédito";

            $contenido = "
                <p>Estimado cliente,</p>
                <p>Adjunto encontrará la tabla de precios correspondiente a su crédito para este año.</p>
                <p>Si tiene alguna inquietud adicional, no dude en contactarnos.</p>
                <br>
                <p>Atentamente,</p>
                <p><b>TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A.S.</b></p>
            ";

            // Logo
            $rutaLogo = __DIR__ . '/../../images/logoCorreo.jpg';
            if (file_exists($rutaLogo)) {
                $mail->AddEmbeddedImage($rutaLogo, 'empresa_logo');
            }

            $mail->isHTML(true);
            $mail->Body = "
                <html>
                <body>
                    <img src='cid:empresa_logo' style='width:280px'><br><br>
                    $contenido
                </body>
                </html>
            ";

            $mail->AltBody = strip_tags($contenido);

            // 🚀 ENVIAR
            $mail->send();
                    $this->guardarCorreoEnviado([
                'credito_id'      => $idCredito,
                'correo_destino'  => $correo,
                'asunto'          => $mail->Subject,
                'cuerpo_html'     => $mail->Body,
                'cuerpo_texto'    => $mail->AltBody,
                'archivo_adjunto' => $rutaExcel,
                'nombre_archivo'  => basename($rutaExcel),
                'enviado_desde'   => 'facturacion@transmillas.com',
                'estado'          => 'enviado',
                'mensaje_error'   => null
            ]);

            // ===============================
            // 📬 GUARDAR EN ENVIADOS (IMAP)
            // ===============================
            $imapPath = '{imap.hostinger.com:993/imap/ssl}Sent Items';
            

            $imapStream = imap_open(
                $imapPath,
                'facturacion@transmillas.com',
                'Transmillas2026@'
            );

            if ($imapStream) {
                imap_append(
                    $imapStream,
                    $imapPath,
                    $mail->getSentMIMEMessage()
                );
                imap_close($imapStream);
            }
            // ===============================

            // Log
            $log = "[" . date('Y-m-d H:i:s') . "] 📊 Precios enviados a $correo (Crédito ID: $idCredito)\n";
            file_put_contents(__DIR__ . '/log_envioPreciosCorreo.txt', $log, FILE_APPEND);

            return [
                'success' => true,
                'mensaje' => "Archivo enviado correctamente a $correo"
            ];

        } catch (Exception $e) {
            $this->guardarCorreoEnviado([
                'credito_id'      => $idCredito,
                'correo_destino'  => $correo,
                'asunto'          => 'Tabla de precios – Crédito',
                'cuerpo_html'     => null,
                'cuerpo_texto'    => null,
                'archivo_adjunto' => $rutaExcel,
                'nombre_archivo'  => basename($rutaExcel),
                'enviado_desde'   => 'facturacion@transmillas.com',
                'estado'          => 'error',
                'mensaje_error'   => $e->getMessage()
            ]);

            error_log("❌ Error correo: " . $e->getMessage());

            return [
                'success' => false,
                'mensaje' => 'Error al enviar el correo'
            ];
        }
    }

    public function guardarCorreoEnviado($data)
    {
        try {

            // 🔎 Log inicio
            $this->logCorreo('INICIO guardarCorreoEnviado()', $data);

            if (!isset($this->db)) {
                throw new Exception('La conexión $this->db no existe');
            }

            $sql = "
                INSERT INTO correos_enviados (
                    credito_id,
                    correo_destino,
                    asunto,
                    cuerpo_html,
                    cuerpo_texto,
                    archivo_adjunto,
                    nombre_archivo,
                    enviado_desde,
                    estado,
                    mensaje_error,
                    ip_envio,
                    user_agent
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )
            ";

            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                throw new Exception('Error prepare: ' . $this->db->error);
            }

            $stmt->bind_param(
                "isssssssssss",
                $data['credito_id'],
                $data['correo_destino'],
                $data['asunto'],
                $data['cuerpo_html'],
                $data['cuerpo_texto'],
                $data['archivo_adjunto'],
                $data['nombre_archivo'],
                $data['enviado_desde'],
                $data['estado'],
                $data['mensaje_error'],
                $_SERVER['REMOTE_ADDR'],
                $_SERVER['HTTP_USER_AGENT']
            );

            if (!$stmt->execute()) {
                throw new Exception('Error execute: ' . $stmt->error);
            }

            $this->logCorreo(
                'INSERT OK - ID: ' . $stmt->insert_id
            );

            $stmt->close();
            return true;

        } catch (Exception $e) {

            $this->logCorreo(
                'ERROR guardarCorreoEnviado(): ' . $e->getMessage(),
                $data
            );

            return false;
        }
    }
    private function logCorreo($mensaje, $data = [])
    {
        $log = "[" . date('Y-m-d H:i:s') . "] ";
        $log .= $mensaje;

        if (!empty($data)) {
            $log .= " | DATA: " . json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        $log .= PHP_EOL;

        file_put_contents(
            __DIR__ . '/log_correos_db.txt',
            $log,
            FILE_APPEND
        );
    }



    /**
     * 💬 Enviar comprobante por WhatsApp
     */
    public function enviarComprobanteCelular($idCredito, $celular, $correo, $info = [])
    {
        // 📄 Archivo
        $archivo = $info['archivo'] ?? null;
        $nombre  = $info['nombre'] ?? 'Archivo.xlsx';

        // 🧾 Log
        $logPath = __DIR__ . '/log_envioWhats.txt';
        $logMessage = "[" . date("Y-m-d H:i:s") . "] WhatsApp enviado a $celular (Crédito ID: $idCredito)\n";
        file_put_contents($logPath, $logMessage, FILE_APPEND);

        // 📱 Enviar documento (API externa)
        $this->enviarAlertaWhat($celular, 'documento', [
            'archivo' => $archivo,
            'nombre'  => $nombre,
            'credito' => $idCredito
        ]);

        return true;
    }

    /**
     * 🔁 Tu función existente para enviar alertas por WhatsApp
     */
    public function enviarAlertaWhat($telefono, $tipo, $data)
    {
        $url = "https://www.transmillas.com/ChatbotTransmillas/alertas.php";

        $payload = [
            "telefono" => $telefono,
            "tipo"     => $tipo,
            "data"     => $data
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer MiSuperToken123'
            ],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            error_log("❌ Error WhatsApp: " . curl_error($curl));
        }

        curl_close($curl);
    }
}
