<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../model/InduccionesComunicadosModel.php";

$modelo = new InduccionesComunicados();

//  Obtener comunicados 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $estado = $_POST['estado'] ?? '';
    $comunicados = $modelo->obtenerComunicados($estado);
    echo json_encode($comunicados);
    exit;
}

// Actualizar campo estado 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_campo'])) {
    $id = $_POST['id'];
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];

    $modelo->actualizarCampo($id, $campo, $valor);
    echo json_encode(['ok' => true]);
    exit;
}

// Eliminar comunicado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_usuario'])) {
    $id = $_POST['id'];
    $modelo->eliminarComunicado($id);
    echo json_encode(['ok' => true]);
    exit;
}

// vista principal
include "../view/iduccionesComunicados/index.php";
