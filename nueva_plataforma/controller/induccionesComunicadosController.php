<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../model/induccionesComunicadosModel.php";

$modelo = new induccionesComunicados();

// ✅ 1. Cargar datos AJAX para DataTable
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $rol = $_POST['rol'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $usuarios = $modelo->obtenerUsuarios($rol, $estado);
    echo json_encode($usuarios);
    exit;
}

// ✅ 2. Actualizar un campo específico (estado, etc)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_campo'])) {
    $id = $_POST['id'];
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];
    $modelo->actualizarCampo($id, $campo, $valor);
    echo json_encode(['ok' => true]);
    exit;
}

// ✅ 3. Eliminar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_usuario'])) {
    $id = $_POST['id'];
    $modelo->eliminarUsuario($id);
    echo json_encode(['ok' => true]);
    exit;
}

// ✅ 4. Agregar nuevo comunicado o inducción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ci_nombre_documento'])) {
    // Recolectar datos del formulario
    $nombreDoc   = $_POST['ci_nombre_documento'];
    $encargado   = $_POST['ci_encargado'];
    $usuario     = $_POST['ci_usuario'];
    $linkDoc     = $_POST['ci_link_documento'];
    $estado      = $_POST['ci_estado'];
    $fechaUsuario    = $_POST['ci_fecha_confirmacion_usuario'];
    $fechaEncargado  = $_POST['ci_fecha_confirmacion_encargado'];

    $archivoNombre = "";
    $carpetaDestino = "../documentos_ci/";

    // Si hay archivo, subirlo
    if (!empty($_FILES['ci_ruta_archivo']['name'])) {
        $archivoTmp     = $_FILES['ci_ruta_archivo']['tmp_name'];
        $archivoNombre  = basename($_FILES['ci_ruta_archivo']['name']);
        $archivoDestino = $carpetaDestino . $archivoNombre;

        // Crear carpeta si no existe
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        move_uploaded_file($archivoTmp, $archivoDestino);
    }

    // Insertar en la base de datos usando el modelo
    $insertado = $modelo->insertarComunicado([
        'nombreDoc'      => $nombreDoc,
        'encargado'      => $encargado,
        'usuario'        => $usuario,
        'linkDoc'        => $linkDoc,
        'estado'         => $estado,
        'fechaUsuario'   => $fechaUsuario,
        'fechaEncargado' => $fechaEncargado
    ], $archivoNombre);

    if ($insertado) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['error' => 'Error al insertar el comunicado.']);
    }
    exit;
}

// ✅ 5. Mostrar la vista por defecto
$roles = $modelo->obtenerRoles();
include "../view/iduccionesComunicados/index.php";
