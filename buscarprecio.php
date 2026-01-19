<?php
require("login_autentica.php"); 
include("declara.php");

//  echo $sql="SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudaddes=$param21 order by tip_nom";
//  // echo "$id_nombre";
// $DB->Execute($sql);
//   while($rw1=mysqli_fetch_row($DB->Consulta_ID)){

    // Recibir los parámetros
    $parametro1 = $_POST['param1'];
    $parametro2 = $_POST['param2'];
    $parametro3 = $_POST['param3'];
    $parametro4 = $_POST['param4'];

    // Realizar algún procesamiento con los parámetros

    $kilos=$parametro1;
    $sqlprecios="SELECT idprecioskilos FROM `precioskilos` where '$kilos' BETWEEN pre_inicial and prec_final;"; 
    $DB->Execute($sqlprecios);
    $confipre=mysqli_fetch_row($DB->Consulta_ID); 
    $idprecios=$confipre[0];
    if($idprecios==0 or $idprecios==''){
		$idprecios=1;
	}
    
    $sql3="SELECT `idprecios`, `pre_kilo`, `con_precios` 
    FROM `precios` inner join `configuracionkilos` on con_idprecioskilos=idprecios  
    WHERE con_tipo='normal'  
    and `pre_idciudadori`='$parametro2'  
    and `pre_idciudaddes`='$parametro3'  
    and pre_tiposervicio='$parametro4' 
    and con_idprecios='$idprecios'
     AND pre_estado = 1";
			$DB->Execute($sql3);
			$rw2=mysqli_fetch_row($DB->Consulta_ID); 

    // Crear un array con los valores de respuesta
    $valoresRespuesta = array(
        "prekilo" => $rw2[1],
        "adicional" => $rw2[2]
    );

    // Devolver los valores como respuesta JSON
    echo json_encode($valoresRespuesta);

?>