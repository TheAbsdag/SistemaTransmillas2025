<?php


require("../../login_autentica.php"); // ESTE archivo debe tener session_start()

require_once "../model/EntregarModel.php";

$modelo = new EntregarModel();


/* ============================================================
   ACCIÓN: MOSTRAR VISTA
   ============================================================ */
if (isset($_GET['accion']) && $_GET['accion'] === 'vista') {

    // Recibir idServicio por GET
    $idServicio = $_GET['idServicio'] ?? 0;

    // Recibir datos POST si vienen desde otro módulo
    $sede    = $_POST['sede']    ?? '';
    $acceso  = $_POST['acceso']  ?? '';
    $usuario = $_POST['usuario'] ?? '';

    // Cargar vista principal
    include "../view/Entregar/index.php";
    exit;
}

/* ============================================================
   ACCIÓN: GUARDAR ENTREGA
   ============================================================ */
if (isset($_POST['accion']) && $_POST['accion'] === 'guardarEntrega') {

    $resp = $modelo->guardarEntrega($_POST, $_FILES);

    header("Content-Type: application/json");
    echo json_encode($resp);
    exit;
}


/* ============================================================
   ACCIÓN: GUARDAR NO ENTREGADO
   ============================================================ */
if (isset($_POST['accion']) && $_POST['accion'] === 'guardarNoEntregar') {

    ob_start();
    $resp = $modelo->guardarNoEntregar($_POST, $_FILES);
    $debug = ob_get_clean();

    if ($debug !== "") {
        file_put_contents(__DIR__ . "/debug_output.log", $debug, FILE_APPEND);
    }

    file_put_contents(__DIR__ . "/RESPUESTA_DEBUG.txt", json_encode($resp));

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($resp);
    exit;
}
/* ============================================================
   Enviar Firma Entrega
   ============================================================ */
if (isset($_POST['accion']) && $_POST['accion'] === 'enviarLinkFirma') {
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    header('Content-Type: application/json');

    $session = $_SESSION ?? [];
    $usuario = $session['usuario_id'] ?? null;

    if (empty($usuario)) {
        // logController('ACCESO DENEGADO enviarLinkFirma', [
        //     'motivo' => 'Sesión no válida'
        // ]);

        echo json_encode([
            'ok' => false,
            'mensaje' => 'Sesión expirada'
        ]);
        exit;
    }

    // logController('REQUEST enviarLinkFirma', [
    //     'post' => $_POST,
    //     'usuario' => $usuario
    // ]);

    try {

        // 🔎 Validaciones básicas
        $idservicio = $_POST['idservicio'] ?? null;
        $nombre     = trim($_POST['nombre'] ?? '');
        $telefono   = trim($_POST['telefono'] ?? '');
        $tipopago   = trim($_POST['tipopago'] ?? '');

        $link = "$idservicio&accion=guardarFirmaEntrega&tipo_pago=$tipopago";

        if (empty($idservicio) || !is_numeric($idservicio)) {
            echo json_encode([
                'ok' => false,
                'mensaje' => 'ID de servicio inválido'
            ]);
            exit;
        }

        if (empty($telefono)) {
            echo json_encode([
                'ok' => false,
                'mensaje' => 'Teléfono requerido'
            ]);
            exit;
        }

        $idservicio = intval($idservicio);

        // 🔥 Tipo de alerta (usa el mismo que tu sistema ya maneje)
        $tipoAlerta = 44;

        // 🔥 Llamar al modelo
        $resp = $modelo->reEnviarFirmaWhat($telefono, $tipoAlerta, $idservicio, $link);

        if (!$resp['ok']) {
            echo json_encode([
                'ok' => false,
                'mensaje' => 'No se pudo enviar el mensaje de WhatsApp'
            ]);
            exit;
        }

        echo json_encode([
            'ok' => true,
            'mensaje' => 'Link enviado correctamente'
        ]);
        exit;

    } catch (Throwable $e) {

        echo json_encode([
            'ok' => false,
            'mensaje' => 'Error interno al reenviar link'
        ]);
        exit;
    }
}
/* ============================================================
   ACCIÓN: Cargar sello
   ============================================================ */

if (isset($_POST['accion']) && $_POST['accion'] === 'guardarSello') {

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header('Content-Type: application/json');

    $session = $_SESSION ?? [];
    $usuario = $session['usuario_id'] ?? null;

    if (empty($usuario)) {
        echo json_encode([
            'ok' => false,
            'mensaje' => 'Sesión expirada'
        ]);
        exit;
    }

    try {

        if (empty($_POST['idservicio']) || !is_numeric($_POST['idservicio'])) {
            echo json_encode([
                'ok' => false,
                'mensaje' => 'ID de servicio inválido'
            ]);
            exit;
        }

        $idservicio = intval($_POST['idservicio']);

        if (empty($_POST['firmaBase64'])) {
            echo json_encode([
                'ok' => false,
                'mensaje' => 'No se recibió la imagen del sello'
            ]);
            exit;
        }

        $firmaBase64 = $_POST['firmaBase64'];

        if (strpos($firmaBase64, 'data:image') !== 0) {
            echo json_encode([
                'ok' => false,
                'mensaje' => 'Formato de imagen inválido'
            ]);
            exit;
        }

        $guardado = $modelo->guardarFirmaRecogida($idservicio, $firmaBase64);

        if (!$guardado) {
            echo json_encode([
                'ok' => false,
                'mensaje' => 'No se pudo guardar el sello'
            ]);
            exit;
        }

        echo json_encode([
            'ok' => true,
            'mensaje' => 'Sello guardado correctamente'
        ]);
        exit;

    } catch (Throwable $e) {

        echo json_encode([
            'ok' => false,
            'mensaje' => 'Error interno al guardar sello'
        ]);
        exit;
    }
}

/* ============================================================
   ACCIÓN: BUSCAR DATOS DEL SERVICIO
   ============================================================ */
if (isset($_GET['accion']) && $_GET['accion'] === 'buscarEntrega') {

    $id = isset($_GET['id']) ? $_GET['id'] : 0;

    $datos = $modelo->buscarEntrega($id);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos);
    exit;
}
/* ============================================================
   ACCIÓN: CONSULTAR ESTADO DE FIRMA
   ============================================================ */
if (isset($_GET['accion']) && $_GET['accion'] === 'consultarEstadoFirma') {

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $firmada = ($id > 0) ? $modelo->existeFirmaEntregaPublica($id) : false;

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'ok' => true,
        'firmada' => (bool)$firmada
    ]);
    exit;
}

// Si no coincide ninguna acción
echo "Acción no válida.";
exit;

