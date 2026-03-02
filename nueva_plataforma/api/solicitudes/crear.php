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
$logFile = $logDir . '/api_solicitudes_crear_' . date('Y-m-d') . '.log';

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

    if (is_string($valor)) {
        if (strlen($valor) > 2000) {
            return substr($valor, 0, 2000) . '...[truncated]';
        }
        return $valor;
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
        $json = json_encode(array_merge($base, ['log_error' => 'json_encode_failed']), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    file_put_contents($logFile, $json . PHP_EOL, FILE_APPEND | LOCK_EX);
};

$extraerFiles = static function (array $files): array {
    $resumen = [];

    foreach ($files as $campo => $info) {
        if (!is_array($info)) {
            continue;
        }

        if (isset($info['name']) && is_array($info['name'])) {
            $items = [];
            $total = count($info['name']);
            for ($i = 0; $i < $total; $i++) {
                $items[] = [
                    'name' => $info['name'][$i] ?? '',
                    'type' => $info['type'][$i] ?? '',
                    'size' => $info['size'][$i] ?? 0,
                    'error' => $info['error'][$i] ?? null
                ];
            }
            $resumen[$campo] = $items;
        } else {
            $resumen[$campo] = [
                'name' => $info['name'] ?? '',
                'type' => $info['type'] ?? '',
                'size' => $info['size'] ?? 0,
                'error' => $info['error'] ?? null
            ];
        }
    }

    return $resumen;
};

$emitJson = static function (int $status, array $payload) use (&$responseSent): void {
    if (!headers_sent()) {
        http_response_code($status);
    }
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    $responseSent = true;
};

register_shutdown_function(static function () use (&$responseSent, $requestId, $debugEnabled, $log, $inicio): void {
    $fatal = error_get_last();
    if ($fatal !== null && in_array((int)$fatal['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR], true)) {
        $log('error', 'fatal.shutdown', [
            'error' => $fatal['message'] ?? '',
            'linea' => $fatal['line'] ?? 0,
            'archivo' => $fatal['file'] ?? ''
        ]);

        if (!$responseSent) {
            if (!headers_sent()) {
                http_response_code(500);
                header('Content-Type: application/json; charset=utf-8');
                header('X-Request-Id: ' . $requestId);
            }

            $payload = [
                'ok' => false,
                'mensaje' => 'Error fatal al procesar la solicitud',
                'request_id' => $requestId,
                'detalle' => [
                    'fase' => 'shutdown',
                    'tipo' => 'fatal_error'
                ]
            ];

            if ($debugEnabled) {
                $payload['detalle']['error'] = $fatal['message'] ?? '';
                $payload['detalle']['archivo'] = $fatal['file'] ?? '';
                $payload['detalle']['linea'] = $fatal['line'] ?? 0;
            }

            echo json_encode($payload, JSON_UNESCAPED_UNICODE);
            $responseSent = true;
        }
    }

    $duracionTotalMs = (int)round((microtime(true) - $inicio) * 1000);
    $log('info', 'solicitud.fin', ['duracion_total_ms' => $duracionTotalMs]);
});

set_error_handler(static function (int $severity, string $message, string $file, int $line) use ($log): bool {
    $log('warning', 'php.warning', [
        'severity' => $severity,
        'error' => $message,
        'archivo' => $file,
        'linea' => $line
    ]);

    return false;
});

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('X-Request-Id: ' . $requestId);

$log('info', 'solicitud.inicio', [
    'content_type' => $_SERVER['CONTENT_TYPE'] ?? '',
    'content_length' => $_SERVER['CONTENT_LENGTH'] ?? '',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    'debug_enabled' => $debugEnabled
]);

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    if (!headers_sent()) {
        http_response_code(204);
    }
    $responseSent = true;
    $log('info', 'solicitud.options');
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    if (!headers_sent()) {
        header('Allow: POST, OPTIONS');
    }

    $emitJson(405, [
        'ok' => false,
        'mensaje' => 'Metodo no permitido',
        'request_id' => $requestId,
        'detalle' => ['fase' => 'validacion_metodo']
    ]);

    $log('warning', 'metodo.no_permitido', ['status' => 405]);
    exit;
}

try {
    $dbConfigPath = __DIR__ . '/../../config/database.php';
    $modelPath = __DIR__ . '/../../model/SolicitudWhatsAppModel.php';

    $log('info', 'dependencias.inicio', ['db_file' => $dbConfigPath, 'model_file' => $modelPath]);

    if (!is_file($dbConfigPath)) {
        throw new RuntimeException('No existe database.php');
    }
    if (!is_file($modelPath)) {
        throw new RuntimeException('No existe SolicitudWhatsAppModel.php');
    }

    require_once $dbConfigPath;
    require_once $modelPath;
    $log('info', 'dependencias.ok');

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
            'request_id' => $requestId,
            'detalle' => ['fase' => 'autenticacion']
        ]);

        $log('warning', 'auth.invalida', ['auth_header_present' => $authHeader !== '']);
        exit;
    }

    $log('info', 'auth.ok');

    $contentType = strtolower(trim((string)($_SERVER['CONTENT_TYPE'] ?? '')));
    $esJson = strpos($contentType, 'application/json') !== false;

    if ($esJson) {
        $rawBody = (string)file_get_contents('php://input');
        $log('info', 'payload.raw_json', [
            'raw_length' => strlen($rawBody),
            'raw_preview' => strlen($rawBody) > 2000 ? substr($rawBody, 0, 2000) . '...[truncated]' : $rawBody
        ]);

        $input = json_decode($rawBody, true);

        if (!is_array($input)) {
            $emitJson(400, [
                'ok' => false,
                'mensaje' => 'JSON invalido',
                'request_id' => $requestId,
                'detalle' => [
                    'fase' => 'parseo_json',
                    'json_error' => json_last_error_msg()
                ]
            ]);

            $log('warning', 'payload.json_invalido', ['json_error' => json_last_error_msg()]);
            exit;
        }
    } else {
        $input = is_array($_POST) ? $_POST : [];
    }

    $files = $_FILES ?? [];

    $log('info', 'payload.parsed', [
        'es_json' => $esJson,
        'input' => $input,
        'files' => $extraerFiles($files)
    ]);

    $model = new SolicitudWhatsAppModel();
    $log('info', 'modelo.instanciado');

    $inicioModelo = microtime(true);
    $resultado = $model->guardarSolicitud($input, $files);
    $tiempoModeloMs = (int)round((microtime(true) - $inicioModelo) * 1000);

    $ok = (bool)($resultado['ok'] ?? false);
    $status = $ok ? 200 : 422;

    $respuesta = [
        'ok' => $ok,
        'resultado' => $resultado,
        'request_id' => $requestId
    ];

    if (!$ok) {
        $respuesta['detalle'] = [
            'fase' => 'modelo',
            'mensaje_modelo' => (string)($resultado['mensaje'] ?? 'Error sin detalle del modelo')
        ];
    }

    $emitJson($status, $respuesta);

    $log('info', 'modelo.resultado', [
        'status' => $status,
        'ok' => $ok,
        'duracion_modelo_ms' => $tiempoModeloMs,
        'resultado' => $resultado
    ]);
} catch (Throwable $e) {
    $detalle = [
        'fase' => 'excepcion',
        'tipo' => get_class($e)
    ];

    if ($debugEnabled) {
        $detalle['error'] = $e->getMessage();
        $detalle['archivo'] = $e->getFile();
        $detalle['linea'] = $e->getLine();
    }

    $emitJson(500, [
        'ok' => false,
        'mensaje' => 'Error al crear solicitud',
        'request_id' => $requestId,
        'detalle' => $detalle
    ]);

    $log('error', 'excepcion', [
        'tipo' => get_class($e),
        'error' => $e->getMessage(),
        'linea' => $e->getLine(),
        'archivo' => $e->getFile(),
        'trace' => $debugEnabled ? $e->getTraceAsString() : '[disabled]'
    ]);

    error_log('crear.php error [' . $requestId . ']: ' . $e->getMessage());
}