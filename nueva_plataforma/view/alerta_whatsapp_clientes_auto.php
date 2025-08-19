<?php
// =====================
// CONFIGURACIÓN DE LOGS
// =====================
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/creacion_Automatica_error.log');
error_reporting(E_ALL);
date_default_timezone_set('America/Bogota');
function logMsg($tipo, $mensaje) {
    $fecha = date("Y-m-d H:i:s");
    error_log("[$fecha] [$tipo] $mensaje");
}

// Captura de errores fatales
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null) {
        logMsg("FATAL", "{$error['message']} en {$error['file']} línea {$error['line']}");
    }
});

require_once "../model/ServiciosAutomaticosModel.php";

$modelo = new serviciosAuto();

// ===== INICIO DEL PROCESO =====
logMsg("INFO", "🚀 Inicio de ejecución de creación automática de servicios");

try {
    $servicios = $modelo->obtenerSerProgramados($fecha ?? null, $tipo ?? null);

    if (!is_array($servicios)) {
        throw new Exception("La función obtenerSerProgramados no devolvió un array válido");
    }

    $servicios = json_decode(json_encode($servicios), true);

    $diasSemana = [
        "Sunday" => "Domingo", "Monday" => "Lunes", "Tuesday" => "Martes",
        "Wednesday" => "Miércoles", "Thursday" => "Jueves", "Friday" => "Viernes", "Saturday" => "Sábado"
    ];
    $diaActual = $diasSemana[date('l')];

    $horaActual = date('H:i');
    $horaActualSeg = strtotime($horaActual);
    $horaLimite = date('H:i', strtotime($horaActual . ' +50 minutes'));
    $horaLimiteSeg = strtotime($horaLimite);

    $totalServicios = count($servicios);
    $ejecutados = 0;
    $saltados = 0;

    logMsg("INFO", "📋 Servicios obtenidos: $totalServicios | Día actual: $diaActual | Rango hora: $horaActual - $horaLimite");

    foreach ($servicios as $servicio) {
        $id = $servicio['aut_id'] ?? 'SIN_ID';

        if (!isset($servicio['aut_direccion'], $servicio['aut_telefono'], $servicio['aut_dias'], $servicio['aut_fecha'], $servicio['cliente'], $servicio['aut_ciudad_origen'])) {
            logMsg("WARN", "Servicio ID: $id incompleto: " . json_encode($servicio));
            $saltados++;
            continue;
        }

        $direccion = str_replace("&", " ", $servicio['aut_direccion']);
        $telefonos = json_decode($servicio['aut_telefono'], true) ?: [];
        $dias = json_decode($servicio['aut_dias'], true) ?: [];
        $cliente = $servicio['cliente'];
        $ciudad = $servicio['aut_ciudad_origen'];

        // Validar día
        if (!in_array($diaActual, $dias, true)) {
            logMsg("INFO", "⏭ Servicio ID: $id omitido - No corresponde el día ($diaActual)");
            $saltados++;
            continue;
        }

        // Validar hora
        $horaProg = date('H:i', strtotime($servicio['aut_fecha']));
        $horaProgSeg = strtotime($horaProg);

        if ($horaProgSeg >= $horaActualSeg && $horaProgSeg <= $horaLimiteSeg) {
            logMsg("INFO", "✅ Ejecutando servicio ID: $id | Cliente: $cliente | Hora: $horaProg");
            foreach ($telefonos as $tel) {
                if (!empty($tel) && is_string($tel)) {
                    $modelo->enviarAlertaWhat($tel, "36");
                    logMsg("INFO", "📨 Mensaje enviado a $tel para servicio ID: $id");
                } else {
                    logMsg("WARN", "Teléfono inválido en servicio ID: $id -> " . json_encode($tel));
                }
            }
            $ejecutados++;
        } else {
            logMsg("INFO", "⏭ Servicio ID: $id omitido - Hora fuera de rango (Prog: $horaProg, Rango: $horaActual - $horaLimite)");
            $saltados++;
        }
    }

    logMsg("INFO", "🏁 Fin del proceso: Total=$totalServicios | Ejecutados=$ejecutados | Omitidos=$saltados");

} catch (Throwable $e) {
    logMsg("ERROR", "Excepción: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine());
}