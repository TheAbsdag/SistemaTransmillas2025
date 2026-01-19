<?php
// controller/RecogerController.php

require_once "../model/RecogerModel.php";

$modelo = new RecogerModel();




/* ============================================================
   ACCIÓN: MOSTRAR VISTA
   ============================================================ */
if (isset($_GET['accion']) && $_GET['accion'] === 'vista') {

    $idServicio = $_GET['idServicio'] ?? 0;

    // En caso de que luego envíes sede, acceso, usuario desde otro módulo
    $sede    = $_POST['sede']    ?? '';
    $acceso  = $_POST['acceso']  ?? '';
    $usuario = $_POST['usuario'] ?? '';

    include "../view/Recoger/index.php";
    exit;
}

/* ============================================================
   ACCIÓN: BUSCAR DATOS DEL SERVICIO
   ============================================================ */
if (isset($_GET['accion']) && $_GET['accion'] === 'buscarRecogida') {

    $id = intval($_GET['id'] ?? 0);
    $datos = $modelo->buscarRecogida($id);

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($datos);
    exit;
}

/* ============================================================
   ACCIÓN: GUARDAR RECOGIDO
   ============================================================ */
if (isset($_POST['accion']) && $_POST['accion'] === 'guardarRecogido') {

    $resp = $modelo->guardarRecogido($_POST, $_FILES);

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($resp);
    exit;
}

/* ============================================================
   ACCIÓN: GUARDAR NO RECOGIDO
   ============================================================ */
if (isset($_POST['accion']) && $_POST['accion'] === 'guardarNoRecogido') {

    $resp = $modelo->guardarNoRecogido($_POST, $_FILES);

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($resp);
    exit;
}

/* ============================================================
   ACCIÓN: LISTAR TIPOS DE PAQUETE (param21)
   ============================================================ */
if (isset($_GET['accion']) && $_GET['accion'] === 'listarTipos') {

    $tipos = $modelo->listarTipos();

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($tipos);
    exit;
}

/* ============================================================
   ACCIÓN: LISTAR MÉTODOS DE PAGO (param30)
   ============================================================ */
if (isset($_GET['accion']) && $_GET['accion'] === 'listarMetodosPago') {

    $metodos = $modelo->listarMetodosPago();

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($metodos);
    exit;
}


/* ============================================================
   ACCIÓN: LISTAR MÉTODOS DE Cretitos (creditos)
   ============================================================ */
if (isset($_GET['accion']) && $_GET['accion'] === 'listarCreditos') {

    $creditos = $modelo->listarCreditos();

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($creditos);
    exit;
}
/* ============================================================
   ACCIÓN: Calcular valor 
   ============================================================ */

if (isset($_POST['accion']) && $_POST['accion'] === 'calcularValorTotal') {

    // ===============================
    // LOG DE ARRANQUE
    // ===============================
    $rutaLog = __DIR__ . "/log_calculos.txt";
    $fecha = date("[Y-m-d H:i:s] ");
    file_put_contents($rutaLog, $fecha . "---- INICIO REQUEST calcularValorTotal ----" . PHP_EOL, FILE_APPEND);
    file_put_contents($rutaLog, $fecha . "POST: " . json_encode($_POST) . PHP_EOL, FILE_APPEND);

    // ===============================
    // RECIBIR VALORES
    // ===============================
    $peso        = $_POST['peso']        ?? 0;
    $volumen     = $_POST['volumen']     ?? 0;
    $ciudadOri   = $_POST['ciudadOri']   ?? 0;
    $ciudadDes   = $_POST['ciudadDes']   ?? 0;
    $tipoServ    = $_POST['tipoServ']    ?? 0;
    $pordeclarado = $_POST['pordeclarado'] ?? 0;
    $tipoCliente = $_POST['tipocliente'] ?? 0; // 1 si es crédito
    $credito     = $_POST['rel_nom_credito'] ?? 0; // nombre del cliente, usado para buscar ID
    $tipoPago    = $_POST['tipoPago'] ?? 0;
    // ===============================
    // BUSCAR ID DEL CRÉDITO
    // ===============================
    $resultado = $modelo->idCredito($credito);

    if (!empty($resultado)) {
        $idcredito = $resultado[0]["idcreditos"];
        file_put_contents($rutaLog, $fecha . "ID de crédito encontrado: $idcredito" . PHP_EOL, FILE_APPEND);
    } else {
        $idcredito = 0;
        file_put_contents($rutaLog, $fecha . "No se encontró crédito, se usa ID=0" . PHP_EOL, FILE_APPEND);
    }

    // ===============================
    // EJECUTAR CÁLCULO
    // ===============================
    $resp = $modelo->calcularValorConLogicaVieja(
        $peso,            // kilos
        $volumen,         // volumen
        $ciudadOri,       // ciudad origen
        $ciudadDes,       // ciudad destino
        $tipoServ,        // tipo servicio
        $pordeclarado,    // valor declarado
        $idcredito,       // ID crédito CORREGIDO
        $tipoCliente,     // 1 si es crédito
        0,                // valor préstamo
        0,                // param5
        5,                 // kilos iniciales
        $tipoPago=0
    );

    file_put_contents($rutaLog, $fecha . "RESPUESTA FINAL: " . json_encode($resp) . PHP_EOL, FILE_APPEND);
    file_put_contents($rutaLog, $fecha . "---- FIN REQUEST ----" . PHP_EOL . PHP_EOL, FILE_APPEND);

    // ===============================
    // RESPUESTA JSON
    // ===============================
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($resp);
    exit;
}

// Ninguna acción coincide
echo "Acción no válida en RecogerController.";
exit;
