<?php
require_once "../model/ValidarGuiaModel.php";

$modelo = new ValidarGuiaModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $sedeDestino = $_POST['sedeDestino'] ?? '';
    $sedeOrigen = $_POST['sedeOrigen'] ?? '';
    $param1 = $_POST['param1'] ?? '';
    $param2 = $_POST['param2'] ?? '';

    $guias = $modelo->obtenerGuiasXValidar($fecha, $sedeDestino, $sedeOrigen, $param1, $param2);
    echo json_encode($guias);
    exit;
}

// Puedes agregar aquí otros `if` para registrar cambios o validar guías si se requiere

include "../view/validar_guias/index.php";