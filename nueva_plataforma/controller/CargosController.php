<?php

// error_reporting(E_ALL); // Mostrar todos los errores
// ini_set('display_errors', 1); // Habilitar la visualización
// ini_set('display_startup_errors', 1); // Errores en el arranque
require_once "../model/CargosModel.php";

$modelo = new CargosModel();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    // Parámetros enviados por DataTables
    $draw = intval($_POST['draw'] ?? 0);
    $start = intval($_POST['start'] ?? 0);
    $length = intval($_POST['length'] ?? 10);
    $searchValue = $_POST['search']['value'] ?? '';

    $tipoContrato = $_POST['tipo_contrato'] ?? '';

    // Obtener total de registros sin filtrar
    $totalRegistros = $modelo->contarCargos();

    // Obtener total de registros filtrados
    $totalFiltrados = $modelo->contarCargos($searchValue,$tipoContrato);

    // Obtener solo los registros necesarios para esta página
    $clientes = $modelo->obtenerCargosPaginados($start, $length, $searchValue,$tipoContrato);

    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => $totalRegistros,
        "recordsFiltered" => $totalFiltrados,
        "data" => $clientes
    ]);
    exit;
}
if (isset($_GET['accion']) && $_GET['accion'] === 'obtener_cargo') {

    $idcargo = intval($_GET['idcargo']);

    $data = $modelo->obtenerCargoYSalarios($idcargo);

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}


if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar_cargo') {

    $data = [
        'idcargo' => intval($_POST['idcargo']),
        'cargo' => $_POST['cargo'],
        'tipo_contrato' => $_POST['Tipo_Contrato'],
        'recogida' => $_POST['recogida'],
        'valor_recogida' => ($_POST['recogida'] === 'SI')
            ? intval($_POST['valor_recogida'])
            : 0
    ];

    $ok = $modelo->actualizarCargo($data);

    header('Content-Type: application/json');
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Cargo actualizado correctamente' : 'Error al actualizar el cargo'
    ]);
    exit;
}

if (isset($_POST['accion']) && $_POST['accion'] === 'guardar_salario') {

    $data = [
        'idcargo' => intval($_POST['idcargo']),
        'anio'    => intval($_POST['anio']),
        'salario' => intval($_POST['salario']),
        'auxilio' => intval($_POST['auxilio']),
        'otros'   => intval($_POST['otros']),
        'dias'    => intval($_POST['dias']),
        'salud'   => intval($_POST['salud']),
        'pension'    => intval($_POST['pension'])
    ];

    $ok = $modelo->insertarSalarioCargo($data);

    header('Content-Type: application/json');
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Salario agregado correctamente' : 'Error al guardar salario'
    ]);
    exit;
}

if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_salario') {

    $idSalario = intval($_POST['id_salario']);

    $ok = $modelo->eliminarSalarioCargo($idSalario);

    header('Content-Type: application/json');
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Salario eliminado' : 'Error al eliminar salario'
    ]);
    exit;
}
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_cargo') {

    $idcargo = intval($_POST['idcargo']);

    // 🔒 Validar si tiene salarios
    if ($modelo->cargoTieneSalarios($idcargo)) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No se puede eliminar el cargo porque tiene salarios asociados'
        ]);
        exit;
    }

    $ok = $modelo->eliminarCargo($idcargo);

    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Cargo eliminado' : 'Error al eliminar cargo'
    ]);
    exit;
}

if (isset($_POST['accion']) && $_POST['accion'] === 'guardar_nuevo_cargo') {

    $data = [
        'cargo' => $_POST['cargo'],
        'tipo_contrato' => $_POST['tipo_contrato'],
        'recogida' => $_POST['recogida'],
        'valor_recogida' => ($_POST['recogida'] === 'SI')
            ? intval($_POST['valor_recogida'])
            : 0,
        'anio' => intval($_POST['anio']),
        'salario' => intval($_POST['salario']),
        'auxilio' => intval($_POST['auxilio']),
        'otros' => intval($_POST['otros']),
        'dias' => intval($_POST['dias']),
        'salud'   => intval($_POST['salud']),
        'pension'    => intval($_POST['pension'])
    ];

    $ok = $modelo->insertarCargo($data);

    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Cargo creado correctamente' : 'Error al crear cargo'
    ]);
    exit;
} 












// Acción para buscar cliente por teléfono
if (isset($_GET['accion']) && $_GET['accion'] === 'buscar_por_telefono') {
    $telefono = $_GET['telefono'] ?? '';
    $cliente = $modelo->buscarClientePorTelefono($telefono);

    header('Content-Type: application/json');
    echo json_encode($cliente ?: null);
    exit;
}
if (isset($_POST['accion']) && $_POST['accion'] === 'editar_cliente') {
    $data = $_POST; // Aquí vienen todos los campos del formulario
    
    // Procesar la actualización en el modelo
    $resultado = $modelo->actualizarCliente($data);

    header('Content-Type: application/json');
    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? 'Cliente editado con éxito.' : 'Error al editar cliente.'
    ]);
    exit;
}
$roles = $modelo->obtenerRoles();
$ciudades = $modelo->obtenerCiudades();
$creditos = $modelo->obtenerCreditos();

include "../view/Cargos/index.php";




