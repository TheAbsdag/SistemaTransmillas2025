<?php
require("login_autentica.php"); 
include("declara.php");

 $param20=$_GET["param20"]; //ciudad origen
 $param21=$_GET["param21"]; //ciudad destino
 $param22=$_GET["param22"]; //Credito

if($param22!='' and $param22!=0){
    $FB->llena_texto("",37,279,$DB,"(SELECT `pre_tiposervicio`,`tip_nom` FROM `precios_credito` inner join tiposervicio on idtiposervicio = pre_tiposervicio and pre_idciudadori=$param20 and pre_idciudades=$param21 and pre_idcredito=$param22 and pre_estado=1 order by tip_nom)","",$valor3,17,1);

}else{
    $FB->llena_texto("",37,279,$DB,"(SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudaddes=$param21 and pre_estado=1 order by tip_nom)","",$valor3,17,1);

}


//  echo $sql="SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudaddes=$param21 order by tip_nom";
//  // echo "$id_nombre";
// $DB->Execute($sql);
//   while($rw1=mysqli_fetch_row($DB->Consulta_ID)){

// echo $rw1[0];
//   }
?>