<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';
require '../../PHPMailer/src/Exception.php';
require_once '../config/database.php';

class FacturaMailer {

    private $db;

    public function __construct() {
        $conexion = new Database();
        $this->db = $conexion->connect();
    }

    public function enviarFactura() {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ventastransmillas@gmail.com';
            $mail->Password   = 'tpwv clpk qqdo dbgx';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $destinatario = $_POST['correo'] ?? '';
            $contenido = $_POST['body'] ?? '';
            $idFactura = $_POST['idfac'] ?? 0;
            $asunto = $_POST['asunto'] ?? 'Documentos de Facturación';
            $numero = $_POST['numero'] ?? '';
            $linkFac = $_POST['linkFac'] ?? '';
            $linkfac1 = $_POST['linkfac1'] ?? '';

            $mail->setFrom('ventastransmillas@gmail.com', 'TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A.S.');

            if (!empty($destinatario)) {
                $mail->addAddress($destinatario);
            }
            // ✨ Agregar destinatarios adicionales desde $_POST['correos']
            if (!empty($_POST['correos'])) {
                $correos = json_decode($_POST['correos'], true);
                if (is_array($correos)) {
                    foreach ($correos as $destinatarioExtra) {
                        if (filter_var($destinatarioExtra, FILTER_VALIDATE_EMAIL)) {
                            $mail->addAddress($destinatarioExtra);
                        }
                    }
                } else {
                    error_log("Notice: 'correos' no es un array válido o está vacío.");
                }
            }
            $mail->AddEmbeddedImage('../../images/logoCorreo.jpg', 'empresa_logo');
            $mail->isHTML(true);
            $mail->Subject = $asunto;

            $mail->Body = '
                <html>
                <head>
                    <style>
                        .footer {
                            font-size: 12px;
                            color: #777;
                            margin-top: 20px;
                            border-top: 1px solid #ddd;
                            padding-top: 10px;
                        }
                    </style>
                </head>
                <body>
                    <div>
                        <img src="cid:empresa_logo" alt="Logo de la empresa" style="width: 400px;">
                        <p>' . $contenido . '</p>
                        <div class="footer">
                            <p>Gracias por su atención.</p>
                            <p>TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A.S.</p>
                            <p>Carrera 20 # 56-26 Galerías</p>
                            <p>PBX:3103122</p>
                        </div>
                    </div>
                </body>
                </html>';

            $mail->AltBody = strip_tags($contenido);

            // Adjuntos desde $_FILES
            for ($i = 0; $i <= 1; $i++) {
                $key = 'File' . $i;
                if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
                    $mail->addAttachment($_FILES[$key]['tmp_name'], $_FILES[$key]['name']);
                }
            }

            // Adjuntos desde enlaces
            if (!empty($linkFac)) {
                $mail->addAttachment($linkFac, basename($linkFac));
            }
            if (!empty($linkfac1)) {
                $mail->addAttachment($linkfac1, basename($linkfac1));
            }

            $mail->send();

            // WhatsApp alerta después de envío
            if (!empty($numero)) {
                $this->enviarAlertaWhat($contenido, $numero, "33", $linkFac);
            }

            // Guardar archivos subidos si es necesario
            $uploadDir = __DIR__ . 'img_facturas/';
            $baseUrl = 'https://sistema.transmillas.com/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            for ($i = 0; $i <= 1; $i++) {
                $key = 'File' . $i;
                if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES[$key]['tmp_name'];
                    $name = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.\-]/', '', $_FILES[$key]['name']);
                    $dest = $uploadDir . $name;
                    if (move_uploaded_file($tmpName, $dest)) {
                        $this->enviarAlertaWhat($contenido, $numero, "34", $baseUrl . 'img_facturas/' . $name);
                    }
                }
            }

            // Actualizar contador en DB
            if ($idFactura > 0) {
                $sql = "SELECT fac_correofac FROM facturascreditos WHERE idfacturascreditos = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $idFactura);
                $stmt->execute();

                $fac_correofac = 0;
                $stmt->bind_result($fac_correofac);
                if ($stmt->fetch()) {
                    $stmt->close();

                    $nummensajes = $fac_correofac + 1;

                    $sqlUpdate = "UPDATE facturascreditos SET fac_correofac = ? WHERE idfacturascreditos = ?";
                    $stmtUpdate = $this->db->prepare($sqlUpdate);
                    $stmtUpdate->bind_param("ii", $nummensajes, $idFactura);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();

                    echo 'Correo enviado y contador actualizado.';
                } else {
                    echo 'Factura no encontrada.';
                }
            }

        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    }

    private function enviarAlertaWhat($numguia, $telefono, $tipo, $text2) {
        $url = "https://www.transmillas.com/ChatbotTransmillas/alertas.php";

        $data = array(
            "numero_guia" => $numguia,
            "telefono" => $telefono,
            "tipo_alerta" => $tipo,
            "texto2" => $text2
        );

        $data_json = json_encode($data);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer MiSuperToken123'
            ),
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            error_log("Error en alerta WhatsApp: $error");
        } else {
            $response_data = json_decode($response, true);
            error_log("WhatsApp enviado: " . print_r($response_data, true));
        }

        curl_close($curl);
    }
}
