<?php
require("connection/conectarse.php");
require("connection/funciones.php");
require("connection/arrays.php");
require("connection/funciones_clases.php");
require("connection/sql_transact.php");
include('definirvar.php');


// Verificar si se reciben los datos esperados

if (isset($_POST['idfirma'])) {

    if(isset($_FILES['imagen'], $_POST['idfirma'], $_POST['idguia'])) {

        // Ruta y nombre de archivo destino
        $idfirma = $_POST['idfirma'];
        $idguia = $_POST['idguia'];
        $carpeta_destino = 'tmp_img/';
        $nombre_archivo_original = $_FILES['imagen']['name'];
    
        $extension = pathinfo($nombre_archivo_original, PATHINFO_EXTENSION);
        $nombre_archivo_nuevo = 'imagen_'.$idguia.'.png'; // Nuevo nombre de archivo único
        $ruta_archivo = $carpeta_destino . $nombre_archivo_nuevo;
          // Mover la imagen al directorio
          if(move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_archivo)) {
            
            // Reducir la calidad de la imagen al 60%
            compressImage($ruta_archivo);
    
            // Convertir la imagen a BLOB
            $imagen_contenido = addslashes(file_get_contents($ruta_archivo));
    
            
    
            // Conectar a la base de datos
            $DB = new DB_mssql;
            $DB->conectar();
    
            // Definir la fecha actual
            $fechatiempo = date('Y-m-d H:i:s');
    
            // Actualizar la tabla con la firma
                $sql = "UPDATE firma_clientes SET fecha_registro='$fechatiempo', firma='$imagen_contenido', estado='pendiente',tipo='imagen' WHERE id='$idfirma'";
    
            // Ejecutar la consulta SQL y manejar los errores
            if ($DB->Execute($sql)) {
    
                
                echo "Gracias";
    
            } else {
                echo "Error al guardar la firma";
            }
        }
    } else {
       
        echo "Faltan datos necesarios";
    }
    
}else {

   
    if(isset($_FILES['imagen'], $_POST['idguia'],$_POST['nombre'],$_POST['telefono'])) {

        // Ruta y nombre de archivo destino
        $telefono= $_POST['telefono'];
        $nombre= $_POST['nombre'];
        $idfirma = $_POST['idfirma'];
        $idguia = $_POST['idguia'];
        $id_servicio = $_POST['id_param'];
        $tipo = $_POST['tipo'];
        
        $carpeta_destino = 'tmp_img/';
        $nombre_archivo_original = $_FILES['imagen']['name'];
    
        $extension = pathinfo($nombre_archivo_original, PATHINFO_EXTENSION);
        $nombre_archivo_nuevo = 'imagen_'.$idguia.'.png'; // Nuevo nombre de archivo único
        $ruta_archivo = $carpeta_destino . $nombre_archivo_nuevo;
          // Mover la imagen al directorio
          if(move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_archivo)) {
            
            // Reducir la calidad de la imagen al 60%
            compressImage($ruta_archivo);
    
            // Convertir la imagen a BLOB
            $imagen_contenido = addslashes(file_get_contents($ruta_archivo));
    
            
    
            // Conectar a la base de datos
            $DB = new DB_mssql;
            $DB->conectar();
    
            // Definir la fecha actual
            $fechatiempo = date('Y-m-d H:i:s');
    
            // Actualizar la tabla con la firma
                $sql = "INSERT INTO firma_clientes (id_guia,firma, tipo_firma, nombre, numero_documento,correo_electronico, telefono,enviar_whatsapp,foto,fecha_registro,estado,tipo) 
	                    VALUES ('$id_servicio','$imagen_contenido', '$tipo', '$nombre', '', '', '$telefono', '','','$fechatiempo','pendiente','imagen')";
    
            // Ejecutar la consulta SQL y manejar los errores
            if ($DB->Execute($sql)) {
    
                
                echo "Gracias";
    
            } else {
                echo "Error al guardar la firma";
            }
        }
    } else {
       
        echo "Faltan datos necesarios";
    }





}


// Función para reducir la calidad de la imagen al 60%
function compressImage($ruta_archivo) {
    $calidad = 60; // Calidad de compresión de la imagen (0-100)

    // Cargar la imagen
    $imagen = imagecreatefromjpeg($ruta_archivo);

    // Guardar la imagen con la calidad especificada
    imagejpeg($imagen, $ruta_archivo, $calidad);

    // Liberar memoria
    imagedestroy($imagen);
}
?>
