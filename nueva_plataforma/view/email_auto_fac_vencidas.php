<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/database.php';
require_once '../model/FacturaMailer.php';

date_default_timezone_set("America/Bogota");
$fechaActual = date('Y-m-d');
$horaActual = date('H:i:s');

$conexion = new Database();
$db = $conexion->connect();
$mailer = new FacturaMailer();

// Preparar log
$logDir = __DIR__ . '/logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}
$logFile = $logDir . "/log_facturas_" . date('Y-m-d') . ".txt";



// Abrir archivo de log para escritura
$logHandle = fopen($logFile, 'a');

$logLine = "================================================NUEVO ENVIO=========================================================\n====================================================================================================================";
fwrite($logHandle, $logLine);

$sql = "SELECT `idfacturascreditos`, `fac_fechafactura`,`fac_credito`, `fac_numerofactura`, 
               `fac_fechaprefac`,`fac_idservicios`, `fac_iduserpre`,`fac_numeroref`, 
               `fac_fechafacturado`, `fac_fechavencimiento`, `fac_estado`,`fac_tipopago`,
               `fac_iduserfac`,fac_precio,`fac_fecharadicado`,fac_fechapago,
               fac_notacredito,fac_fecharafacturado,fac_pagoconfir,fac_userconfirmo,
               fac_fechacomfir,fac_valorpendiente,fac_preciofinal,fac_correoven, 
               fac_nit,fac_correofac,fac_correo_auto 
        FROM facturascreditos 
        WHERE date(fac_fechafactura) >= '2024-01-01' 
            AND (fac_tipopago = 'Pendiente' OR fac_tipopago IS NULL) 
            AND fac_estado = 'Facturado' 
        ORDER BY fac_numeroref ASC ";

$result = $db->query($sql);
$totalEnviados = 0;

while ($rw1 = $result->fetch_assoc()) {
    $idFactura = $rw1['idfacturascreditos'];
    $numero = $rw1['fac_numeroref'];
    $fechaVence = $rw1['fac_fechavencimiento'];
    $correo = '';
    $mensaje = "Estimado cliente, le recordamos que la factura # $numero se encuentra vencida. Si ya realizó su pago, por favor enviar el soporte a este correo.";
    $asunto = "Factura Vencida Transmillas #$numero";

    if ($rw1['fac_credito'] == "EXTERNOS") {
        $correo = $rw1['fac_correo_auto'];
    } else {
        $sql2 = "SELECT idcreditos, cre_nombre, idhojadevida 
                 FROM creditos 
                 INNER JOIN hojadevidacliente 
                 ON hoj_clientecredito = idcreditos 
                 WHERE cre_nombre = ?";
        $stmt = $db->prepare($sql2);
        $stmt->bind_param("s", $rw1['fac_credito']);
        $stmt->execute();
        $res = $stmt->get_result();
        $credito = $res->fetch_assoc();
        $stmt->close();

        if ($credito) {
            $sqlCorreo = "SELECT cont_correo 
                          FROM contactofacturacion 
                          WHERE cont_idhojavida = ? AND con_principal = 1";
            $stmtCorreo = $db->prepare($sqlCorreo);
            $stmtCorreo->bind_param("i", $credito['idhojadevida']);
            $stmtCorreo->execute();
            $resCorreo = $stmtCorreo->get_result();
            $correoRes = $resCorreo->fetch_assoc();
            $stmtCorreo->close();
            if ($correoRes) {
                $correo = $correoRes['cont_correo'];
            }
        }
    }

    if (!empty($correo) && $fechaActual >= $fechaVence) {
        $_POST['correo'] = $correo;
        // $_POST['correo'] = "jose523a@gmail.com";
        $_POST['body'] = $mensaje;
        $_POST['idfac'] = $idFactura;
        $_POST['asunto'] = $asunto;
        $_POST['numero'] = ''; // para alertas si se requiere

        ob_start();
        $mailer->enviarFactura();
        $resultadoEnvio = trim(ob_get_clean());

        $logLine = "[" . date('Y-m-d H:i:s') . "] Factura de ".$rw1['fac_credito']."  ID $idFactura / Factura#$numero enviada a $correo => $resultadoEnvio\n";
        fwrite($logHandle, $logLine);

        echo "✅ $logLine<br>";
        // Actualizar contador manualmente aquí si no deseas que lo haga FacturaMailer:
        
        $sqlUpdate = "UPDATE facturascreditos SET fac_correoven = fac_correoven + 1 WHERE idfacturascreditos = ?";
        $stmtUpd = $db->prepare($sqlUpdate);
        $stmtUpd->bind_param("i", $idFactura);
        $stmtUpd->execute();
        $stmtUpd->close();
        

        $totalEnviados++;

        sleep(15); // Pausa de 15 segundos entre envíos
    } else {
        $motivo = empty($correo) ? "❌ SIN CORREO" : "⚠️ NO VENCIDA AÚN";
        $logLine = "[" . date('Y-m-d H:i:s') . "] Factura de ".$rw1['fac_credito']." ID $idFactura / Factura#$numero no enviada ($motivo)\n";
        fwrite($logHandle, $logLine);
    }
}

// Cerrar log
fclose($logHandle);

echo "<br><strong>Total de correos enviados: $totalEnviados</strong>";
?>
