<?php

function obtenerCorreos($limite = 20) {
    $correo = 'facturaciontransmillas@gmail.com';
    $password = 'qxlh uxsh ilgp xojp';

    $inbox = imap_open(
        '{imap.gmail.com:993/imap/ssl}INBOX',
        $correo,
        $password
    );

    if (!$inbox) {
        return [
            'ok' => false,
            'error' => imap_last_error(),
            'data' => []
        ];
    }

    $emails = imap_search($inbox, 'ALL');

    if (!$emails) {
        imap_close($inbox);
        return ['ok' => true, 'data' => []];
    }

    rsort($emails); // más recientes primero
    $emails = array_slice($emails, 0, $limite);

    $data = [];

    foreach ($emails as $num) {
        $overview = imap_fetch_overview($inbox, $num, 0)[0];
        $body = imap_fetchbody($inbox, $num, 1);

        $fromRaw = $overview->from ?? '';
        $fromEmail = '';

        // Extraer email si viene como "Nombre <correo>"
        if (preg_match('/<(.+)>/', $fromRaw, $m)) {
            $fromEmail = trim($m[1]);
        }

        $data[] = [
            'from'        => htmlspecialchars($fromRaw, ENT_QUOTES, 'UTF-8'), // SOLO PARA MOSTRAR
            'from_email'  => $fromEmail,                                       // SOLO PARA DATA
            'subject'     => htmlspecialchars($overview->subject ?? '(Sin asunto)', ENT_QUOTES, 'UTF-8'),
            'body'        => strip_tags($body),
            'attachments' => '—',
            'id'          => $num
        ];
    }


    imap_close($inbox);

    return [
        'ok' => true,
        'data' => $data
    ];
}
