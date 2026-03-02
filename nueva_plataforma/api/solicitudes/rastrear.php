<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST, OPTIONS');
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Metodo no permitido'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$toInt = static function ($valor, int $default = 0): int {
    if ($valor === null || $valor === '') {
        return $default;
    }
    return (int)$valor;
};

$toStr = static function ($valor, string $default = ''): string {
    if ($valor === null) {
        return $default;
    }
    return trim((string)$valor);
};

try {
    require_once __DIR__ . '/../../config/database.php';
    $estadoGuia = [
        '' => 'Validacion Datos',
        '0' => 'Validacion Datos',
        '1' => 'Validacion Datos',
        '2' => 'Asignar Recogida',
        '3' => 'Recoger Paquete',
        '4' => 'Pesar Operador',
        '5' => 'No Recogida',
        '6' => 'Verificar Peso',
        '7' => 'Validar Guias',
        '8' => 'Pendiente por Asignar Encomienda',
        '9' => 'Encomienda Asignada',
        '10' => 'Finalizo Entrega de Guia',
        '11' => 'No Entregado',
        '12' => 'No Llego a Sede',
        '13' => 'Incompleto',
        '14' => 'Guia en Sede',
        '15' => 'Grupos Creditos',
        '16' => 'Perdida',
        '17' => 'Incautada',
        '18' => 'Reclamacion Abierta',
        '19' => 'Reclamacion Conciliacion',
        '20' => 'Reclamacion Cerrada',
        '21' => 'Revalidacion Datos',
        '22' => 'Pendientes X Cobrar',
        '100' => 'Cancelada'
    ];

    $apiToken = getenv('WHATSAPP_API_TOKEN') ?: 'MiSuperToken123';

    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? ($_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '');
    if ($authHeader === '' && function_exists('getallheaders')) {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? ($headers['authorization'] ?? '');
    }

    if (!preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)
        || !hash_equals($apiToken, trim((string)($matches[1] ?? '')))) {
        http_response_code(401);
        echo json_encode([
            'ok' => false,
            'mensaje' => 'Token invalido'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $contentType = strtolower(trim((string)($_SERVER['CONTENT_TYPE'] ?? '')));
    $esJson = strpos($contentType, 'application/json') !== false;

    if ($esJson) {
        $rawBody = (string)file_get_contents('php://input');
        $input = json_decode($rawBody, true);
        if (!is_array($input)) {
            http_response_code(400);
            echo json_encode([
                'ok' => false,
                'mensaje' => 'JSON invalido'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    } else {
        $input = is_array($_POST) ? $_POST : [];
    }

    $guia = $toStr($input['guia'] ?? '');
    $idServicio = $toInt($input['idservicio'] ?? ($input['id_servicio'] ?? null), 0);
    $telefonoDestinatario = $toStr($input['telefono_destinatario'] ?? ($input['telefono_contacto'] ?? ''));
    $telefonoRemitente = $toStr($input['telefono_remitente'] ?? ($input['telefono_cliente'] ?? ''));
    $telefonoLegacy = $toStr($input['telefono'] ?? '');

    // Compatibilidad: "telefono" se evalua como posible remitente o destinatario.
    if ($telefonoLegacy !== '') {
        if ($telefonoDestinatario === '') {
            $telefonoDestinatario = $telefonoLegacy;
        }
        if ($telefonoRemitente === '') {
            $telefonoRemitente = $telefonoLegacy;
        }
    }

    if ($guia === '' && $idServicio <= 0) {
        http_response_code(422);
        echo json_encode([
            'ok' => false,
            'mensaje' => 'Debes enviar guia o idservicio'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($telefonoDestinatario === '' && $telefonoRemitente === '') {
        http_response_code(422);
        echo json_encode([
            'ok' => false,
            'mensaje' => 'Debes enviar telefono_remitente o telefono_destinatario'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $db = (new Database())->connect();

    if ($idServicio > 0) {
        $sql = "SELECT ser_estado, idservicios, ser_consecutivo, ser_telefonocontacto
                FROM servicios
                WHERE idservicios = ?
                LIMIT 1";
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException('Error al preparar consulta por idservicio: ' . $db->error);
        }
        $stmt->bind_param('i', $idServicio);
    } else {
        $sql = "SELECT ser_estado, idservicios, ser_consecutivo, ser_telefonocontacto
                FROM servicios
                WHERE ser_consecutivo = ?
                LIMIT 1";
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException('Error al preparar consulta por guia: ' . $db->error);
        }
        $stmt->bind_param('s', $guia);
    }

    if (!$stmt->execute()) {
        throw new RuntimeException('Error al ejecutar consulta principal: ' . $stmt->error);
    }

    $res = $stmt->get_result();
    $servicio = $res ? $res->fetch_assoc() : null;
    $stmt->close();

    if (!$servicio) {
        http_response_code(404);
        echo json_encode([
            'ok' => false,
            'mensaje' => 'No se encontro el servicio'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $idServicioServicio = (int)$servicio['idservicios'];

    $coincideDestinatario = false;
    if ($telefonoDestinatario !== '') {
        $coincideDestinatario = ($telefonoDestinatario === (string)($servicio['ser_telefonocontacto'] ?? ''));
    }

    $coincideRemitente = false;
    if ($telefonoRemitente !== '') {
        $sqlTel = "SELECT cli_telefono
                   FROM serviciosdia
                   WHERE idservicios = ? AND cli_telefono = ?
                   LIMIT 1";
        $stmtTel = $db->prepare($sqlTel);
        if (!$stmtTel) {
            throw new RuntimeException('Error al preparar validacion de telefono remitente: ' . $db->error);
        }

        $stmtTel->bind_param('is', $idServicioServicio, $telefonoRemitente);
        if (!$stmtTel->execute()) {
            throw new RuntimeException('Error al ejecutar validacion de telefono remitente: ' . $stmtTel->error);
        }

        $resTel = $stmtTel->get_result();
        $coincideRemitente = ($resTel && $resTel->num_rows > 0);
        $stmtTel->close();
    }

    if (!$coincideDestinatario && !$coincideRemitente) {
        http_response_code(403);
        echo json_encode([
            'ok' => false,
            'mensaje' => 'Telefono remitente/destinatario no autorizado para este servicio'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $estadoCodigo = (int)$servicio['ser_estado'];
    $estadoTexto = $estadoGuia[(string)$estadoCodigo] ?? ($estadoGuia[$estadoCodigo] ?? 'Estado desconocido');

    echo json_encode([
        'ok' => true,
        'mensaje' => 'Rastreo consultado correctamente',
        'servicio' => [
            'idservicios' => (int)$servicio['idservicios'],
            'guia' => (string)$servicio['ser_consecutivo'],
            'estado' => $estadoCodigo,
            'estado_texto' => (string)$estadoTexto,
            'telefono_contacto' => (string)$servicio['ser_telefonocontacto']
        ]
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Error interno al rastrear servicio',
        'detalle' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
