<?php





// Incluir los archivos de PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Crear una nueva instancia de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    // $mail->isSMTP();
    // $mail->Host = 'smtp.gmail.com';
    // $mail->SMTPAuth = true;
    // $mail->Username = 'jose523a@gmail.com'; // Reemplaza con tu correo de Gmail
    // $mail->Password = 'joseNVD22'; // Reemplaza con tu contraseña de Gmail
    // $mail->SMTPSecure = 'tls';
    // $mail->Port = 587;
    //Clave ventas transmillas  gega vsfg okti mpum 
    //clave de pruebas jose R ngtz uiqb txff wbyy

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ventastransmillas@gmail.com';
    $mail->Password   = 'tpwv clpk qqdo dbgx'; // Asegúrate de usar la contraseña de la aplicación si tienes 2FA habilitado
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $destinatario = $_POST['correo'];

    // Remitente y destinatarios
    $mail->setFrom('ventastransmillas@gmail.com', 'TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A.S.'); // Reemplaza con tu correo y nombre
    $mail->addAddress($destinatario, ''); // Reemplaza con el correo del destinatario
   


    
    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Documentos Transmillas';
    $mail->Body    = 'Buen día adjuntamos adjunto a este correo se encuentran los documentos requeridos de transmillas.';
    $mail->AltBody = 'Este es el cuerpo del correo en texto plano para clientes de correo que no soporten HTML';


    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $uploadFile = $_FILES['file']['tmp_name'];
        $uploadFileName = $_FILES['file']['name'];
        $mail->addAttachment($uploadFile, $uploadFileName);
    }
                // Adjuntar otros archivos desde el servidor si están disponibles
                if (isset($_POST['file_names'])) {
                    $fileNames = json_decode($_POST['file_names'], true);
                    foreach ($fileNames as $fileName) {
                        $fileName = trim($fileName);
                        $filePath = 'imgDocTransmi/' . $fileName; // Asegúrate de que esta ruta es correcta
                        if (file_exists($filePath)) {
                            $mail->addAttachment($filePath, $fileName);
                        }
                    }
                }
    

    // Enviar el correo
    $mail->send();
    echo 'El mensaje ha sido enviado';
} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Error de correo: {$mail->ErrorInfo}";
}




// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $mail = new PHPMailer(true);

//     try {
//         // Configuración del servidor SMTP
//         $mail->isSMTP();
//         $mail->Host       = 'smtp.gmail.com'; // Tu servidor SMTP
//         $mail->SMTPAuth   = true;
//         $mail->Username   = 'tu_correo@gmail.com'; // Tu usuario SMTP
//         $mail->Password   = 'tu_contraseña_de_aplicación'; // Tu contraseña de aplicación
//         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//         $mail->Port       = 587;

//         // Habilitar la depuración
//         $mail->SMTPDebug = 0; // Cambia a 2 para habilitar la depuración completa
//         $mail->Debugoutput = 'html'; // Opcional: formato de salida de depuración

//         // Remitente y destinatario
//         $mail->setFrom('tu_correo@gmail.com', 'Tu Nombre');
//         $mail->addAddress('destinatario@example.com', 'Destinatario Nombre');

//         // Adjuntar el archivo subido
//         if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
//             $uploadFile = $_FILES['file']['tmp_name'];
//             $uploadFileName = $_FILES['file']['name'];
//             $mail->addAttachment($uploadFile, $uploadFileName);
//         }

//         // Adjuntar otros archivos desde el servidor si están disponibles
//         if (isset($_POST['file_names'])) {
//             $fileNames = json_decode($_POST['file_names'], true);
//             foreach ($fileNames as $fileName) {
//                 $fileName = trim($fileName);
//                 $filePath = '/ruta/a/los/archivos/' . $fileName; // Asegúrate de que esta ruta es correcta
//                 if (file_exists($filePath)) {
//                     $mail->addAttachment($filePath, $fileName);
//                 }
//             }
//         }

//         // Obtener el valor del input de texto
//         $textInput = $_POST['text_input'];

//         // Contenido del correo
//         $mail->isHTML(true);
//         $mail->Subject = 'Aquí están los archivos que solicitaste';
//         $mail->Body    = 'Este correo contiene los archivos adjuntos que solicitaste.<br>Texto adicional: ' . htmlspecialchars($textInput);

//         // Enviar el correo
//         $mail->send();
//         echo 'El correo ha sido enviado con éxito.';
//     } catch (Exception $e) {
//         echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
//     }
// }
?>