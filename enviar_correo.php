<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require("login_autentica.php");
include("declara.php");

// Requiere PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$iddoc = intval($_POST['iddoccliente']);
$sql_doc = "SELECT docl_nombre, docl_documento, docl_idhvc FROM doc_hoja_clientes WHERE iddoccliente = $iddoc";
$res_doc = $DB->Execute($sql_doc);
$row_doc = mysqli_fetch_assoc($res_doc);

$nombre_doc = $row_doc['docl_nombre'];
$archivo_doc = $row_doc['docl_documento']; 
$idhvc = $row_doc['docl_idhvc'];

$sql_contactos = "SELECT cont_correo FROM contactofacturacion WHERE cont_idhojavida = '$idhvc' AND actualizacion_datos = 'si'";
$res_contactos = $DB->Execute($sql_contactos);

$destinatarios = [];
while ($row = mysqli_fetch_assoc($res_contactos)) {
    $correo = trim($row['cont_correo']);
    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $destinatarios[] = $correo;
    }
}

if (empty($destinatarios)) {
    echo "No se encontraron destinatarios con actualización de datos activa.";
    exit;
}

$asunto = "Solicitud de actualizacion de documento: $nombre_doc";
$mensaje = "Estimado/a,\n\n";
$mensaje .= "Esperamos que se encuentre bien.\n\n";
$mensaje .= "Nos permitimos solicitar la actualización de la informacion correspondiente al documento: **$nombre_doc**, el cual se encuentra registrado en nuestro sistema.\n\n";
$mensaje .= "Le agradecemos diligenciar y adjuntar la información solicitada en el siguiente documento:\n\n";
$mensaje .= "Por favor responda a este mismo correo con el documento diligenciado o con la información correspondiente lo más pronto posible.\n\n";
$mensaje .= "Agradecemos su atención y colaboración.\n\n";
$mensaje .= "Cordialmente,\n";
$mensaje .= "Equipo de Transmillas\n";

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'actualizaciondatostransmillas@gmail.com';
    $mail->Password = 'yetf oypc euao nbnq'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('actualizaciondatostransmillas@gmail.com', 'Transmillas');

    foreach ($destinatarios as $correo) {
        $mail->addAddress($correo);
    }

    $mail->Subject = $asunto;
    $mail->Body = $mensaje;

    if (!empty($archivo_doc) && file_exists("ruta_documentos/$archivo_doc")) {
        $mail->addAttachment("ruta_documentos/$archivo_doc", $archivo_doc);
    }

    $mail->send();
    echo "Correo enviado correctamente a " . count($destinatarios) . " destinatarios.";

} catch (Exception $e) {
    echo "Error al enviar correo: {$mail->ErrorInfo}";
}
?>
