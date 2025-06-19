<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../config/database.php';
require_once '../model/FacturaMailer.php';

date_default_timezone_set("America/Bogota");

// Logger simple
function log_envio($mensaje) {
    $logFile = __DIR__ . '/logs/envio_Prefacturas_auto.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $mensaje\n", FILE_APPEND);
}

// Crear instancia DB
$db = new Database();
$conn = $db->connect();

// Fechas
$hoy = new DateTime();
$dia = (int)$hoy->format('d');
$fechaInicio = ($dia < 21) ? new DateTime('first day of last month') : new DateTime('first day of this month');
$fechaFin = ($dia < 21) ? new DateTime('last day of last month') : new DateTime('last day of this month');
$fin = $fechaFin->format('Y-m-d');

// Consulta principal
$sqlPre = "SELECT idfacturascreditos, fac_fechafactura, fac_credito, fac_numerofactura, fac_fechaprefac, fac_idservicios, fac_iduserpre, fac_numeroref, fac_fechafacturado, fac_fechavencimiento, fac_estado, fac_tipopago, fac_iduserfac, fac_precio, fac_fecharadicado, fac_fechapago, fac_notacredito, fac_fecharafacturado, fac_pagoconfir, fac_userconfirmo, fac_fechacomfir, fac_valorpendiente, fac_preciofinal, fac_correoven, fac_nit, fac_correofac 
           FROM facturascreditos 
           WHERE DATE(fac_fechafactura) >= '2024-01-01' 
             AND DATE(fac_fechafactura) <= '$fin' 
             AND fac_estado = 'Pre-Facturado' 
           ORDER BY fac_numeroref ASC";

$result5 = $conn->query($sqlPre);

if (!$result5) {
    log_envio("Error en consulta SQL principal: " . $conn->error);
    die("Error en consulta.");
}

// Procesar facturas
while ($rw1 = $result5->fetch_assoc()) {
    $id_p = $rw1['idfacturascreditos'];
    $cliente = $conn->real_escape_string($rw1['fac_credito']);
    $archivo = "../../pre_facturas/{$rw1['fac_numerofactura']}.xls";

    if (!file_exists($archivo)) {
        log_envio("Archivo no encontrado: $archivo");
        continue;
    }

    // Obtener cliente
    $sql2 = "SELECT idcreditos, cre_nombre, idhojadevida, cre_numero_auto 
             FROM creditos 
             INNER JOIN hojadevidacliente ON hoj_clientecredito = idcreditos 
             WHERE cre_nombre = '$cliente'";

    $result3 = $conn->query($sql2);
    if (!$result3) {
        log_envio("Error en SQL cliente: $sql2 - " . $conn->error);
        continue;
    }

    $rw2 = $result3->fetch_assoc();
    if (!$rw2) {
        log_envio("Cliente no encontrado para $cliente");
        continue;
    }

    // Correos configurados para envío automático
    $correo_sql = "SELECT cont_correo 
                   FROM contactofacturacion 
                   WHERE cont_idhojavida = '{$rw2['idhojadevida']}' 
                     AND con_correo_automatico = 'si'";

    $result4 = $conn->query($correo_sql);
    if (!$result4) {
        log_envio("Error en SQL correo: $correo_sql - " . $conn->error);
        continue;
    }

    while ($ema = $result4->fetch_assoc()) {
        $numero = $rw2['cre_numero_auto'];
        $mensaje = "Estimado cliente envío archivo en excel con la relación de los servicios prestados para su respectiva aprobación esperando respuesta para generar la factura correspondiente";
        $email = $ema['cont_correo'];
        $asunto = "Aprobación de Pre-Factura N° {$rw1['fac_numerofactura']}";

        log_envio("Enviando a $email con archivo $archivo");

        // Simular $_POST para que la clase funcione igual
        echo$_POST['correo'] = $email;
        echo$_POST['body'] = $mensaje;
        echo$_POST['idfac'] = $id_p;
        echo$_POST['asunto'] = $asunto;
        echo$_POST['numero'] = $numero;
        echo$_POST['linkFac'] = $archivo;

        // Enviar correo
        $facturaMailer = new FacturaMailer();
        $facturaMailer->enviarFactura();

        // Esperar 15 segundos
        sleep(1);
    }
}

$conn->close();
log_envio("Proceso finalizado correctamente.");