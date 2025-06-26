<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require("login_autentica.php"); 
require("declara.php");

$DB = new DB_mssql;
$DB->conectar();

$fecha_hoy = date("Y-m-d");

$sql = "SELECT d.iddoccliente, d.docl_nombre, d.docl_fecha_venc, d.docl_idhvc
        FROM doc_hoja_clientes d
        INNER JOIN (
            SELECT docl_nombre, MAX(iddoccliente) as max_id
            FROM doc_hoja_clientes
            GROUP BY docl_nombre
        ) latest ON d.docl_nombre = latest.docl_nombre AND d.iddoccliente = latest.max_id
        WHERE DATEDIFF(d.docl_fecha_venc, '$fecha_hoy') <= 30";

$DB->Execute($sql);
$result = $DB->Consulta_ID;

if (!$result) {
    die("Error en la consulta principal: " . mysqli_error($DB->link));
}

$documentos_por_cliente = [];

while ($doc = mysqli_fetch_assoc($result)) {
    $idhvc = $doc['docl_idhvc'];
    $documentos_por_cliente[$idhvc][] = [
        'nombre' => $doc['docl_nombre'],
        'vencimiento' => $doc['docl_fecha_venc']
    ];
}
foreach ($documentos_por_cliente as $idhvc => $documentos) {

    $correos = [];
    $DB->Execute("SELECT cont_correo FROM contactofacturacion WHERE cont_idhojavida = '$idhvc' AND actualizacion_datos = 'si'");
    $res_contactos = $DB->Consulta_ID;
    while ($contacto = mysqli_fetch_assoc($res_contactos)) {
        $correo = trim($contacto['cont_correo']);
        if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $correos[] = $correo;
        }
    }

    if (empty($correos)) {
        echo "Sin correos válidos para cliente  (ID: $idhvc)<br>";
        continue;
    }

    $tabla = "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse; font-family: Arial, sans-serif;'>
                <thead style='background-color:#f2f2f2;'>
                    <tr>
                        <th style='background-color:#004080; color:#fff;'>Documento</th>
                        <th style='background-color:#004080; color:#fff;'>Fecha de vencimiento</th>
                    </tr>
                </thead>
                <tbody>";

    foreach ($documentos as $doc) {
        $tabla .= "<tr>
                     <td>{$doc['nombre']}</td>
                     <td>{$doc['vencimiento']}</td>
                   </tr>";
    }

    $tabla .= "</tbody></table>";
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'actualizaciondatostransmillas@gmail.com';
        $mail->Password = 'bszm qoji posj irht';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('actualizaciondatostransmillas@gmail.com', 'Transmillas');

        foreach ($correos as $destino) {
            $mail->addAddress($destino);
        }

        $mail->isHTML(true);
        $mail->Subject = "Documentos próximos a vencer - Cliente";
        $mail->Body = "
            <html>
            <body style='font-family: Arial, sans-serif; color: #333;'>
                <p>Estimado(s)<strong></strong>,</p>
                <p>Nos permitimos informarle que los siguientes documentos asociados a su empresa están próximos a vencer:</p>
                $tabla
                <p style='margin-top: 20px;'>Le solicitamos realizar la actualización correspondiente lo antes posible.</p>
                <p>Puede remitir la documentación actualizada respondiendo a este correo.</p>
                <br>
                <p style='font-size: 14px;'>
                    Atentamente,<br>
                    <strong>Área Administrativa</strong><br>
                    Transmillas S.A.S<br>
                    actualizaciondatostransmillas@gmail.com
                </p>
            </body>
            </html>
        ";
        $mail->AltBody = "Documentos próximos a vencer. Revisa el correo en modo HTML para ver el detalle.";

        $mail->send();
        echo "Correo enviado a  ➤ " . implode(', ', $correos) . "<br>";
    } catch (Exception $e) {
        echo "Error al enviar a: {$mail->ErrorInfo}<br>";
    }

    $mail->clearAddresses();
}
?>
