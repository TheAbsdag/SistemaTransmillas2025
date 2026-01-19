<?php
// --- CONFIGURACIÓN DE CONEXIÓN ---
$host = "localhost";
$user = "u713516042_jose2";      // Cambia esto por tu usuario MySQL (por ejemplo: u713516042_jose2)
$pass = "Dobarli23@transmillas";   // Cambia esto por tu contraseña real
$db   = "u713516042_transmillas2";

    // $host = "localhost";
    // $user = "u713516042_jose2";
    // $pass = "Dobarli23@transmillas";
    // $dbname = "u713516042_transmillas2";




$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    die("❌ Error de conexión: " . $mysqli->connect_error);
}

$carpeta = __DIR__ . '/firmas_clientes/';
if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}

$sql = "SELECT id, numero_documento, id_guia, firma FROM firma_clientes WHERE firma IS NOT NULL "; // puedes quitar el LIMIT después
$result = $mysqli->query($sql);

if (!$result) {
    die("❌ Error al obtener datos: " . $mysqli->error);
}

$contador = 0;
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $documento = $row['numero_documento'] ?: 'sin_doc';
    $idGuia = $row['id_guia'] ?: '0';
    $firma = $row['firma'];

    // Convertir a string para detectar si es base64
    $firmaStr = trim($firma);

    // Detectar y limpiar formato base64
    if (strpos($firmaStr, 'base64,') !== false) {
        $firmaStr = substr($firmaStr, strpos($firmaStr, 'base64,') + 7);
    }

    // Si parece base64 (solo texto alfanumérico largo), decodificar
    if (preg_match('/^[A-Za-z0-9+\/=]+$/', $firmaStr)) {
        $firmaBinaria = base64_decode($firmaStr);
    } else {
        // Si no, asumimos que es binario puro
        $firmaBinaria = $firma;
    }

    // Nombre del archivo
    $nombreArchivo = $documento . '_guia' . $idGuia . '_firma' . $id . '.png';
    $rutaArchivo = $carpeta . $nombreArchivo;

    // Guardar archivo
    file_put_contents($rutaArchivo, $firmaBinaria);

    // Guardar ruta en la base de datos
    $rutaDB = 'firmas_clientes/' . $nombreArchivo;
    $update = $mysqli->prepare("UPDATE firma_clientes SET firma = NULL, firma_clientes = ? WHERE id = ?");
    $update->bind_param("si", $rutaDB, $id);
    $update->execute();

    $contador++;
}

echo "✅ Migración completada: {$contador} firmas procesadas correctamente.";
?>