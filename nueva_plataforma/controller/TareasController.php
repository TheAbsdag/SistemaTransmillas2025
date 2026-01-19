<?php
require_once "../model/TareasModel.php";
$model = new TareasModel();
date_default_timezone_set('America/Bogota');

// seguridad: si tu proyecto exige comprobaciones por POST['sede'] o acceso, puedes replicarlas aquí
// por ejemplo:
// $acceso = $_POST['acceso'] ?? $_GET['acceso'] ?? null;
// $sede = $_POST['sede'] ?? $_GET['sede'] ?? null;

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

if ($accion === 'listarSemana') {
    // opcional: recibir fecha de inicio de semana
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $data = $model->obtenerAsignacionesSemana($fecha_inicio);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

if ($accion === 'listarTareas') {
    $tareas = $model->obtenerTareas();
    header('Content-Type: application/json');
    echo json_encode($tareas);
    exit;
}
if ($accion === 'eliminarTarea') {
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['ok' => false, 'msg' => 'ID inválido']);
        exit;
    }

    $res = $model->eliminarTarea($id);
    header('Content-Type: application/json');
    echo json_encode($res);
    exit;
}

if ($accion === 'listarUsuarios') {
    $usuarios = $model->obtenerUsuarios();
    header('Content-Type: application/json');
    echo json_encode($usuarios);
    exit;
}


if ($accion === 'agregarTarea') {
    $nombre = trim($_POST['nombre'] ?? '');
    $cantidad = intval($_POST['cantidad'] ?? 1);
    if ($nombre === '' || $cantidad < 1) {
        echo json_encode(['ok' => false, 'msg' => 'Datos inválidos']);
        exit;
    }
    $ok = $model->agregarTarea($nombre, $cantidad);
    echo json_encode(['ok' => (bool)$ok]);
    exit;
}

if ($accion === 'sortear') {
    // sortear para la fecha actual o fecha enviada
    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $res = $model->sortearTareasDelDia($fecha);
    header('Content-Type: application/json');
    echo json_encode($res);
    exit;
}

// Listar operadores por sede (para UI)
if ($accion === 'listarOperadoresPorSede') {
    $sede = $_POST['sede'] ?? '';
    $ops = $model->obtenerOperadores($sede);
    header('Content-Type: application/json');
    echo json_encode($ops);
    exit;
}

if ($accion === 'sortearTareasSeleccionadas') {
    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $seleccionados = $_POST['usuarios'] ?? [];
    $tarea_id = $_POST['tarea_id'] ?? ''; // 👈 nueva variable para la tarea seleccionada

    if (empty($seleccionados)) {
        echo json_encode(['ok' => false, 'msg' => 'Debe seleccionar al menos un operador']);
        exit;
    }

    // Si llegan como JSON string, convertirlos a array
    if (!is_array($seleccionados)) {
        $seleccionados = json_decode($seleccionados, true);
    }

    // Llamar al modelo con o sin tarea_id (según se haya seleccionado)
    if (!empty($tarea_id)) {
        $res = $model->sortearTareasDelDia($fecha, $seleccionados, $tarea_id);
    } else {
        $res = $model->sortearTareasDelDia($fecha, $seleccionados);
    }

    header('Content-Type: application/json');
    echo json_encode($res);
    exit;
}
if ($accion === 'eliminarAsignacion') {

    header('Content-Type: application/json');

    // Obtener ID
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode([
            'ok' => false,
            'msg' => 'ID inválido'
        ]);
        exit;
    }

    // Llamar al modelo
    $res = $model->eliminarAsignacion($id);

    // Responder (el modelo debe retornar ['ok'=>true/false, 'msg'=>'...'])
    echo json_encode($res);
    exit;
}

// Si llegaste hasta aquí y no hay acción, carga la vista (si quieres usar controller como router)
include "../view/Tareas/index.php";
