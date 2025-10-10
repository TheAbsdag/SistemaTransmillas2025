<?php



// Incluir los archivos de PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Crear una nueva instancia de PHPMailer
$mail = new PHPMailer(true);



    $numCorreos = 0;
    $numWhatsApp = 0;
try {


    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ventastransmillas@gmail.com';
    $mail->Password   = 'tpwv clpk qqdo dbgx'; // Asegúrate de usar la contraseña de la aplicación si tienes 2FA habilitado
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $destinatario = $_POST['correo'];
    $correos = json_decode($_POST['correos'], true);



    // $destinatario = "jose523a@gmail.com";

    // Remitente y destinatarios
    $mail->setFrom('ventastransmillas@gmail.com', 'TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A.S.'); // Reemplaza con tu correo y nombre
    if ($destinatario!="") {
        $mail->addAddress($destinatario, ''); // Reemplaza con el correo del destinatario

    }
    if (is_array($correos)) {
        foreach ($correos as $destinatarios) {
            $mail->addAddress($destinatarios);
        }
    } else {
        echo "Error: 'correos' no es un array válido o esta vacio.";
        // exit;
    }

    $contenido=$_POST['body'];
    $idFactura=$_POST['idfac'];



    // Adjuntar imágenes embebidas
    $mail->AddEmbeddedImage('images/logoCorreo.jpg', 'empresa_logo');
    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Documentos de Facturacion';
    // $mail->Subject = 'PRUEBAS SOFTWARE';

    $contenidoHTML = '
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

    $mail->Body    = $contenidoHTML;
    $mail->AltBody = strip_tags($contenido);

    if (isset($_FILES['File0']) && $_FILES['File0']['error'] == UPLOAD_ERR_OK) {
        $uploadFile0 = $_FILES['File0']['tmp_name'];
        $uploadFileName0 = $_FILES['File0']['name'];
        $mail->addAttachment($uploadFile0, $uploadFileName0);
    }

    if (isset($_FILES['File1']) && $_FILES['File1']['error'] == UPLOAD_ERR_OK) {
        $uploadFile1 = $_FILES['File1']['tmp_name'];
        $uploadFileName1 = $_FILES['File1']['name'];
        $mail->addAttachment($uploadFile1, $uploadFileName1);
    }


    if (isset($_POST['linkFac']) ) {
        // Ruta del archivo existente en el servidor
        $existingFilePath = $_POST['linkFac'];
        $existingFileName = $_POST['linkFac']; // Nombre con el que deseas que aparezca en el correo

        // Adjunta el archivo existente en el servidor
        $mail->addAttachment($existingFilePath, $existingFileName);
    }
    if (isset($_POST['linkfac1']) ) {
        // Ruta del archivo existente en el servidor
        $existingFilePath = $_POST['linkfac1'];
        $existingFileName = $_POST['linkfac1']; // Nombre con el que deseas que aparezca en el correo

        // Adjunta el archivo existente en el servidor
        $mail->addAttachment($existingFilePath, $existingFileName);
    }


    // Enviar el correo
    $mail->send();
    

    if ($_POST['numero']!="") {
        $numero=$_POST['numero'];
        $link="https://sistema.transmillas.com/".$existingFileName;
        enviarAlertaWhat($contenido,$numero,"33",$link);
    }

    // 1. Directorio donde vas a guardar las imágenes (debe ser público)
    $uploadDir = __DIR__ . '/img_facturas/';
    // URL pública base de ese directorio
    $baseUrl   = 'https://sistema.transmillas.com/';

    // Asegúrate de que exista la carpeta
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $uploadedLinks = [];

    // 2. Procesar File0 y File1 en un mismo bucle
    for ($i = 0; $i <= 1; $i++) {
        $key = 'File' . $i;
        if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES[$key]['tmp_name'];
            // Sanitiza y haz único el nombre: evita colisiones
            $name = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.\-]/', '', $_FILES[$key]['name']);
            $dest = $uploadDir . $name;

            if (move_uploaded_file($tmpName, $dest)) {
                // 3. Guarda la URL pública
                $uploadedLinks[] = $baseUrl . $name;
                

                if ($_POST['numero']!="") {
                    $numero=$_POST['numero'];
                    $link="https://sistema.transmillas.com/".$existingFileName;
                    $resultado = enviarAlertaWhat($contenido,$numero,"34",$link);
                    if ($resultado["status"]) {
                        echo $resultado["mensaje"]; 
                        $numWhatsApp++; // solo cuenta si se envió bien
                    } else {
                        echo $resultado["mensaje"]; // muestra el error
                    }
                }                
                // // 4. Adjunta al correo si quieres
                // $mail->addAttachment($dest, $name);
            }
        }
    }




    // Incluir la clase de conexión
    require_once 'nueva_plataforma/config/database.php';

    // Crear instancia y conectar
    $db = new Database();
    $conn = $db->connect();

    // Asegúrate de tener un valor válido de $idFactura
    $idFactura = isset($_POST['idfac']) ? intval($_POST['idfac']) : 0;

    if ($idFactura > 0) {
        // Obtener el valor actual de fac_correofac
        
        $sql = "SELECT fac_correofac FROM facturascreditos WHERE idfacturascreditos = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idFactura);
        $stmt->execute();
        $stmt->bind_result($fac_correofac);
        
        if ($stmt->fetch()) {
            $stmt->close();

            // Incrementar el contador
            $nummensajes = $fac_correofac + 1;

            // Actualizar el valor
            
            $sqlUpdate = "UPDATE facturascreditos SET fac_correofac = ? WHERE idfacturascreditos = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ii", $nummensajes, $idFactura);
            $stmtUpdate->execute();

            if ($stmtUpdate->affected_rows > 0) {
                // echo 'El mensaje ha sido enviado y el contador actualizado.';
                 
            } else {
                // echo 'El mensaje ha sido enviado pero no se pudo actualizar el contador.';
                // $numCorreos++;
            }

            $stmtUpdate->close();
        } else {
            // echo 'Factura no encontrada.';
        }
    } else {
        // echo 'ID de factura inválido.';
    }

    // Cerrar conexión
    $conn->close();
 

} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Error de correo: {$mail->ErrorInfo}";
}

 echo "✅ Correos enviados.";
function enviarAlertaWhat($numguia, $telefono, $tipo, $text2) {
    $url = "https://www.transmillas.com/ChatbotTransmillas/alertas.php";

    $data = array(
        "numero_guia" => $numguia,
        "telefono"    => $telefono,
        "tipo_alerta" => $tipo,
        "texto2"      => $text2
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
        curl_close($curl);
        return [
            "status" => false,
            "mensaje" => "❌ Error en la solicitud: $error"
        ];
    }

    curl_close($curl);

    // Decodificar la respuesta
    $response_data = json_decode($response, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        // Si existe un error en la respuesta de la API
        if (isset($response_data["error"])) {
            return [
                "status" => false,
                "mensaje" => "❌ Error API: " . $response_data["error"] . 
                             " (Código: " . ($response_data["status_code"] ?? "N/A") . ")"
            ];
        }

        // Si tiene un "success" u otro campo que confirme envío
        if (isset($response_data["success"]) && $response_data["success"]) {
            return [
                "status" => true,
                "mensaje" => "✅ WhatsApp enviado correctamente"
            ];
        }
    }

    // Si la API no responde JSON válido
    return [
        "status" => false,
        "mensaje" => "⚠️ Respuesta inesperada de la API: $response"
    ];
}



?>