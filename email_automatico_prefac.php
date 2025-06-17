<?php
// Configuración
$bd = "u713516042_transmillas2"; 
$host = "localhost";
$user = "u713516042_jose2";
$pass = "Dobarli23@transmillas";
date_default_timezone_set("America/Bogota");

// Logger simple
function log_envio($mensaje) {
    $logFile = __DIR__ . '/../logs/envio_Prefacturas_auto.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $mensaje\n", FILE_APPEND);
}

$link = mysqli_connect($host, $user, $pass);
if (!$link) {
    log_envio("Error de conexión: " . mysqli_connect_error());
    die("Error de conexión.");
}
if (!mysqli_select_db($link, $bd)) {
    log_envio("Error seleccionando base de datos: " . mysqli_error($link));
    die("Error al seleccionar DB.");
}

// Fechas
$hoy = new DateTime('now');
$dia = (int)$hoy->format('d');
$fechaInicio = ($dia < 21) ? new DateTime('first day of last month') : new DateTime('first day of this month');
$fechaFin = ($dia < 21) ? new DateTime('last day of last month') : new DateTime('last day of this month');
$fin = $fechaFin->format('Y-m-d');

// Consulta principal
$sqlPre = "SELECT `idfacturascreditos`, `fac_fechafactura`,`fac_credito`, `fac_numerofactura`, `fac_fechaprefac`, `fac_idservicios`, `fac_iduserpre`, `fac_numeroref`, `fac_fechafacturado`, `fac_fechavencimiento`, `fac_estado`, `fac_tipopago`, `fac_iduserfac`, fac_precio, `fac_fecharadicado`, fac_fechapago, fac_notacredito, fac_fecharafacturado, fac_pagoconfir, fac_userconfirmo, fac_fechacomfir, fac_valorpendiente, fac_preciofinal, fac_correoven, fac_nit, fac_correofac FROM `facturascreditos` WHERE date(fac_fechafactura)>='2024-01-01' and date(fac_fechafactura)<='$fin' and fac_estado='Pre-Facturado' ORDER BY fac_numeroref ASC";

$result5 = mysqli_query($link, $sqlPre);
if (!$result5) {
    log_envio("Error en consulta SQL principal: " . mysqli_error($link));
    die("Error en consulta.");
}

?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var facturasVencidas = [];
</script>
<?php

while ($rw1 = mysqli_fetch_row($result5)) {
    $id_p = $rw1[0];
    $cliente = mysqli_real_escape_string($link, $rw1[2]);

    $sql2 = "SELECT `idcreditos`, `cre_nombre`, idhojadevida, cre_numero_auto 
             FROM `creditos` 
             INNER JOIN hojadevidacliente ON hoj_clientecredito = idcreditos 
             WHERE cre_nombre = '$cliente'";
    
    $result3 = mysqli_query($link, $sql2);
    if (!$result3) {
        log_envio("Error en SQL cliente: $sql2 - " . mysqli_error($link));
        continue;
    }

    $rw2 = mysqli_fetch_row($result3);
    if (!$rw2) {
        log_envio("Cliente no encontrado para $cliente");
        continue;
    }

    $correo_sql = "SELECT `cont_correo` FROM `contactofacturacion` 
                   WHERE cont_idhojavida = '$rw2[2]' AND con_principal = '1'";

    $result4 = mysqli_query($link, $correo_sql);
    if (!$result4) {
        log_envio("Error en SQL correo: $correo_sql - " . mysqli_error($link));
        continue;
    }

    $ema = mysqli_fetch_row($result4);
    $numero = $rw2[3];

    $archivo = "pre_facturas/{$rw1[3]}.xls";
    if (file_exists($archivo)) {
        $linkFac = $archivo;
        $mensaje = "Estimado cliente envío archivo en excel con la relación de los servicios prestados para su respectiva aprobación esperando respuesta para generar la factura correspondiente";
        $email = $ema[0];
        $asunto = "Aprobación de Pre-Factura N° {$rw1[3]}";

        log_envio("Preparado envío para $email - Factura $rw1[3]");

        echo "<script>facturasVencidas.push({
            id: $id_p,
            email: '$email',
            mensaje: `$mensaje`,
            asunto: `$asunto`,
            numero: `$numero`,
            linkFac: `$linkFac`
        });</script>";
    } else {
        log_envio("Archivo no encontrado: $archivo");
    }
}

// Cerrar conexión
mysqli_close($link);
log_envio("Proceso finalizado correctamente.");
?>
<script>
function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function enviarCorreosSecuenciales() {
    for (let i = 0; i < facturasVencidas.length; i++) {
        const f = facturasVencidas[i];
        console.log(`Enviando correo ${i + 1} de ${facturasVencidas.length}...`);
        sendEmail(f.id, f.email, f.mensaje, f.asunto, f.numero, f.linkFac);
        await delay(15000);
    }
    console.log("Todos los correos han sido enviados.");
}

setTimeout(() => {
    if (facturasVencidas.length > 0) {
        enviarCorreosSecuenciales();
    } else {
        console.log("No hay facturas vencidas para enviar.");
    }
}, 2000);
</script>