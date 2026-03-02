<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../../config/database.php';

try {

    // 🔐 Validar token
    $apiToken = 'MiSuperToken123';
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

    if (!preg_match('/Bearer\s+(.+)/i', $authHeader, $matches) ||
        !hash_equals($apiToken, trim($matches[1]))) {

        http_response_code(401);
        echo json_encode(['ok' => false, 'mensaje' => 'Token inválido']);
        exit;
    }

    $telRem = trim((string)($_POST['tel_remitente'] ?? ''));
    $telDes = trim((string)($_POST['tel_destinatario'] ?? ''));

    $db = (new Database())->connect();

    $telefonos = [];

    foreach ([$telRem, $telDes] as $tel) {
        if ($tel === '') continue;

        $telefonos[] = $tel;

        $solo = preg_replace('/\D+/', '', $tel);
        if ($solo !== '' && $solo !== $tel) {
            $telefonos[] = $solo;
        }
    }

    $telefonos = array_values(array_unique($telefonos));

    if (empty($telefonos)) {
        echo json_encode(['ok' => true, 'total' => 0, 'creditos' => []]);
        exit;
    }

    $placeholders = implode(',', array_fill(0, count($telefonos), '?'));

    $sql = "
        SELECT c.idcreditos, c.cre_nombre
        FROM creditos c
        INNER JOIN rel_crecli rc ON rc.rel_idcredito = c.idcreditos
        INNER JOIN clientesdir cd ON cd.idclientesdir = rc.rel_idcliente
        WHERE cd.cli_telefono IN ($placeholders)
        GROUP BY c.idcreditos, c.cre_nombre
        ORDER BY c.cre_nombre ASC
    ";

    $stmt = $db->prepare($sql);
    $types = str_repeat('s', count($telefonos));
    $stmt->bind_param($types, ...$telefonos);
    $stmt->execute();

    $res = $stmt->get_result();
    $creditos = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

    echo json_encode([
        'ok' => true,
        'total' => count($creditos),
        'creditos' => $creditos
    ]);

} catch (Throwable $e) {

    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Error consultando créditos'
    ]);
}