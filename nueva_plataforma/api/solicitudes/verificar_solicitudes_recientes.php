<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, x-api-key');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($metodo !== 'GET' && $metodo !== 'POST') {
    header('Allow: GET, POST, OPTIONS');
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Metodo no permitido'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$toStr = static function ($valor): string {
    if ($valor === null) {
        return '';
    }
    return trim((string)$valor);
};

$toInt = static function ($valor): int {
    if ($valor === null || $valor === '') {
        return 0;
    }
    return (int)$valor;
};

$normalizarTelefono = static function (string $telefono): string {
    $soloDigitos = preg_replace('/\D+/', '', $telefono);
    return $soloDigitos ?? '';
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
    $apiKeyHeader = trim((string)($_SERVER['HTTP_X_API_KEY'] ?? ''));
    $tokenRecibido = '';

    if (preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)) {
        $tokenRecibido = trim((string)$matches[1]);
    } elseif ($apiKeyHeader !== '') {
        $tokenRecibido = $apiKeyHeader;
    } elseif ($authHeader === '' && function_exists('getallheaders')) {
        $headers = getallheaders();
        $authFromHeaders = trim((string)($headers['Authorization'] ?? ($headers['authorization'] ?? '')));
        if (preg_match('/Bearer\s+(.+)/i', $authFromHeaders, $matches2)) {
            $tokenRecibido = trim((string)$matches2[1]);
        }
    }

    if ($tokenRecibido === '' || !hash_equals($apiToken, $tokenRecibido)) {
        http_response_code(401);
        echo json_encode([
            'ok' => false,
            'mensaje' => 'Token invalido o no enviado'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $contentType = strtolower(trim((string)($_SERVER['CONTENT_TYPE'] ?? '')));
    $esJson = strpos($contentType, 'application/json') !== false;

    if ($metodo === 'POST' && $esJson) {
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
        $input = ($metodo === 'POST')
            ? (is_array($_POST) ? $_POST : [])
            : (is_array($_GET) ? $_GET : []);
    }

    $telefonoRemitenteRaw = $toStr($input['telefono_remitente'] ?? ($input['telefonoR'] ?? ''));
    $telefonoDestinatarioRaw = $toStr($input['telefono_destinatario'] ?? ($input['telefonoD'] ?? ''));
    $ciudadRemitente = $toInt($input['ciudad_remitente'] ?? ($input['ciudadR'] ?? null));
    $ciudadDestinatario = $toInt($input['ciudad_destinatario'] ?? ($input['ciudadD'] ?? null));

    $telefonoRemitente = $normalizarTelefono($telefonoRemitenteRaw);
    $telefonoDestinatario = $normalizarTelefono($telefonoDestinatarioRaw);

    if ($telefonoRemitente === '' || $telefonoDestinatario === '' || $ciudadRemitente <= 0 || $ciudadDestinatario <= 0) {
        http_response_code(422);
        echo json_encode([
            'ok' => false,
            'mensaje' => 'Debes enviar telefono_remitente, telefono_destinatario, ciudad_remitente y ciudad_destinatario'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $db = (new Database())->connect();

    $sql = "SELECT
                s.idservicios,
                s.ser_consecutivo AS guia,
                s.ser_fecharegistro,
                s.ser_estado,
                cs.cli_nombre AS remitente_nombre,
                cs.cli_telefono AS remitente_telefono,
                cs.cli_idciudad AS remitente_ciudad_id,
                c2.ciu_nombre AS remitente_ciudad_nombre,
                s.ser_destinatario AS destinatario_nombre,
                s.ser_telefonocontacto AS destinatario_telefono,
                s.ser_ciudadentrega AS destinatario_ciudad_id,
                c1.ciu_nombre AS destinatario_ciudad_nombre
            FROM clientesservicios cs
            INNER JOIN rel_sercli rsc ON rsc.ser_idclientes = cs.idclientesdir
            INNER JOIN servicios s ON s.idservicios = rsc.ser_idservicio
            LEFT JOIN ciudades c1 ON c1.idciudades = s.ser_ciudadentrega
            LEFT JOIN ciudades c2 ON c2.idciudades = cs.cli_idciudad
            WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(cs.cli_telefono, ' ', ''), '-', ''), '(', ''), ')', ''), '+', '') = ?
              AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(s.ser_telefonocontacto, ' ', ''), '-', ''), '(', ''), ')', ''), '+', '') = ?
              AND cs.cli_idciudad = ?
              AND s.ser_ciudadentrega = ?
              AND s.ser_fecharegistro >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
              AND (s.ser_estado < 4 OR s.ser_estado IN (0, 1, 5, 21))
            ORDER BY s.ser_fecharegistro DESC";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new RuntimeException('Error al preparar consulta: ' . $db->error);
    }

    $stmt->bind_param('ssii', $telefonoRemitente, $telefonoDestinatario, $ciudadRemitente, $ciudadDestinatario);
    if (!$stmt->execute()) {
        throw new RuntimeException('Error al ejecutar consulta: ' . $stmt->error);
    }

    $result = $stmt->get_result();
    $solicitudes = [];
    while ($row = $result->fetch_assoc()) {
        $estadoCodigo = (int)($row['ser_estado'] ?? 0);
        $row['estado_codigo'] = $estadoCodigo;
        $row['estado_texto'] = $estadoGuia[(string)$estadoCodigo] ?? 'Estado desconocido';
        $solicitudes[] = $row;
    }
    $stmt->close();

    echo json_encode([
        'ok' => true,
        'existe_solicitud_reciente' => count($solicitudes) > 0,
        'ventana_horas' => 24,
        'total' => count($solicitudes),
        'solicitudes' => $solicitudes
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Error interno al verificar solicitudes recientes',
        'detalle' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
