<?php
require("login_autentica.php");
include("declara.php");

@$accion=$_REQUEST["accion"];
$fechatiempo=date("Y-m-d H:i:s");
$fecha=date("Y-m-d");


if($accion==1){


		 $sql1="INSERT INTO `hojadevidacliente`(`hoj_sede`, `hoj_fechaingreso`, `hoj_nit`, `hoj_cedula`, `hoj_nombre`, `hoj_razonsocial`,`hoj_tipocliente`,`hoj_direccionrf`,`hoj_ciudad`,`hoj_email`,`hoj_telefono1`, `hoj_telefono2`, `hoj_clientecredito`,hoj_cedula_rpl,hoj_fechacreacion,hoj_nom_id)  
		VALUES ('$param1','$param2','$param3','$param4','$param5','$param6','$param7','$param8','$param9','$param10','$param11','$param12','$param13','$param14','$fechatiempo','$param15')";
		$vinculo=$DB->Executeid($sql1);

		 $caso='datoscontacto';	
		 $idhojadevida=$vinculo;


}else{
	
@$idhojadevida=$_REQUEST["idhojadevida"];

switch ($condecion)
{
		case "datospersonales":

		
			 $sql1="UPDATE hojadevidacliente set `hoj_sede`='$param1',`hoj_fechaingreso`='$param2', `hoj_nit`='$param3', `hoj_cedula`='$param4', `hoj_nombre`='$param5',`hoj_razonsocial`='$param6', `hoj_tipocliente`='$param7', `hoj_direccionrf`='$param8', `hoj_ciudad`='$param9', `hoj_email`='$param10', `hoj_telefono1`='$param11', `hoj_telefono2`='$param12',`hoj_clientecredito`='$param13',`hoj_cedula_rpl`='$param14', `hoj_fechacreacion`='$fechatiempo',hoj_nom_id='$param15' where idhojadevida='$idhojadevida'  ";
			
			$DB1->Execute($sql1);

		 $caso='datoscontacto';	
	break;
	case "datoscontacto":
	
		if($param8==2){
			$sql2="INSERT INTO `contactofacturacion`( `con_nombre`, `cont_telefono1`, `cont_ext1`, `cont_telefono2`, `cont_ext2`, `cont_celular`, `cont_correo`, `cont_fecharegistra`,cont_idhojavida)
			VALUES ('$param1','$param2','$param3','$param4','$param5','$param6','$param9','$fechatiempo','$param7')";
			$vinculo=$DB->Executeid($sql2);

			$caso='datoscontacto';
			

		}else{
			$caso='datosfacturacion';
		}
	break;
	case "datosfacturacion":
	
					
		 $sql1="UPDATE hojadevidacliente set `hoj_fechanaradicacion`='$param1',`hoj_fechanacorte`='$param2', `hoj_numerocuenta`='$param3', `hoj_plazopago`='$param4', `hoj_novedadesfactura`='$param5' where idhojadevida='$idhojadevida' ";
		$DB1->Execute($sql1);

	 $caso='documentos';	

	
	break;
	case "documentos":

		if($_FILES["param101"]!=''){
			$QL->addDocumento1($_FILES["param101"], 1, "hojadevidacliente", $idhojadevida, "hojadevidacliente", $DB); //documentos 
			}
		if($_FILES["param102"]!=''){
			$QL->addDocumento1($_FILES["param102"], 2, "hojadevidacliente", $idhojadevida, "hojadevidacliente", $DB); //documentos 
			}
		if($_FILES["param103"]!=''){
			$QL->addDocumento1($_FILES["param103"], 3, "hojadevidacliente", $idhojadevida, "hojadevidacliente", $DB); //documentos 
			}
		if($_FILES["param104"]!=''){
			$QL->addDocumento1($_FILES["param104"], 4, "hojadevidacliente", $idhojadevida, "hojadevidacliente", $DB); //
			}
			if($_FILES["param105"]!=''){
			$QL->addDocumento1($_FILES["param105"], 5, "hojadevidacliente", $idhojadevida, "hojadevidacliente", $DB); //pagare
			}
			if($_FILES["param106"]!=''){
			$QL->addDocumento1($_FILES["param106"], 6, "hojadevidacliente", $idhojadevida, "hojadevidacliente", $DB); //es
			}

			$caso='documentos';	


	break;
	default:

	break;
	}


}


if($caso=='final'){
	header ("Location:hojadevidaclientes.php");
}else{

	header ("Location:new_hojadevidacliente.php?bandera=1&condecion=$caso&idhojadevida=$idhojadevida");
}



?> 