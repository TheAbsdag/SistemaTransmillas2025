<?php

require_once "../model/DescargasOficinaModel.php";

$modelo = new DescargasOficinaModel();

$acceso=$_POST['acceso'];
$sede=$_POST['sede'];

$usuario=$_POST['usuario'];


    // if($acceso!=1 and $acceso!=10){
    //     $_POST['ciudad'] = $sede;
    // }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $fecha = $_POST['fecha'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';




    $sedes = $modelo->sedes($ciudad);
    if($sedes=='0'){
        $conde2.="";

    }else {
           
        $conde2.=" and ((cli_idciudad in $sedes and ser_estado in (4,6))) "; 	
    }
    $operador = $_POST['operador'] ?? '';

    $creditos = $_POST['creditos'] ?? '';


    $servicios = $modelo->obtenerSerProgramados($fecha, $conde2, $operador,$creditos);
    echo json_encode($servicios);
    exit;
}

// Acción para buscar remesas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'buscarRemesas') {
    $fecha = $_POST['fecha'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';
    $operador = $_POST['operador'] ?? '';

    $remesas = $modelo->buscarRemesas($fecha, $ciudad, $operador);

    header('Content-Type: application/json');
    echo json_encode($remesas ?: []);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_usuario'], $_POST['id'])) {
    $id = $_POST['id'];

    $modelo = new serviciosAuto();
    $resultado = $modelo->eliminarServicio($id);

    echo json_encode(['ok' => $resultado]);
    exit;
}


// Acción para buscar servicio
if (isset($_GET['accion']) && $_GET['accion'] === 'buscarServicio') {
    $id = $_GET['id'] ?? '';
    $servicio = $modelo->buscarServicio($id);

    header('Content-Type: application/json');
    echo json_encode($servicio ?: null);
    exit;
}

// Acción para buscar servicio
if (isset($_GET['accion']) && $_GET['accion'] === 'buscarServicioPorGuia') {
    $id = $_GET['id'] ?? '';
    $servicio = $modelo->buscarServicioPorGuia($id);

    header('Content-Type: application/json');
    echo json_encode($servicio ?: null);
    exit;
}



// Acción para validar Remesas
if (isset($_POST['accion']) && $_POST['accion'] === 'Verificar Remesa') {
    $id = $_POST['id_param'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $resultado = $modelo->validarRemesa($id, $descripcion,$usuario);

    header('Content-Type: application/json');
    echo json_encode($resultado ?: null);
    exit;
}
if (isset($_POST['accion']) && $_POST['accion'] === 'listarOperadoresPorCiudad') {
    $ciudad = $_POST['ciudad'] ?? '';

    // Aquí consultas tus operadores filtrados por ciudad
    $operadores = $modelo->obtenerOperadores($ciudad);

    echo json_encode($operadores);
    exit;
}

$roles = $modelo->obtenerRoles();

if($acceso!=1 and $acceso!=10){
    $conde=" and idsedes='$sede' ";
}

$ciudades = $modelo->obtenerCiudades($conde);
$operadores = $modelo->obtenerOperadores($sede);
$creditos = $modelo->obtenerCreditos();
include "../view/DescargasOficina/index.php";
