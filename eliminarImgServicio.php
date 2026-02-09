<?php

$directorio = __DIR__ . '/imgServicios'; // Cambia si la ruta es diferente
// $directorio = __DIR__ . '/imgNoEntregas';
$fecha_limite = strtotime('2024-12-31'); // Fecha límite

// Abrimos el directorio
if (is_dir($directorio)) {
    $archivos = scandir($directorio);

    foreach ($archivos as $archivo) {
        // Ignorar . y ..
        if ($archivo === '.' || $archivo === '..') continue;

        $ruta = $directorio . '/' . $archivo;

        // Solo actuar sobre archivos
        if (is_file($ruta)) {
            $modificacion = filemtime($ruta); // Fecha de modificación

            // Comparamos con la fecha límite
            if ($modificacion < $fecha_limite) {
                if (unlink($ruta)) {
                    echo "Eliminado: $archivo\n";
                } else {
                    echo "Error al eliminar: $archivo\n";
                }
                
            }
        }
    }
} else {
    echo "El directorio no existe.";
}