<?php
require "login_autentica.php";
include "declara.php";

header("Content-Type: application/json");

// Validar parámetros GET
if (!isset($_GET['id']) || !isset($_GET['nombre'])) {
    echo json_encode(["error" => "Faltan parámetros id o nombre"]);
    exit;
}

$idhojadevida = intval($_GET['id']);
$nombre = $_GET['nombre'];
$nombre_escapado = str_replace("'", "''", trim($nombre));

$sql = "SELECT iddoccliente, docl_nombre, docl_fecha_creacion, docl_fecha_venc, docl_documento 
        FROM doc_hoja_clientes 
        WHERE docl_idhvc = $idhojadevida 
        AND TRIM(LOWER(docl_nombre)) = LOWER('$nombre_escapado')"
        ;

$DB->Execute($sql);
$resultado = $DB->Consulta_ID;


$documentos = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $documentos[] = [
        "iddoccliente" => $fila["iddoccliente"],
        "docl_nombre" => $fila["docl_nombre"],
        "docl_fecha_creacion" => $fila["docl_fecha_creacion"],
        "docl_fecha_venc" => $fila["docl_fecha_venc"],
        "docl_documento" => $fila["docl_documento"]
    ];
}

// Si no hay resultados, puedes devolver un mensaje vacío también
echo json_encode($documentos);
