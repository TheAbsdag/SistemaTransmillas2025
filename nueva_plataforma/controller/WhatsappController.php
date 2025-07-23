<?php
require_once "../model/WhatsappModel.php";

$modelo = new UsuarioModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $rol = $_POST['rol'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $usuarios = $modelo->obtenerMensajes($rol, $estado);
    echo json_encode($usuarios);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_campo'])) {
    $id = $_POST['id'];
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];

    $modelo = new UsuarioModel();
    $modelo->actualizarCampo($id, $campo, $valor);
    echo json_encode(['ok' => true]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_usuario'])) {
    $id = $_POST['id'];
    $modelo = new UsuarioModel();
    $modelo->eliminarUsuario($id);
    echo json_encode(['ok' => true]);
    exit;
}

$roles = $modelo->obtenerRoles();
include "../view/whatsapp/index.php";
