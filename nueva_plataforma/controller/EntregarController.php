<?php
require_once "../model/EntregarModel.php";

$modelo = new EntregarModel();




/* ============================================================
   ACCIÓN: GUARDAR ENTREGA
   ============================================================ */
if (isset($_POST['accion']) && $_POST['accion'] === 'guardarEntrega') {

    $resp = $modelo->guardarEntrega($_POST, $_FILES);

    header("Content-Type: application/json");
    echo json_encode($resp);
    exit;
}


/* ============================================================
   ACCIÓN: GUARDAR NO ENTREGADO
   ============================================================ */
if (isset($_POST['accion']) && $_POST['accion'] === 'guardarNoEntregar') {

    ob_start();
    $resp = $modelo->guardarNoEntregar($_POST, $_FILES);
    $debug = ob_get_clean();

    if ($debug !== "") {
        file_put_contents(__DIR__ . "/debug_output.log", $debug, FILE_APPEND);
    }

    file_put_contents(__DIR__ . "/RESPUESTA_DEBUG.txt", json_encode($resp));

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($resp);
    exit;
}

/* ============================================================
   ACCIÓN: MOSTRAR VISTA
   ============================================================ */
if (isset($_GET['accion']) && $_GET['accion'] === 'vista') {

    // Recibir idServicio por GET
    $idServicio = isset($_GET['idServicio']) ? $_GET['idServicio'] : 0;

    // Recibir datos POST si vienen desde otro módulo
    $sede    = $_POST['sede']    ?? '';
    $acceso  = $_POST['acceso']  ?? '';
    $usuario = $_POST['usuario'] ?? '';

    // Cargar vista principal
    include "../view/Entregar/index.php";
    exit;
}


/* ============================================================
   ACCIÓN: BUSCAR DATOS DEL SERVICIO
   ============================================================ */
if (isset($_GET['accion']) && $_GET['accion'] === 'buscarEntrega') {

    $id = isset($_GET['id']) ? $_GET['id'] : 0;

    $datos = $modelo->buscarEntrega($id);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos);
    exit;
}


// Si no coincide ninguna acción
echo "Acción no válida.";
exit;