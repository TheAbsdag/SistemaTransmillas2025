<?php 
require("login_autentica.php");
session_start();
 
include("layout.php");

echo '
<style>
.alerta-vencimiento td {
  background-color: #ffcccc !important;
}
</style>
';

$FB->titulo_azul1("Hojas de Vida Clienetes",9,0,5);  
$FB->abre_form("form1","hojadevidaclientes.php","post");
$FB->nuevo7("new_hojadevidacliente.php?condecion=datospersonales&accion=1");

	if($param5!=''){  $conde2="and hoj_sede=$param5"; }  else { $conde2=""; }

$FB->llena_texto("Busqueda por:",3,82,$DB,$busqueda5,"",$param3,17,0);
$FB->llena_texto("Dato:", 2, 1, $DB, "", "","$param2",4,0);
$FB->llena_texto("Estado:",4,82,$DB,$estadosac2,"",$param4,1,0);


if ($param6 !="" and $param6 !='0') {

	$cond6="and hoj_fechaingreso like '$param6%'";
	
}else{
	$cond6="";
}




$FB->llena_texto("", 3, 142, $DB, "BUSCAR", "","", 1, 0);
$FB->cierra_form(); 

$conde1=""; 

  if($param2!="" and $param3!=""){ 
   $conde1="and $param3 like '%$param2%' "; 
	}else { $conde1="  "; } 


  if($param4!='0' and $param4!=''){
	  $cond5=" and hoj_estado='$param4'";
  }

  if($param1!='0' and $param1!=''){
	$cond3=" and hoj_tipocontrato='$param1'";
}

 $sql="SELECT COUNT(*) FROM `hojadevidacliente`  $conde1 $conde2 $cond5 $cond3";
$DB->Execute($sql); 
$valor=$DB->recogedato(0);
if(isset($_REQUEST["CantidadMostrar"])){ $CantidadMostrar=$_REQUEST["CantidadMostrar"]; } else { $CantidadMostrar=50; } 
//$CantidadMostrar=5;
$compag =(int)(!isset($_GET['pag'])) ? 1 : $_GET['pag']; 
$TotalRegistro  =ceil($valor/$CantidadMostrar);


if(isset($_REQUEST["ordby"])){ $ordby=$_REQUEST["ordby"]; } else { $ordby="hoj_nombre,hoj_apellido"; } 
if(@$_REQUEST["asc"]!=""){ $asc=$_REQUEST["asc"]; } else {$asc="ASC"; } 	$asc2=""; if($asc=="ASC"){ $asc2="DESC";}
//$condlimit=$FB->llena_sigant($pagina, $ordby, $asc, $valor); 


$FB->titulo_azul1("#",1,0,7); 
$FB->titulo_azul1("Credito",1,0,0); 
$FB->titulo_azul1("Nit",1,0,0); 
$FB->titulo_azul1("Cedula",1,0,0); 
$FB->titulo_azul1("Nombres",1,0,0); 
$FB->titulo_azul1("Razon Social",1,0,0); 
$FB->titulo_azul1("Telefonos",1,0,0); 
$FB->titulo_azul1("Correo",1,0,0); 
$FB->titulo_azul1("Ciudad",1,0,0); 
$FB->titulo_azul1("Direcci&oacute;n Radicacion",1,0,0);
$FB->titulo_azul1("Tipo Cliente ",1,0,0); 
$FB->titulo_azul1("Estado",1,0,0); 
$FB->titulo_azul3("Acciones",2,0,2,$param_edicion);


   $sql="SELECT `idhojadevida`, `cre_nombre`,`hoj_nit`,`hoj_cedula`, `hoj_nombre`, `hoj_razonsocial`,`hoj_telefono1`, `hoj_telefono2`,   `hoj_email`,`hoj_ciudad`, `hoj_direccionrf`, `hoj_tipocliente`,`hoj_estado`, `hoj_fechacreacion`, `hoj_fechaingreso`, `hoj_sede`,   `hoj_fechanaradicacion`, `hoj_fechanacorte`, `hoj_numerocuenta`, `hoj_plazopago`, `hoj_novedadesfactura` FROM `hojadevidacliente` left join creditos on idcreditos=hoj_clientecredito where idhojadevida>0  $conde1 $conde2 $cond5 $cond3 $cond6 ORDER BY  hoj_nombre asc LIMIT ".(($compag-1)*$CantidadMostrar)." , ".$CantidadMostrar;

$DB->Execute($sql); $va=(($compag-1)*$CantidadMostrar); 
	while($rw1=mysqli_fetch_row($DB->Consulta_ID))
	{
		$id_p = $rw1[0];
		$hayVencido = false;
		$sqlDocs = "SELECT `iddoccliente`, `docl_nombre`, `docl_documento`, `docl_idhvc`, `docl_fecha_venc` 
					FROM `doc_hoja_clientes` WHERE docl_idhvc = $id_p";
		$resDocs = $DB1->Execute($sqlDocs);

		$cont = 0;
		$color = ""; 
		while ($row = mysqli_fetch_assoc($resDocs)) {
			if ($cont < 1) {
				$fecha_vencimiento = $row['docl_fecha_venc']; 
				$fecha_actual = date("Y-m-d");
				$dias_restantes = (strtotime($fecha_vencimiento) - strtotime($fecha_actual)) / (60 * 60 * 24);

				if ($dias_restantes <= 30) {
					$cont++;
					$documento_por_vencer[$idhojadevida] = true;
					$color = "#FFCCCC"; 
				} else {
					$color = ($p == 0) ? "#FFFFFF" : "#EFEFEF";
				}
			} else {
				break;
			}
		}

		if (!isset($color) || $color == "") {
			$va++; 
			$p = $va % 2;
			$color = ($p == 0) ? "#FFFFFF" : "#EFEFEF";
		}

		echo "<tr class='$classFila' style='background-color: $color;' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";

		//if($rw1[1]==0){$rw1[1]='NO';}
		$telefonos=$rw1[6]." - ".$rw1[7];
		echo "<td>".$va."</td>
		<td>".$rw1[1]."</td>
		<td>".$rw1[2]."</td>
		<td>".$rw1[3]."</td>
		<td>".$rw1[4]."</td>
		<td>".$rw1[5]."</td>
		<td>".$telefonos."</td>
		<td>".$rw1[8]."</td>
		<td>".$rw1[9]."</td>
		<td>".$rw1[10]."</td>
		";

		echo "<td><div id='inactivo$va'>"; 
		echo "<select name='param11' id='param11' class='form-control' onChange='cambio_ajax2(this.value,82, \"inactivo$va\", \"$va\", 1, $id_p)' required>";		
			
		$LT->llenaselect_ar22($rw1[22],$estadosac);
			echo "</select></div></td>";

		 $DB1->editar("new_hojadevidacliente.php",$id_p, "hojadevidacliente", 1,'datospersonales');
	} 

 //Operacion matematica para boton siguiente y atras 
	$IncrimentNum =(($compag +1)<=$TotalRegistro)?($compag +1):1;
  	$DecrementNum =(($compag -1))<1?1:($compag -1);
 // onchange=location.href='http://www.dominio/pagina.php?ref='+this.value
 $selec200="";
 $selec50="";
 $selec100="";
 $selec10000="";
 if($CantidadMostrar==50){ $selec50="Selected";} else if($CantidadMostrar==100){ $selec100="Selected";} else if($CantidadMostrar==200){ $selec100="Selected";} else if($CantidadMostrar==10000){ $selec10000="Selected";}
	echo "<section class='paginacion'><ul ><li>Mostrar <select onchange=location.href=\"?CantidadMostrar=\"+this.value ><option value='50' $selec50 >50</option><option value='100' $selec100>100</option><option value='200' $selec200>200</option><option value='10000' $selec10000>Todos..</option></select></li><li><a>Total Registros: $valor </a></li><li ><a href=\"?pag=".$DecrementNum."\" >&#171;&#171;</a></li>";
    //Se resta y suma con el numero de pag actual con el cantidad de 
    //numeros  a mostrar
     $Desde=$compag-(ceil($CantidadMostrar/2)-1);
     $Hasta=$compag+(ceil($CantidadMostrar/2)-1);
     //Se valida
     $Desde=($Desde<1)?1: $Desde;
     $Hasta=(($Hasta<$CantidadMostrar)?$CantidadMostrar:$Hasta)/10;
     //Se muestra los numeros de paginas
     for($i=$Desde; $i<=$Hasta;$i++){
     	//Se valida la paginacion total
     	//de registros
     	if($i<=$TotalRegistro){
     		//Validamos la pag activo
     	  if($i==$compag){

           echo "<li><a href=\"?pag=".$i."\" class=\"active\">".$i."</a></li>";

     	  }else {

     	  	echo "<li><a href=\"?pag=".$i."\">".$i."</a></li>";

     	  }     		

     	}

     }

	echo "<li class=\"active\"><a href=\"?pag=".$IncrimentNum."\">&#187;&#187;</a></li></ul>";



include("footer.php");

?>