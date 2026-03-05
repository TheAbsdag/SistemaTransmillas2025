<?php
ini_set('display_errors', 0);
error_reporting(E_ALL); // Keep logging errors, but don't display them
require("../../login_autentica.php"); // Inicia sesión y define $id_usuario, $nivel_acceso, etc.
require_once "../model/SeguimientoUsuarioModel.php";
ob_start(); // Inicia buffer de salida
$captured_errors = []; // Almacenará los errores

set_error_handler(function($errno, $errstr, $errfile, $errline) use (&$captured_errors) {
    $captured_errors[] = [
        'type'    => $errno,
        'message' => $errstr,
        'file'    => $errfile,
        'line'    => $errline
    ];
    return true; // Evita que PHP muestre el error
});

$modelo = new SeguimientoUsuarioModel();

// --------------------------------------------------------------------
// PETICIONES AJAX PARA DATATABLE (server-side)
// --------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    // Asegurar que la respuesta siempre sea JSON
    header('Content-Type: application/json');

    try {
        // Validar y sanitizar entradas
        $draw   = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
        $start  = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $search = $_POST['search']['value'] ?? '';
        $orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
        $orderDir = $_POST['order'][0]['dir'] ?? 'ASC';
        $columns = $_POST['columns'] ?? [];
        $orderColumn = $columns[$orderColumnIndex]['data'] ?? 'usu_nombre';

        // Filtros personalizados
        $filtros = [
            'fecha_inicio' => $_POST['fecha_inicio'] ?? date('Y-m-d'),
            'fecha_fin'    => $_POST['fecha_fin'] ?? date('Y-m-d'),
            'sede'         => $_POST['sede'] ?? '',
            'operario'     => $_POST['operario'] ?? '',
            'motivo'       => $_POST['motivo'] ?? '',
            'tipo_contrato'=> $_POST['tipo_contrato'] ?? ''
        ];

        // Obtener datos del modelo
        $result = $modelo->getRegistrosDataTable($start, $length, $filtros, $search, $orderColumn, $orderDir);
        $totalFiltrados = $modelo->getTotalFiltrados($filtros, $search);
        // Calcular total sin filtrar (usuarios * días)
        $fecha_inicio = new DateTime($filtros['fecha_inicio']);
        $fecha_fin = new DateTime($filtros['fecha_fin']);
        $dias = $fecha_inicio->diff($fecha_fin)->days + 1;
        $totalUsuarios = $modelo->getTotalRegistros($filtros, $search);
        $totalRegistros = $totalUsuarios * $dias;

        echo json_encode([
            "draw" => $draw,
            "recordsTotal" => $totalRegistros,
            "recordsFiltered" => $totalFiltrados,
            "data" => $result
        ]);
    } catch (Exception $e) {
        // En producción, registra el error en un log en lugar de mostrarlo
        error_log("Error en AJAX seguimiento: " . $e->getMessage());
        echo json_encode([
            "draw" => $draw ?? 0,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => [],
            "error" => "Ocurrió un error interno"
        ]);
    }
    exit;
}

// --------------------------------------------------------------------
// OBTENER OPERARIOS POR SEDE (para dropdown dependiente)
// --------------------------------------------------------------------
if (isset($_GET['accion']) && $_GET['accion'] === 'get_operarios') {
    $idsede = intval($_GET['idsede'] ?? 0);
    $operarios = $modelo->getOperariosPorSede($idsede);
    header('Content-Type: application/json');
    echo json_encode($operarios);
    exit;
}

// --------------------------------------------------------------------
// OBTENER ZONAS POR SEDE (para modal de ingreso)
// --------------------------------------------------------------------
if (isset($_GET['accion']) && $_GET['accion'] === 'get_zonas') {
    $idsede = intval($_GET['idsede'] ?? 0);
    $zonas = $modelo->getZonasPorSede($idsede);
    header('Content-Type: application/json');
    echo json_encode($zonas);
    exit;
}

// --------------------------------------------------------------------
// OBTENER DEUDA DE UN OPERARIO
// --------------------------------------------------------------------
if (isset($_GET['accion']) && $_GET['accion'] === 'get_deuda') {
    $idoperario = intval($_GET['idoperario'] ?? 0);
    $deuda = $modelo->getDeudaOperario($idoperario);
    header('Content-Type: application/json');
    echo json_encode(['deuda' => $deuda]);
    exit;
}

// --------------------------------------------------------------------
// GUARDAR NUEVO INGRESO (SeguimientoUser)
// --------------------------------------------------------------------
if (isset($_POST['accion']) && $_POST['accion'] === 'guardar_ingreso') {
    $data = [
        'operario' => intval($_POST['operario']),
        'sede' => intval($_POST['sede']),
        'fecha' => $_POST['fecha'],
        'motivo' => $_POST['motivo'],
        'descripcion' => $_POST['descripcion'],
        'zona' => intval($_POST['zona']),
        'prueba' => $_POST['prueba']
    ];
    $imagen = $_FILES['imagen'] ?? null;

    $ok = $modelo->insertarIngreso($data, $imagen, $_SESSION['usuario_id']);
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Ingreso registrado correctamente' : 'Error al registrar ingreso'
    ]);
    exit;
}

// --------------------------------------------------------------------
// GUARDAR CAMBIO EN SEGUIMIENTO (Cambio_seguimientoUser)
// --------------------------------------------------------------------
if (isset($_POST['accion']) && $_POST['accion'] === 'guardar_cambio') {
    $id_seguimiento = intval($_POST['id_seguimiento']);
    $data = [
        'motivo' => $_POST['motivo'],
        'descripcion' => $_POST['descripcion'],
        'zona' => intval($_POST['zona']),
        'prueba' => $_POST['prueba'],
        'horas' => $_POST['horas']
    ];
    $imagen = $_FILES['imagen'] ?? null;

    $ok = $modelo->actualizarIngreso($id_seguimiento, $data, $imagen, $_SESSION['usuario_id']);
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Cambios guardados' : 'Error al guardar cambios'
    ]);
    exit;
}

// --------------------------------------------------------------------
// AGREGAR DÍA FESTIVO (para todos los operarios de empresa)
// --------------------------------------------------------------------
if (isset($_POST['accion']) && $_POST['accion'] === 'guardar_festivos') {
    $fecha = $_POST['fecha'];
    $sede = intval($_POST['sede'] ?? 0);
    $ok = $modelo->insertarFestivos($fecha, $sede, $_SESSION['usuario_id']);
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Días festivos agregados' : 'Error al agregar festivos'
    ]);
    exit;
}

// --------------------------------------------------------------------
// AGREGAR VACACIONES (para un operario en un rango)
// --------------------------------------------------------------------
if (isset($_POST['accion']) && $_POST['accion'] === 'guardar_vacaciones') {
    $data = [
        'operario' => intval($_POST['operario']),
        'fecha_ini' => $_POST['fecha_ini'],
        'fecha_fin' => $_POST['fecha_fin']
    ];
    $ok = $modelo->insertarVacaciones($data, $_SESSION['usuario_id']);
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Vacaciones registradas' : 'Error al registrar vacaciones'
    ]);
    exit;
}

// --------------------------------------------------------------------
// AGREGAR LICENCIA / PERMISO
// --------------------------------------------------------------------
if (isset($_POST['accion']) && $_POST['accion'] === 'guardar_licencia') {
    $data = [
        'operario' => intval($_POST['operario']),
        'fecha_ini' => $_POST['fecha_ini'],
        'fecha_fin' => $_POST['fecha_fin'],
        'motivo' => $_POST['motivo'],
        'descripcion' => $_POST['descripcion']
    ];
    $ok = $modelo->insertarLicencia($data, $_SESSION['usuario_id']);
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Licencia registrada' : 'Error al registrar licencia'
    ]);
    exit;
}

// --------------------------------------------------------------------
// MOSTRAR LA VISTA (index)
// --------------------------------------------------------------------
// Obtener datos para los filtros
$sedes = $modelo->getSedes();
$motivos = $modelo->getMotivosIngreso();
$tiposContrato = $modelo->getTiposContrato();

include "../view/seguimientousuario/index.php";