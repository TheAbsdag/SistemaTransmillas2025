<?php
require("../../login_autentica.php"); // ESTE archivo debe tener session_start()
require_once "../model/LiquidacionesModel.php";

$modelo = new LiquidacionesModel();

$acceso=$_POST['acceso'];
$sede=$_POST['sede'];

$usuario=$_POST['usuario'];


    // if($acceso!=1 and $acceso!=10){
    //     $_POST['ciudad'] = $sede;
    // }












// ✅ Acción para actualizar el estado de la liquidación
if (isset($_POST['accion']) && $_POST['accion'] === 'actualizarEstadoLiquidacion') {
    // Recibir variables del POST
    $idLiquidado    = $_POST['idLiquidado'] ?? '';
    $liquidado     = $_POST['liquidado'] ?? 0;
    $idQuienLiqd    = $_POST['idQuienLiqd'] ?? '';
    $idhojadevida = $_POST['idhojadevida'] ?? '';
    // Los demás datos enviados (por si luego los usas para log o auditoría)
    $datosExtras = [
        'nombre'           => $_POST['nombre'] ?? '',
        'cedula'           => $_POST['cedula'] ?? '',
        'fecha_ingreso'    => $_POST['fecha_ingreso'] ?? '',
        'fecha_retiro'     => $_POST['fecha_retiro'] ?? '',
        'dias_trabajados'  => $_POST['dias_trabajados'] ?? 0,
        'dias_cesantias'   => $_POST['dias_cesantias'] ?? 0,
        'dias_prima'       => $_POST['dias_prima'] ?? 0,
        'dias_vacaciones'  => $_POST['dias_vacaciones'] ?? 0,
        'sueldobasico'     => $_POST['sueldobasico'] ?? 0,
        'transporte'       => $_POST['transporte'] ?? 0,
        'cesantias'        => $_POST['cesantias'] ?? 0,
        'intereses'        => $_POST['intereses'] ?? 0,
        'prima'            => $_POST['prima'] ?? 0,
        'vacaciones'       => $_POST['vacaciones'] ?? 0,
        'valor_total'      => $_POST['valor_total'] ?? 0,
        'cargo'            => $_POST['cargo'] ?? '',
        'valorTotalDevengado'   => $_POST['valorTotalDevengado'] ?? 0,
        'noTrabajados'           => $_POST['noTrabajados'] ?? 0,
        'valorVacacionesCompletas' => $_POST['valorVacacionesCompletas'] ?? 0,
        'valorDeudas'            => $_POST['valorDeudas'] ?? 0,
        'valorVacacionestomadas' => $_POST['valorVacacionestomadas'] ?? 0,
        'dias_vacacionesTomadas' => $_POST['dias_vacacionesTomadas'] ?? 0,
        'firma'                  => $_POST['firma'] ?? '',
        'cant_vacaciones_tomadas'=> $_POST['cant_vacaciones_tomadas'] ?? 0,
    ];

    // Llamamos al método del modelo
    $resultado = $modelo->actualizarEstadoLiquidacion($idLiquidado, $liquidado, $idQuienLiqd, $datosExtras,$idhojadevida,0);

    // Retornamos respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($resultado ?: null);
    exit;
}
// Subir Comprobante
if (isset($_POST['accion']) && $_POST['accion'] === 'subirComprobante') {
    // 🔹 Recibir los datos enviados
    $seleccionados = isset($_POST['seleccionados']) ? json_decode($_POST['seleccionados'], true) : [];
    $archivo = $_FILES['comprobante'] ?? null;

    // Validaciones básicas
    if (empty($seleccionados)) {
        $respuesta = ['status' => 'error', 'message' => 'No se recibieron IDs seleccionados'];
        header('Content-Type: application/json');
        echo json_encode($respuesta);
        exit;
    }

    if (!$archivo || $archivo['error'] !== UPLOAD_ERR_OK) {
        $respuesta = ['status' => 'error', 'message' => 'No se recibió el archivo o hubo un error al subirlo'];
        header('Content-Type: application/json');
        echo json_encode($respuesta);
        exit;
    }

    // 🔹 Llamamos al modelo
    $resultado = $modelo->subirComprobantePago($seleccionados, $archivo);

    // 🔹 Retornar respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($resultado ?: ['status' => 'error', 'message' => 'Error inesperado en el controlador']);
    exit;
}

// // Mostrar todos los errores


if (isset($_POST['accion']) && $_POST['accion'] === 'verificarLiquidado') {
    $id = $_POST['idhojadevida'];
    $resultado = $modelo->verificarSiEstaLiquidado($id);

    header('Content-Type: application/json');

    echo json_encode([
        'success' => true,
        'liquidado' => $resultado
    ]);
    exit;
}

if (isset($_POST['accion']) && $_POST['accion'] === 'listarOperadoresPorCiudad') {
    $ciudad = $_POST['ciudad'] ?? '';

    // Aquí consultas tus operadores filtrados por ciudad
    $operadores = $modelo->obtenerOperadores($ciudad);

    echo json_encode($operadores);
    exit;
}

// 📩 Enviar comprobante por correo
if (isset($_POST['accion']) && $_POST['accion'] === 'enviarComprobanteCorreo') {
    $idhojadevida = $_POST['idhojadevida'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $celular = $_POST['celular'] ?? '';
    $jsonData = $_POST['jsonData'] ?? '';

    // Decodificar el JSON si existe
    $info = [];
    if (!empty($jsonData)) {
        $info = json_decode(urldecode($jsonData), true);
    }

    // Pasamos el JSON decodificado al modelo si lo deseas usar allí
    $resultado = $modelo->enviarComprobanteCorreo($idhojadevida, $correo, $celular, $info);

    echo json_encode(['success' => $resultado]);
    exit;
}

// 💬 Enviar comprobante por WhatsApp
if (isset($_POST['accion']) && $_POST['accion'] === 'enviarComprobanteCelular') {
    $idhojadevida = $_POST['idhojadevida'] ?? '';
    $celular = $_POST['celular'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $jsonData = $_POST['jsonData'] ?? '';

    // Decodificar el JSON si existe
    $info = [];
    if (!empty($jsonData)) {
        $info = json_decode(urldecode($jsonData), true);
    }

    // Pasamos el JSON decodificado al modelo si lo deseas usar allí
    $resultado = $modelo->enviarComprobanteCelular($idhojadevida, $celular, $correo, $info);

    echo json_encode(['success' => $resultado]);
    exit;
}

// 📩 Enviar Examenes por correo
if (isset($_POST['accion']) && $_POST['accion'] === 'enviarExamenesCorreo') {
    $idhojadevida = $_POST['idhojadevida'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $celular = $_POST['celular'] ?? '';

    $resultado = $modelo->enviarExamenesCorreo($idhojadevida, $correo, $celular);
    echo json_encode(['success' => $resultado]);
    exit;
}

// 💬 Enviar Examenes por WhatsApp
if (isset($_POST['accion']) && $_POST['accion'] === 'enviarExamenesCelular') {
    $idhojadevida = $_POST['idhojadevida'] ?? '';
    $celular = $_POST['celular'] ?? '';
    $correo = $_POST['correo'] ?? '';

    $resultado = $modelo->enviarExamenesCelular($idhojadevida, $celular, $correo);
    echo json_encode(['success' => $resultado]);
    exit;
}



$roles = $modelo->obtenerRoles();

if($acceso!=1 and $acceso!=10){
    $conde=" and idsedes='$sede' ";
}

$ciudades = $modelo->obtenerCiudades($conde);
$operadores = $modelo->obtenerOperadores($sede);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $Anio = $_POST['Anio'] ?? '';
    $sede = $_POST['ciudad'] ?? '';




    
    $operador = $_POST['operador'] ?? '';

    $estado = $_POST['estado'] ?? '';


    $servicios = $modelo->obtenerLiquidacionesCalculadas($Anio, $sede, $operador,$estado);
    echo json_encode($servicios);
    exit;
}

include "../view/Liquidaciones/index.php";
