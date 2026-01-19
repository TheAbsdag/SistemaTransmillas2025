<?php
require_once "../config/database.php";

class CorreosEnviadosModel {

    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function obtenerCorreos($limite = 50) {

        $sql = "
            SELECT
                id,
                correo_destino,
                asunto,
                nombre_archivo,
                estado,
                fecha_envio
            FROM correos_enviados
            ORDER BY fecha_envio DESC
            LIMIT " . intval($limite);

        $res = $this->db->query($sql);

        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerCorreoPorId($id) {
        $id = intval($id);
        $sql = "SELECT * FROM correos_enviados WHERE id = $id LIMIT 1";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_assoc() : null;
    }

    public function registrarEnvio($data) {

        // Si NO agregaste reenvio_de_id, borra esa columna del SQL y del bind_param
        $sql = "INSERT INTO correos_enviados (
                    credito_id, reenvio_de_id, correo_destino, asunto,
                    cuerpo_html, cuerpo_texto,
                    archivo_adjunto, nombre_archivo,
                    enviado_desde, estado, mensaje_error,
                    ip_envio, user_agent
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $credito_id     = $data['credito_id'] ?? null;
        $reenvio_de_id  = $data['reenvio_de_id'] ?? null;
        $correo_destino = $data['correo_destino'] ?? '';
        $asunto         = $data['asunto'] ?? '';
        $cuerpo_html    = $data['cuerpo_html'] ?? null;
        $cuerpo_texto   = $data['cuerpo_texto'] ?? null;
        $archivo        = $data['archivo_adjunto'] ?? null;
        $nombre_archivo = $data['nombre_archivo'] ?? null;
        $enviado_desde  = $data['enviado_desde'] ?? '';
        $estado         = $data['estado'] ?? 'enviado';
        $mensaje_error  = $data['mensaje_error'] ?? null;
        $ip_envio       = $_SERVER['REMOTE_ADDR'] ?? null;
        $user_agent     = $_SERVER['HTTP_USER_AGENT'] ?? null;

        $stmt->bind_param(
            "iiisssssssiss",
            $credito_id,
            $reenvio_de_id,
            $correo_destino,
            $asunto,
            $cuerpo_html,
            $cuerpo_texto,
            $archivo,
            $nombre_archivo,
            $enviado_desde,
            $estado,
            $mensaje_error,
            $ip_envio,
            $user_agent
        );

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
    public function reenviarCorreo($id)
{
    require_once "../helpers/PHPMailer/Exception.php";
    require_once "../helpers/PHPMailer/PHPMailer.php";
    require_once "../helpers/PHPMailer/SMTP.php";

    $id = intval($id);
    $original = $this->obtenerCorreoPorId($id);

    if (!$original) {
        return ['ok' => false, 'msg' => 'Correo no encontrado'];
    }

    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'facturacion@transmillas.com';
        $mail->Password   = 'Transmillas2026@';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->CharSet = 'UTF-8';
        $mail->setFrom(
            $original['enviado_desde'],
            'Transmillas Logística'
        );
        $mail->addReplyTo(
            $original['enviado_desde'],
            'Transmillas Logística'
        );

        $mail->addAddress($original['correo_destino']);
        $mail->Subject = $original['asunto'];
        $mail->isHTML(true);

        $mail->Body    = $original['cuerpo_html'];
        $mail->AltBody = $original['cuerpo_texto'] 
            ?? strip_tags($original['cuerpo_html']);

        if (!empty($original['archivo_adjunto']) &&
            file_exists($original['archivo_adjunto'])) {

            $mail->addAttachment(
                $original['archivo_adjunto'],
                $original['nombre_archivo']
            );
        }

        // 🚀 Enviar
        $mail->send();

        // 📦 Registrar nuevo envío
        $this->registrarEnvio([
            'credito_id'      => $original['credito_id'],
            'correo_destino'  => $original['correo_destino'],
            'asunto'          => $original['asunto'],
            'cuerpo_html'     => $original['cuerpo_html'],
            'cuerpo_texto'    => $original['cuerpo_texto'],
            'archivo_adjunto' => $original['archivo_adjunto'],
            'nombre_archivo'  => $original['nombre_archivo'],
            'enviado_desde'   => $original['enviado_desde'],
            'estado'          => 'enviado',
            'mensaje_error'   => null
        ]);

        return ['ok' => true, 'msg' => 'Correo reenviado correctamente'];

    } catch (Exception $e) {

        // Registrar error
        $this->registrarEnvio([
            'credito_id'      => $original['credito_id'],
            'correo_destino'  => $original['correo_destino'],
            'asunto'          => $original['asunto'],
            'cuerpo_html'     => $original['cuerpo_html'],
            'cuerpo_texto'    => $original['cuerpo_texto'],
            'archivo_adjunto' => $original['archivo_adjunto'],
            'nombre_archivo'  => $original['nombre_archivo'],
            'enviado_desde'   => $original['enviado_desde'],
            'estado'          => 'error',
            'mensaje_error'   => $e->getMessage()
        ]);

        return ['ok' => false, 'msg' => $e->getMessage()];
    }
}
public function sincronizarCorreosRecibidos($limite = 50)
{
    $inbox = imap_open(
        '{imap.hostinger.com:993/imap/ssl}INBOX',
        'facturacion@transmillas.com',
        'Transmillas2026@'
    );

    if (!$inbox) {
        return false;
    }

    // Traer correos (los más recientes)
    $emails = imap_search($inbox, 'ALL');

    if (!$emails) {
        imap_close($inbox);
        return true;
    }

    rsort($emails);
    $emails = array_slice($emails, 0, $limite);

    foreach ($emails as $num) {

        $uid = imap_uid($inbox, $num);

        // 🔐 Evitar duplicados
        $uidEsc = $this->db->real_escape_string($uid);
        $check = $this->db->query(
            "SELECT id FROM correos_recibidos WHERE message_uid = '$uidEsc' LIMIT 1"
        );

        if ($check && $check->num_rows > 0) {
            continue;
        }

        $header = imap_headerinfo($inbox, $num);

        $from = '';
        if (!empty($header->from[0])) {
            $from = $header->from[0]->mailbox . '@' . $header->from[0]->host;
        }

        $subject = isset($header->subject)
            ? imap_utf8($header->subject)
            : '(Sin asunto)';

        $fecha = date('Y-m-d H:i:s', strtotime($header->date));

        // 📄 CUERPO
        $bodyHtml = '';
        $bodyText = '';

        $structure = imap_fetchstructure($inbox, $num);

        if (!empty($structure->parts)) {
            foreach ($structure->parts as $i => $part) {
                if ($part->subtype === 'HTML') {
                    $bodyHtml = imap_fetchbody($inbox, $num, $i + 1);
                } elseif ($part->subtype === 'PLAIN') {
                    $bodyText = imap_fetchbody($inbox, $num, $i + 1);
                }
            }
        } else {
            $bodyText = imap_fetchbody($inbox, $num, 1);
        }

        if ($structure->encoding == 3) {
            $bodyHtml = base64_decode($bodyHtml);
            $bodyText = base64_decode($bodyText);
        } elseif ($structure->encoding == 4) {
            $bodyHtml = quoted_printable_decode($bodyHtml);
            $bodyText = quoted_printable_decode($bodyText);
        }

        $tieneAdjuntos = !empty($structure->parts);

        // 💾 GUARDAR EN BD
        $stmt = $this->db->prepare("
            INSERT INTO correos_recibidos (
                message_uid,
                correo_origen,
                asunto,
                cuerpo_html,
                cuerpo_texto,
                fecha_correo,
                tiene_adjuntos
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        if ($stmt) {
            $stmt->bind_param(
                "ssssssi",
                $uid,
                $from,
                $subject,
                $bodyHtml,
                $bodyText,
                $fecha,
                $tieneAdjuntos
            );
            $stmt->execute();
            $stmt->close();
        }
    }

    imap_close($inbox);
    return true;
}

public function obtenerCorreosRecibidos($limite = 100)
{
    $sql = "
        SELECT
            id,
            correo_origen AS correo_destino,
            asunto,
            tiene_adjuntos,
            leido,
            fecha_correo AS fecha_envio
        FROM correos_recibidos
        ORDER BY fecha_correo DESC
        LIMIT " . intval($limite);

    $res = $this->db->query($sql);

    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

}
