<?php

// require_once 'PHPExcel/Classes/PHPExcel.php';

// // Verificar si se envió el formulario
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Verificar si se seleccionó un archivo
//     if (isset($_FILES["excelFile"])) {
//         $archivo = $_FILES["excelFile"];

//         // Verificar si no hubo errores al cargar el archivo
//         if ($archivo["error"] === UPLOAD_ERR_OK) {
//             $nombre_temporal = $archivo["tmp_name"];
//             $nombre_original = $archivo["name"];

//             // Cargar archivo Excel
//             $inputFileType = PHPExcel_IOFactory::identify($nombre_temporal);
//             $objReader = PHPExcel_IOFactory::createReader($inputFileType);
//             $objPHPExcel = $objReader->load($nombre_temporal);
//             $sheet = $objPHPExcel->getSheet(0);
//             $highestRow = $sheet->getHighestRow();


//             // Obtener el número de filas y columnas
//             $highestColumn = $sheet->getHighestColumn();
//             $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);


//             // Iterar sobre cada columna
//             for ($col = 0; $col <= $highestColumnIndex; $col++) {
//                 $columnaLetra = PHPExcel_Cell::stringFromColumnIndex($col);
            
//                 // Verificar si el primer dato de la columna es vacío
//                 $primerCelda = $sheet->getCell($columnaLetra . '1'); // Considerando que los datos comienzan en la fila 1
//                 $valorCelda = $primerCelda->getValue();

//                 if (empty($valorCelda)) {
//                     // Eliminar la columna vacía
//                     $sheet->removeColumn($columnaLetra);
//                     $highestColumnIndex--; // Actualizar el índice de la columna más alta después de la eliminación
//                     $col--; // Ajustar el índice del bucle
//                 }
//             }

//             // Preparar la conexión a la base de datos
//             require("login_autentica.php");
//             $DB = new DB_mssql;
//             $DB->conectar();

//             // Verificar si el archivo ya existe en la tabla
//             $sql_verificar = "SELECT COUNT(*) AS count FROM `transdavivienda` WHERE `archivo` = '$nombre_original'";
//             $resultado_verificacion = $DB->Execute($sql_verificar);
//             $fila_verificacion = $DB->recogedato(0);
//             $archivo_existente = $fila_verificacion > 0;

//             if (!$archivo_existente) {
//                 // El archivo no existe en la tabla, proceder con la inserción
         
//                 if ($highestColumnIndex > 8) {
//                         // Iterar sobre las filas
//                         for ($row = 2; $row <= $highestRow; $row++) {
//                             // Obtener datos de la fila
//                             $data = $sheet->rangeToArray('A' . $row . ':' . $sheet->getHighestColumn() . $row, null, true, false)[0];

//                             // Validar si la fila está vacía
//                             if (!empty(array_filter($data))) {

//                                     // Formatear la fecha
//                                     $fechaFormateada = DateTime::createFromFormat('d/m/Y', $data[0])->format('Y-m-d');
//                                     // Obtener el valor y quitar espacios y el signo $
//                                     $valor = str_replace([' ', '$'], '', $data[7]);
//                                     // Asignar valores a los parámetros de la consulta
//                                      $sql = "INSERT INTO transdavivienda (Fecha_Sistema, Documento, Descripcion_Motivo, Transaccion, Oficina_Recaudo, Nit_Originador, Valor_Cheque, Valor_Total, Referencia1, Referencia2, archivo, factura) VALUES ('$fechaFormateada', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$valor ', '$data[8]', '$data[9]', '$nombre_original', 'Sin facturar')";
//                                     // Ejecutar la consulta
//                                      $DB->Execute($sql);
                                

//                                 }
//                             }

//             } else {

//                 for ($row = 2; $row <= $highestRow; $row++) {
//                     // Obtener datos de la fila
//                     $data = $sheet->rangeToArray('A' . $row . ':' . $sheet->getHighestColumn() . $row, null, true, false)[0];

//                     // Validar si la fila está vacía
//                     if (!empty(array_filter($data))) {

//                           $fechaString =$data[0];
//                           $timestamp = strtotime($fechaString);
//                           $fechaFormateada = date('Y-m-d', $timestamp);
//                         // Obtener el valor y quitar espacios y el signo $
//                           $valor = str_replace([' ', '$','+','-'], '', $data[7]);

//                         // Realizar el primer insert si el número de columnas es igual o menor a 7 para cargar el otro formato 
//                            $sql = "INSERT INTO transdavivienda (Fecha_Sistema, Documento, Descripcion_Motivo,Transaccion,  Oficina_Recaudo, Nit_Originador, Valor_Cheque,  Valor_Total, archivo, factura) VALUES ('$fechaFormateada', '$data[1]', '$data[2]', '$data[3]','$data[4]', '$data[5]','$data[6]', '$valor', '$nombre_original', 'Sin facturar')";
//                             // Ejecutar la consulta
//                            $DB->Execute($sql); 
                        

//                         }
//                     }
           
//             }

//                 echo "Archivo Excel cargado y datos insertados en la base de datos exitosamente.";

//             } else {
//                 echo "Error: El archivo con nombre '$nombre_original' ya fue cargado previamente. Por favor validar.";
//             }

//             // Cerrar la conexión a la base de datos
//             $DB->cerrarconsulta();
//         } else {
//             echo "Error: No se seleccionó un archivo o hubo un problema al cargarlo.";
//         }
//     }
// }
?>



<?php

require_once 'PHPExcel/Classes/PHPExcel.php';

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se seleccionó un archivo
    if (isset($_FILES["excelFile"])) {
        $archivo = $_FILES["excelFile"];

        // Verificar si no hubo errores al cargar el archivo
        if ($archivo["error"] === UPLOAD_ERR_OK) {
            $nombre_temporal = $archivo["tmp_name"];
            $nombre_original = $archivo["name"];

            // Preparar la conexión a la base de datos
            require("login_autentica.php");
            $DB = new DB_mssql;
            $DB->conectar();

            // Verificar si el archivo ya existe en la tabla
            $sql_verificar = "SELECT COUNT(*) AS count FROM `transdavivienda` WHERE `archivo` = '$nombre_original'";
            $resultado_verificacion = $DB->Execute($sql_verificar);
            $fila_verificacion = $DB->recogedato(0);
            $archivo_existente = $fila_verificacion > 0;

            if (!$archivo_existente) {
                // El archivo no existe en la tabla, proceder con la inserción

                // Cargar archivo Excel
                $inputFileType = PHPExcel_IOFactory::identify($nombre_temporal);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($nombre_temporal);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();

                // Iterar sobre las filas
                for ($row = 2; $row <= $highestRow; $row++) {
                    $data = $sheet->rangeToArray('A' . $row . ':' . $sheet->getHighestColumn() . $row, null, true, false)[0];

                    // Convertir la fecha si es un valor numérico (formato Excel)
                    $fechaExcel = $data[0];
                    $fechaConvertida = null;

                                        // Validar si es un número de Excel válido
                    if (is_numeric($fechaExcel) && $fechaExcel > 0) {
                        // Convertir número de Excel a timestamp
                        $timestamp = PHPExcel_Shared_Date::ExcelToPHP($fechaExcel);

                        // Crear DateTime desde timestamp y zona horaria
                        $fechaDateTime = new DateTime('@' . $timestamp); // evita el warning del plugin
                        $fechaDateTime->setTimezone(new DateTimeZone('America/Bogota'));

                        // (Opcional) Corregir desfase si persiste
                        $fechaDateTime->modify('+1 day');

                        $fechaConvertida = $fechaDateTime->format('Y-m-d');
                    } elseif (!empty($fechaExcel)) {
                        $fechaConvertida = date('Y-m-d', strtotime(str_replace('/', '-', $fechaExcel)));

                        if ($fechaConvertida === '1970-01-01') {
                            echo "❌ Error: La fecha en la fila $row no tiene un formato válido: '$fechaExcel'<br>";
                            exit;
                        }
                    } else {
                        echo "❌ Error: La fecha en la fila $row está vacía<br>";
                        exit;
                    }
                    // Ahora usa $fechaConvertida en el INSERT
                    $sql = "INSERT INTO transdavivienda (
                        Fecha_Sistema, Documento, Descripcion_Motivo, Transaccion, Oficina_Recaudo, Nit_Originador, Valor_Cheque, Valor_Total, archivo, factura
                    ) VALUES (
                        '$fechaConvertida', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]', '$nombre_original', 'Sin facturar'
                    )";
                    // Ejecutar la consulta
                    $DB->Execute($sql);
                }

                echo "Archivo Excel cargado y datos insertados en la base de datos exitosamente.";
            } else {
                echo "Error: El archivo con nombre '$nombre_original' ya fue cargado previamente. por favor validar";
            }

            // Cerrar la conexión a la base de datos
            $DB->cerrarconsulta();
        } else {
            echo "Error: No se seleccionó un archivo o hubo un problema al cargarlo.";
        }
    }
}
?>
