<?php 
// require("login_autentica.php"); 
// include("layout.php");
// //include("cabezote4.php"); 
// //echo "jose: ".$param5;
// $conde22=" ";
// if($param5!=''){ $id_sedes=$param5;  $conde2=" "; }  
// if($param5==''){   $conde22="and gas_idciudaddes=$id_sedes"; } 
// else{
// 	$conde22="and gas_idciudaddes=$param5";
// }

// ?>
// <script>


// function validarllegada(des)
// {
// 	var valorguia= document.getElementById("codigoEscaneado").value;
// 	if(valorguia!=""){
	
// 		var valorguia2 = valorguia.split(' ');
// 		pop_general2(valorguia2[0],"descargaoficina",1);
// 		return false;
// 	}else{
// 		return true;
// 	}
	
	
			
// }


// function imprimirguia(url)
// {
	
// 	if(url!='' && url!=undefined){
// // Retirar para la nueva guia
// 		// url='ticketfacturatodos.php?pagina2=verificarpeso.php&id_param='+url;
// 		// var a = document.createElement("a");
// 		// a.target = "_blank";
// 		// a.href = url;
// 		// a.click();
// 	}

// }

// function buscarsede(dato)
// {


// 	p6=document.getElementById('param4').value;
// 	p3=document.getElementById('param3').value;

// 	 if(dato==3){
// 		destino="ticketfacturatodos2.php?param33="+p3+"&param34="+p6;
	
// 	}
// 	else if(dato==4){
// 		destino="phpqrcode/ticket3.php?param33="+p3+"&param34="+p6+"&modulo=4";
// 	}

// 	window.location=destino;
	
// }
// let scale = 1; // Escala inicial de la imagen ampliada

// function abrirVentana(url) {
//     window.open(url, "ventanaEmergente", "width=600,height=400,scrollbars=yes,resizable=yes");
// }
// function ampliarImagen(element) {
//   const overlay = document.getElementById("image-overlay");
//   const imagenAmpliada = document.getElementById("ampliada");

//   // Configura la imagen ampliada
//   imagenAmpliada.src = element.src;
//   scale = 1; // Restablece el zoom al abrir la imagen
//   imagenAmpliada.style.transform = `scale(${scale})`;

//   // Muestra la vista ampliada
//   overlay.style.display = "flex";

//   // Agrega el evento de zoom con la rueda del mouse
//   imagenAmpliada.addEventListener("wheel", ajustarZoom);
// }

// function cerrarImagen() {
//   const overlay = document.getElementById("image-overlay");
//   const imagenAmpliada = document.getElementById("ampliada");

//   // Oculta la vista ampliada
//   overlay.style.display = "none";

//   // Elimina el evento de zoom
//   imagenAmpliada.removeEventListener("wheel", ajustarZoom);
// }

// function ajustarZoom(event) {
//   event.preventDefault();

//   // Ajusta la escala según la dirección de la rueda del mouse
//   if (event.deltaY < 0) {
//     scale += 0.1; // Acerca
//   } else {
//     scale -= 0.1; // Aleja
//   }

//   // Limita la escala a un rango razonable
//   scale = Math.max(0.5, Math.min(3, scale));

//   // Aplica la nueva escala
//   const imagenAmpliada = document.getElementById("ampliada");
//   imagenAmpliada.style.transform = `scale(${scale})`;
// }
// </script>

// <head>
<!-- // <link href="css/estilosImagenes.css" rel="stylesheet" type="text/css" />

// 	</head>
// <body onload="cambio_ajax2(<?php echo $id_sedes;?>, 107, 'llega_sub1', 'param3', 1, <?php echo $id_sedes;?>); imprimirguia(<?php echo $id_param;?>);"> -->

// <?php 

// //echo $_SESSION['usuario_rol'];
// //$FB->abre_form("form1","verificarpeso.php","post");
// echo '<form action="verificarpeso.php" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="return validarllegada();" >';

// //$FB->nuevo("Planillas", "$id_ciudad", "nuevo_admin.php");
// $FB->titulo_azul1("Descargar Oficina",9,0,5);  



//  //$conde="and ser_fechafinal like '$fechaactual%'"; 
//  $conde11="and gas_fecrecogida like '$fechaactual%'"; 
 
// if($param4!=''){
// 	// $conde="and ser_fechafinal like '$param4%'";   
// 	$conde11="and gas_fecrecogida like '$param4%'";  
// 	 $fechaactual=$param4;
// 	  }
// //$FB->llena_texto("Fecha de Busqueda:", 4, 10, $DB, "", "", "$fechaactual", 1, 0);
// $conde3="";	
// $conde4="";	

// if($nivel_acceso==1 or $nivel_acceso==10){
// 	if($param5==''){ $id_sedes=0;  $conde2=" "; } 
// 	 $conde2.=" and  ser_estado in (6,4)";
// }
// else {	
// 	$conde4=" and idsedes='$id_sedes' ";	
// }


// 	$idcidades=ciudadesedes($id_sedes,$DB);
// 	if($idcidades=='0'){
// 		$conde2.="";

// 	}else {
// 		//$conde2.=" and ((cli_idciudad in $idcidades and ser_estado=6) or (inner_sedes=$id_sedes and ser_estado=11)) "; 		  
// 		$conde2.=" and ((cli_idciudad in $idcidades and ser_estado in (4,6))) "; 	
// 	}

	
//  if($nivel_acceso==3) {
	
// $conde3="and ser_idresponsable='$id_usuario'";	
	
// }else {
// 	if($param3!=''){
// 		$conde3="and ser_idresponsable='$param3'";	
// 	}
	
// }
// //echo "SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde4";

// $FB->llena_texto("Sede:",5,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde4)", "cambio_ajax2(this.value, 107, \"llega_sub1\", \"param3\", 1, 0)", "$id_sedes", 1, 0);
// $FB->llena_texto("Operario:", 3, 4, $DB, "llega_sub1", "", $param3,4,0);

// $FB->llena_texto("Busqueda por:",1,82,$DB,$busqueda,"",$param1,1,0);
// $FB->llena_texto("Dato:", 2, 1, $DB, "", "","$param2", 4,0);
// echo '<tr><td class="text">Escanear Código: </td><td align="right" ><div class="form-group">
// <div class="input-group">
// 	<div class="input-group-addon"><i class="fa fa-barcode"></i></div>
// 	<input autofocus type="text" class="form-control producto" name="codigoEscaneado" id="codigoEscaneado" autocomplete="off"  >
// </div>
// </div></td>';
// echo "<td><button type='button' class='btn btn-primary btn-lg' onclick='buscarsede(4);'>Imprimir Codigos</button></td>";

// $FB->llena_texto("", 3, 142, $DB, "BUSCAR", "","", 1, 0);

// $FB->cierra_form(); 
// $FB->titulo_azul1("Fecha",1,0,7); 
// $FB->titulo_azul1("Remitente",1,0,0); 
// $FB->titulo_azul1("Direcci&oacute;n",1,0,0); 
// $FB->titulo_azul1("Destinatario",1,0,0); 
// $FB->titulo_azul1("Ciudad",1,0,0); 
// $FB->titulo_azul1("Direcci&oacute;n",1,0,0); 
// $FB->titulo_azul1("Descripci&oacute;n PQ",1,0,0); 
// $FB->titulo_azul1("Piezas",1,0,0); 
// $FB->titulo_azul1("Mensajero",1,0,0); 
// $FB->titulo_azul1("Pago	",1,0,0); 
// $FB->titulo_azul1("# Guia",1,0,0); 
// $FB->titulo_azul1("Guia R",1,0,0); 
// $FB->titulo_azul1("Estado",1,0,0); 
// $FB->titulo_azul1("Pesar",1,0,0); 
// $FB->titulo_azul1("Codigo",1,0,0); 
// //$FB->titulo_azul1("Editar",1,0,0);
// //$FB->titulo_azul3("Validar",2,0,2,$param_edicion);

// $conde1=""; 
// $conde=""; 

// if($param2!="" and $param1!=""){ 
//  $conde1="and $param1 like '%$param2%' "; 
//   }else { $conde1="  "; } 
  
// if($param1==""){ $param1="ser_prioridad"; } 

// echo$sql="SELECT `idservicios`,`cli_nombre`,`cli_direccion`,`ser_destinatario`,`ciu_nombre`,  `ser_direccioncontacto`, `ser_paquetedescripcion`, `ser_piezas`,`usu_nombre`,`ser_clasificacion`,`ser_consecutivo`,`ser_pendientecobrar`,cli_idciudad,ser_estado,ser_guiare,ser_fechafinal
//  FROM serviciosdia 
//  inner join usuarios on idusuarios=ser_idresponsable  where   ser_idverificadopeso=0 and ser_fechafinal > '2025-01-01 00:00:00'  $conde1 $conde $conde2 $conde3 ORDER BY idservicios,ser_fechafinal asc ";

// $DB->Execute($sql); $va=0; 
// 	while($rw1=mysqli_fetch_row($DB->Consulta_ID))
// 	{
	
// 		//if($rw1[9]!==1 or $rw1[11]==1){
// 		$estado="";
// 		$id_p=$rw1[0];
// 		$va++; $p=$va%2;
// 		if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
		
// 		if($rw1[13]==11){ $color="#ec7878";  $estado="No Entregada"; }
// 		else{ $estado='Sin Pesar'; }
		
// 		echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
// 		$rw1[9]=$tipopago[$rw1[9]];
// 		$planillas=explode("/",$rw1[10]);
// 		$rw1[2]=str_replace("&"," ", $rw1[2]);
// 		$rw1[5]=str_replace("&"," ", $rw1[5]);
		
		
// 		echo "<td>".$rw1[15]."</td>
// 		<td>".$rw1[1]."</td>
// 		<td>".$rw1[2]."</td>
// 		<td>".$rw1[3]."</td>
// 		<td>".$rw1[4]."</td>
// 		<td>".$rw1[5]."</td>
// 		<td>".$rw1[6]."</td>
// 		<td>".$rw1[7]."</td>
// 		<td>".$rw1[8]."</td>
// 		<td>".$rw1[9]."</td>
// 		<td>".$rw1[10]."</td>
// 		<td>".$rw1[14]."</td>
// 		<td>".$estado."</td>
		
// 		";
// 		$sqlrecogida="SELECT ima_ruta,ima_tipo,idimagenguias from imagenguias where ima_idservicio=$id_p ";
// 					$DB1->Execute($sqlrecogida); 
// 					while($guiasi=mysqli_fetch_row($DB1->Consulta_ID))
// 					{

// 						if($guiasi[1]=='Recogida'){
// 							$recogidasg=$guiasi[0];
// 							$idimagenre=$guiasi[2];
// 						}elseif($guiasi[1]=='Entregar' or $guiasi[1]=='Entrega'){
// 							$entrgasg=$guiasi[0];
// 							$idimagenent=$guiasi[2];
// 						}

// 					}
// 		// echo "<td align='center' >";
// 		// echo "<a href='$recogidasg' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia </a>";
		
// 		// $sqlimg="SELECT ser_img_recog,ser_img_entre from servicios where idservicios=$id_p ";
// 		// $DB1->Execute($sqlimg); 
// 		// $img=mysqli_fetch_row($DB1->Consulta_ID);
// 		// if ($img[0]!="") {
// 		// 	echo "<td align='center' >";
// 		// 			echo "<a href='imgServicios/$img[0]' target='_blank'>Ver</td>";
// 		// }else {
// 		// 	echo "<td align='center' >";
// 		// 	echo "</td>";
// 		// }


// 		if($rw1[13]==11){
				
// 			echo "<td align='center' >";
// 		echo "<a  href ='cambio_adminok.php?tabla=devolver&id_param=$id_p';  style='cursor: pointer;' title='Peso' ><img src='img/devolver.png'></a></td>";
		
// 		}else {
			
// 			echo "<td align='center' >";
// 			echo "<a  onclick='pop_general2($id_p,\"validapeso\",$rw1[12])';  style='cursor: pointer;' title='Peso' ><img src='img/peso.png'></a></td>";
		
// 		}
// 		 echo "<td align='center'  data-toggle='tooltip' data-placement='top'  title='' ><a  onclick='generarcodigo($id_p)';  style='cursor: pointer;' title='Imprimir Codigo' >
// 		<img src='img/codigo.png'  target='_blank' ></a>";
// 		echo '</td>'; 

// /* 		echo "<td align='center'  data-toggle='tooltip' data-placement='top'  title='' >
// 		<a  onclick='pop_dis11($id_p, \"Editar Datos Guia\", \"verificarpeso.php\",\"editarguiacompleta.php\",0)';  style='cursor: pointer;' title='Verificar Datos' >
// 		<img src='img/informacion.jpg'></a>";
// 		echo '</td>'; */
		
// 		echo "</tr>"; 
// 		//}
// 	}

// 	$conde33=" ";

// 	if($param3==''){   $conde33=""; } 
// 	else{
// 		$conde33="gas_iduserrecoge=$param3";
// 	}


// 	$FB->titulo_azul1("Sede Origen",1,0,5); 
// 	$FB->titulo_azul1("Sede Destino",1,0,0); 	
// 	$FB->titulo_azul1("Datos TR",1,0,0); 
// 	$FB->titulo_azul1("Tel Conductor",1,0,0); 
// 	$FB->titulo_azul1("Pagar en?",1,0,0); 
// 	$FB->titulo_azul1("Descripcion",1,0,0); 
// 	$FB->titulo_azul1("Peso",1,0,0); 
// 	$FB->titulo_azul1("Piezas",1,0,0); 
// 	$FB->titulo_azul1("Confirmo",1,0,0); 
// 	$FB->titulo_azul1("Valor Aprobado",1,0,0); 
// 	$FB->titulo_azul1("Fecha Confirmacion",1,0,0);
// 	$FB->titulo_azul1("Asigno Recogida",1,0,0);  
// 	$FB->titulo_azul1("Fecha Recogida",1,0,0);  
// 	$FB->titulo_azul1("Operario Recoge",1,0,0); 
// 	$FB->titulo_azul1("Validar",1,0,0); 

// 	$conde11="";
	
// 	$sql2="SELECT `idgastos`, `gas_fecharegistro`, `usu_nombre`, `gas_idciudadori`, `sed_nombre`, `gas_empresa`, `gas_bus`,
// 	`gas_telconductor`,`gas_pagar`,`gas_iduserremesa`, `gas_nomremesa`,`gas_descripcion`,`gas_peso`,`gas_piezas`,`gas_valor`,
// 	 gas_usucom,gas_cantcom,gas_feccom ,gas_idciudaddes,gas_iduserrecoge,gas_recogio,gas_entrego,gas_fecrecogida, `gas_descrecogio`,
// 	`gas_nomvalida`, `gas_fechavalida`
// 	 FROM `gastos` inner join usuarios on gas_idusuario=idusuarios inner join sedes on idsedes=gas_idciudaddes
// 	  WHERE idgastos>0 and gas_iduserrecoge>0  and gas_recogio=1 and gas_nomvalida='' $conde11 $conde22 $conde33 ORDER BY gas_fecrecogida  $asc";
// 	$DB1->Execute($sql2); 
	
// 	while($rw1=mysqli_fetch_row($DB1->Consulta_ID))
// 	{
// 		$estado="";
// 		$id_p=$rw1[0];
// 		$va++; $p=$va%2;
// 		if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}

// 		$sql2="SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes='$rw1[3]'";
// 		$DB->Execute($sql2);
// 		$rw=mysqli_fetch_row($DB->Consulta_ID);
		
// echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";

//   echo "<td>".$rw[1]."</td>
// 		<td>".$rw1[4]."</td>
// 		<td>".$rw1[5] / $rw1[6] ."</td>
// 		<td>".$rw1[7]."</td>
// 		<td>".$rw1[8]."</td>
// 		<td>".$rw1[11]."</td>
// 		<td>".$rw1[12]."</td>
// 		<td>".$rw1[13]."</td>
// 		<td>".$rw1[15]."</td>
// 		<td>".$rw1[16]."</td>
// 		<td>".$rw1[17]."</td>
// 		<td>".$rw1[18]."</td>
// 		<td>".$rw1[22]."</td>";

// 		$sql5="SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE `idusuarios`='$rw1[19]' ";
// 		$DB->Execute($sql5);
// 		$nombreuser=$DB->recogedato(1);
// 		echo  "<td>".$nombreuser."</td>";

// 		echo "<td align='center' >
// 		<a  onclick='pop_dis10($id_p,\"Verificar Remesa\",1)';  style='cursor: pointer;' title='Verificar Remesa' ><img src='img/Confirmar1.png'></a></td>";

// 	echo "</tr>"; 

// 	} 
?>

	<!-- <div id="image-overlay" class="image-overlay">
	<span class="close" onclick="cerrarImagen()">&times;</span>
	<img id="ampliada" class="ampliada" src="" alt="Imagen ampliada">
  	</div> -->

<?php
// include("footer.php");
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php 
require("login_autentica.php"); 
include("layout.php");
?>
  <style>
    body, html {
      height: 100%;
      margin: 0;
    }
    iframe {
      width: 100%;
      height: 100%;
      border: none;
    }
  </style>

<iframe src="https://sistema.transmillas.com/nueva_plataforma/controller/DescargasOficinaController.php?acceso=<?=$nivel_acceso?>&sede=<?=$id_sedes?>" width="100%"
        style="border: none;"></iframe>
<script>
  function ajustarAlturaIframe() {
    const iframe = document.getElementById('iframeClientes');
    if (iframe) {
      iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
    }
  }

  // Ajustar altura cuando el iframe cargue
  document.getElementById('iframeClientes').addEventListener('load', ajustarAlturaIframe);

  // También puedes hacer un ajuste cada X segundos si el contenido cambia
  setInterval(ajustarAlturaIframe, 1000);
</script>