<?php
require_once "../model/WhatsappModel.php";

$modelo = new WhatsappModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $fecha = $_POST['fecha'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $usuarios = $modelo->obtenerMensajes($fecha, $tipo);
    $json = json_encode($usuarios, JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        $logPath = __DIR__ . '/../model/log_consultas.txt';
        $logMessage = "[" . date("Y-m-d H:i:s") . "] ERROR_JSON: " . json_last_error_msg() . "\n";
        file_put_contents($logPath, $logMessage, FILE_APPEND);
        echo '[]';
        exit;
    }
    echo $json;
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_campo'])) {
    $id = $_POST['id'];
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];

    $modelo = new WhatsappModel();
    $modelo->actualizarCampo($id, $campo, $valor);
    echo json_encode(['ok' => true]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verificar_mensaje_37'])) {

    $telefono = $_POST['telefono'];

    
    $modelo = new WhatsappModel();

    $yaEnviado = $modelo->yaSeEnvioMensajeTipo37($telefono);

    echo json_encode([
        'ok' => true,
        'ya_enviado' => $yaEnviado
    ]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_usuario'])) {
    $id = $_POST['id'];
    $modelo = new WhatsappModel();
    $modelo->eliminarUsuario($id);
    echo json_encode(['ok' => true]);
    exit;
}

$roles = $modelo->obtenerRoles();
include "../view/whatsapp/index.php";
