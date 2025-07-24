<?php 
require("login_autentica.php"); 
$id_ciudad= $_SESSION['usu_idsede'];
$id_usuario= $_SESSION['usuario_id'];
@$tipoguia=$_REQUEST["tipoguia"];
@$registros=$_REQUEST["registros"];
$id_nombre=$_SESSION['usuario_nombre'];
$DB = new DB_mssql;
$DB->conectar();
$DB1 = new DB_mssql;
$DB1->conectar();


$fechaHoraActual = date('Y-m-d H:i:s');

// datos = {"idUsuario":idusuario,"fechaini":fechaactual,"fechafin":fechafinal,"funcion":confirmarPago,"confirma":confirma};

// {"idUsuario":valor1,"abono":valorAbono,"fechaini":fechaini,"fechafin":fechafin};
// if($piezasg>1){
    $funcion=$_POST['funcion'];

if ($funcion == "confirmarPago"){

$idusuario=$_POST['idUsuario'];
$abono=$_POST['abono'];
$fechaini=$_POST['fechaini'];
$fechafin=$_POST['fechafin'];

$estado=$_POST['confirma'];
$tipo=$_POST['tipo'];


        // if ($tipo=="Basico") {
            $sql1="SELECT  count(*) from nomina where nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini'  ";		
            $DB1->Execute($sql1);
            $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
            if ($rw1[0]>0) {
                echo$sql="UPDATE `nomina` SET `nom_confirma`='$estado' WHERE nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' ";
                $DB1->Execute($sql);
                // and nom_tipo_pago='$tipo'
            }else{
                // $sql="INSERT INTO `nomina`(`nom_id_usu`,`nom_confirma`,`nom_fecha_inicio`, `nom_fecha_fin`, `nom_tipo_pago`) VALUES ('$idusuario','$estado','$fechaini','$fechafin','$tipo')";
                // $DB1->Execute($sql);
            }
        // }
        // else if ($tipo=="Otros") {
        //     $sql1="SELECT  count(*) from nomina where nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo' ";		
        //     $DB1->Execute($sql1);
        //     $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
        //     if ($rw1[0]>0) {
           

        //          $sql="UPDATE `nomina` SET `nom_confirma`='$estado' WHERE nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' ";
        //         $DB1->Execute($sql);
        //         // and nom_tipo_pago='$tipo'
        //     }else{
        //     //    $sql="INSERT INTO `nomina`(`nom_id_usu`,`nom_confirma`,`nom_fecha_inicio`, `nom_fecha_fin`, `nom_tipo_pago`) VALUES ('$idusuario','$estado','$fechaini','$fechafin','$tipo')";
        //     //     $DB1->Execute($sql);
        //     }
        // }


        if ($sql) {
            echo"ok";
        }

    }else if ($funcion == "guardarCuenCobro"){

        

        $idusuario=$_POST['idUsuario'];
        $abono=$_POST['abono'];
        $fechaini=$_POST['fechaini'];
        $fechafin=$_POST['fechafin'];
       
        $estado=$_POST['confirma'];
        $tipo=$_POST['tipo'];
        $desprendible=$_POST['desprendible'];
        

        if ($tipo=="Basico") {
            $sql1="SELECT  count(*) from nomina where nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo' ";		
            $DB1->Execute($sql1);
            $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
            if ($rw1[0]>0) {
                echo$sql="UPDATE `nomina` SET `nom_confirma`='$estado',`nom_cuentaCobro`='$desprendible',nom_confirmaUsu='no' WHERE nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo'";
                $DB1->Execute($sql);
           
            }else{
                $sql="INSERT INTO `nomina`(`nom_id_usu`,`nom_confirma`,`nom_fecha_inicio`, `nom_fecha_fin`, `nom_tipo_pago`,`nom_cuentaCobro`) VALUES ('$idusuario','$estado','$fechaini','$fechafin','$tipo','$desprendible')";
                $DB1->Execute($sql);
            }
        }else if ($tipo=="Otros") {
            $sql1="SELECT  count(*) from nomina where nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo' ";		
            $DB1->Execute($sql1);
            $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
            if ($rw1[0]>0) {
           

                echo$sql="UPDATE `nomina` SET `nom_confirma`='$estado',`nom_cuentaCobro`='$desprendible',nom_confirmaUsu='no' WHERE nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo'";
                $DB1->Execute($sql);
            }else{
                $sql="INSERT INTO `nomina`(`nom_id_usu`,`nom_confirma`,`nom_fecha_inicio`, `nom_fecha_fin`, `nom_tipo_pago`,`nom_cuentaCobro`) VALUES ('$idusuario','$estado','$fechaini','$fechafin','$tipo','$desprendible')";
                $DB1->Execute($sql);
            }
        }


        if ($sql) {
            echo"ok";
        }

    }else if ($funcion == "validarUsuario") {
       

        $idusuario=$_POST['idUsuario'];
        $abono=$_POST['abono'];
        $fechaini=$_POST['fechaini'];
        $fechafin=$_POST['fechafin'];
       
        $estado=$_POST['confirma'];
        $tipo=$_POST['tipo'];
        $desprendible=$_POST['desprendible'];
        $Observacion=$_POST['Observacion'];
        

        if ($tipo=="Basico") {
            $sql1="SELECT  count(*) from nomina where nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo' ";		
            $DB1->Execute($sql1);
            $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
            if ($rw1[0]>0) {
                $sql="UPDATE `nomina` SET `nom_confirmaUsu`='$estado', nom_motivoObser='$Observacion', nom_fechaconfirmaUsus='$fechaHoraActual' WHERE nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo'";
                $DB1->Execute($sql);
           
            }else{
                // $sql="INSERT INTO `nomina`(`nom_id_usu`,`nom_confirma`,`nom_fecha_inicio`, `nom_fecha_fin`, `nom_tipo_pago`,`nom_cuentaCobro`) VALUES ('$idusuario','$estado','$fechaini','$fechafin','$tipo','$desprendible')";
                // $DB1->Execute($sql);
            }
        }else if ($tipo=="Otros") {
            $sql1="SELECT  count(*) from nomina where nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo' ";		
            $DB1->Execute($sql1);
            $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
            if ($rw1[0]>0) {
           

                $sql="UPDATE `nomina` SET `nom_confirmaUsu`='$estado', nom_motivoObser='$Observacion', nom_fechaconfirmaUsus='$fechaHoraActual'  WHERE nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo'";
                $DB1->Execute($sql);
            }else{
                // $sql="INSERT INTO `nomina`(`nom_id_usu`,`nom_confirma`,`nom_fecha_inicio`, `nom_fecha_fin`, `nom_tipo_pago`,`nom_cuentaCobro`) VALUES ('$idusuario','$estado','$fechaini','$fechafin','$tipo','$desprendible')";
                // $DB1->Execute($sql);
            }
        }


        if ($sql) {
            echo"ok";
        }






    }elseif ($funcion == "confirmaAdmin") {
       

        $idusuario=$_POST['idUsuario'];
        $abono=$_POST['abono'];
        $fechaini=$_POST['fechaini'];
        $fechafin=$_POST['fechafin'];
        $confirma=$_POST['confirma'];
        
        $tipo=$_POST['tipo'];
        // $desprendible=$_POST['desprendible'];
        // $Observacion=$_POST['Observacion'];
        

        if ($tipo=="Basico") {
            $sql1="SELECT  count(*) from nomina where nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo' ";		
            $DB1->Execute($sql1);
            $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
            if ($rw1[0]>0) {
                echo$sql="UPDATE `nomina` SET  nom_confiAdmi='$id_usuario', nom_fechaConfiAdmi='$fechaHoraActual',nom_confirmaAdmin='$confirma',nom_fecha_fin='$fechafin' WHERE nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo'";
                $DB1->Execute($sql);
           
            }else{
                echo$sql="INSERT INTO `nomina`(`nom_id_usu`,`nom_fecha_inicio`, `nom_fecha_fin`, `nom_tipo_pago`,`nom_confiAdmi`,`nom_fechaConfiAdmi`,`nom_confirmaAdmin`) VALUES ('$idusuario','$fechaini','$fechafin','$tipo','$id_usuario','$fechaHoraActual','$confirma')";
                $DB1->Execute($sql);
            }
        }else if ($tipo=="Otros") {
            $sql1="SELECT  count(*) from nomina where nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo' ";		
            $DB1->Execute($sql1);
            $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
            if ($rw1[0]>0) {
           

                echo$sql="UPDATE `nomina` SET nom_confiAdmi='$id_usuario', nom_fechaConfiAdmi='$fechaHoraActual',nom_confirmaAdmin='$confirma',nom_fecha_fin='$fechafin'  WHERE nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo'";
                $DB1->Execute($sql);
            }else{
                echo$sql="INSERT INTO `nomina`(`nom_id_usu`,`nom_fecha_inicio`, `nom_fecha_fin`, `nom_tipo_pago`,`nom_confiAdmi`,`nom_fechaConfiAdmi`,`nom_confirmaAdmin`) VALUES ('$idusuario','$fechaini','$fechafin','$tipo','$id_usuario','$fechaHoraActual','$confirma')";
                $DB1->Execute($sql);
            }
        }


        if ($sql) {
            echo"ok";
        }






    }else if($funcion =="nomParafiscales"){

        echo"aqui".$fechaini=$_POST['fechaIni'];
        $fechafin=$_POST['fechafin'];
        $confirma=$_POST['confirma'];


        // Supongamos que tienes una fecha en formato de cadena
        // $fechaString = "2021-02-03 15:30:00";

        // Creamos un objeto DateTime a partir de la cadena de fecha
        $fecha1 = new DateTime($fechaini);

        // Obtenemos la fecha completa sin la parte del tiempo
        $fechaSinTiempo1 = $fecha1->format('Y-m-d');

        // Creamos un objeto DateTime a partir de la cadena de fecha
        $fecha2 = new DateTime($fechafin);

        // Obtenemos la fecha completa sin la parte del tiempo
        $fechaSinTiempo2 = $fecha2->format('Y-m-d');

        // Mostramos la fecha sin tiempo
        echo "Fecha completa sin tiempo: $fechaSinTiempo";

        $usuConfirma=$_POST['usuConfirma'];
    
            $sql1="SELECT `nome_id`, `nome_fechaIni`, `nome_fechaFin`, `nome_confirmaParafi`, `nome_fotoParafiscales` FROM `NominasEmpresa` WHERE nome_fechaIni='$fechaSinTiempo1' and nome_fechaFin='$fechaSinTiempo2' ";		
            $DB1->Execute($sql1);
            $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
            if ($rw1[0]>0) {
                echo$sql="UPDATE `NominasEmpresa` SET `nome_confirmaParafi`='$confirma',nome_quienConfirma='$id_usuario' WHERE  nome_fechaIni='$fechaSinTiempo1' and nome_fechaFin='$fechaSinTiempo2'";
                $DB1->Execute($sql);
           
            }else{
                echo$sql="INSERT INTO `NominasEmpresa`( `nome_fechaIni`, `nome_fechaFin`, `nome_confirmaParafi`,nome_quienConfirma) VALUES ('$fechaSinTiempo1','$fechaSinTiempo2','$confirma','$id_usuario')";
                $DB1->Execute($sql);
            }
    
    
    }else if($funcion =="guardarImagenPagoPara"){

        $fechaini=$_POST['fechaIni'];
        $fechafin=$_POST['fechafin'];
        $confirma=$_POST['confirma'];
        $usuConfirma=$_POST['usuConfirma'];


                // Supongamos que tienes una fecha en formato de cadena
        // $fechaString = "2021-02-03 15:30:00";

        // Creamos un objeto DateTime a partir de la cadena de fecha
        $fecha1 = new DateTime($fechaini);

        // Obtenemos la fecha completa sin la parte del tiempo
        $fechaSinTiempo1 = $fecha1->format('Y-m-d');

        // Creamos un objeto DateTime a partir de la cadena de fecha
        $fecha2 = new DateTime($fechafin);

        // Obtenemos la fecha completa sin la parte del tiempo
        $fechaSinTiempo2 = $fecha2->format('Y-m-d');


        if (is_uploaded_file($_FILES['imagen']['tmp_name'])){
            $nombreArchivo = $_FILES["imagen"]["name"];
            $imagen = date("Y-m-d-H-i-s").$nombreArchivo;
           
            move_uploaded_file($_FILES['imagen']['tmp_name'], "./img_nomina/".$imagen);
         }else{
             $imagen = "";
         }



    
            $sql1="SELECT `nome_id`, `nome_fechaIni`, `nome_fechaFin`, `nome_confirmaParafi`, `nome_fotoParafiscales` FROM `NominasEmpresa` WHERE nome_fechaIni='$fechaSinTiempo1' and nome_fechaFin='$fechaSinTiempo2' ";		
            $DB1->Execute($sql1);
            $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
            if ($rw1[0]>0) {
                $sql="UPDATE `NominasEmpresa` SET `nome_confirmaParafi`='$confirma', `nome_fotoParafiscales`='$imagen' WHERE  nome_fechaIni='$fechaSinTiempo1' and nome_fechaFin='$fechaSinTiempo2'";
                $DB1->Execute($sql);
           
            }else{
                $sql="INSERT INTO `NominasEmpresa`( `nome_fechaIni`, `nome_fechaFin`, `nome_confirmaParafi`, `nome_fotoParafiscales`) VALUES ('$fechaSinTiempo1','$fechaSinTiempo2','$confirma','$imagen')";
                $DB1->Execute($sql);
            }
    
    
    }else if ($funcion =="devolverRetegarantia") {
        $fechaini=$_POST['fechaini'];
        $idUsuario=$_POST['idUsuario'];
        $valor=$_POST['valor'];
        

        $sql1="SELECT `nome_id`, `nome_fechaIni`, `nome_fechaFin`, `nome_confirmaParafi`, `nome_fotoParafiscales` FROM `NominasEmpresa` WHERE nome_fechaIni='$fechaini' and nom_id_usu='$idUsuario'  ";		
        $DB1->Execute($sql1);
        $rw1=mysqli_fetch_row($DB1->Consulta_ID);
    
        if ($rw1[0]>0) {

            $sql="UPDATE `nomina` SET `nom_devolRetegara`='$valor' WHERE  nom_fecha_inicio='$fechaini' and nom_id_usu='$idUsuario' and nom_tipo_pago='Basico'";
            $DB->Execute($sql);


        }else{
            $sql="INSERT INTO `nomina`(`nom_id_usu`,`nom_fecha_inicio`, `nom_tipo_pago`,`nom_devolRetegara`) VALUES ('$idUsuario','$fechaini','Basico','$valor')";
            $DB1->Execute($sql);

        }


        

    }else if ($funcion =="restarRetegarantia") {

        $fechaini=$_POST['fechaini'];
        $idUsuario=$_POST['idUsuario'];
        $valor=$_POST['valor'];
        $idUsuNom=$_POST['idUsuNom'];

        $sql1="SELECT hoj_valorRetegarantia FROM `hojadevida` WHERE hoj_cedula='$idUsuario'  ";		
        $DB1->Execute($sql1);
        $rw1=mysqli_fetch_row($DB1->Consulta_ID);

        $nuevoValor=$rw1[0]+60000;
        
        $sql="UPDATE `hojadevida` SET `hoj_valorRetegarantia`='$nuevoValor' WHERE  hoj_cedula='$idUsuario' ";
        $DB->Execute($sql);

        $sql2="SELECT `nome_id`, `nome_fechaIni`, `nome_fechaFin`, `nome_confirmaParafi`, `nome_fotoParafiscales` FROM `NominasEmpresa` WHERE nome_fechaIni='$fechaini' and nom_id_usu='$idUsuNom'  ";		
        $DB1->Execute($sql2);
        $rw2=mysqli_fetch_row($DB1->Consulta_ID);
    
        if ($rw2[0]>0) {

            $sql3="UPDATE `nomina` SET `nom_devolRetegara`='$valor' WHERE  nom_fecha_inicio='$fechaini' and nom_id_usu='$idUsuNom' and nom_tipo_pago='Basico'";
            $DB->Execute($sql3);


        }else{
            $sql3="INSERT INTO `nomina`(`nom_id_usu`,`nom_fecha_inicio`, `nom_tipo_pago`,`nom_retedes`) VALUES ('$idUsuNom','$fechaini','Basico','60000')";
            $DB1->Execute($sql3);

        }

        if ($sql) {
            echo$nuevoValor;
        }




    }elseif ($funcion =="confirmaAdminPrima") {
       
        $idusuario=$_POST['idUsuario'];
        $abono=$_POST['abono'];
        $fechaini=$_POST['fechaini'];
        $fechafin=$_POST['fechafin'];
        $confirma=$_POST['confirma'];
        
        $tipo=$_POST['tipo'];
        // $desprendible=$_POST['desprendible'];
        // $Observacion=$_POST['Observacion'];
        

        // if ($tipo=="Basico") {
            // $sql1="SELECT  count(*) from nomina where nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo' ";		
            // $DB1->Execute($sql1);
            // $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
            // if ($rw1[0]>0) {
            //     UPDATE `primas` SET `pri_confirma`='[value-2]',`pri_fecha_inicio`='[value-3]',`pri_fecha_fin`='[value-4]',`pri_idusu`='[value-5]',`pri_semestre`='[value-6]',`pri_fechaconfirmausu`='[value-7]' WHERE 1
            //     echo$sql="UPDATE `nomina` SET  nom_confiAdmi='$id_usuario', nom_fechaConfiAdmi='$fechaHoraActual',nom_confirmaAdmin='$confirma',nom_fecha_fin='$fechafin' WHERE nom_id_usu='$idusuario' and nom_fecha_inicio='$fechaini' and nom_tipo_pago='$tipo'";
            //     $DB1->Execute($sql);
           
            // }else{
                $sql=" INSERT INTO `primas`(`pri_idusu`,`pri_fecha_inicio`, `pri_fecha_fin`, `pri_idadminconfi`,`pri_semestre`, `pri_fechaadminconfi`,pri_confiAdmin) VALUES ('$idusuario','$fechaini','$fechafin','$id_usuario','$tipo','$fechaHoraActual','$confirma')";
                // echo$sql="INSERT INTO `nomina`(`nom_id_usu`,`nom_fecha_inicio`, `nom_fecha_fin`, `nom_tipo_pago`,`nom_confiAdmi`,`nom_fechaConfiAdmi`,`nom_confirmaAdmin`) VALUES ('$idusuario','$fechaini','$fechafin','$tipo','$fechaHoraActual','$confirma')";
                $DB1->Execute($sql);
            // }
        // }


        if ($sql) {
            echo"ok";
        } # code..
    }elseif ($funcion =="guardarDesPrima") {
        $idusuario=$_POST['idUsuario'];
        $abono=$_POST['abono'];
        $fechaini=$_POST['fechaini'];
        $fechafin=$_POST['fechafin'];
       
        $estado=$_POST['confirma'];
        $tipo=$_POST['tipo'];
        $desprendible=$_POST['desprendible'];
        

        
            echo$sql1="SELECT  count(*)  FROM `primas` WHERE  pri_idusu='$idusuario' and pri_fecha_inicio='$fechaini'  ";		
            $DB1->Execute($sql1);
            $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
            if ($rw1[0]>0) {
                echo$sql="UPDATE `primas` SET `pri_confirma`='$estado',`pri_docprima`='$desprendible',pri_confirmaUsus='no' WHERE pri_idusu='$idusuario' and pri_fecha_inicio='$fechaini' ";
                $DB1->Execute($sql);
           
            }
       
    }elseif ($funcion =="confirmarPagoPrima") {
        
        $idusuario=$_POST['idUsuario'];
        $abono=$_POST['abono'];
        $fechaini=$_POST['fechaini'];
        $fechafin=$_POST['fechafin'];

        $estado=$_POST['confirma'];
        $tipo=$_POST['tipo'];


        // if ($tipo=="Basico") {
            $sql1="SELECT  count(*) from primas where pri_idusu='$idusuario' and pri_fecha_inicio='$fechaini'  ";		
            $DB1->Execute($sql1);
            $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
            if ($rw1[0]>0) {
                $sql="UPDATE `primas` SET `pri_confirma`='$estado' WHERE pri_idusu='$idusuario' and pri_fecha_inicio='$fechaini' ";
                // $DB1->Execute($sql);
                    if ($DB1->Execute($sql)) {
                    echo"ok";
                    }
                // and nom_tipo_pago='$tipo'
            }else{
                // $sql="INSERT INTO `nomina`(`nom_id_usu`,`nom_confirma`,`nom_fecha_inicio`, `nom_fecha_fin`, `nom_tipo_pago`) VALUES ('$idusuario','$estado','$fechaini','$fechafin','$tipo')";
                // $DB1->Execute($sql);
            }
    }else if ($funcion == "validarUsuarioPrima") {
       

        $idusuario=$_POST['idUsuario'];
        $abono=$_POST['abono'];
        $fechaini=$_POST['fechaini'];
        $fechafin=$_POST['fechafin'];
       
        $estado=$_POST['confirma'];
        $tipo=$_POST['tipo'];
        $desprendible=$_POST['desprendible'];
        $Observacion=$_POST['Observacion'];
        $nombre=$_POST['nombre'];

        // if ($tipo=="Basico") {
            echo$sql1="SELECT  count(*),pri_docprima,pri_confiAdmin from primas where  pri_idusu='$idusuario' and pri_fecha_inicio='$fechaini' ";		
            $DB1->Execute($sql1);
            $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        
            if ($rw1[0]>0) {
                $validadoDesprendible="Validado el $fechaHoraActual";
                $rutaDeComproBas=$rw1[1]."&confirmado=".$validadoDesprendible;
                echo$sql="UPDATE `primas` SET `pri_confirmaUsus`='$estado', pri_fechaconfirmausu='$fechaHoraActual',pri_docprima='$rutaDeComproBas' WHERE  pri_idusu='$idusuario' and pri_fecha_inicio='$fechaini'";
                $DB1->Execute($sql);
           
            }else{
                // $sql="INSERT INTO `nomina`(`nom_id_usu`,`nom_confirma`,`nom_fecha_inicio`, `nom_fecha_fin`, `nom_tipo_pago`,`nom_cuentaCobro`) VALUES ('$idusuario','$estado','$fechaini','$fechafin','$tipo','$desprendible')";
                // $DB1->Execute($sql);
            }
        // }


        if ($sql) {
            echo"ok";
        }






    }
    