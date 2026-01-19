<?php
require_once "../model/WhatsappModel.php";

$modelo = new WhatsappModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $fecha = $_POST['fecha'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $usuarios = $modelo->obtenerMensajes($fecha, $tipo);
    echo json_encode($usuarios);
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
