<?php 
// require("login_autentica.php"); 
// include("layout.php");

// $FB->titulo_azul1("Clientes",9,0,5);  
// $FB->abre_form("form1","clientes.php","post");
// $FB->nuevo2("Cliente", "", "new_cliente.php");

// 	if($param5!=''){  $conde2="and cli_idciudad=$param5"; }  else { $conde2=""; }
// $FB->llena_texto("Ciudad :",5,2,$DB,"(SELECT `idciudades`,`ciu_nombre` FROM ciudades where inner_estados=1 )", "", "$param5", 17, 0);

// //}
// $FB->llena_texto("Busqueda por:",1,82,$DB,$clientes,"",$param1,1,0);
// $FB->llena_texto("Dato:", 2, 1, $DB, "", "","$param2", 4,0);
// $FB->llena_texto("", 3, 142, $DB, "BUSCAR", "","", 1, 0);
// $FB->cierra_form(); 
// if($param1==""){ $param1="cli_nombre"; } 
// $conde1=""; 


//   if($param1=='idclientes'){
// 	$conde1=" and idclientes=$param2";
//   }else if($param2!="" and $param1!=""){ 
// 	$conde1="and $param1 like '%$param2%' "; 
// 	  }else { $conde1="  "; } 

//  $sql="SELECT COUNT(*) FROM `clientes` inner join clientesdir on cli_idclientes=idclientes join ciudades on cli_idciudad=idciudades where cli_principal=1  $conde1 $conde2";
// $DB->Execute($sql); 
// $valor=$DB->recogedato(0);
// if(isset($_REQUEST["CantidadMostrar"])){ $CantidadMostrar=$_REQUEST["CantidadMostrar"]; } else { $CantidadMostrar=50; } 
// //$CantidadMostrar=5;
// $compag =(int)(!isset($_GET['pag'])) ? 1 : $_GET['pag']; 
// $TotalRegistro  =ceil($valor/$CantidadMostrar);

// //echo "<tr class='text'><td align='left'><b>Total Registros: $valor</b></td><td width='100px'  class='text'><b></td><td></tr> ";



// if(isset($_REQUEST["ordby"])){ $ordby=$_REQUEST["ordby"]; } else { $ordby="cli_nombre"; } 
// if(@$_REQUEST["asc"]!=""){ $asc=$_REQUEST["asc"]; } else {$asc="ASC"; } 	$asc2=""; if($asc=="ASC"){ $asc2="DESC";}
// //$condlimit=$FB->llena_sigant($pagina, $ordby, $asc, $valor); 


// $FB->titulo_azul1("#",1,0,7); 
// $FB->titulo_azul1("ID Cliente",1,0,0); 
// $FB->titulo_azul1("CC / Nit",1,0,0); 
// $FB->titulo_azul1("Nombre Del Cliente",1,0,0); 
// $FB->titulo_azul1("Tel&eacute;fonos ",1,0,0); 
// $FB->titulo_azul1("Ciudad ",1,0,0); 
// $FB->titulo_azul1("Direcci&oacute;n",1,0,0);
// $FB->titulo_azul1("Email",1,0,0); 
// $FB->titulo_azul1("Clasificaci&oacute;n",1,0,0); 
// $FB->titulo_azul1("Valor Aprobado",1,0,0); 
// $FB->titulo_azul3("Acciones",2,0,2,$param_edicion);


//   echo$sql="SELECT `idclientes`,`cli_iddocumento`,`cli_nombre`, `cli_telefono`,`ciu_nombre`,`cli_direccion`,`cli_email`,cli_clasificacion,cli_valoraprobado FROM `clientes` inner join clientesdir on cli_idclientes=idclientes join ciudades on cli_idciudad=idciudades where idclientes>0  $conde1 $conde2  ORDER BY  cli_nombre asc LIMIT ".(($compag-1)*$CantidadMostrar)." , ".$CantidadMostrar;

// $DB->Execute($sql); $va=(($compag-1)*$CantidadMostrar); 
// 	while($rw1=mysqli_fetch_row($DB->Consulta_ID))
// 	{
// 		$id_p=$rw1[0];
// 		$va++; $p=$va%2;
// 		if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
// 		echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
// 		$rw1[5]=str_replace("&"," ", $rw1[5]);
// 		$rw1[7]=@$clasificacion[$rw1[7]];
// 		echo "<td>".$va."</td>
// 		<td>".$rw1[0]."</td>
// 		<td>".$rw1[1]."</td>
// 		<td>".$rw1[2]."</td>
// 		<td>".$rw1[3]."</td>
// 		<td>".$rw1[4]."</td>
// 		<td>".$rw1[5]."</td>
// 		<td>".$rw1[6]."</td>
// 		<td>".$rw1[7]."</td>
// 		<td>".$rw1[8]."</td>
// 		";
// 		 $DB1->editar("new_cliente.php",$id_p, "Clientes", 1,2);
// 	}

//  //Operacion matematica para boton siguiente y atras 
// 	$IncrimentNum =(($compag +1)<=$TotalRegistro)?($compag +1):1;
//   	$DecrementNum =(($compag -1))<1?1:($compag -1);
//  // onchange=location.href='http://www.dominio/pagina.php?ref='+this.value
//  $selec200="";
//  $selec50="";
//  $selec100="";
//  $selec10000="";
//  if($CantidadMostrar==50){ $selec50="Selected";} else if($CantidadMostrar==100){ $selec100="Selected";} else if($CantidadMostrar==200){ $selec200="Selected";} else if($CantidadMostrar==10000){ $selec10000="Selected";}
// 	echo "<section class='paginacion'><ul ><li>Mostrar <select onchange=location.href=\"?CantidadMostrar=\"+this.value ><option value='50' $selec50 >50</option><option value='100' $selec100>100</option><option value='200' $selec200>200</option><option value='10000' $selec10000>Todos..</option></select></li><li><a>Total Registros: $valor </a></li><li ><a href=\"?CantidadMostrar=$CantidadMostrar&pag=".$DecrementNum."\" >&#171;&#171;</a></li>";
//     //Se resta y suma con el numero de pag actual con el cantidad de 
//     //numeros  a mostrar
//      $Desde=$compag-(ceil($CantidadMostrar/2)-1);
//      $Hasta=$compag+(ceil($CantidadMostrar/2)-1);
//      //Se valida
//      $Desde=($Desde<1)?1: $Desde;
//      $Hasta=(($Hasta<$CantidadMostrar)?$CantidadMostrar:$Hasta)/10;
//      //Se muestra los numeros de paginas
//      for($i=$Desde; $i<=$Hasta;$i++){
//      	//Se valida la paginacion total
//      	//de registros
//      	if($i<=$TotalRegistro){
//      		//Validamos la pag activo
//      	  if($i==$compag){

//            echo "<li><a href=\"?CantidadMostrar=$CantidadMostrar&pag=".$i."\" class=\"active\">".$i."</a></li>";

//      	  }else {

//      	  	echo "<li><a href=\"?CantidadMostrar=$CantidadMostrar&pag=".$i."\">".$i."</a></li>";

//      	  }     		

//      	}

//      }

// 	echo "<li class=\"active\"><a href=\"?pag=".$IncrimentNum."\">&#187;&#187;</a></li></ul>";



// include("footer.php");
//SELECT ser_idclientes,ser_idservicio,cli_idclientes FROM `servicios` inner join rel_sercli on idservicios=ser_idservicio inner join clientesservicios on ser_idclientes=idclientesdir where ser_guiare='AR29997'
?>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php 
require("login_autentica.php"); 
include("layout.php");
if($rcrear==1) { $FB->nuevo2("Cliente", "", "new_cliente.php"); } 
?>
  <style>
    body, html {
      height: 100%;
      margin: 0;
    }
    iframe {
      width: 100%;
      height: 100vh;
      border: none;
    }
  </style>

<iframe src="/nueva_plataforma/controller/ClientesController.php" width="100%"
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