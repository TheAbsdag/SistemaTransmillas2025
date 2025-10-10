<?php





// Incluir los archivos de PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Crear una nueva instancia de PHPMailer
$mail = new PHPMailer(true);
// $mail->SMTPDebug = 2; // O usa 3 o 4 si quieres aún más detalle
// $mail->Debugoutput = 'html';

try {


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
   

    $contenido=$_POST['body'];
    $idFactura=$_POST['idfac'];
    $asunto=$_POST['asunto'];


    // Adjuntar imágenes embebidas
    $mail->AddEmbeddedImage('images/logoCorreo.jpg', 'empresa_logo');
    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = ''.$asunto.'';

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


    // Enviar el correo
    $mail->send();
    echo 'El mensaje ha sido enviado';



    if (isset($_POST['cond'])) {

        
    } else {
   
        
        require("connection/conectarse.php");
        require("connection/funciones.php");
        require("connection/arrays.php");
        require("connection/funciones_clases.php");
        require("connection/sql_transact.php");
        require("connection/llenatablas.php");
        
        $DB = new DB_mssql;
        $DB->conectar();
        $DB1 = new DB_mssql;
        $DB1->conectar();
        $FB = new funciones_varias;
        $QL = new sql_transact;
        $LT = new llenatablas;
        $id_usuario = $_SESSION['usuario_id'];
    
        $sql2="SELECT fac_correoven FROM `facturascreditos`  WHERE idfacturascreditos='$idFactura'";
        $DB1->Execute($sql2); 
        $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
        $nummensajes=$rw1[0]+1;
        $sqlsqlupdate = "UPDATE `facturascreditos` SET fac_correoven='$nummensajes'  WHERE idfacturascreditos='$idFactura'";
        $DB->Execute($sqlsqlupdate);
    }
         $resultado.="IdFactura $idFactura Vencida  Se envia al correo $destinatario Alerta <br> \n";

        // Abre el archivo en modo "append" para agregar contenido al final del archivo
        $archivo = fopen("emailsEnviados.txt", "a");

        // Escribe el resultado en el archivo, seguido de un salto de línea
        fwrite($archivo, $resultado . "\n");
    
        // Cierra el archivo
        fclose($archivo);

} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Error de correo: {$mail->ErrorInfo}";
}





?>