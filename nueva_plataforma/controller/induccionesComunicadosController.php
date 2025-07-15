<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../model/induccionesComunicadosModel.php";
$modelo = new induccionesComunicados();

// ✅ 1. Buscar usuarios para Select2
file_put_contents("debug_log.txt", json_encode($_POST));
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_usuarios'])) {
    echo json_encode([
        ["usu_nombre" => "Juan Pérez"],
        ["usu_nombre" => "Ana García"],
        ["usu_nombre" => "Carlos Gómez"]
    ]);
    exit;
}



// ✅ 2. Cargar datos AJAX para DataTable
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $estado = $_POST['estado'] ?? '';
    $comunicados = $modelo->obtenerComunicados($estado);
    echo json_encode($comunicados);
    exit;
}

// ✅ 3. Actualizar campo (como el estado)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_campo'])) {
    $id = $_POST['id'];
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];

    $modelo->actualizarCampoCI($id, $campo, $valor);
    echo json_encode(['ok' => true]);
    exit;
}

// ✅ 4. Eliminar comunicado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_usuario'])) {
    $id = $_POST['id'];
    $modelo->eliminarComunicado($id);
    echo json_encode(['ok' => true]);
    exit;
}

// ✅ 5. Agregar nuevo comunicado o inducción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ci_nombre_documento'])) {
    // Recolectar datos del formulario
    $nombreDoc       = $_POST['ci_nombre_documento'];
    $encargado       = $_POST['ci_encargado'];
    $usuarios        = $_POST['ci_usuario']; // ← Array de nombres de usuarios
    $linkDoc         = $_POST['ci_link_documento'];
    $estado          = $_POST['ci_estado'];
    $fechaUsuario    = $_POST['ci_fecha_confirmacion_usuario'];
    $fechaEncargado  = $_POST['ci_fecha_confirmacion_encargado'];
    $fechaRegistro   = date('Y-m-d');

    $archivoNombre = "";
    $carpetaDestino = "../documentos_ci/";

    // Subir archivo si existe
    if (!empty($_FILES['ci_ruta_archivo']['name'])) {
        $archivoTmp     = $_FILES['ci_ruta_archivo']['tmp_name'];
        $archivoNombre  = basename($_FILES['ci_ruta_archivo']['name']);
        $archivoDestino = $carpetaDestino . $archivoNombre;

        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        move_uploaded_file($archivoTmp, $archivoDestino);
    }

    // Insertar un registro por cada usuario seleccionado
    $todoBien = true;

    foreach ($usuarios as $usuario) {
        $ok = $modelo->insertarComunicado(
            $nombreDoc,
            $encargado,
            $usuario,
            $linkDoc,
            $archivoNombre,
            $estado,
            $fechaRegistro,
            $fechaUsuario,
            $fechaEncargado
        );

        if (!$ok) {
            $todoBien = false;
            break;
        }
    }

    echo json_encode($todoBien ? ['ok' => true] : ['error' => 'Error al insertar uno o más comunicados.']);
    exit;
}

// ✅ 6. Mostrar la vista por defecto
$roles = $modelo->obtenerRoles();
include "../view/iduccionesComunicados/index.php";
