<?php
require_once __DIR__ . '/../model/SolicitudWhatsAppModel.php';

$modelo = new SolicitudWhatsAppModel();
$isAjax = isset($_POST['accion']);

if ($isAjax && ($_POST['accion'] ?? '') === 'obtenerCreditosAsociados') {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $telRem = trim((string)($_POST['telremitente'] ?? ''));
        $telDes = trim((string)($_POST['teldestino'] ?? ''));

        $creditos = $modelo->obtenerCreditosAsociados($telRem, $telDes);

        echo json_encode([
            'ok' => true,
            'creditos' => $creditos
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } catch (Throwable $e) {
        $modelo->log('ERROR controller obtenerCreditosAsociados', [
            'mensaje' => $e->getMessage(),
            'linea' => $e->getLine()
        ]);

        echo json_encode([
            'ok' => false,
            'mensaje' => 'No se pudieron consultar los creditos'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if ($isAjax && ($_POST['accion'] ?? '') === 'guardarSolicitudWhatsApp') {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $required = ['param2', 'param6', 'param4', 'param5', 'param8', 'param9', 'param11', 'param10', 'param13'];

        foreach ($required as $field) {
            if (empty(trim((string)($_POST[$field] ?? '')))) {
                echo json_encode([
                    'ok' => false,
                    'mensaje' => 'Falta el campo requerido: ' . $field
                ]);
                exit;
            }
        }

        $resultado = $modelo->guardarSolicitud($_POST, $_FILES);
        echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
        exit;
    } catch (Throwable $e) {
        $modelo->log('ERROR controller guardarSolicitudWhatsApp', [
            'mensaje' => $e->getMessage(),
            'linea' => $e->getLine()
        ]);

        echo json_encode([
            'ok' => false,
            'mensaje' => 'Error interno al guardar la solicitud'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$ciudadesR = $modelo->obtenerCiudadesRemitentePublico();
$ciudades = $modelo->obtenerCiudades();
$direcciones = $modelo->obtenerDirecciones();
$lugares = $modelo->obtenerLugar();

include '../view/SolicitudWhatsApp/index.php';
