<?php

require("login_autentica.php"); 
include("declara.php");

header("Content-Type: application/json");

$id = $_GET['id']; 

$stmt = $DB->Execute("SELECT docl_nombre FROM doc_hoja_clientes WHERE iddoccliente = ?");
$stmt->bind_param("i", $id);
$stmt->Execute();

$stmt->bind_result($nombre);
$stmt->fetch();

$stmt->close();

if ($nombre == '') {
    echo json_encode([]);
    exit;
}

$stmt = $DB->Execute("SELECT iddoccliente, docl_nombre, docl_fecha_creacion, docl_fecha_venc, docl_documento FROM doc_hoja_clientes WHERE docl_nombre = ?");
$stmt->bind_param("s", $nombre);
$stmt->Execute();

$res = $stmt->get_result();

$documentos = [];

while ($row = $res->fetch_assoc()) {
    $documentos[] = $row;
}

$stmt->close();

 echo json_encode($documentos);
?>

