<?php

require_once "../model/ClientesModel.php";

$modelo = new ClientesModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $fecha = $_POST['fecha'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $usuarios = $modelo->obtenerClientes($fecha, $tipo);
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_usuario'], $_POST['id'])) {
    $id = $_POST['id'];

    $modelo = new serviciosAuto();
    $resultado = $modelo->eliminarServicio($id);

    echo json_encode(['ok' => $resultado]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente'], $_POST['ciudadRecogida'], $_POST['dias'])) {
    
    $cliente = $_POST['cliente'];
    $ciudad = $_POST['ciudadRecogida'];
    $dias = $_POST['dias']; // array
    $telefonos=$_POST['telefono_externo'];
    $direccion=$_POST['direccion'];
    $hora=$_POST['hora'];
    // // Captura todos los teléfonos enviados como array
    // $telefonos = isset($_POST['telefono_externo']) ? $_POST['telefono_externo'] : [];

    // // Convertir el array a JSON
    // $jsonTelefonos = json_encode($telefonos);

    $modelo = new serviciosAuto();
    $resultado = $modelo->crearServicioAutomatico($cliente, $ciudad, $dias, $telefonos,$direccion,$hora);

    if ($resultado) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'mensaje' => 'No se pudo guardar']);
    }
    exit;
}

$roles = $modelo->obtenerRoles();
$ciudades = $modelo->obtenerCiudades();
$creditos = $modelo->obtenerClientes();
include "../view/ServiciosAutomaticos/index.php";
