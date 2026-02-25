<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, x-api-key');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../../config/database.php';

try {
    $apiToken = 'MiSuperToken123';
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    $apiKeyHeader = trim((string)($_SERVER['HTTP_X_API_KEY'] ?? ''));
    $tokenRecibido = '';

    if (preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)) {
        $tokenRecibido = trim((string)$matches[1]);
    } elseif ($apiKeyHeader !== '') {
        $tokenRecibido = $apiKeyHeader;
    }

    if ($tokenRecibido === '' || !hash_equals($apiToken, $tokenRecibido)) {
        http_response_code(401);
        echo json_encode([
            'ok' => false,
            'mensaje' => 'Token invalido o no enviado'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $db = (new Database())->connect();

    $sql = "SELECT idciudades, ciu_nombre
            FROM ciudades
            WHERE inner_estados = 1
            ORDER BY idciudades ASC";

    $result = $db->query($sql);
    if ($result === false) {
        throw new RuntimeException('Error al consultar ciudades: ' . $db->error);
    }

    $ciudades = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'ok' => true,
        'total' => count($ciudades),
        'ciudades' => $ciudades
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Error interno al listar ciudades',
        'detalle' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
