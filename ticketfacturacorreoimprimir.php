<?php
//  require("login_autentica.php");
require("connection/conectarse.php");
require("connection/arrays.php");
require("connection/funciones.php");
require("connection/funciones_clases.php");
require("connection/sql_transact.php");
require("connection/llenatablas.php");
require("connection/PasswordHash.php");
require("definirvar.php");
date_default_timezone_set("America/Bogota");

include("mpdf2/mpdf.php");

include 'barcode.php';
$DB = new DB_mssql;
$DB->conectar();
$DB1 = new DB_mssql;
$DB1->conectar();
$DB2 = new DB_mssql;
$DB2->conectar();
$tiposervicio = "";
@$pagina2 = $_REQUEST["pagina2"];
@$imprimir = $_REQUEST["imprimir"];
$vis=$_GET['vis'];
?>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script type="text/javascript" src="js/html2canvas.js"></script>
<script type="text/javascript">
  
  var redirecionar = '<?php echo $pagina2;  ?>';

 

  $(function() {
    var factura = document.getElementById('factura').value;
    var idServicio = document.getElementById('idServicio').value;
    var tipo = document.getElementById('tipo').value;
    factura = factura+'_'+tipo+'.jpg';
    
    downloadCanvas('imagen', factura,tipo,idServicio);

    function downloadCanvas(canvasId, filename,tipo,idServicio) {
        var domElement = document.getElementById(canvasId);

        html2canvas(domElement, {
            onrendered: function(domElementCanvas) {
                // Crear un nuevo canvas con fondo blanco
                var canvasWithWhiteBackground = document.createElement('canvas');
                canvasWithWhiteBackground.width = domElementCanvas.width;
                canvasWithWhiteBackground.height = domElementCanvas.height;
                var context = canvasWithWhiteBackground.getContext('2d');
                context.fillStyle = '#ffffff'; // Fondo blanco
                context.fillRect(0, 0, canvasWithWhiteBackground.width, canvasWithWhiteBackground.height);
                context.drawImage(domElementCanvas, 0, 0);

                // Convertir canvas a URL en formato JPEG
                var dataURL = canvasWithWhiteBackground.toDataURL("image/jpeg");

                // Descargar la imagen localmente
                const vis = '<?php echo $vis; ?>';
                console.log(vis);
                if (vis=="adm") {

                }else{

                    var link = document.createElement('a');
                    link.href = dataURL;
                    link.download = filename;
                    link.click();
                }

                // Enviar la imagen al servidor
                // uploadToServer(dataURL, filename,tipo,idServicio,factura);
            }
        });
    }

    function uploadToServer(dataURL, filename,tipo,idServicio,factura) {
        fetch('guardarGuiaImg.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                image: dataURL,
                filename: filename,
                tipo: tipo,
                idServicio: idServicio,
                factura: factura
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Imagen guardada en el servidor:', data);
        })
        .catch(error => {
            console.error('Error al guardar la imagen:', error);
        });
    }
});
</script>
<link rel="stylesheet" href="css/imprimir.css">
<style type="text/css">

  #imagen {
    width: 500px;  
    display: flex; 
    flex-direction: column;
    /* justify-content: center; */
    /* align-items: center; */
    flex-direction: column;
    background-image: url('img/menbrete.jpg');
    background-repeat: no-repeat; 
    height: 3000px;
    transition: transform 0.3s ease-in-out;
    transform-origin: center top;

    /* Evita que la imagen se repita */
   /* background-position: center; 
    /* Centra la imagen */
    /* background-size: cover; Escala la imagen para cubrir el div */

  }
  #white-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #ffffff;
    z-index: -1; /* Asegura que el fondo blanco esté detrás del canvas */
}

.b-what{

  color: white; /* Color del texto */
    border: 2px solid white; /* Color del borde */
    background-color: rgb(21, 148, 64); /* Fondo transparente */
    padding: 10px 20px; /* Espaciado interno */
    font-size: 16px; /* Tamaño del texto */
    cursor: pointer; /* Cambia el cursor al pasar */
    border-radius: 50px; /* Bordes completamente redondeados */
    transition: all 0.3s ease; /* Transiciones suaves */
    box-shadow: 0 4px 6px rgba(255, 255, 255, 0.2); /* Sombra suave */
    letter-spacing: 1px; /* Espaciado entre letras */

}
  /* Efecto al pasar el mouse */
  #guardar:hover {
    background-color: white; /* Fondo blanco */
    color: black; /* Texto negro */
    box-shadow: 0 6px 12px rgba(21, 148, 63, 0.87); /* Sombra más grande */
    transform: scale(1.05); /* Ligeramente más grande */
  }

  /* Estilo al presionar el botón */
  #guardar:active {
    transform: scale(0.98); /* Ligeramente más pequeño */
    box-shadow: 0 3px 6px rgba(21, 148, 63, 0.87); /* Sombra más pequeña */
  }

/* Para el pop Up*/ 
/* Estilos básicos para el pop-up */
body {
            place-items: center;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;

        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .popup {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            position: relative;
        }

        .popup h2 {
            margin-top: 0;
            color: #333;
        }

        .popup p {
            margin: 15px 0;
            color: #555;
        }

        .popup button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .popup button:hover {
            background-color: #45a049;
        }
        /* Clase para imágenes con tamaño de icono */
        .icono {
            width: 25px; /* Ancho del icono */
            height: 25px; /* Alto del icono */
            object-fit: cover; /* Ajusta el contenido dentro del tamaño */
        }


        .zoom-controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .zoom-controls button {
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            
            color: white;
            border: none;
            border-radius: 5px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /*Evita que los botones se escalen con el body*/
        .zoom-controls button {
            transform: none !important;
        }
</style>

<?php

$date = date("Y-m-d");




$sql = "SELECT `idclientes`,`ser_consecutivo`, `cli_nombre`,  `ser_destinatario`, `ser_telefonocontacto`,`ciu_nombre`,
 `ser_direccioncontacto`, `ser_paquetedescripcion`, `ser_piezas`,`ser_clasificacion`, `ser_valorprestamo`, 
 `ser_valorabono`, `ser_valorseguro`,`ser_resolucion`, `ser_pendientecobrar`,ser_valor,ser_peso,`cli_idciudad`,`ser_ciudadentrega`,
 `ser_tipopaq` ,`cli_telefono`, `cli_direccion`,ser_volumen,ser_verificado,ser_prioridad,ser_guiare,ser_estado,ser_devolverreci,ser_fecharegistro,ser_descripcion,cli_iddocumento FROM serviciosdia where idservicios=$id_param ";
$DB->Execute($sql);
$rw = mysqli_fetch_array($DB->Consulta_ID);

if (isset($_GET['peso'])) {
if ($_GET['peso']!="") {
  $rw[16]=$_GET['peso'];
  // echo "$var llegó con el valor: " . $_GET['peso'] . "<br>";
}

} 
if (isset($_GET['volumen'])) {
  if ($_GET['volumen']!="") {
  $rw[22]=$_GET['volumen'];
  }
  // echo "$var llegó con el valor: " . $_GET[$var] . "<br>";
} 
if (isset($_GET['seguro'])) {
  if ($_GET['seguro']!="") {
  $rw[12]=$_GET['seguro'];
  }
  // echo "$var llegó con el valor: " . $_GET[$var] . "<br>";
}
if (isset($_GET['valorf'])) {
  if ($_GET['valorf']!="") {
  $rw[15]=$_GET['valorf'];
  }
  // echo "$var llegó con el valor: " . $_GET[$var] . "<br>";
} 

if ($imprimir == "Entrega" or $imprimir == "Entregar"){
    $sql0 = "SELECT idciudades,inner_sedes FROM `ciudades`  where  ciu_nombre=$rw[5]";
    $DB1->Execute($sql0);
    $rw0 = mysqli_fetch_array($DB1->Consulta_ID);

    $sql2 = "SELECT `idsedes`, `sed_nombre`, `sed_telefono`, `sed_direccion` FROM `sedes` WHERE idsedes=$rw0[1]";
    $DB1->Execute($sql2);
    $rw2 = mysqli_fetch_array($DB1->Consulta_ID);

}elseif ($imprimir == "Recogida") {
    $sql0 = "SELECT idciudades,inner_sedes FROM `ciudades`  where  idciudades=$rw[17]";
    $DB1->Execute($sql0);
    $rw0 = mysqli_fetch_array($DB1->Consulta_ID);

    $sql2 = "SELECT `idsedes`, `sed_nombre`, `sed_telefono`, `sed_direccion` FROM `sedes` WHERE idsedes=$rw0[1]";
    $DB1->Execute($sql2);
    $rw2 = mysqli_fetch_array($DB1->Consulta_ID);
  }

$sql3 = "SELECT ciu_nombre FROM `ciudades`  where idciudades=$rw[17]";
$DB2->Execute($sql3);
$rw3 = mysqli_fetch_array($DB2->Consulta_ID);



$sql5 = "SELECT `cli_iddocumento` FROM `clientes` inner join clientesservicios  on cli_idclientes=idclientes  WHERE cli_telefono='$rw[4]'";
$DB1->Execute($sql5);
$rw5 = mysqli_fetch_array($DB1->Consulta_ID);

$planillas = explode("/", $rw[1]);

@$rw[9] = $tipopago[$rw[9]];
$rw[6] = str_replace("&", " ", $rw[6]);
$rw[21] = str_replace("&", " ", $rw[21]);
$rw[10] = str_replace(".", "", $rw[10]);
$rw[12] = str_replace(".", "", $rw[12]);
$abono = str_replace(".", "", $rw[11]);
$seguro = ($rw[12] * 1) / 100;

if ($rw[26] >= 10) {
  $tipoo = 'Entrega:';
  $sql = "SELECT gui_userecomienda FROM `guias` where gui_idservicio=$id_param ";
  $DB->Execute($sql);
  $usuguia = $DB->recogedato(0);
} else {
  $tipoo = 'Recoge:';
  $sql = "SELECT gui_recogio FROM `guias` where gui_idservicio=$id_param ";
  $DB->Execute($sql);
  $usuguia = $DB->recogedato(0);
}
  $userg = explode(" ", $usuguia);
  $Usuariog = $userg[0] . " " . $userg[1];
  if ($rw[9] == 'Credito') {
    $fechatiempo = $rw[28];
}

// <img src='img/logofactura.png' alt='Logotipo' />

$html = "
	<div id='imprimir' class='ticket'  >
  <div id='blanco'></div>
	
    <p class='centrado'>Transmillas logistica y transportadora S.A.S.
      <br>NIT:901089478-8
      <br>SUCURSAL:  $rw2[1] 
      <br>$rw2[3] 
      <br><img src='img/whatsappp.png' class='icono' >TEL : 310 8093773
     <br>$fechatiempo
     </p>
     <table>
        <tr>
        <td>
				<img src='img/politicaweb.png' alt='politica' style='max-width:100%;width:auto;height:auto;'/>
				</td>
				<td>
           <div style='font-size:25px;text-align:center;' aling=center >DESTINO: $rw[5]</div>
           <div style='font-size:25px;text-align:center;' aling=center >REMESA #: $rw[1] </div>
				</td>
				</tr>
				</table>
     <div style='font-size:25px;'  >$tipoo#: $Usuariog </div>
    <table>
      <thead>
        <tr>
        </tr>
      </thead>
      <tbody>
        <tr>";

$html .=  "<tr><th class='columna1'>REMITENTE:</th>
		<td class='columna2'>$rw[2]</td>
        </tr>
        <tr>
          <th class='columna1'>T&Eacute;LEFONO:</th>
          <td class='columna2'>*******</td>
        </tr>
        <tr>
          <th class='columna1'>CIUDAD:</th>
          <td class='columna2'>$rw3[0]</td>
        </tr>
        <tr>
          <th class='columna1'>DIRECCI&Oacute;N:</th>
          <td class='columna2'>$rw[21]</td>
        </tr>
		<tr>
          <th class='columna1'>CC/NIT:</th>
          <td class='columna2'>$rw[33]</td>
        </tr>
		";
//	$html.=  "<tr><th class='columna1'>---------------------</th><td class='columna2'>--------------------</td></td></tr>";
$html .=  "<tr><th class='columna1'>DESTINATARIO:</th>
		<td class='columna2'>  &nbsp $rw[3]</td>
        </tr>
        <tr>
          <th class='columna1'>T&Eacute;LEFONO:</th>
          <td class='columna2'>*******</td>
        </tr>
        <tr>
          <th class='columna1'>CIUDAD:</th>
          <td class='columna2'>$rw[5]</td>
        </tr>
        <tr>
          <th class='columna1'>DIRECCI&Oacute;N:</th>
          <td class='columna2'>$rw[6]</td>
        </tr>
		<tr>
          <th class='columna1'>CC/NIT:</th>
          <td class='columna2'>$rw5[0]</td>
        </tr>
		";
//	$html.=  "<tr><th class='columna1'>---------------------</th><td class='columna2'>--------------------</td></td></tr>";

$cond = "&#9633";
$cond1 = "&#9633";
if ($rw[23] == 1) {
  $cond = "&#9632;";
} else if ($rw[23] == 0) {
  $cond1 = "&#9632;";
} else {
  $cond = "&#9633;";
  $cond1 = "&#9633;";
}
$credito = $rw[9];


$sqls = "SELECT tip_nom,gui_tiposervicio FROM `tiposervicio` RIGHT join guias  on gui_tiposervicio=idtiposervicio where gui_idservicio=$id_param ";
$DB->Execute($sqls);
$tiposervicios = mysqli_fetch_row($DB->Consulta_ID);
if ($tiposervicios[1] == '1000') {
  $tiposervicio = ' A Convenir';
} else if ($tiposervicios[0] == '' and  ($tiposervicios[1] == "" or $tiposervicios[1] == "0")) {
  $tiposervicio = 'Carga via terrestre';
} else {
  $tiposervicio = $tiposervicios[0];
}

if ($rw[9] == 'Credito') {
  $sqlc = "SELECT rel_nom_credito FROM `rel_sercre` where idservicio=$id_param ";
  $DB->Execute($sqlc);
  $creditouser = $DB->recogedato(0);
  $credito = $rw[9] . "/ " . $creditouser;

}

$sqlc = "SELECT pag_cuenta,pag_nombre,pag_tipopago FROM `pagoscuentas` inner join tipospagos on idtipospagos=pag_tipopago where pag_idservicio=$id_param";
$DB->Execute($sqlc);
$cuenta = mysqli_fetch_row($DB->Consulta_ID);
if ($cuenta != '') {
  $pagoen = "$cuenta[1]/$cuenta[0]";
} else {

  if ($credito == 'Contado') {
    $pagoen = "Efectivo";
  } else {
    $pagoen = "Por Definir";
  }

}

if ($rw[14] == 1) {
  $credito == 'Pendiente por Cobrar';
  $pagoen = "Pendiente por Cobrar";

}

$colorTP="";
$textoTP="";
if ( $credito == 'Pendiente por Cobrar' or $rw[9] == 'Credito' or $rw[9] == "Al Cobro") {
  $colorTP="style='background-color: #e74c3c; border:  border-radius: 10px; padding: 10px;' ";
  $textoTP="<b>Falta pago</b>";
}else if($credito == 'Contado'){
  $colorTP="style='background-color: #27ae60; border: 5px  border-radius: 10px; padding: 10px;' ";
  $textoTP="Pagada";
}

$html .= "
	   <tr>
          <th class='columna1'>TIPO:</th>
          <td class='columna2'>$rw[19]</td>
        </tr>
	   <tr>
          <th class='columna1'>DICE TENER:</th>
          <td class='columna2'>$rw[7]</td>
        </tr>
        <tr>
          <th class='columna1'>PIEZAS:</th>
          <td class='columna2'>$rw[8]</td>
        </tr>
        <tr $colorTP>
          <th class='columna1'>TIPO PAGO:</th>
          <td class='columna2' >$credito  $textoTP</td>
        </tr>
        <tr>
          <th class='columna1'>PAGO EN:</th>
          <td class='columna2'>$pagoen</td>
        </tr>
        <tr>
        <th class='columna1'> SERVICIO:</th>
        <td class='columna2'>$tiposervicio / Entrega de 24-48 horas</td>
        
      </tr>";
      
      // echo"$rw[16] <= $precioinicialkilos";

      if ($rw[16] <=0) {
        $html .= "<tr style='background-color: #e74c3c;  border-radius: 10px; padding: 10px;'>
        <th class='columna1'>PESO:</th>
        <td class='columna2'> <b>No ha sido pesado</b></td>
        </tr>	";
      }else {
        if ($rw[16]>= 30) {
          $html .= "<tr>
            <th class='columna1'>PESO Kg:</th>
            <td class='columna2'>$rw[16]</td>
          </tr>	";
        }

      }

// if ($rw[16] <= $precioinicialkilos) {

// } else {
//   $html .= "<tr>
// 			  <th class='columna1'>PESO Kg:</th>
// 			  <td class='columna2'>$rw[16]</td>
// 		</tr>	";
// }
$html .= "<tr>
			  <th class='columna1'>VOLUMEN:</th>
			  <td class='columna2'>$rw[22]</td>
		</tr>
		<tr>
			  <th class='columna1'>VERIFICADO:</th>
			  <td class='columna2'>SI   $cond NO  $cond1 </td>
    </tr>
		";
$estadopaquete = $rw[29];
$html .= " <tr><td colspan=2 class='columna3' >
<p><b> ¡ESTADO DEL PAQUETE!</strike>
<br>$estadopaquete</b></p>
</td>
</tr>";

$html2 = "";


$sql = "SELECT `pre_porcentaje` FROM `prestamo` WHERE `pre_inicio`<'$rw[10]' and `pre_final`>='$rw[10]'";
$DB->Execute($sql);
$porprestamo = $DB->recogedato(0);

$dosporcentaje = explode(" ", $porprestamo);

if (@$dosporcentaje[1] == '%') {

  $porprestamo = ($rw[10] * @$dosporcentaje[0]) / 100;
}
// echo $porprestamo;
@$totalprestamo = $rw[10] + $porprestamo - $abono;
$totalflete = $rw[15] + $seguro;

if ($rw[26] >= 9 and $rw[9] == 'Contado') {
  $totalfinal = $totalprestamo;
} else {
  if ($rw[16] >= 1 or $tiposervicios[1] == '1000') {
    $totalfinal = $totalflete + $totalprestamo;
  }
}

$totaldevolucion = $totalfinal * -1;
$totaldevolucion = number_format($totaldevolucion, 0, ".", ".");
$totalflete = number_format($totalflete, 0, ".", ".");
$totalprestamo = number_format($totalprestamo, 0, ".", ".");
$totalfinal = number_format($totalfinal, 0, ".", ".");

$porprestamo = number_format($porprestamo, 0, ".", ".");
$seguro = number_format($seguro, 0, ".", ".");
@$abono = number_format($abono, 0, ".", ".");
@$rw[10] = number_format($rw[10], 0, ".", ".");
@$rw[15] = number_format($rw[15], 0, ".", ".");
@$rw[12] = number_format($rw[12], 0, ".", ".");


$html2 .= "<tr>
			  <td colspan=2  class='columna3' >VALOR COMPRA: $ $rw[10]</td>
			</tr>
			<tr>
			  <td colspan=2  class='columna3' >COBRO COMPRA: $ $porprestamo</td>
			</tr>
			<tr>
			  <td colspan=2  class='columna3' >ABONO: $ $abono</td>
			</tr>
			<tr>
			  <td colspan=2  class='columna3'style='font-size:22px;text-align:center;' >TOTAL FLETE + COMPRA:  $totalfinal</td>
			</tr>";

if ($rw[26] >= 6 and $totalfinal < 1) {

  $html2 .= "<tr>
				<td colspan=2  class='columna3'style='font-size:22px;text-align:center;' >DEVOLUCION:  $totaldevolucion</td>
			  </tr>";
}
if ($rw[27] == 1) {
  $html .= " <tr><td colspan=2  class='columna3' style='font-size:22px;text-align:center;'  ><strike><b>¡OJO! DEVOLVER RECIBIDO.</b> </strike> </td></tr>";
}
if ($rw[16] > 0 or $tiposervicios[1] == '1000') {
  $valorflete = $rw[15];
} else {
  $valorflete = 'PENDIENTE POR LIQUIDAR';
}

$html .= " <tr><td colspan=2 class='columna3' >
    <p><b> ¡EL VALOR DECLARADO DEL  ENVIO ES: $ $rw[12]!</strike>
    </td>
    </tr>";
$html .= "
			<tr>
			   <td colspan=2  class='columna3' > VALOR SEGURO: $ $seguro</td>
			</tr>
			<tr>
			  <td colspan=2  class='columna3' > VALOR FLETE: $ $valorflete</td>
			</tr>
			<tr>
			<td colspan=2  class='columna3' style='font-size:22px;text-align:center;' >  TOTAL FLETE: $totalflete</td>
			</tr>
			";

if ($rw[16] <= 0) {
  $html .= " <tr><td colspan=2  class='columna3' >PENDIENTE POR LIQUIDAR EN LA OFICINA. </td></tr>";
}

$html .= " <tr><td colspan=2 class='columna3' >
<p> ¡GRACIAS POR SU CONFIANZA!
<br>El cliente acepta las condiciones de transporte
<br>consulte nuestra politica de contrato en www.transmillas.com/politica.php</p>
</td>
</tr>";
$html .= "
<tr>
  <td colspan=2 class='columna3'>
    <p style='color:#c0392b; font-weight:bold; font-size:14px; line-height:1.4;'>
      <br>
      Aviso: Transmillas no se hace responsable por daños o suciedad en <br> mercancías sin embalaje 
      (ejemplo: colchones, vidrios u objetos <br> frágiles). El embalaje es responsabilidad del cliente.
    </p>
  </td>
</tr>";



if ($imprimir == "Recogida") {

  $sql1 = "SELECT firma,`nombre`, `numero_documento`,`correo_electronico`, `telefono`,tipo,firma_clientes FROM firma_clientes WHERE tipo_firma = 'Recogida' and id_guia='$id_param' order by id desc limit 1";
  $resultado = $DB1->Execute($sql1);
  $fila = mysqli_fetch_assoc($resultado);
  $imagen = $fila['firma'];
  $tipo = $fila['tipo'];
  $telefono1 = $fila['telefono'];



  if ($fila['firma_clientes']=="") {
    if ($tipo == 'imagen') {

      if($enviarcorreo!=2 and $enviarcorreo!=3){
        // Codificar el contenido del ArrayBuffer como una cadena base64
        $imagen_base64 = 'tmp_img/imagen_' . $rw[1] . '.png';
      }else{
            // Codificar el contenido del ArrayBuffer como una cadena base64
            $base64Data = base64_encode($imagen);
            // Construir el data URL
            $imagen_base64 = 'data:image/png;base64,'. $base64Data;
      }

    } else {

      // Decodificar los datos base64
      $imagen_base64 = $imagen;
    }

  }else {
    $imagen_base64 = $fila['firma_clientes'];
  }

  $nombre = $fila['nombre'];
  $documento = $fila['numero_documento'];
  $telefono = $fila['telefono'];
  $correo = $fila['correo_electronico'];
  if($vis!='peso'){ 
        $html .= "<tr><td colspan=2 class='columna3'>
          <div style='display: inline-block; text-align: left;'>
              <div style='width:500px;height:500px; overflow: hidden; border: 1px solid black;'>
                  <img src='$imagen_base64' style='width: 500px; height:500px;' alt='Firma'/>
              </div>
              <div>FIRMA ENTREGA</div>
          </div>
          <div>QUIEN ENTREGA: $nombre</div>
          <div>CC/NIT: $documento</div>
          <div>Teléfono: $telefono</div>
      </td></tr>";
    } 
}

if ($imprimir == "Entrega" or $imprimir == "Entregar") {


  $sql1 = "SELECT firma,`nombre`, `numero_documento`,`correo_electronico`, `telefono`,tipo,firma_clientes FROM firma_clientes WHERE tipo_firma = 'Entrega' and id_guia='$id_param' order by id desc limit 1";
  $resultado = $DB1->Execute($sql1);
  $fila = mysqli_fetch_assoc($resultado);
  $imagen = $fila['firma'];
  $tipo = $fila['tipo'];
  $telefono1 = $fila['telefono'];

  if ($fila['firma_clientes']=="") {
    if ($tipo == 'imagen') {

      if($enviarcorreo!=2 and $enviarcorreo!=3){
        // Codificar el contenido del ArrayBuffer como una cadena base64
        $imagen_base64 = 'tmp_img/imagen_' . $rw[1] . '.png';
      }else{
            // Codificar el contenido del ArrayBuffer como una cadena base64
            $base64Data = base64_encode($imagen);
            // Construir el data URL
            $imagen_base64 = 'data:image/png;base64,'. $base64Data;
      }



    } else {

      // Decodificar los datos base64
      $imagen_base64 = $imagen;
    }
  }else {
    $imagen_base64 = $fila['firma_clientes'];
  }
  

  $nombre = $fila['nombre'];
  $documento = $fila['numero_documento'];
  $telefono = $fila['telefono'];
  $correo = $fila['correo_electronico'];

  $html .= "<tr><td colspan=2 class='columna3'>
    <div style='display: inline-block; text-align: center;'>
    <div style='width:500px; height:500px; overflow: hidden; border: 1px solid black;'>
    <img src='$imagen_base64' style='width:500px; height:500px;' alt='Firma'/>
        </div>
        <div>FIRMA RECIBE</div>
    </div>
    <div>QUIEN RECIBE: $nombre</div>
    <div>CC/NIT: $documento</div>
    <div>Teléfono: $telefono</div>
</td></tr>";

}


$html .= $html2;
$html .= "</tbody>
		</table>
		</br>
		</br>
		
		 </div>";

// if ($cuenta != '') {
//   $id_p = $cuenta[2];
//   $sql1 = "SELECT doc_ruta FROM documentos WHERE doc_idviene='$id_p' AND doc_tabla='tipospagos' ORDER BY doc_fecha DESC limit 1";
//   $DB1->Execute($sql1);
//   $ruta = $DB1->recogedato(0);

//   if ($ruta != '') {
//     $html .= "<div style='text-align: center;'>
//           <tr><td colspan=2 class='columna3' >
//           <p><b> ¡PAGUE ESCANEANDO El QR!</strike>
//           </td>
//           </tr>
//           <tr><td colspan=2 class='columna3' >
//             <img src='$ruta' style='width:216px;height:216px;' alt='metodopago'>
//             </td>
//             </tr>
//           </div>";
//   }
// }
$html .= "<div style='text-align: center;'>
            <tr><td>
            <p><b>¡PAGUE SOLO POR NUESTROS MEDIOS AUTORIZADOS!</b></p>
            </td>
            </tr>
            <tr><td>
            <p><b><img src='img/bancolombia.png' style='width:50px;height:50px;' ></b></p>
              
              </td>
              <td><p><b></b></p></td>
            </tr>
            <tr><td>
            <p><b></b></p>
              
              </td>
              <td><p><b><img src='img/superTrans.png' style='width:300px;height:100px;' ></b></p></td>
            </tr>
          </div>";


$code = $rw[1];
$data = $code;

// barcode('phpqrcode/temp/' . $code . '.png', $code, 60, 'horizontal', 'code128', true);

// $html .= "
// <div style='text-align: center;'>
// <img src='phpqrcode/temp/$code.png' style='width:440px; height:200px;' alt='codigobarras'>
// </div>";


?>

<body>
<input id="idServicio" name="idServicio" type="hidden" value="<?php echo $id_param; ?>">
  <input id="factura" name="factura" type="hidden" value="<?php echo $rw[1]; ?>">
  <input id="tipo" name="tipo" type="hidden" value="<?php echo $imprimir; ?>">
  <div id="imagen">
  
  <div id="loading" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
    <img src="img/enviando.gif" alt="Cargando..." />
</div>

    <?php
    if($enviarcorreo!=3){
       echo $html;
    }

    if($enviarcorreo!=2 and $enviarcorreo!=3){
          // Definir los destinatarios y demás datos necesarios
          $correo = $correo . ',transmillasremesas@yahoo.com';

          $usu_mail = explode(',', $correo); // Direcciones de correo electrónico de los destinatarios
          $archivo = ''; // Ruta del archivo adjunto, si lo hay
          // $mensaje = $html; // Cuerpo del correo (en este caso, el HTML generado)
          $mensaje = "Estimad@/ $nombre

              Espero este mensaje le encuentre bien. Queremos informarle que hemos procesado su solicitud y hemos adjuntado la Remesa/soporte correspondiente al envío de su paquete con Transmillas.
              Por favor, encuentre el archivo adjunto que contiene su Remesa/soporte. Revíselo detenidamente para asegurarse de que toda la información esté correcta. Si encuentra algún error o tiene alguna pregunta, no dude en ponerse en contacto con nuestro equipo de atención al cliente.
              Recuerde que puede contar con nosotros para cualquier consulta adicional que pueda surgir. Nuestro objetivo es brindarle un servicio de calidad y asegurarnos de que su experiencia con nosotros sea satisfactoria en todo momento.
              Agradecemos su confianza en Transmillas para el envío de su paquete y esperamos seguir siendo su elección preferida en el futuro.
            
              ¡Que tenga un excelente día!
            
              Atentamente,
              Transmillas
              
              Este correo es solo informativo. Por favor, no responda a este mensaje. Cualquier duda, por favor ingrese a la página de transmillas.com.
              ";

          $asunto = 'Remesa #' . $rw[1]; // Asunto del correo
          $mpdf = new mPDF('c','A1');
          $mpdf->dpi = 96;
          $mpdf->img_dpi = 96;

          
        // Habilitar la inclusión de imágenes
        $mpdf->SetImportUse();
        
        // Agregar una página con el ancho especificado y un tamaño de largo suficientemente grande
        $width = 210; // La mitad del ancho de una hoja A4 en milímetros
        $height = 5000; // Un largo suficientemente grande
        
        $mpdf->AddPage('P', // 'P' para orientación vertical, 'L' para orientación horizontal
            '', // Tipo de hoja personalizado
            '', // Tamaño de fuente
            '', // Margen izquierdo
            '', // Margen derecho
            '', // Margen superior
            '', // Margen inferior
            $width // Ancho de la página
        );

          // $mpdf->WriteHTML($html);

          // $pdf_temp_path = 'remesaticket' . $rw[1] . '.pdf';

          // $mpdf->Output($pdf_temp_path, 'F');

          $mpdf->WriteHTML($html);

          // Define la carpeta donde se guardará el PDF
          $folder_path = 'Guias/'; // Reemplaza con la ruta real de tu carpeta en el servidor

          // Asegúrate de que la carpeta exista y tenga permisos de escritura
          if (!is_dir($folder_path)) {
              mkdir($folder_path, 0777, true); // Crea la carpeta si no existe
          }

          // Nombre del archivo PDF
          $pdf_temp_path = $folder_path . $imprimir.'remesaticket' . $rw[1] . '.pdf';

          // Guarda el PDF en la carpeta
          // $mpdf->Output($pdf_temp_path, 'F');
        
            // Llamar a la función para enviar el correo
            // enviar_mail_con_pdf($usu_mail, $pdf_temp_path, $mensaje, $nombre, $asunto, $imagen_base64); 
            
            
    }
       // Configurar la zona horaria de Bogotá
       date_default_timezone_set("America/Bogota");

       // Obtener el mes y el año
       $mes = date("m"); // Formato numérico (01-12)
       $anio = date("Y"); // Año completo (ejemplo: 2025)
   
       // Ruta donde guardar la imagen
      //  echo$filePath = 'imagesguias/guias_'.$mes.'_'.$anio.'/' . $filename;
    $imagen1 = "imagesguias/guias_".$mes."_".$anio."/".$code."_".$imprimir.".jpg";
    
  
  
  //  }	
       // Redirigir a otra página
   // header("Location: $pagina2?&bandera=$bandera");
   $telefonos = [$telefono1, $rw[4], $rw[20]];
   $telefonosJson = json_encode($telefonos);
    ?>
  </div>
  <div class="zoom-controls">
        <button onclick="zoomOut()">➖</button>
        <button onclick="zoomIn()">➕</button>
  </div>
 
</body>


    <script>
        let scale = 1;

        function zoomIn() {
            scale += 0.1;
            document.getElementById("imagen").style.transform = `scale(${scale})`;
        }

        function zoomOut() {
            if (scale > 0.5) {
                scale -= 0.1;
                document.getElementById("imagen").style.transform = `scale(${scale})`;
            }
        }
    </script>

