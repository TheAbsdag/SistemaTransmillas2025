

<?php
require("login_autentica.php"); 

// include("layout.php");
$id_sedes= $_SESSION['usu_idsede'];
$id_usuario= $_SESSION['usuario_id'];
$id_nombre=$_SESSION['usuario_nombre'];
$nivel_acceso=$_SESSION['usuario_rol'];
$precioinicialkilos=$_SESSION['precioinicial'];

if ($nivel_acceso!=1) { 
	include("layout.php");
?>



<style>
        .container{
            float: left;
            margin-right: 10px;
			display: flex;
            align-items: center;
        }
		.archivo-button {
         margin-right: 10px; /* Espacio entre el input y el botón */
        }
        .email-button {
            display: inline-flex;
            align-items: center;
            background-color: #2196F3; /* Color de fondo */
            color: white; /* Color del texto */
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
			margin-right: 10px; /* Espacio entre el input y el botón */
        }

        .file-button {
            display: inline-flex;
            align-items: center;
            background-color: #4CAF50; /* Color de fondo */
            color: white; /* Color del texto */
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
			margin-right: 10px; /* Espacio entre el input y el botón */
        }
        .email-button i {
            margin-right: 8px; /* Espacio entre el icono y el texto */
        }

        .file-button i {
            margin-right: 8px; /* Espacio entre el icono y el texto */
        }



        .file-button {
            background-color: #4CAF50;
        }
	
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<?php

 $conde="";
$conde="pre_idciudadori";
$conde1="";
$conde2="";
// if(isset($_REQUEST["param1"])){ if($param1!=""){  $conde="pre_idciudadori"; $conde1="and pre_idciudadori='$param1'"; } } else {$param1=""; $conde1=""; }
// if(isset($_REQUEST["param2"])){  if($param2!=""){ $conde="pre_idciudades"; $conde1.="and pre_idciudades='$param2'"; } } else {$param2="";  $conde1="";}
// if(isset($_REQUEST["param5"])){  if($param5!="" and $param2!="" and $param1!=""){ $conde="pre_idciudades"; $conde1.="and pre_idciudades='$param2'"; $conde2.="and pre_idcredito='$param5'";} } else {$param="";  $conde1=""; $conde2="";}


	if($param1!="" and $param2!=""){  
		$conde="pre_idciudadori"; 
		$conde1="and pre_idciudadori='$param1'"; 
		$conde2="and pre_idciudades='$param2'"; 
	}if($param1!="") {
		
		$conde1="and pre_idciudadori='$param1'"; 
		$conde2=""; 
	}else if ($param2!="") {
		
		$conde1=""; 
		$conde2="and pre_idciudades='$param2'"; 
	}else{
		
		$conde1=""; 
		$conde2=""; 
	
	}



if($param5!=""){
	 $conde="pre_idciudades"; 
	 $conde3="and pre_idcredito='$param5'";
	 }else {
		$conde3="";
	 } 




$FB->titulo_azul1("Configuraci&oacute;n de Precios Creditos",9,0,7);  
$FB->abre_form("form1","precios_creditos.php","post");

// cambio1(this.value, param2.value, \"precios_creditos.php\", 1);
// cambio1(param1.value, this.value, \"precios_creditos.php\", 1);


$FB->llena_texto("Ciudad Origen:",1,2,$DB,"SELECT `idciudades`, `ciu_nombre` FROM `ciudades`  where inner_estados=1 order by ciu_nombre" ,"",$param1,1,0);
$FB->llena_texto("Ciudad Destino:",2,2,$DB,"SELECT `idciudades`, `ciu_nombre` FROM ciudades where inner_estados=1 order by ciu_nombre ","",$param2,4,0);
$FB->llena_texto("Credito:",5,2,$DB,"SELECT idcreditos,cre_nombre FROM  creditos  where idcreditos>0 ","",$param5,4,0);
$FB->llena_texto("", 3, 142, $DB, "BUSCAR", "","", 1, 0);
$FB->cierra_form(); 

if($rcrear==1) { $FB->nuevo("Precios credito", $condecion, ""); } 



	$htmlTable = '';
	$htmlTable .= '<table border="1">';
	// $htmlTable .= '<tr><th colspan="10" style="cennter">Precios Creditos '.$param5.' </th></tr>';

	// Cabeceras de la tabla
	$htmlTable .= '<tr>';
	$htmlTable .= '<th>Ciudad Origen</th>';
	$htmlTable .= '<th>Ciudad Destino</th>';
	$htmlTable .= '<th>Primeros ' . $precioinicialkilos . ' Kg</th>';
$FB->titulo_azul1("Credito",1,0,5); 
$FB->titulo_azul1("Ciudad Origen",1,0,0); 
$FB->titulo_azul1("Ciudad Destino",1,0,0); 
$FB->titulo_azul1("Primeros $primeroskilos Kg ",1,0,0); 

$sql = "SELECT `idprecioskilos`, `pre_inicial`, `prec_final` FROM `precioskilos`";
$DB1->Execute($sql);
$menus=array();
while ($rw2 = mysqli_fetch_row($DB1->Consulta_ID)) {
	$htmlTable .= '<th>Precio Kg ' . $rw2[1] . ' a ' . $rw2[2] . ' Kg</th>';

	$FB->titulo_azul1("Precio Kg $rw2[1] a $rw2[2]",1,0,0); 
	array_push($menus,$rw2[0]);
}
$htmlTable .= '<th>Tipo Servicio</th></tr>';
$FB->titulo_azul1("Servicio",1,0,0); 
$FB->titulo_azul3("Acciones",2,0,2,$param_edicion);

$sql="SELECT `idprecioscredito`,  `pre_idciudadori`, `pre_idciudades`,`pre_preciokilo`, `pre_precioadicional`, `pre_tiposervicio`, `pre_idcredito`,cre_nombre FROM `precios_credito` inner join creditos on idcreditos=pre_idcredito where idprecioscredito>0  $conde1 $conde2 $conde3 ORDER BY $conde, $ord $asc";
$DB1->Execute($sql); $va=0;
while($rw1=mysqli_fetch_row($DB1->Consulta_ID))
{
	$preciosconfi=array();
	$id_p=$rw1[0];
	$va++; $p=$va%2;
	if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
	
	$sql1="SELECT  `ciu_nombre`,idciudades FROM `ciudades` WHERE idciudades in ($rw1[1],$rw1[2]) ";
	$DB->Execute($sql1); 
	while($rw=mysqli_fetch_row($DB->Consulta_ID))
	{
		
		if($rw[1]==$rw1[1]){
			$valor[1]=$rw[0];
		}else {
			$valor[2]=$rw[0];
		}
	}
	$nomcredito = $rw1[7];
	if($rw1[1]==$rw1[2]){ $valor[2]=$valor[1];  }
	$htmlTable .= "<tr>";
	echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
	$htmlTable .= "<td>".$valor[1]."</td>
	<td>".$valor[2]."</td>";
	echo "<td>".$rw1[7]."</td><td>".$valor[1]."</td>
	<td>".$valor[2]."</td>";
	$htmlTable .= "<td>".$rw1[3]."</td>"; 	
	echo "<td>".$rw1[3]."</td>";
		

		$sql2 = "SELECT  `con_precios`,con_idprecios FROM `configuracionkilos` WHERE con_idprecioskilos='$id_p' and con_tipo='Credito' order by idconfiguracionkilos asc";
		$DB->Execute($sql2);
		while($rw3=mysqli_fetch_row($DB->Consulta_ID))
		{
			$preciosconfi[$rw3[1]]=$rw3[0];
			
		}
		
		foreach ($menus as $value) {

			 $datosp =$preciosconfi[$value];
			if($datosp !=''){
				$htmlTable .= "<td>".$datosp."</td>";
				echo "<td>".$datosp."</td>";
			}
			else{
				$htmlTable .= "<td>0</td>";
				echo "<td>0</td>";
			}
		}

		

		if($rw1[5]!=0 and $rw1[5]!=NULL){
			$sql33="Select `idtiposervicio`, `tip_nom` from tiposervicio WHERE `idtiposervicio`='$rw1[5]'"; 
			$DB->Execute($sql33);
			$rw7=mysqli_fetch_row($DB->Consulta_ID); 
			$htmlTable .= "<td>$rw7[1]</td>";
			echo "<td>$rw7[1]</td>";
		}else {
			$htmlTable .= "<td>Carga via terrestre</td>";
			echo "<td>Carga via terrestre</td>";
		}

	$DB->edites($id_p, "Precios credito", 1, $condecion);
	$htmlTable .= "</tr>";
	echo "</tr>";
}
$htmlTable .= "</table>";
$_SESSION['htmlTable'] = $htmlTable;
$_SESSION['nomcredito'] = $nomcredito;

include("footer.php"); ?>

<div class="container">
        <form id="pdfForm" action="descargarprecios.php" method="POST" style="display: none;">
            <textarea name="htmlTable" style="display: none;"><?php echo htmlspecialchars($htmlTable); ?></textarea>
			<input  name="nomcredito" type="hidden" value="<?php echo htmlspecialchars($nomcredito); ?>" style="display: none;">
        </form>
        <button type="button" class="icon-button file-button" onclick="document.getElementById('pdfForm').submit();">
            <i class="fas fa-file"></i> Documento
        </button>
    </div>




<?php  } else{?>


	
<?php


?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php 

?>
<style>
  html, body {
    margin: 0;
    height: 100%;
    overflow: hidden; /* Evita scroll doble */
  }

  .iframe-container {
    width: 100%;
    height: 100vh; /* Ocupa toda la pantalla */
    display: flex;
  }

  .iframe-container iframe {
    flex: 1;
    border: none;
  }
</style>

<form id="redirectForm" action="https://sistema.transmillas.com/nueva_plataforma/controller/PreciosCreditoController.php" method="post">
  <input type="hidden" name="acceso" value="<?= $nivel_acceso ?>">
  <input type="hidden" name="sede" value="<?= $id_sedes ?>">
  <input type="hidden" name="usuario" value="<?= $id_nombre ?>">
</form>

<script>
  // Enviar automáticamente el formulario al cargar la página
  document.getElementById("redirectForm").submit();
</script>

<?php  } ?>