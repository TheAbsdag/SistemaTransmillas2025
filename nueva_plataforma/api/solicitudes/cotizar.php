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
$logFile = $logDir . '/api_solicitudes_cotizar_' . date('Y-m-d') . '.log';

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
                'mensaje' => 'Error fatal al procesar la cotizacion',
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

$toFloat = static function ($valor, float $default = 0.0): float {
    if ($valor === null) {
        return $default;
    }

    if (is_int($valor) || is_float($valor)) {
        return (float)$valor;
    }

    if (!is_string($valor)) {
        return $default;
    }

    $limpio = trim($valor);
    if ($limpio === '') {
        return $default;
    }

    $limpio = str_replace([' ', ','], ['', '.'], $limpio);
    if (!is_numeric($limpio)) {
        return $default;
    }

    return (float)$limpio;
};

$toInt = static function ($valor, int $default = 0): int {
    if ($valor === null || $valor === '') {
        return $default;
    }

    if (is_bool($valor)) {
        return $valor ? 1 : 0;
    }

    return (int)$valor;
};

$toMoneyString = static function ($valor): string {
    if ($valor === null) {
        return '0';
    }

    if (is_int($valor) || is_float($valor)) {
        return (string)$valor;
    }

    $txt = trim((string)$valor);
    if ($txt === '') {
        return '0';
    }

    $txt = str_replace(['$', ' '], '', $txt);
    return str_replace(',', '.', $txt);
};

try {
    $modelPath = __DIR__ . '/../../model/RecogidasMovilModel.php';

    $log('info', 'dependencias.inicio', ['model_file' => $modelPath]);

    if (!is_file($modelPath)) {
        throw new RuntimeException('No existe RecogidasMovilModel.php');
    }

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

    $log('info', 'payload.parsed', [
        'es_json' => $esJson,
        'input' => $input
    ]);

    $peso = $toFloat($input['peso'] ?? null, -1);
    $volumen = $toFloat($input['volumen'] ?? 0, 0);
    $ciudadOrigen = $toInt($input['ciudad_origen'] ?? ($input['ciudadOri'] ?? null), 0);
    $ciudadDestino = $toInt($input['ciudad_destino'] ?? ($input['ciudadDes'] ?? null), 0);
    $tipoServicio = $toInt($input['tipo_servicio'] ?? ($input['tipoServ'] ?? null), 0);

    if ($peso < 0 || $ciudadOrigen <= 0 || $ciudadDestino <= 0 || $tipoServicio < 0) {
        $emitJson(422, [
            'ok' => false,
            'mensaje' => 'Parametros invalidos para cotizar',
            'request_id' => $requestId,
            'detalle' => [
                'campos_requeridos' => [
                    'peso (>=' . '0)',
                    'ciudad_origen (>0)',
                    'ciudad_destino (>0)',
                    'tipo_servicio (>=' . '0)'
                ]
            ]
        ]);

        $log('warning', 'validacion.parametros_invalidos', [
            'peso' => $peso,
            'ciudad_origen' => $ciudadOrigen,
            'ciudad_destino' => $ciudadDestino,
            'tipo_servicio' => $tipoServicio
        ]);
        exit;
    }

    $valorDeclarado = $toMoneyString($input['valor_declarado'] ?? ($input['pordeclarado'] ?? '0'));
    $valorPrestamo = $toMoneyString($input['valor_prestamo'] ?? '0');
    $abono = $toMoneyString($input['abono'] ?? '0');
    $precioInicialKilos = $toFloat($input['precio_inicial_kilos'] ?? 5, 5);
    $tipoPago = $toInt($input['tipo_pago'] ?? ($input['tipoPago'] ?? 0), 0);
    $tipoCliente = $toInt($input['tipo_cliente'] ?? ($input['tipocliente'] ?? 0), 0);

    $idCredito = $toInt($input['id_credito'] ?? 0, 0);
    $nombreCredito = trim((string)($input['nombre_credito'] ?? ($input['rel_nom_credito'] ?? '')));

    $model = new RecogidasMovilModel();
    $log('info', 'modelo.instanciado');

    if ($idCredito <= 0 && $nombreCredito !== '') {
        $listaCreditos = $model->idCredito($nombreCredito);
        if (!empty($listaCreditos) && isset($listaCreditos[0]['idcreditos'])) {
            $idCredito = (int)$listaCreditos[0]['idcreditos'];
        }
    }

    $inicioModelo = microtime(true);
    $resultado = $model->calcularValorConLogicaVieja(
        $peso,
        $volumen,
        $ciudadOrigen,
        $ciudadDestino,
        $tipoServicio,
        $valorDeclarado,
        $idCredito,
        $tipoCliente,
        $valorPrestamo,
        $abono,
        $precioInicialKilos,
        $tipoPago
    );
    $tiempoModeloMs = (int)round((microtime(true) - $inicioModelo) * 1000);

    $ok = (bool)($resultado['ok'] ?? false);
    $status = $ok ? 200 : 422;

    $respuesta = [
        'ok' => $ok,
        'cotizacion' => $resultado,
        'entrada_normalizada' => [
            'peso' => $peso,
            'volumen' => $volumen,
            'ciudad_origen' => $ciudadOrigen,
            'ciudad_destino' => $ciudadDestino,
            'tipo_servicio' => $tipoServicio,
            'valor_declarado' => $valorDeclarado,
            'id_credito' => $idCredito,
            'tipo_cliente' => $tipoCliente,
            'valor_prestamo' => $valorPrestamo,
            'abono' => $abono,
            'precio_inicial_kilos' => $precioInicialKilos,
            'tipo_pago' => $tipoPago
        ],
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
        'resultado' => $resultado,
        'entrada' => $respuesta['entrada_normalizada']
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
        'mensaje' => 'Error al cotizar servicio',
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

    error_log('cotizar.php error [' . $requestId . ']: ' . $e->getMessage());
}
