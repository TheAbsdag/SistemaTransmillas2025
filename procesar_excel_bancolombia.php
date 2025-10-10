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
            $sql_verificar = "SELECT COUNT(*) AS count FROM `transbancolombia` WHERE `archivo` = '$nombre_original'";
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
                    // Convertir fecha desde Excel (columna A)
                    $fechaExcel = $data[0];
                    $fechaSQL = '0000-00-00'; // valor por defecto en caso de error

                    // Intentar convertir usando formato de fecha de Excel
                    if (is_numeric($fechaExcel)) {
                        $fechaPHP = PHPExcel_Shared_Date::ExcelToPHPObject($fechaExcel);
                        $fechaSQL = $fechaPHP->format('Y-m-d');
                    } elseif (is_string($fechaExcel)) {
                        $fechaPHP = DateTime::createFromFormat('d-M-y', $fechaExcel); // ej: 29-jul-25
                        if ($fechaPHP) {
                            $fechaSQL = $fechaPHP->format('Y-m-d');
                        }
                    }

                    $valorOriginal = $data[4];

                    // Eliminar "COP", "$", espacios, puntos de mil y cambiar coma decimal por punto
                    $valorLimpio = str_replace(['COP', '$', '.', ' '], '', $valorOriginal);
                    $valorLimpio = str_replace(',', '.', $valorLimpio); // para MySQL decimal
                    // Asignar valores a los parámetros de la consulta
                    $sql = "INSERT INTO `transbancolombia`(`Fecha`, `Descripcion`, `SucursalCanal`, `Referencia1`, `Valor`,`archivo`,`Factura`) VALUES ('$fechaSQL', '$data[2]', '$data[1]', '$data[3]', '$valorLimpio','$nombre_original','Sin facturar')";
                    
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