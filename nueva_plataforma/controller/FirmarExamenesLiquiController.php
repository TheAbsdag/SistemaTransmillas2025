<?php

require_once "../model/LiquidacionesModel.php";

$modelo = new LiquidacionesModel();

// 🖊️ Guardar firma del trabajador
if (isset($_POST['accion']) && $_POST['accion'] === 'guardarFirma') {
    $idhojadevida = $_POST['idhojadevida'];
    $firma = $_POST['firma'];
    $examenes = $_POST['examenes']; // ✅ nuevo dato

    // 🔹 Corregido: la variable correcta es $firma
    if (empty($idhojadevida) || empty($firma)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos.']);
        exit;
    }

    // Llamamos al modelo
    $resultado = $modelo->guardarFirmaExamenesLiquidacion($idhojadevida, $firma, $examenes);

    echo json_encode(['success' => $resultado]);
    exit;
}




$id = $_GET['id']; // id de la liquidación


$liquidacion = $modelo->traerDesprendible($id);

// Validar si existe resultado
if (!$liquidacion || empty($liquidacion[0]['liq_docLiqui'])) {
    die("No se encontró la liquidación o no tiene datos válidos.");
}

// Decodificar el JSON almacenado
$datos = json_decode($liquidacion[0]['liq_docLiqui'], true);
if (!is_array($datos)) {
    die("El formato del JSON no es válido.");
}

include "../view/Liquidaciones/firma_Examenes_liquidacion.php";