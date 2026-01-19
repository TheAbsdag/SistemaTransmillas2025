<?php 
require("login_autentica.php"); 
include("layout.php");

?>
<html>
<head>
  <link rel="stylesheet" href="css/imprimir.css">
    <style type="text/css" media="print">
    @page 
    {
        size:  auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
    }

    html
    {
        background-color: #FFFFFF; 
        margin: 0px;  /* this affects the margin on the html before sending to printer */
    }

    body
    {
        border: solid 1px blue ;
        margin: 10mm 15mm 10mm 15mm; /* margin you want for the content */
    }
    </style>
</head>
<body>
<?php
  $sql="SELECT `idclientes`,`ser_consecutivo`, `cli_nombre`,  `ser_destinatario`, `ser_telefonocontacto`,`ciu_nombre`,
 `ser_direccioncontacto`, `ser_paquetedescripcion`, `ser_piezas`,`ser_clasificacion`, `ser_valorprestamo`, 
 `ser_valorabono`, `ser_valorseguro`,`ser_resolucion`, `ser_pendientecobrar`,ser_valor,ser_peso,`cli_idciudad`,`ser_ciudadentrega` FROM serviciosdia where idservicios=$id_param ";
$DB->Execute($sql);
$rw=mysqli_fetch_array($DB->Consulta_ID);	

$planillas=explode("/",$rw[1]);
$rw[9]=$tipopago[$rw[9]];
$rw[6]=str_replace("&"," ", $rw[6]);
$seguro=($rw[12]*1)/100;
   // <img src='images/imprimir.png' alt='Logotipo'>
 	echo "
	<div id='imprimir' class='ticket'  >
    <p class='centrado'>REMESA DE TRANSPORTE
      <br>NIT:901089478-8
      <br>RESOLUCI&Oacute;N:$rw[13]
      <br># DE GUIA:$rw[1]
      <br>$fechatiempo</p>
    <table>
      <thead>
        <tr>
        </tr>
      </thead>
      <tbody>
        <tr>";
        echo '<th class="columna1">DESTINATARIO:</th>';
        echo  "<td class='columna2'>$rw[3]</td>
        </tr>
        <tr>
          <th class='columna1'>T&eacute;LEFONO:</th>
          <td class='columna2'>$rw[4]</td>
        </tr>
        <tr>
          <th class='columna1'>CIUDAD:</th>
          <td class='columna2'>$rw[5]</td>
        </tr>
        <tr>
          <th class='columna1'>DIRECCI&oacute;N:</th>
          <td class='columna2'>$rw[6]</td>
        </tr>
        <tr>
          <th class='columna1'>DESCRIPCI&oacute;N:</th>
          <td class='columna2'>$rw[7]</td>
        </tr>
        <tr>
          <th class='columna1'>PIEZAS:</th>
          <td class='columna2'>$rw[8]</td>
        </tr>
        <tr>
          <th class='columna1'>TIPO PAGO:</th>
          <td class='columna2'>$rw[9]</td>
        </tr>
        <tr>
          <th class='columna1'>PRESTAMO:</th>
          <td class='columna2'>$rw[10]</td>
        </tr>
        <tr>
          <th class='columna1'>ABONO:</th>
          <td class='columna2'>$rw[11]</td>
        </tr>
        <tr>
          <th class='columna1'>SEGURO:</th>
          <td class='columna2'>$rw[12]</td>
        </tr> 
		";
		
		if($rw[9]=="Contado" and $rw[14]==0){
		
		 $sql1="SELECT `pre_kilo`,`pre_adicional` FROM `precios` WHERE `pre_idciudadori`=$rw[17]  and `pre_idciudaddes`=$rw[18]  AND pre_estado = 1";
		$DB->Execute($sql1);
		$rw1=mysqli_fetch_row($DB->Consulta_ID); 
		

		
		if($rw[16]>3){ $varor1=$rw1[0]*3;  $valor2=($rw[15]-3)*$rw1[1];  $valortotal=$varor1+$varor1+$seguro;   }
		else { $valortotal=($rw[15]*3)+$seguro;  }
		
		 $sql="UPDATE servicios SET ser_valor=$valortotal WHERE idservicios='$id_param' ";
		$DB1->Execute($sql);	
			
	  echo "<tr>
			  <th class='columna1'>Peso Kg:</th>
			  <td class='columna2'>$rw[16]</td>
			</tr><tr>
			  <th class='columna1'>Valor Kg:</th>
			  <td class='columna2'>$ $rw1[0]</td>
			</tr>
			<tr>
			  <th class='columna1'>1%SEGURO:</th>
			  <td class='columna2'>$seguro</td>
			</tr><tr>
			  <th class='columna1'>Valor ToTal:</th>
			  <td class='columna2'>$ $valortotal</td>
			</tr>";	
			
		}
        echo "<tr>
          <th class='columna1'>CLIENTE:</th>
          <td class='columna2'>$rw[2]</td>
        </tr>
        <tr>
          <th class='columna1'>FIRMA:</th>
          <td class='columna2'></td>
        </tr>
      </tbody>
    </table>
    <p class='centrado'>¡GRACIAS POR SU CONFIANZA!
      <br>El cliente acepta las condiciones de transporte
      <br>consulte nuestra politica 
	  <br>de contrato en www.transmillas.com/politica.php</p>
  </div>"; 
 ?>
</body>

</html>