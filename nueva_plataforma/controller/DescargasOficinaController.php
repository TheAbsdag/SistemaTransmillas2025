<?php

require_once "../model/DescargasOficinaModel.php";

$modelo = new DescargasOficinaModel();

$acceso=$_GET['acceso'];
$sede=$_GET['sede'];
$usuario=$_GET['uauario'];

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


    $servicios = $modelo->obtenerSerProgramados($fecha, $conde2, $operador);
    echo json_encode($servicios);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_usuario'], $_POST['id'])) {
    $id = $_POST['id'];

    $modelo = new serviciosAuto();
    $resultado = $modelo->eliminarServicio($id);

    echo json_encode(['ok' => $resultado]);
    exit;
}


// Acción para buscar cliente por teléfono
if (isset($_GET['accion']) && $_GET['accion'] === 'buscarServicio') {
    $id = $_GET['id'] ?? '';
    $servicio = $modelo->buscarServicio($id);

    header('Content-Type: application/json');
    echo json_encode($servicio ?: null);
    exit;
}

$roles = $modelo->obtenerRoles();

if($acceso!=1 and $acceso!=10){
    $conde=" and idsedes='$sede' ";
}

$ciudades = $modelo->obtenerCiudades($conde);
$operadores = $modelo->obtenerOperadores();
include "../view/DescargasOficina/index.php";
