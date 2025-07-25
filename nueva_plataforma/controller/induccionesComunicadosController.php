<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../model/induccionesComunicadosModel.php";
require_once __DIR__ . '/../../declara.php';

$modelo = new induccionesComunicados();
$accion = $_REQUEST["accion"] ?? null;

switch ($accion) {

    // Mostrar registros para DataTable
    case "mostrar":
        $estado = $_POST["estado"] ?? '';
        $rol = $nivel_acceso;        // Rol del usuario desde declara.php
        $nombreUsuario = $id_nombre; // Nombre desde declara.php

        $condiciones = "";

        if ($rol != 1 && $rol != 12) {
            // Filtrar solo los registros que contienen su nombre
            $condiciones .= " AND FIND_IN_SET('$nombreUsuario', ci_usuario)";
        }

        if (!empty($estado)) {
            $condiciones .= " AND ci_estado = '$estado'";
        }

        $sql = "SELECT * FROM comunicados_inducciones WHERE 1=1 $condiciones ORDER BY ci_fecha_registro DESC";
        $resultado = $DB->consulta($sql);
        
        $datos = [];
        while ($row = $DB->fetch_assoc($resultado)) {
            $row["es_admin"] = in_array($rol, [1, 12]) ? 1 : 0; // Para el frontend
            $datos[] = $row;
        }

        echo json_encode($datos);
        break;

    // Agregar nuevo comunicado o inducción
    case "agregar":
        $nombreDoc       = $_POST['ci_nombre_documento'];
        $encargado       = $_POST['ci_encargado'];
        $usuarios        = $_POST['ci_usuario'];
        $linkDoc         = $_POST['ci_link_documento'];
        $estado          = $_POST['ci_estado'];
        $fechaUsuario    = $_POST['ci_fecha_confirmacion_usuario'];
        $fechaEncargado  = $_POST['ci_fecha_confirmacion_encargado'];
        $fechaRegistro   = date('Y-m-d');
        $archivoNombre   = "";

        $carpetaDestino = "../documentos_ci/";

        if (!empty($_FILES['ci_ruta_archivo']['name'])) {
            $archivoTmp     = $_FILES['ci_ruta_archivo']['tmp_name'];
            $archivoNombre  = basename($_FILES['ci_ruta_archivo']['name']);
            $archivoDestino = $carpetaDestino . $archivoNombre;

            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            move_uploaded_file($archivoTmp, $archivoDestino);
        }

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
        break;

    // Actualizar campo (como estado)
    case "actualizar_campo":
        $id = $_POST['id'];
        $campo = $_POST['campo'];
        $valor = $_POST['valor'];
        $modelo->actualizarCampoCI($id, $campo, $valor);
        echo json_encode(['ok' => true]);
        break;

    // Eliminar un comunicado
    case "eliminar":
        $id = $_POST['id'];
        $modelo->eliminarComunicado($id);
        echo json_encode(['ok' => true]);
        break;

    // Obtener sedes principales
    case "obtener_sedes":
        $sql = "SELECT idsedes, sed_nombre FROM sedes WHERE sed_principal = 'si' ORDER BY sed_nombre";
        $result = (new Database())->connect()->query($sql);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        break;

    // Obtener usuarios por sede
    case "obtener_usuarios":
        $sedeId = $_GET['sede_id'] ?? '';
        $usuarios = $modelo->obtenerUsuariosPorSede($sedeId);
        echo json_encode($usuarios);
        break;

    // Obtener encargados
    case "obtener_encargados":
        $encargados = $modelo->obtenerEncargados();
        echo json_encode($encargados);
        break;
    
    // Si no hay acción, cargar vista
    default:
        $roles = $modelo->obtenerRoles();
        include "../view/iduccionesComunicados/index.php";
        break;
}
