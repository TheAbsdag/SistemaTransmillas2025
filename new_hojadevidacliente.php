<?php 
require("login_autentica.php"); 
include("layout.php");
include("cabezote3.php"); 
@$accion=$_REQUEST["accion"];
 $id_nombre=$_SESSION['usuario_nombre'];
 $DB = new DB_mssql;
 $DB->conectar();  
 
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<script>
function subirimagen(imagen) {

  
    var textoSubiendo = "Cargando imágen";
    var btnEnviar = $("imagen1");
    var textoSubir = btnEnviar.text();
    alert('okkk');
    //var enviarimagen=document.getElementById(imagen).value;
    var formData = new FormData();
        var files = $('#'+imagen)[0].files[0];
        formData.append('file',files);

      //enviarimagen=append("imagen",$("input[name="+imagen+"]")[0].files[0]);
      	datos = {"condecion":"subirimagen","imagen":formData};

        $.ajax({
            url: 'subirimagenes.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function (data) {
                btnEnviar.html(textoSubiendo);
                btnEnviar.attr("disabled",true);
            },
            success: function (data) {
                btnEnviar.html(textoSubir);
                btnEnviar.attr("disabled",false);
            }
        });
        return false;
   
}


</script>


 <style>
input[type="submit"] {
  background: #d81921;
  color: #fff;
  display: block;
  margin: 0 auto;
  padding: 10px 0;
  width: 200px;
  border: none;
  border-radius: .5rem;
}

span.spinner-border {
  position: absolute;
    top: 5px;
    left: 260px;
}


</style>
<?php 
@$idhojadevida=$_REQUEST["idhojadevida"];
@$id_param=$_REQUEST["id_param"];
//echo $condecion;
if($idhojadevida==''){
	$idhojadevida=$id_param;
}

if($accion!=1){

	
	 $sql="SELECT `idhojadevida`,`hoj_sede`,  `hoj_fechaingreso`,  `hoj_nit`,`hoj_cedula`, `hoj_nombre`, `hoj_razonsocial`,`hoj_tipocliente`,`hoj_direccionrf`,`hoj_ciudad`, `hoj_email`, `hoj_telefono1`, `hoj_telefono2`,   `hoj_clientecredito`, `hoj_fechanaradicacion`, `hoj_fechanacorte`, `hoj_numerocuenta`, `hoj_plazopago`, `hoj_novedadesfactura`,hoj_cedula_rpl,`hoj_estado`, `hoj_fechacreacion`,hoj_mas_docs,hoj_nom_id FROM `hojadevidacliente`  where idhojadevida='$idhojadevida'";		
	$DB1->Execute($sql);
	$rw=mysqli_fetch_row($DB1->Consulta_ID);
	$id_sedes=$rw[2];

echo '<tr><td>
<div class="btn-group">';
  echo "<button type='button' class='btn btn-primary'  onclick=\"window.location='new_hojadevidacliente.php?condecion=datospersonales&idhojadevida=$idhojadevida'\" >Datos Personales</button>";
  echo "<button type='button' class='btn btn-primary'  onclick=\"window.location='new_hojadevidacliente.php?condecion=datoscontacto&idhojadevida=$idhojadevida'\" >Contacto Facturacion</button>";
  echo "<button type='button' class='btn btn-primary'  onclick=\"window.location='new_hojadevidacliente.php?condecion=datosfacturacion&idhojadevida=$idhojadevida'\" >Facturacion</button>";
  echo "<button type='button' class='btn btn-primary'  onclick=\"window.location='new_hojadevidacliente.php?condecion=documentos&idhojadevida=$idhojadevida'\" >Documentos</button>";
 echo '
</div>
<tr><td>';

$id_p=$rw[0];

} 


//echo "wwwwwwwwwwwww".$rw[2];
$FB->abre_form("form","newhojadevidaclienteok.php","post");

$FB->titulo_azul1("Hoja de Vida Clientes",10,0, 7);  

if($rutafoto!=''){


	echo "<tr><td><img src='$rutafoto' class='img-circle' alt='User Image' style='background-color:#FF0000;width:80px;height:80px' /><td>
	<td>
	Nombre: $rw[3]"."<br>
	</td>
	</tr>";

}
switch ($condecion)
{


case "datospersonales":

	
$FB->titulo_azul1("Datos Personales",10,0, 5);  
$FB->llena_texto("Logo:", 101, 6, $DB, "", "", "",1, 0);

echo "<td>Logo </td>".$LT->llenadocs3($DB1, "hojadevidaclientes",$id_p, 1, 35, 'Ver');
echo "</tr>";
if($rw[1]>0 and $nivel_acceso!=1){ $habi=2; }else{ $habi=1;}
$FB->llena_texto("Sede :",1,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0)", "", "$rw[1]", 17, 1);
$FB->llena_texto("Fecha de ingreso:", 2, 10, $DB, "", "", "$rw[2]", 4,$habi);
$FB->llena_texto("Nit:",3, 1, $DB, "", "", "$rw[3]", 1, 1);
$FB->llena_texto("Cedula:",4, 1, $DB, "", "", "$rw[4]", 4, 1);
$FB->llena_texto("Nombre:",5, 1, $DB, "", "", "$rw[5]", 17, 1);
$FB->llena_texto("Razon Social:",6, 1, $DB, "", "", "$rw[6]", 4, 1);
$FB->llena_texto("Tipo Cliente:",7, 83, $DB, $tipocliente, "", "$rw[7]", 1, 1);
$FB->llena_texto("Direccion de Radicado:",8, 1, $DB, "", "", "$rw[8]", 4, 1);
$FB->llena_texto("Ciudad:",9, 1, $DB, "", "", "$rw[9]", 17, 1);
$FB->llena_texto("Correo:",10, 111, $DB, "", "", "$rw[10]", 4, 1);
$FB->llena_texto("Telefono Cliente:", 11, 1, $DB, "", "", "$rw[11]", 1, 0);
$FB->llena_texto("Telefono 2:", 12, 1, $DB, "", "", "$rw[12]", 4, 0);
$FB->llena_texto("Credito:",13,2, $DB, "(SELECT `idcreditos`,`cre_nombre` FROM `creditos`)", "", "$rw[13]", 17, 0);
$FB->llena_texto("Cedula Representante Legal:",14, 1, $DB, "", "", "$rw[19]", 4, 1);
$FB->llena_texto("Nombre de identificador:",15, 1, $DB, "", "", "$rw[23]", 4, 0);

$caso='datoscontacto';
$FB->cierra_tabla(); 			
break;
case "datoscontacto":

	include('contactofacturacion.php');
	
	$FB->cierra_tabla(); 		
	$caso='datosestudios';		
break;
case "datosfacturacion":

	
	$FB->llena_texto("Fecha Facturacion:",1, 10, $DB, "", "", "$rw[14]", 1, 1);
	$FB->llena_texto("Fecha Corte:",2, 10, $DB, "", "", "$rw[15]", 1, 1);
	$FB->llena_texto("Numero De Cuenta:",3, 1, $DB, "", "", "$rw[16]", 1, 0);
	$FB->llena_texto("Plazo Pago Cliente:", 4, 83, $DB, $plazocliente, "", "$rw[17]", 1, 1);
	$FB->llena_texto("Novedades de Factura:",5, 1, $DB, "", "", "$rw[18]", 1, 0);

  
  if ($rw[19]!="") {
    
  }

$caso='documentos';
break;
case "documentos":

	include('subirarchivohojas.php');

$caso='documentos';


$FB->cierra_tabla(); 			
break;

}

echo '
<input type="hidden" name="id_param" id="id_param" value="'.$id_param.'">
<input type="hidden" name="id_param0" id="id_param0" value="'.$rw[0].'">
<input type="hidden" name="accion" id="accion" value="'.$accion.'">
<input type="hidden" name="condecion" id="condecion" value="'.$condecion.'">
<input type="hidden" name="idhojadevida" id="idhojadevida" value="'.$idhojadevida.'">
</table>';
echo '</div>';


	echo "<tr bgcolor='#F5F5F5'><td align='center' colspan='4'><input class='btn btn-primary btn-sm' data-widget='edit' data-toggle='tooltip' type='' 
	onClick='javascript:history.back();' value='Atras' style='width:190px;'>
	<input class='btn btn-primary btn-sm' data-widget='edit' data-toggle='tooltip' type=''  onclick=\"window.location='hojadevidaclientes.php?'\" value='Cerrar' style='width:190px;'>			
	<input class='btn btn-primary btn-sm' data-widget='edit' data-toggle='tooltip' type='submit'  name='enviar' value='Guardar-Siguiente' style='width:190px;' >
	<span class='spinner-border' role='status' aria-hidden='true'></span>
	</td></tr>";

	echo '<div class="preloader-wrapper big active">
	<div class="spinner-layer spinner-red-only">
	  <div class="circle-clipper left">
		<div class="circle"></div>
	  </div>
	  <div class="gap-patch">
		<div class="circle"></div>
	  </div>
	  <div class="circle-clipper right">
		<div class="circle"></div>
	  </div>
	</div>
  </div>';

include("footer.php");
?>
<script>
	function sendData(checkbox) {
    // Verifica si el checkbox está marcado o desmarcado
    let isChecked = checkbox.checked;
    let id = checkbox.id;

    // Dato a enviar (puede ser el id del checkbox u otro dato)
    let dataToSend = {
        id: id,
        checked: isChecked
    };

    // Envío de la solicitud AJAX
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "consuHvC.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Maneja la respuesta si es necesario
            console.log(xhr.responseText);
        }
    };

    // Convertimos el objeto a un JSON string y lo enviamos
    xhr.send(JSON.stringify(dataToSend));
}
</script>