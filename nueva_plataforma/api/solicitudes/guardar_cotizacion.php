<?php
declare(strict_types=1);

$requestId = '';
try {
    $requestId = bin2hex(random_bytes(8));
} catch (Throwable $e) {
    $requestId = uniqid('req_', true);
}

$inicio = microtime(true);
$responseSent = false;
$debugEnabled = in_array(strtolower((string)(getenv('APP_DEBUG') ?: getenv('API_DEBUG') ?: '0')), ['1', 'true', 'yes'], true);

$logDir = __DIR__ . '/../../logs';
$logFile = $logDir . '/api_solicitudes_guardar_cotizacion_' . date('Y-m-d') . '.log';

$normalizarLog = static function ($valor, int $depth = 0) use (&$normalizarLog) {
    if ($depth >= 5) {
        return '[max_depth]';
    }

    if (is_array($valor)) {
        $salida = [];
        foreach ($valor as $k => $v) {
            $salida[$k] = $normalizarLog($v, $depth + 1);
        }
        return $salida;
    }

    if (is_object($valor)) {
        return $normalizarLog((array)$valor, $depth + 1);
    }

    if (is_string($valor) && strlen($valor) > 2000) {
        return substr($valor, 0, 2000) . '...[truncated]';
    }

    return $valor;
};

$log = static function (string $nivel, string $evento, array $contexto = []) use ($logDir, $logFile, $requestId, $normalizarLog): void {
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0775, true);
    }

    $base = [
        'timestamp' => date('Y-m-d H:i:s'),
        'request_id' => $requestId,
        'nivel' => $nivel,
        'evento' => $evento,
        'metodo' => $_SERVER['REQUEST_METHOD'] ?? '',
        'uri' => $_SERVER['REQUEST_URI'] ?? '',
        'ip' => $_SERVER['REMOTE_ADDR'] ?? ''
    ];

    $linea = array_merge($base, $normalizarLog($contexto));
    $json = json_encode($linea, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        return;
    }

    file_put_contents($logFile, $json . PHP_EOL, FILE_APPEND | LOCK_EX);
};

$emitJson = static function (int $status, array $payload) use (&$responseSent): void {
    if (!headers_sent()) {
        http_response_code($status);
    }
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    $responseSent = true;
};

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('X-Request-Id: ' . $requestId);

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST, OPTIONS');
    $emitJson(405, [
        'ok' => false,
        'mensaje' => 'Metodo no permitido',
        'request_id' => $requestId
    ]);
    exit;
}

$toInt = static function ($valor, int $default = 0): int {
    if ($valor === null || $valor === '') {
        return $default;
    }
    return (int)$valor;
};

$toFloat = static function ($valor, float $default = 0.0): float {
    if ($valor === null || $valor === '') {
        return $default;
    }

    if (is_int($valor) || is_float($valor)) {
        return (float)$valor;
    }

    $txt = str_replace(['$', ' ', ','], ['', '', '.'], trim((string)$valor));
    return is_numeric($txt) ? (float)$txt : $default;
};

$toStr = static function ($valor, string $default = ''): string {
    if ($valor === null) {
        return $default;
    }
    return trim((string)$valor);
};

try {
    $dbConfigPath = __DIR__ . '/../../config/database.php';
    if (!is_file($dbConfigPath)) {
        throw new RuntimeException('No existe database.php');
    }
    require_once $dbConfigPath;

    $apiToken = getenv('WHATSAPP_API_TOKEN') ?: 'MiSuperToken123';

    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? ($_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '');
    if ($authHeader === '' && function_exists('getallheaders')) {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? ($headers['authorization'] ?? '');
    }

    if (!preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)
        || !hash_equals($apiToken, trim((string)($matches[1] ?? '')))) {
        $emitJson(401, [
            'ok' => false,
            'mensaje' => 'Token invalido',
            'request_id' => $requestId
        ]);
        exit;
    }

    $contentType = strtolower(trim((string)($_SERVER['CONTENT_TYPE'] ?? '')));
    $esJson = strpos($contentType, 'application/json') !== false;

    if ($esJson) {
        $rawBody = (string)file_get_contents('php://input');
        $input = json_decode($rawBody, true);

        if (!is_array($input)) {
            $emitJson(400, [
                'ok' => false,
                'mensaje' => 'JSON invalido',
                'request_id' => $requestId
            ]);
            exit;
        }
    } else {
        $input = is_array($_POST) ? $_POST : [];
    }

    $cotClirente = $toStr($input['cot_clirente'] ?? ($input['cliente'] ?? ''));
    $cotOrigen = $toStr($input['cot_origen'] ?? ($input['origen'] ?? ''));
    $cotDirecOrigen = $toStr($input['cot_direc_origen'] ?? ($input['direccion_origen'] ?? ''));
    $cotDestino = $toStr($input['cot_destino'] ?? ($input['destino'] ?? ''));
    $cotDirecDestino = $toStr($input['cot_direc_destino'] ?? ($input['direccion_destino'] ?? ''));
    $cotDescMerc = $toStr($input['cot_desc_merc'] ?? ($input['descripcion'] ?? ''));
    $cotPeso = $toFloat($input['cot_peso'] ?? ($input['peso'] ?? 0), 0);
    $cotWhatsapp = $toStr($input['cot_Whatsapp'] ?? ($input['whatsapp'] ?? ''));
    $cotFecha = $toStr($input['cot_fecha'] ?? ($input['fecha'] ?? date('Y-m-d H:i:s')));
    $cotIdIngresa = $toInt($input['cot_id_ingresa'] ?? ($input['id_ingresa'] ?? ($input['usuario'] ?? 0)), 0);
    $cotEstado = $toStr($input['cot_estado'] ?? ($input['estado'] ?? ''), '');

    $fotosRaw = $input['cot_fotos'] ?? ($input['fotos'] ?? '');
    if (is_array($fotosRaw) || is_object($fotosRaw)) {
        $cotFotos = json_encode($fotosRaw, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($cotFotos === false) {
            $cotFotos = '';
        }
    } else {
        $cotFotos = $toStr($fotosRaw, '');
    }

    $cotPiezas = $toInt($input['cot_piezas'] ?? ($input['piezas'] ?? 1), 1);
    $cotValAsegurado = $toFloat($input['cot_val_asegurado'] ?? ($input['val_asegurado'] ?? 0), 0);
    $cot_val_servicio = $toFloat($input['cot_val_servicio'] ?? ($input['val_servicio'] ?? 0), 0);
    if ($cotClirente === '' || $cotOrigen === '' || $cotDestino === '' || $cotPeso <= 0 || $cotIdIngresa <= 0) {
        $emitJson(422, [
            'ok' => false,
            'mensaje' => 'Faltan campos requeridos para guardar cotizacion',
            'request_id' => $requestId,
            'detalle' => [
                'requeridos' => [
                    'cot_clirente/cliente',
                    'cot_origen/origen',
                    'cot_destino/destino',
                    'cot_peso/peso',
                    'cot_id_ingresa/id_ingresa/usuario'
                ]
            ]
        ]);
        exit;
    }

    $db = (new Database())->connect();

    $sql = "INSERT INTO cotozaciones (
            cot_clirente, cot_origen, cot_direc_origen,
            cot_destino, cot_direc_destino, cot_desc_merc, cot_peso,
            cot_Whatsapp, cot_fecha, cot_id_ingresa,
            cot_estado, cot_fotos, cot_piezas, cot_val_asegurado, cot_val_servicio
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new RuntimeException('Error al preparar SQL: ' . $db->error);
    }

    $okBind = $stmt->bind_param(
        'ssssssdssissidd',
        $cotClirente,
        $cotOrigen,
        $cotDirecOrigen,
        $cotDestino,
        $cotDirecDestino,
        $cotDescMerc,
        $cotPeso,
        $cotWhatsapp,
        $cotFecha,
        $cotIdIngresa,
        $cotEstado,
        $cotFotos,
        $cotPiezas,
        $cotValAsegurado,
        $cot_val_servicio
    );

    if (!$okBind) {
        throw new RuntimeException('Error en bind_param: ' . $stmt->error);
    }

    $okExec = $stmt->execute();
    if (!$okExec) {
        throw new RuntimeException('Error al ejecutar INSERT: ' . $stmt->error);
    }

    $idCotizacion = (int)$stmt->insert_id;
    $stmt->close();

    $respuesta = [
        'ok' => true,
        'mensaje' => 'Cotizacion guardada correctamente',
        'id_cotizacion' => $idCotizacion,
        'request_id' => $requestId
    ];

    $emitJson(200, $respuesta);

    $log('info', 'cotizacion.guardada', [
        'id_cotizacion' => $idCotizacion,
        'entrada' => [
            'cot_clirente' => $cotClirente,
            'cot_origen' => $cotOrigen,
            'cot_destino' => $cotDestino,
            'cot_peso' => $cotPeso,
            'cot_id_ingresa' => $cotIdIngresa,
            'cot_estado' => $cotEstado,
            'cot_piezas' => $cotPiezas,
            'cot_val_asegurado' => $cotValAsegurado
        ],
        'duracion_total_ms' => (int)round((microtime(true) - $inicio) * 1000)
    ]);
} catch (Throwable $e) {
    $payload = [
        'ok' => false,
        'mensaje' => 'Error al guardar cotizacion',
        'request_id' => $requestId
    ];

    if ($debugEnabled) {
        $payload['detalle'] = [
            'error' => $e->getMessage(),
            'archivo' => $e->getFile(),
            'linea' => $e->getLine()
        ];
    }

    $emitJson(500, $payload);

    $log('error', 'cotizacion.error', [
        'error' => $e->getMessage(),
        'linea' => $e->getLine(),
        'archivo' => $e->getFile(),
        'duracion_total_ms' => (int)round((microtime(true) - $inicio) * 1000)
    ]);
}
