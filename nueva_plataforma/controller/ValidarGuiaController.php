<?php
require_once '../model/ValidarGuiaModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $model = new ValidarGuiaModel();

    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $sedeDestino = $_POST['sedeDestino'] ?? '';
    $sedeOrigen = $_POST['sedeOrigen'] ?? '';
    $param1 = $_POST['param1'] ?? '';
    $param2 = $_POST['param2'] ?? '';

    $data = $model->obtenerGuiasXValidar($fecha, $sedeDestino, $sedeOrigen, $param1, $param2);

    echo json_encode($data);
    exit;
}