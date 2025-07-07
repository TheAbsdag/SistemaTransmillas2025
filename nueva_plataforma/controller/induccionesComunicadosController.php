<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../model/induccionesComunicadosModel.php";

$modelo = new induccionesComunicados();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $rol = $_POST['rol'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $usuarios = $modelo->obtenerUsuarios($rol, $estado);
    echo json_encode($usuarios);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_campo'])) {
    $id = $_POST['id'];
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];

    $modelo = new induccionesComunicados();
    $modelo->actualizarCampo($id, $campo, $valor);
    echo json_encode(['ok' => true]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_usuario'])) {
    $id = $_POST['id'];
    $modelo = new induccionesComunicados();
    $modelo->eliminarUsuario($id);
    echo json_encode(['ok' => true]);
    exit;
}

$roles = $modelo->obtenerRoles();
include "../view/iduccionesComunicados/index.php";
