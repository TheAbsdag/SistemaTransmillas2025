<?php

require_once "../model/layoutModel.php";

$modelo = new serviciosAuto();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $fecha = $_POST['fecha'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $usuarios = $modelo->obtenerSerProgramados($fecha, $tipo);
    echo json_encode($usuarios);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_campo'])) {
    $id = $_POST['id'];
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];

    $modelo = new serviciosAuto();
    $modelo->actualizarCampo($id, $campo, $valor);
    echo json_encode(['ok' => true]);
    exit;
}