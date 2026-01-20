<?php

require("../../login_autentica.php"); // 👈 AQUÍ, NO EN LA VISTA
require_once "../model/DispositivosModel.php";

$modelo = new Dispositivos();

// Datos del usuario autenticado
$usuario = $_SESSION['usuario_id'];
$sede    = $_SESSION['usu_idsede'];

/* =====================================
   VERIFICAR ESTADO DEL DISPOSITIVO
===================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verificar_dispositivo'])) {

    $deviceId = $_POST['device_id'] ?? null;

    if (!$deviceId) {
        echo json_encode(['error' => true]);
        exit;
    }

    $dispositivo = $modelo->obtenerDispositivo($usuario, $deviceId);

    if ($dispositivo) {
        echo json_encode([
            'vinculado'  => true,
            'autorizado' => (int)$dispositivo['authorized']
        ]);
    } else {
        echo json_encode([
            'vinculado' => false
        ]);
    }
    exit;
}

/* =====================================
   VINCULAR DISPOSITIVO
===================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vincular_dispositivo'])) {

    $deviceId     = $_POST['device_id'] ?? null;
    $userAgent    = $_POST['user_agent'] ?? null;
    $platform     = $_POST['platform'] ?? null;
    $screenWidth  = $_POST['screen_width'] ?? null;
    $screenHeight = $_POST['screen_height'] ?? null;

    if (
        !$deviceId ||
        !$userAgent ||
        !$platform ||
        !$screenWidth ||
        !$screenHeight
    ) {
        echo json_encode(['error' => true]);
        exit;
    }

    $fingerprint = base64_encode(
        $userAgent .
        $platform .
        $screenWidth .
        $screenHeight
    );

    if ($modelo->obtenerDispositivo($usuario, $deviceId)) {
        echo json_encode(['ya_existe' => true]);
        exit;
    }

    $ip = $_SERVER['REMOTE_ADDR'];
    $ua = $_SERVER['HTTP_USER_AGENT'];

    $modelo->registrarDispositivo(
        $usuario,
        $deviceId,
        $fingerprint,
        $ip,
        $ua,
        $platform
    );

    echo json_encode(['ok' => true]);
    exit;
}

/* =====================================
   CARGAR LA VISTA
===================================== */
include "../view/Dispositivos/index.php";
