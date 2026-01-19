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

    $funcion=$_POST ['funcion'];

    if ($funcion=="precios credito") {

        $credito=$_POST ['credito'];
        $origen= $_POST['origen'];
        $destino= $_POST['destino'];
        $servicio=$_POST ['servicio'];
         
        $sql="SELECT `idprecioscredito`, `pre_idcredito`, `pre_idciudadori`, `pre_idciudades`, `pre_preciokilo`, `pre_precioadicional`, `pre_tiposervicio`, `pre_fechaingreso` FROM `precios_credito` WHERE pre_idcredito='$credito' and pre_idciudadori='$origen' and pre_idciudades='$destino' and pre_tiposervicio ='$servicio'  AND pre_estado = 1";
        $DB1->Execute($sql); $va=0;
        $rw1=mysqli_fetch_row($DB1->Consulta_ID);
        if (empty($rw1)) {
            // echo "El array está vacío.";
        } else {
            echo "Se encontro un precio credito igual verifique e intente de nuevo";
        }
        
    }

