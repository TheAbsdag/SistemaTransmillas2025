<?php
require("login_autentica.php"); 
include("declara.php");

echo $param20=$_GET["param20"];
echo $param21=$_GET["param21"];



echo "<select class='trans'  name='param34' id='param34' required>";
	echo "<option  value=''>seleccione</option>";
	// echo "<option  value='0'>Carga via terrestre</option>";
	echo$sql="SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudaddes=$param21  AND pre_estado = 1 order by tip_nom";
    $LT->llenaselect($sql,0,1, $param34, $DB);

//  echo $sql="SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudaddes=$param21 order by tip_nom";
//  // echo "$id_nombre";
// $DB->Execute($sql);
//   while($rw1=mysqli_fetch_row($DB->Consulta_ID)){

// echo $rw1[0];
//   }
?>