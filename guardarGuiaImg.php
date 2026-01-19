<?php
require("login_autentica.php"); 
include("declara.php");

$id_nombre=$_SESSION['usuario_nombre'];
$id_usuario=$_SESSION['usuario_id'];
$nivel_acceso=$_SESSION['usuario_rol'];
$id_sedes=$_SESSION['usu_idsede'];
$idServicio=$_POST['id_param'];
$imprimir=$_POST['imprimir'];
$idguia=$_POST['idguia'];
// Obtener los datos de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// if (isset($data['image']) && isset($data['filename'])) {
    // Decodificar la imagen en base64
    // $imageData = $data['image'];
    // $filename = $data['filename'];
    // $tipo = $data['tipo'];
    // $idServicio = $data['idServicio'];
    // $factura = $data['factura'];
$sql = "SELECT `idclientes`,`ser_telefonocontacto`,`cli_telefono`,ser_peso,ser_volumen,ser_valorseguro,ser_valor FROM serviciosdia where idservicios=$id_param ";
$DB->Execute($sql);
$rw1 = mysqli_fetch_array($DB->Consulta_ID);


$sql1 = "SELECT firma,`nombre`, `numero_documento`,`correo_electronico`, `telefono`,tipo FROM firma_clientes WHERE tipo_firma = '$imprimir' and id_guia='$idServicio' order by id desc limit 1";
$resultado = $DB1->Execute($sql1);
$fila = mysqli_fetch_assoc($resultado);
$imagen = $fila['firma'];
$tipo = $fila['tipo'];
$telefono1 = $fila['telefono'];





// $telefonos = [$telefono1, $rw[1], $rw[2],];
// $datosjson = json_encode($telefonos);

    if ($imprimir !="" or $idServicio!="" ) {
        # code...
        // $guiaruta="https://historico.transmillas.com/ticketfacturacorreoimprimir.php?imprimir=$imprimir&id_param=$idServicio";
        // $guiarutauser="https://historico.transmillas.com/ticketfacturacorreoimprimir.php?imprimir=$imprimir&id_param=$idServicio&peso=$rw1[3]&volumen=$rw1[4]&seguro=$rw1[5]&valorf=$rw1[6]";
        $guiaruta="https://sistema.transmillas.com/ticketfacturacorreoimprimir.php?imprimir=$imprimir&id_param=$idServicio";
        $guiarutauser="https://sistema.transmillas.com/ticketfacturacorreoimprimir.php?imprimir=$imprimir&id_param=$idServicio&peso=$rw1[3]&volumen=$rw1[4]&seguro=$rw1[5]&valorf=$rw1[6]";


            $sql1="Select * from imagenguias where ima_idservicio=$idServicio and ima_tipo like '%$imprimir%'";
            $DB1->Execute($sql1);
            $rw = mysqli_fetch_array($DB1->Consulta_ID);
            if ($rw[0]!="") {
                // echo"Si existe";
                $sql_ins="UPDATE `imagenguias` SET  `ima_ruta`='$guiaruta', ima_dir='$guiarutauser',ima_nombre='$idguia' where ima_idservicio=$idServicio and ima_tipo='$imprimir' ";
                $DB1->Execute($sql_ins);
            }else {
                // echo"No existe";
            $sql_ins="INSERT INTO `imagenguias`(`ima_nombre`, `ima_ruta`, `ima_tipo`, `ima_fecha`, `ima_idservicio`,ima_dir) Values
            ('$idguia', '$guiaruta', '$imprimir','".date("Y-m-d")."','$idServicio','$guiarutauser')";
            $DB->Execute($sql_ins);
            }

            

            // Guardar la imagen en el servidor
            // if (file_put_contents($filePath, $imageData)) {
            //     echo json_encode(['success' => true, 'message' => 'Imagen guardada correctamente.']);
            // } else {
            //     echo json_encode(['success' => false, 'message' => 'Error al guardar la imagen.']);
            // }

            $telefono2 = $rw1[1];
            $telefono_limpio2 = preg_replace('/\D/', '', $telefono2); // Elimina todo excepto números

            $telefono3 = $rw1[2];
            $telefono_limpio3 = preg_replace('/\D/', '', $telefono3); // Elimina todo excepto números
            
            if ($telefono_limpio3==$fila['telefono']) {
                $telefono_limpio3="";
            }
            if ($telefono_limpio2==$fila['telefono']) {
                $telefono_limpio2="";
            }

            $array_datos_Mensaje = array(
                'num1' =>trim($fila['telefono']),
                'num2' => trim($telefono_limpio2),
                'num3' => trim($telefono_limpio3),
                'guiaruta' => trim($guiarutauser)
            
            );
            $respuesta_json = json_encode($array_datos_Mensaje);
            // echo $respuesta_json;

            if ($respuesta_json === false) {
                echo 'Error al codificar JSON: ' . json_last_error_msg();
            } else {
                echo $respuesta_json;
            }
    }else {
        echo json_encode(['success' => false, 'message' => 'No se guardo la imagen falta tipo de guia o el id de la guia.']);
    }
// } else {
//     echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
// }

?>
