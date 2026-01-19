<?php
require_once "../model/CorreosEnviadosModel.php";

$modelo = new CorreosEnviadosModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'reenviar') {

    $id = intval($_POST['id'] ?? 0);

    $resultado = $modelo->reenviarCorreo($id);

    echo json_encode($resultado);
    exit;
}
/**
 * ==========================
 * 4️⃣ DESCARGAR ADJUNTO
 * ==========================
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['accion'] ?? '') === 'descargar') {

    $id = intval($_GET['id'] ?? 0);
    $correo = $modelo->obtenerCorreoPorId($id);

    if (
        !$correo ||
        empty($correo['archivo_adjunto']) ||
        !file_exists($correo['archivo_adjunto'])
    ) {
        http_response_code(404);
        echo "Archivo no encontrado";
        exit;
    }

    $ruta = $correo['archivo_adjunto'];
    $nombre = $correo['nombre_archivo'] ?: basename($ruta);

    // 🔐 Headers de descarga
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($nombre) . '"');
    header('Content-Length: ' . filesize($ruta));
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: public');

    readfile($ruta);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    if ($_POST['tipo'] === 'recibidos') {
        $modelo->sincronizarCorreosRecibidos();
        $data = $modelo->obtenerCorreosRecibidos();
    } else {
        $data = $modelo->obtenerCorreos();
    }

    // $data = $modelo->obtenerCorreos(100);

    echo json_encode($data);
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {

    $id = intval($_POST['id']);
    $correo = $modelo->obtenerCorreoPorId($id);

    if (!$correo) {
        echo json_encode([
            'ok' => false,
            'html' => '<div class="alert alert-danger">Correo no encontrado</div>'
        ]);
        exit;
    }

    // HTML que se mostrará en el modal
    ob_start();
    ?>
    <div class="container-fluid">

        <div class="row mb-2">
            <div class="col-md-6">
                <b>Para:</b> <?= htmlspecialchars($correo['correo_destino']) ?>
            </div>
            <div class="col-md-6 text-end">
                <b>Fecha:</b> <?= $correo['fecha_envio'] ?>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-6">
                <b>Asunto:</b> <?= htmlspecialchars($correo['asunto']) ?>
            </div>
            <div class="col-md-6 text-end">
                <b>Estado:</b>
                <?php if ($correo['estado'] === 'enviado'): ?>
                    <span class="badge bg-success">Enviado</span>
                <?php else: ?>
                    <span class="badge bg-danger">Error</span>
                <?php endif; ?>
            </div>
        </div>

        <hr>

        <!-- CONTENIDO DEL CORREO -->
        <div class="border rounded p-3" style="background:#f9f9f9;">
            <?= $correo['cuerpo_html'] ?>
        </div>

        <?php if (!empty($correo['nombre_archivo'])): ?>
            <hr>
            <p>
                <i class="fas fa-paperclip"></i>
                <b>Adjunto:</b> <?= htmlspecialchars($correo['nombre_archivo']) ?>
            </p>
        <?php endif; ?>

    </div>
    <?php

    echo json_encode([
        'ok' => true,
        'html' => ob_get_clean()
    ]);
    exit;
}


// Cargar vista
include "../view/CorreosEnviados/index.php";
