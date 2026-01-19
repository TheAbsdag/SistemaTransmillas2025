<?php

require_once "../config/database.php";
require_once '../../PHPMailer/src/PHPMailer.php';
require_once '../../PHPMailer/src/SMTP.php';
require_once '../../PHPMailer/src/Exception.php';

// Importar clases
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class PreciosCredito {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }
    public function logPreciosCreditoModel($msg) {
        $logFile = __DIR__ . "/logs_entrega.log";
        $fecha = date("Y-m-d H:i:s");
        file_put_contents($logFile, "[$fecha] $msg\n", FILE_APPEND);
    }
    public function obtenerPrecioCreditos($Origen="", $Destino="",$Creditos="",$Servicio="",$Estado="") {
        $sql = "SELECT 
            pc.pre_estado,
            pc.idprecioscredito,
            pc.pre_idciudadori,
            ori.ciu_nombre AS ciudad_origen,
            pc.pre_idciudades,
            des.ciu_nombre AS ciudad_destino,
            pc.pre_preciokilo,
            pc.pre_precioadicional,
            pc.pre_tiposervicio,
            pc.pre_idcredito,
            c.cre_nombre,
            pc.pre_fecha_inicial,
            pc.pre_fecha_final,

            CASE 
                WHEN pc.pre_tiposervicio = 0 THEN 'Carga via terrestre'
                ELSE s.tip_nom
            END AS tip_nom,

            -- Precios por rango de kilos
            MAX(CASE WHEN ck.con_idprecios = 1 THEN ck.con_precios END) AS precio_6_20,
            MAX(CASE WHEN ck.con_idprecios = 2 THEN ck.con_precios END) AS precio_21_50,
            MAX(CASE WHEN ck.con_idprecios = 3 THEN ck.con_precios END) AS precio_51_100,
            MAX(CASE WHEN ck.con_idprecios = 4 THEN ck.con_precios END) AS precio_101_150,
            MAX(CASE WHEN ck.con_idprecios = 5 THEN ck.con_precios END) AS precio_151_200,
            MAX(CASE WHEN ck.con_idprecios = 6 THEN ck.con_precios END) AS precio_201_250

        FROM precios_credito pc
        INNER JOIN creditos c 
                ON c.idcreditos = pc.pre_idcredito
        LEFT JOIN ciudades ori 
                ON ori.idciudades = pc.pre_idciudadori
        LEFT JOIN ciudades des 
                ON des.idciudades = pc.pre_idciudades
        LEFT JOIN tiposervicio s
                ON s.idtiposervicio = pc.pre_tiposervicio

        LEFT JOIN configuracionkilos ck
                ON ck.con_idprecioskilos = pc.idprecioscredito
            AND ck.con_tipo = 'Credito'

        WHERE pc.idprecioscredito > 0";

        // if (isset($Origen) && $Origen !== '') {
        //     $sql .= " AND pre_idciudadori = '$Origen'";
        // }

        // if (isset($Destino) && $Destino !== '') {
        //     $sql .= " AND pre_idciudades = '$Destino'";
        // }
        if (!empty($Origen) && !empty($Destino)) {
            $sql .= " AND (
                (pc.pre_idciudadori = '$Origen' AND pc.pre_idciudades = '$Destino')
                OR
                (pc.pre_idciudadori = '$Destino' AND pc.pre_idciudades = '$Origen')
            )";
        } else {
            if (!empty($Origen)) {
                $sql .= " AND pc.pre_idciudadori = '$Origen'";
            }
            if (!empty($Destino)) {
                $sql .= " AND pc.pre_idciudades = '$Destino'";
            }
        }

        if (isset($Creditos) && $Creditos !== '') {
            $sql .= " AND pre_idcredito = '$Creditos'";
        }
        
        if (isset($Servicio) && $Servicio !== '') {
            $sql .= " AND pre_tiposervicio = '$Servicio'";
        }
        if (isset($Estado) && $Estado !== '') {
            $sql .= " AND pre_estado = '$Estado'";
        }

         $sql .= " GROUP BY pc.idprecioscredito
                    ORDER BY pc.idprecioscredito ASC";



        // Log SQL antes de ejecutar
        error_log("[SQL] obtenerPrecioCreditos: $sql");

        $result = $this->db->query($sql);

        if (!$result) {
            // Log de error SQL
            error_log("[ERROR] MySQL: " . $this->db->error);
            return [];
        }

        // Log de éxito
        error_log("[OK] Consulta ejecutada correctamente");

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
        $sql = "SELECT idtiposervicio, tip_nom FROM tiposervicio WHERE LOWER(tip_estado_final) = 'activo' ";
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

                FROM precios_credito pc
                LEFT JOIN configuracionkilos ck
                        ON ck.con_idprecioskilos = pc.idprecioscredito
                       AND ck.con_tipo = 'Credito'
                WHERE pc.idprecioscredito = $id
                GROUP BY pc.idprecioscredito";

        $result = $this->db->query($sql);
        if (!$result) {
            error_log("[ERROR SQL] " . $this->db->error);
            return [];
        }
        return $result->fetch_assoc();
    }

    public function actualizarPrecioCredito($data)
    {
        $id = intval($data['id']);
        $editActual  = $this->db->real_escape_string($data['editar_precio']);

        if ($data['editar_precio'] == 1) {


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

            $idsActivos = $this->obtenerIdsPreciosCreditoActivos($origen, $destino,$servicio,$credito);
       
            if (!empty($idsActivos)) {
                foreach ($idsActivos as $idPrecio) {
                    $this->actualizarCampo($idPrecio, 'pre_estado', 0);
                }
            }

            // 1️⃣ Guardar origen → destino
            $ok1 = $this->insertarPrecioCredito(
                $credito, $origen, $destino, $primeros, $servicio, $rangos, $fechaInicial, $fechaFinal
            );

            if ($origen!=$destino) {
                # code...

                // 2️⃣ Guardar destino → origen (INVERSO)
                $ok2 = $this->insertarPrecioCredito(
                    $credito, $destino, $origen, $primeros, $servicio, $rangos, $fechaInicial, $fechaFinal
                );
             }

                        


            // $this->actualizarCampo($id, "pre_estado", 0);

            if ($ok1 && $ok2) {
                return true;
            }
        }else{



            $pre_idcredito    = $this->db->real_escape_string($data['pre_idcredito']);
            $pre_idciudadori  = $this->db->real_escape_string($data['pre_idciudadori']);
            $pre_idciudades   = $this->db->real_escape_string($data['pre_idciudades']);
            $pre_preciokilo   = $this->db->real_escape_string($data['pre_preciokilo']);
            $pre_tiposervicio = $this->db->real_escape_string($data['pre_tiposervicio']);
            $fechaInicial = $this->db->real_escape_string($data['pre_fecha_ini']);
            $fechaFinal = $this->db->real_escape_string($data['pre_fecha_fin']);

            
            $rangos = [
                1 => $data['precio_6_20']  ?? 0,
                2 => $data['precio_21_50'] ?? 0,
                3 => $data['precio_51_100'] ?? 0,
                4 => $data['precio_101_150'] ?? 0,
                5 => $data['precio_151_200'] ?? 0,
                6 => $data['precio_201_250'] ?? 0
            ];

            // $idsActivos = $this->obtenerIdsPreciosCreditoActivos($pre_idciudadori, $pre_idciudades);
       
             $ok1  = $this->editarPrecioCredito($id,$pre_idcredito,$pre_idciudadori,$pre_idciudades,$pre_preciokilo,$pre_tiposervicio,$rangos,$fechaInicial,$fechaFinal  );
            // if (!empty($idsActivos)) {
            //     foreach ($idsActivos as $idPrecio) {
            //         // $this->actualizarCampo($idPrecio, 'pre_estado', 0);
            //         if ($idPrecio!=$id) {
            //             $ok1  = $this->editarPrecioCredito($idPrecio,$pre_idcredito,$pre_idciudadori,$pre_idciudades,$pre_preciokilo,$pre_tiposervicio,$rangos,$fechaInicial,$fechaFinal  );
            //         }

            //     }

            // }
            // $ok2  = $this->editarPrecioCredito($id,$pre_idcredito,$pre_idciudades,$pre_idciudadori,$pre_preciokilo,$pre_tiposervicio,$rangos,$fechaInicial,$fechaFinal   );
            
            if ($ok1 ) {
                return true;
            }
   
         
        }
    }

    private function obtenerIdsPreciosCreditoActivos($origen, $destino,$tipoServicio,$credito)
    {
        $ids = [];

        $sql = "SELECT idprecioscredito
                FROM precios_credito
                WHERE pre_estado = 1
                AND (
                        (pre_idciudadori = '$origen' AND pre_idciudades = '$destino')
                    OR (pre_idciudadori = '$destino' AND pre_idciudades = '$origen')
                )AND pre_tiposervicio = $tipoServicio AND idprecioscredito='$credito' ";

        $result = $this->db->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $ids[] = $row['idprecioscredito'];
            }
        }

        return $ids; // array de IDs activos
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

        if ($origen!=$destino) {
            // 2️⃣ Guardar destino → origen (INVERSO)
            $ok2 = $this->insertarPrecioCredito(
                $credito, $destino, $origen, $primeros, $servicio, $rangos, $fechaInicial, $fechaFinal
            );
        }

        return $ok1 && $ok2;
    }
    private function insertarPrecioCredito($credito, $origen, $destino, $primeros, $servicio, $rangos, $fechaInicial, $fechaFinal)
    {
        $sql = "INSERT INTO precios_credito 
                (pre_idcredito, pre_idciudadori, pre_idciudades, pre_preciokilo, pre_tiposervicio,pre_fecha_inicial,pre_fecha_final,pre_estado)
                VALUES 
                ('$credito', '$origen', '$destino', '$primeros', '$servicio','$fechaInicial','$fechaFinal',1)";

        if (!$this->db->query($sql)) {
            error_log("[ERROR SQL INSERT precios_credito] " . $this->db->error);
            return false;
        }

        $idNuevo = $this->db->insert_id;

        foreach ($rangos as $idRango => $valor) {
            $valor = $this->db->real_escape_string($valor);
            $sqlR = "INSERT INTO configuracionkilos 
                    (con_idprecioskilos, con_idprecios, con_precios, con_tipo)
                    VALUES ($idNuevo, $idRango, '$valor', 'Credito')";

            if (!$this->db->query($sqlR)) {
                error_log("[ERROR SQL INSERT configuracionkilos] " . $this->db->error);
            }
        }

        return true;
    }

    public function existePrecioCredito($d)
    {
        $credito  = $this->db->real_escape_string($d['credito']);
        $origen   = $this->db->real_escape_string($d['origen']);
        $destino  = $this->db->real_escape_string($d['destino']);
        $servicio = $this->db->real_escape_string($d['servicio']);
        $FechaInicial  = $this->db->real_escape_string($d['FechaInicial']);
        $FechaFinal = $this->db->real_escape_string($d['FechaFinal']);


        $sql = "SELECT idprecioscredito 
                FROM precios_credito
                WHERE pre_idcredito   = '$credito'
                  AND pre_idciudadori = '$origen'
                  AND pre_idciudades  = '$destino'
                  AND pre_tiposervicio= '$servicio'
                AND '$FechaInicial' >= pre_fecha_inicial
                AND '$FechaFinal'   <= pre_fecha_final
                LIMIT 1";

        $res = $this->db->query($sql);
        if (!$res) {
            error_log("[ERROR SQL existePrecioCredito] " . $this->db->error);
            return false;
        }
        return $res->num_rows > 0;
    }

    public function eliminarPrecioCredito($id)
    {
        $id = intval($id);

        // Primero borramos configuracionkilos asociada
        $sql1 = "DELETE FROM configuracionkilos WHERE con_idprecioskilos = $id AND con_tipo = 'Credito'";
        if (!$this->db->query($sql1)) {
            error_log("[ERROR SQL DELETE configuracionkilos] " . $this->db->error);
        }

        // Luego el registro principal
        $sql2 = "DELETE FROM precios_credito WHERE idprecioscredito = $id";
        if (!$this->db->query($sql2)) {
            error_log("[ERROR SQL DELETE precios_credito] " . $this->db->error);
            return false;
        }

        return true;
    }

    public function buscarReferencia($origen, $destino, $servicio)
{

            date_default_timezone_set('America/Bogota');
        $this->logPreciosCreditoModel("=== INICIO buscarReferencia() ===");


    $origen   = $this->db->real_escape_string($origen);
    $destino  = $this->db->real_escape_string($destino);
    $servicio = $this->db->real_escape_string($servicio);

    $sql = "SELECT 
                pc.idprecioscredito,
                pc.pre_preciokilo,
                c.cre_nombre,

                MAX(CASE WHEN ck.con_idprecios = 1 THEN ck.con_precios END) AS precio_6_20,
                MAX(CASE WHEN ck.con_idprecios = 2 THEN ck.con_precios END) AS precio_21_50,
                MAX(CASE WHEN ck.con_idprecios = 3 THEN ck.con_precios END) AS precio_51_100,
                MAX(CASE WHEN ck.con_idprecios = 4 THEN ck.con_precios END) AS precio_101_150,
                MAX(CASE WHEN ck.con_idprecios = 5 THEN ck.con_precios END) AS precio_151_200,
                MAX(CASE WHEN ck.con_idprecios = 6 THEN ck.con_precios END) AS precio_201_250

            FROM precios_credito pc
            INNER JOIN creditos c
                    ON c.idcreditos = pc.pre_idcredito
            LEFT JOIN configuracionkilos ck
                    ON ck.con_idprecioskilos = pc.idprecioscredito
                   AND ck.con_tipo = 'Credito'
            WHERE pc.pre_idciudadori = '$origen'
              AND pc.pre_idciudades  = '$destino'
              AND pc.pre_tiposervicio = '$servicio'
              AND pc.pre_estado = 1

            GROUP BY pc.idprecioscredito";

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

    $sql = "SELECT pc.idprecioscredito
            FROM precios_credito pc
            LEFT JOIN configuracionkilos ck1  ON ck1.con_idprecioskilos = pc.idprecioscredito AND ck1.con_idprecios = 1 AND ck1.con_tipo = 'Credito'
            LEFT JOIN configuracionkilos ck2  ON ck2.con_idprecioskilos = pc.idprecioscredito AND ck2.con_idprecios = 2 AND ck2.con_tipo = 'Credito'
            LEFT JOIN configuracionkilos ck3  ON ck3.con_idprecioskilos = pc.idprecioscredito AND ck3.con_idprecios = 3 AND ck3.con_tipo = 'Credito'
            LEFT JOIN configuracionkilos ck4  ON ck4.con_idprecioskilos = pc.idprecioscredito AND ck4.con_idprecios = 4 AND ck4.con_tipo = 'Credito'
            LEFT JOIN configuracionkilos ck5  ON ck5.con_idprecioskilos = pc.idprecioscredito AND ck5.con_idprecios = 5 AND ck5.con_tipo = 'Credito'
            LEFT JOIN configuracionkilos ck6  ON ck6.con_idprecioskilos = pc.idprecioscredito AND ck6.con_idprecios = 6 AND ck6.con_tipo = 'Credito'
            WHERE pc.pre_idcredito   = '$credito'
              AND pc.pre_idciudadori = '$origen'
              AND pc.pre_idciudades  = '$destino'
              AND pc.pre_tiposervicio = '$servicio'
              AND pc.pre_preciokilo   = '$primeros'
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

        $sql = "UPDATE precios_credito SET $campo = ? WHERE idprecioscredito = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $valor, $id);
        $stmt->execute();
        $stmt->close();
    }
    public function obtenerNombreCredito($idCredito)
    {
        if (empty($idCredito)) return '';

        $id = intval($idCredito);
        $sql = "SELECT cre_nombre FROM creditos WHERE idcreditos = $id LIMIT 1";
        $res = $this->db->query($sql);

        if ($res && $row = $res->fetch_assoc()) {
            return $row['cre_nombre'];
        }

        return '';
    }


    
public function obtenerContactosPorCredito($idCredito)
{
    $nombreCredito=$this->obtenerNombreCredito($idCredito);

    $nombreCredito = $this->db->real_escape_string($nombreCredito);

    $sql = "
        SELECT DISTINCT
            c.cont_correo AS correo,
            c.cont_celular AS telefono
        FROM hojadevidacliente h
        INNER JOIN creditos cr
            ON cr.idcreditos = h.hoj_clientecredito
        INNER JOIN contactofacturacion c
            ON c.cont_idhojavida = h.idhojadevida
        WHERE cr.cre_nombre = '$nombreCredito'
          AND h.hoj_estado = 'Activo'
          AND (c.cont_correo <> '' OR c.cont_celular <> '')
        
        UNION DISTINCT
        
        SELECT DISTINCT
            h.hoj_email AS correo,
            h.hoj_telefono1 AS telefono
        FROM hojadevidacliente h
        INNER JOIN creditos cr
            ON cr.idcreditos = h.hoj_clientecredito
        WHERE cr.cre_nombre = '$nombreCredito'
          AND h.hoj_estado = 'Activo'
          AND (h.hoj_email <> '' OR h.hoj_telefono1 <> '')
        
        UNION DISTINCT

        SELECT DISTINCT
            h.hoj_email AS correo,
            h.hoj_telefono2 AS telefono
        FROM hojadevidacliente h
        INNER JOIN creditos cr
            ON cr.idcreditos = h.hoj_clientecredito
        WHERE cr.cre_nombre = '$nombreCredito'
          AND h.hoj_estado = 'Activo'
          AND (h.hoj_email <> '' OR h.hoj_telefono2 <> '')
    ";

    $res = $this->db->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

//Enviar correos y whatsapps 


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
