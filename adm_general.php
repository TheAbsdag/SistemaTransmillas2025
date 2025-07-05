<?php
require("login_autentica.php");
include("layout.php");
$FB->titulo_azul1("$tabla",9,0,7); 
?>
<script language="javascript">

</script> 
<body onLoad="">
<?php 
$nivel_acceso=$_SESSION['usuario_rol'];
@$imagen=$_REQUEST['imagen'];
if($imagen!=''){ $variable="imagenes=$imagen"; }else{
	$variable="";
}
$FB->nuevogeneral("$tabla", "general", "$variable");
$tabla=strtolower($tabla);
  $sql="SHOW COLUMNS FROM $tabla";
$DB->Execute($sql); $va=1; $va2=0; 
$sql="SELECT ";
$inner=" ";
	while($rw1=mysqli_fetch_row($DB->Consulta_ID))
	{
		$dato=explode("_",$rw1[0]);
		if($dato[0]=="val"){
			$dato[1]="Valor ".$dato[1];
			
		}else if($dato[0]=="inner"){
			
			if($dato[2]!=''){
				$nombretable=$dato[1]."_".$dato[2];
				$iddes="id".$dato[1]."_".$dato[2];
			}else{
				$nombretable=$dato[1];
				$iddes="id".$dato[1];
			}
			$inner .= " LEFT JOIN $nombretable ON $rw1[0] = $iddes";
			$indice=substr($dato[1],0,3);
			$rw1[0]=$indice."_nombre";
			if ($dato[1] == "sedes") {
			$dato[1] = "Sede";
		}
		}
		$sql.="$rw1[0],";
		if($va2==1){	
		
			$FB->titulo_azul7("$dato[1]",1,0,5,"$rw1[0]",$asc2,$tabla); 
			
		} else if($va2!=0 and $va2>1){	
			
			$FB->titulo_azul1("$dato[1]",1,0,0); 
			//$FB->llena_texto("$dato[1]:",$va, 1, $DB, "", "", "",1, 1);
		}
		$va2++;
	}
 $sql = substr ($sql, 0, -1);

 if($imagen!=''){
	$FB->titulo_azul1("Imagen",1,0,0);
	$va2++;

 }

 if($tabla=='ciudades'){

	 if($nivel_acceso==1){
		$FB->titulo_azul3("Acciones",2,0,2,$param_edicion);

	 }else{
		$param_edicion=0;
	 }
	
 }else{
	$FB->titulo_azul3("Acciones",2,0,2,$param_edicion);

 }

  $sql.=" FROM $tabla $inner ORDER BY $ord $asc";
 $LT->llenatabla($sql,"$va2+1", "$tabla","general","","","","$imagen","","",$param_edicion, $DB, $DB1);
 $FB->llena_texto("tabla", 1, 13, $DB, "", "", "$tabla", 5, 0);


$FB->cierra_tabla(); 
$FB->cierra_form(); 
require("footer.php"); ?>