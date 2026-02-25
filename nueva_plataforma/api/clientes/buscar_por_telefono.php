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

    $telefono = trim((string) ($_GET['telefono'] ?? $_POST['telefono'] ?? ''));

    if ($telefono === '') {
        http_response_code(400);
        echo json_encode([
            'ok' => false,
            'mensaje' => 'Debe enviar el parametro telefono'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $db = (new Database())->connect();

    $sql = "SELECT 
            c.idclientes,
            cd.idclientesdir,
            c.cli_iddocumento,
            cd.cli_nombre,
            cd.cli_correo AS cli_email,
            cd.cli_direccion,
            cd.cli_idciudad,
            ci.ciu_nombre AS ciudad_nombre,
            cd.cli_telefono,
            c.cli_clasificacion,
            c.cli_tipo,
            c.cli_fecharegistro,
            c.cli_valoraprobado,
            c.cli_valorprestado
        FROM clientes c
        INNER JOIN clientesdir cd ON cd.cli_idclientes = c.idclientes
        LEFT JOIN ciudades ci ON ci.idciudades = cd.cli_idciudad
        WHERE cd.cli_telefono = ?
        ORDER BY cd.idclientesdir DESC
        LIMIT 1";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new RuntimeException('Error al preparar la consulta: ' . $db->error);
    }

    $stmt->bind_param('s', $telefono);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result ? $result->fetch_assoc() : null;
    if ($cliente !== null) {
        // Reemplazar & por espacio
        $cliente['cli_direccion'] = str_replace('&', ' ', $cliente['cli_direccion']);

        // Quitar múltiples espacios
        $cliente['cli_direccion'] = preg_replace('/\s+/', ' ', $cliente['cli_direccion']);

        // Limpiar bordes
        $cliente['cli_direccion'] = trim($cliente['cli_direccion']);
    }
    $stmt->close();

    echo json_encode([
        'ok' => true,
        'encontrado' => $cliente !== null,
        'cliente' => $cliente
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Error interno al buscar cliente',
        'detalle' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
