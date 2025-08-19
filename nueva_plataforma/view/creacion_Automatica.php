<?php
// =====================
// CONFIGURACIÓN DE LOGS
// =====================
ini_set('display_errors', 0); // No mostrar errores en pantalla
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/creacion_Automatica_error.log'); // Archivo donde guardar errores
error_reporting(E_ALL); // Registrar todos los errores

// Captura de errores fatales
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null) {
        $mensaje = "[" . date("Y-m-d H:i:s") . "] ERROR FATAL: {$error['message']} en {$error['file']} línea {$error['line']}\n";
        error_log($mensaje);
    }
});

require_once "../model/ServiciosAutomaticosModel.php";

$modelo = new serviciosAuto();

try {
    // Obtener los servicios
    $servicios = $modelo->obtenerSerProgramados($fecha ?? null, $tipo ?? null);

    if (!is_array($servicios)) {
        throw new Exception("La función obtenerSerProgramados no devolvió un array válido.");
    }

    $servicios = json_decode(json_encode($servicios), true);

    // Obtener el día actual en español
    $diasSemana = [
        "Sunday" => "Domingo", "Monday" => "Lunes", "Tuesday" => "Martes",
        "Wednesday" => "Miércoles", "Thursday" => "Jueves", "Friday" => "Viernes", "Saturday" => "Sábado"
    ];
    $diaActual = $diasSemana[date('l')];

    foreach ($servicios as $servicio) {
        // Validar existencia de índices
        if (!isset($servicio['aut_direccion'], $servicio['aut_telefono'], $servicio['aut_dias'], $servicio['cliente'], $servicio['aut_ciudad_origen'])) {
            error_log("Servicio con ID {$servicio['aut_id']} incompleto: " . json_encode($servicio));
            continue;
        }

        $direccion = str_replace("&", " ", $servicio['aut_direccion']);
        $telefonos = json_decode($servicio['aut_telefono'], true) ?: [];
        $dias = json_decode($servicio['aut_dias'], true) ?: [];
        $cliente = $servicio['cliente']; // ya es texto plano
        $ciudad = $servicio['aut_ciudad_origen']; // ya es texto plano

        // Validación si hoy es uno de los días programados
        if (in_array($diaActual, $dias, true)) {
            error_log("✅ Ejecutando servicio para el día $diaActual, ID: {$servicio['aut_id']}");
            // Ejecutar función
            $modelo->insertarServicio($cliente, $telefonos, $ciudad, $direccion);
        } else {
            error_log("⏭️ Hoy no corresponde ejecutar servicio ID: {$servicio['aut_id']}");
        }
    }
} catch (Throwable $e) {
    error_log("❌ Excepción capturada: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine());
}