<?php
require("login_autentica.php");
include("declara.php");

$param20 = $_GET["param20"];
$param21 = $_GET["param21"];
$paramservici = $_GET["paramtipser"];
$idservicio = $_GET["idservicio"];
$cro = $_GET["cro"];

$aux0 = "";
$aux = "";
$aux2 = "";


if ($idservicio =='null') {

	$idservicio = "";
	$aux0 = "selected";

} elseif ($idservicio == 1000) {
	$aux2 = "selected";
	$idservicio = 1000;
} elseif ($idservicio == 0) {

    $aux = "selected";
	$idservicio = 0;
}



// $FB->llena_texto("",27,2,$DB,"(SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudaddes=$param21 order by tip_nom)","","$valortserv",17,1);
if ($cro == "Compra" or $cro == "Recogida") {
	echo "<select class='trans'  name='param27' id='param27' onchange='precioconvenir(84,this.value,0,\"convenir\")' required>";
	echo "<option value='' $aux0 >Seleccione...</option>";
	// echo "<option  value='0'" . $aux . ">Carga via terrestre</option>";
	if($nivel_acceso!=3 ){
		echo "<option  value='1000' " . $aux2 . " >A convenir</option>";
	}

	if ($paramservici != '' && $paramservici != null) {

		 echo$sql = "SELECT `pre_tiposervicio`,`tip_nom` FROM `precios_credito` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudades=$param21 and pre_idcredito='$paramservici' and pre_estado=1";
	} else {

		 echo$sql = "SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudaddes=$param21 and pre_estado=1 order by tip_nom";
	}

	$LT->llenaselect($sql, 0, 1, $idservicio, $DB);
} else if ($cro == "Todo") {
	echo "<select class='trans'  name='param37' id='param37'  onchange='precioconvenir(84,this.value,0,\"convenir\")' required>";
	echo "<option  value=''>Seleccione...</option>";
	// echo "<option  value='0' " . $aux . ">Carga via terrestre</option>";
	if($nivel_acceso==1 ){
		echo "<option  value='1000' " . $aux2 . " >A convenir</option>";
	}
	echo $sql = "SELECT `pre_tiposervicio`,`tip_nom` FROM `precios_credito` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudades=$param21 union SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudaddes=$param21  AND pre_estado = 1 order by tip_nom";
	$LT->llenaselect($sql, 0, 1, $idservicio, $DB);
}  else if ($cro == "Editar") {
	echo "<select class='trans'  name='param37' id='param37'  onchange='precioconvenir(84,this.value,0,\"convenir\")' required>";
	echo "<option value='' $aux0 >Seleccione...</option>";
	// echo "<option  value='0'" . $aux . ">Carga via terrestremal</option>";
	if($nivel_acceso==1 ){
		echo "<option  value='1000' " . $aux2 . " >A convenir</option>";
	}
	if ($paramservici != '') {

		$sql = "SELECT `pre_tiposervicio`,`tip_nom` FROM `precios_credito` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudades=$param21 and pre_idcredito='$paramservici' and pre_estado=1";
	} else {

		$sql = "SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudaddes=$param21 and pre_estado=1 order by tip_nom";
	}

	$LT->llenaselect($sql, 0, 1, $idservicio, $DB);
}else if ( $cro == "oficina") {
	// echo "<select class='trans'  name='param34' id='param34' onchange='precioconvenir(84,this.value,0,\"convenir\")' required>";
	echo "<select class='trans'  name='param34' id='param34' onchange=	'valorpagar(this.value,202,\"llega_sub3\",\"total valor\",1,$id_usuario)' required>";

	echo "<option value='' $aux0 >Seleccione...</option>";
	
	if($nivel_acceso!=3 ){
		echo "<option  value='0'" . $aux . ">Carga via terrestre</option>";
		echo "<option  value='1000' " . $aux2 . " >Servicio cotizado</option>";
	}

	if ($paramservici != '' && $paramservici != null) {

		 $sql = "SELECT `pre_tiposervicio`,`tip_nom` FROM `precios_credito` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudades=$param21 and pre_idcredito='$paramservici' and pre_estado=1";
	} else {

		 $sql = "SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudaddes=$param21 and pre_estado=1 order by tip_nom";
	}

	$LT->llenaselect($sql, 0, 1, $idservicio, $DB);
}else {
	echo "<select class='trans'  name='param34' id='param34' onchange='valorpagar(this.value,202,\"llega_sub3\",\"total valor\",1,$id_usuario)' required>";
	echo "<option value='' $aux0 >Seleccione...</option>";
	echo "<option  value='0'" . $aux . ">Carga via terrestre</option>";
	if($nivel_acceso!=3 ){
		echo "<option  value='1000' " . $aux2 . " >A convenir</option>";
	}
	
	if ($paramservici != '') {

		$sql = "SELECT `pre_tiposervicio`,`tip_nom` FROM `precios_credito` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudades=$param21 and pre_idcredito='$paramservici' and pre_estado=1";
	} else {

		$sql = "SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$param20 and pre_idciudaddes=$param21 and pre_estado=1 order by tip_nom";
	}

	$LT->llenaselect($sql, 0, 1, $idservicio, $DB);
}
