<?php

require_once "../model/FirmarGuiaModel.php";

$modelo = new recogerEntregarModel();

// 🖊️ Guardar firma del trabajador
if (isset($_POST['accion']) && $_POST['accion'] === 'guardarFirmaEntrega') {
    $idServicio = $_POST['idServicio'] ?? '';
    $firmaBase64 = $_POST['firma'] ?? '';

    if (empty($idServicio) || empty($firmaBase64)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos.']);
        exit;
    }

    // Llamamos al modelo
    $resultado = $modelo->guardarFirmaEntrega($idServicio, $firmaBase64);

    echo json_encode(['success' => $resultado]);
    exit;
}


// 🖊️ Guardar firma del trabajador
if (isset($_POST['accion']) && $_POST['accion'] === 'guardarFirmaRecogida') {
    $idServicio = $_POST['idServicio'] ?? '';
    $firmaBase64 = $_POST['firma'] ?? '';

    if (empty($idServicio) || empty($firmaBase64)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos.']);
        exit;
    }

    // Llamamos al modelo
    $resultado = $modelo->guardarFirmaRecogida($idServicio, $firmaBase64);

    echo json_encode(['success' => $resultado]);
    exit;
}


// =========================
// CARGA NORMAL DE LA VISTA (GET)
// =========================

$idServicio = $_GET['para'] ?? null;
$accionFirma = $_GET['accion'] ?? null;
$tipoPago = $_GET['tipo_pago'] ?? null;

if (!$idServicio || !$accionFirma) {
    die("Parámetros incompletos en la URL");
}
// =========================
// VALIDAR SI AÚN PUEDE FIRMAR
// =========================

$puedeFirmar = $modelo->servicioPuedeFirmar($idServicio, $accionFirma);
// Preparar texto según tipo de pago (esto ya no va en la vista)
$textoPago = "";
$colorPago = "";

switch (strtolower($tipoPago)) {
    case "3":
    case "al cobro":
    case "credito":
    case "2":
        $textoPago = "Esta guía NO está paga";
        $colorPago = "#e74c3c";
        break;

    case "1":
    case "contado":
        $textoPago = "Esta guía SÍ está paga";
        $colorPago = "#2ecc71";
        break;
}

include "../view/FirmarGuias/index.php";