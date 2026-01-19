
<?php
require("login_autentica.php");
include("cabezote3.php");

$descargar=$_GET['variable'];
// $nombre = $_GET['valor7'];
$param33 = $_GET['param33'];

$param34 = $_GET['param34'];

$param35 = $_GET['param35'];

$param36 = $_GET['param36'];

$param37 = $_GET['param37'];
?>
<style>
    /* Estilos básicos para la barra superior */
    .topbar {
        background-color: #333;
        color: #fff;
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Estilos para el menú desplegable */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #ddd;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }


    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
    }

    /* Estilos para la tabla */
    .tabla {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #ddd; /* Borde delgado de un solo color */
    }

    .tabla th,
    .tabla td {
        padding: 12px 15px;
        text-align: center;
        border: 1px solid #ddd; /* Borde delgado de un solo color */
    }

    .tabla th {
        background-color: #367fa9;
        color: #fff;
        font-weight: bold;
    }

    /* .tabla tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    } */

    .tabla tbody tr:hover {
        background-color: #ddd;
    }
    summary::-webkit-details-marker {
    color: red; /* Cambiar el color de la flecha */
    }
</style>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

function descargar(p3,p4,p5,p6,p7){

var variable = "descargar"; // Variable que deseas enviar

// Codificar la variable
var variableCodificada = encodeURIComponent(variable);

// Abrir el enlace en una nueva página
window.open("excelDeNomina.php?variable="+variableCodificada+"&param33="+p3+"&param34="+p4+"&param35="+p5+"&param36="+p6+"&param37="+p7, "_blank");
console.log('descargando');
    

}
</script>
<div id="loader">
        <img src="img/loading.gif" alt="Cargando...">
    </div>
<?php
error_reporting(0);




$año=date('Y');

$NombreExcel = "Nomina ".$param36." del mes ".$param34." del  Año ".$año."";


if ($descargar=="descargar") {
header('Content-type:application/xls');
// header('Content-Disposition: attachment; filename='.$nombre.'.xls');
header('Content-Disposition: attachment; filename='.$NombreExcel.'.xls');
}

$asc="ASC";









// if($nivel_acceso==1 or $nivel_acceso==12){
// $FB->titulo_azul1("Eliminar",1,'5%',0);
// }
$asc="ASC";
$conde=" ";
$conde2=" ";
$conde3=" ";
$conde4=" ";
$conde5=" ";

if($param34!=''){ $fechaactual=$param34; }

if($param35!=''){ $id_sedes=$param35;

	$conde4=" and hoj_sede=$id_sedes ";
}
if($param33!=''){
    $cedula="SELECT `usu_identificacion` FROM `usuarios` WHERE `idusuarios`='$param33' ";
    $DB1->Execute($cedula);
    $CedulaUser=$DB1->recogedato(0);

$conde="and `hoj_cedula`= '$CedulaUser' "; }


$conde3="";
$ano=$param39;
if($param34!=''){ $fechaactual=$param34." 00:00:00";  }
if($param36!=''){ $fechafinal=$param36." 23:59:59";  }


if($param36=='Primera'){
	$fechaactual=date($ano.'-'.$param34.'-01'.' 00:00:00');
	$fechafinal=date($ano.'-'.$param34.'-15'.' 23:59:59');
	$diasDeLaQuincena=15;
	$fechaactualSinTiempo=date($ano.'-'.$param34.'-01');
	$fechafinalSinTiempo=date($ano.'-'.$param34.'-15');

}elseif($param36=='Segunda'){
	// $fin = date("t");

	$fecha_aux = date($ano.'-'.$param34.'-d'); // Obtener la fecha actual en formato 'YYYY-MM-DD'
	$fin = date('t', strtotime($fecha_aux));

	$fechaactual=date($ano.'-'.$param34.'-16'.' 00:00:00');
	$fechafinal=date($ano.'-'.$param34.'-'.$fin.' 23:59:59');
	$diasDeLaQuincena=$fin-15;
	// echo "Aquincena   tiene $diasDeLaQuincena días.";

	$fechaactualSinTiempo=date($ano.'-'.$param34.'-16');
	$fechafinalSinTiempo=date($ano.'-'.$param34.'-'.$fin);
}elseif($param36=='Completo'){

	// $fin = date("t");

	$fecha_aux = date($ano.'-'.$param34.'-d'); // Obtener la fecha actual en formato 'YYYY-MM-DD'
	$fin = date('t', strtotime($fecha_aux));

	$fechaactual=date($ano.'-'.$param34.'-01'.' 00:00:00');
	$fechafinal=date($ano.'-'.$param34.'-'.$fin.' 23:59:59');
	$diasDeLaQuincena=$fin-15;
	// echo "Aquincena   tiene $diasDeLaQuincena días.";

	$fechaactualSinTiempo=date($ano.'-'.$param34.'-16');
	$fechafinalSinTiempo=date($ano.'-'.$param34.'-'.$fin);



}


echo'<input id="fechaini" type="hidden" value="'.$fechaactual.'">';
echo'<input id="fechafin" type="hidden" value="'.$fechafinal.'">';
echo'<input id="usuConfirma" type="hidden" value="'.$fechaactual.'">';

//cuantos dias tiene la quincena
        // Creamos un objeto DateTime a partir de la cadena de fecha
        $fecha1 = new DateTime($fechaactual);

        // Obtenemos la fecha completa sin la parte del tiempo
        $fechaSinTiempo1 = $fecha1->format('Y-m-d');

        // Creamos un objeto DateTime a partir de la cadena de fecha
        $fecha2 = new DateTime($fechafinal);

        // Obtenemos la fecha completa sin la parte del tiempo
        $fechaSinTiempo2 = $fecha2->format('Y-m-d');


$sql1="SELECT `nome_id`, `nome_fechaIni`, `nome_fechaFin`, `nome_confirmaParafi`, `nome_fotoParafiscales` FROM `NominasEmpresa` WHERE nome_fechaIni='$fechaSinTiempo1' and nome_fechaFin='$fechaSinTiempo2' ";
$DB1->Execute($sql1);
$rw1=mysqli_fetch_row($DB1->Consulta_ID);


if ($rw1[3]=="si" or $rw1[3]=="Si") {
    
    $si="selected";
}else if ($rw1[3]=="no"){
    $no="selected";
}
if ($rw1[4]=="" ) {
    
    $foto="";
}else{ 
    $foto="<a href='img_nomina/$rw1[4]' target='_blank' style='color: #FFFFFF;'>Ver Imagen</a>";
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?if ($descargar!="descargar") {?>
<div class="topbar">
    <!-- Menú desplegable -->
    <div class="dropdown">
        <label>Confirmar</label>
    <select  style=''  id='confirma' onchange='nomParafiscales(this.value)' class='borrar' required>";
    <option value='' >Seleccionar...</option>
			<option value='si' <?echo$si;?>>SI</option>
			<option value='no' <?echo$no;?> >NO</option>


			</select>
    </div>
    <div class="dropdown">
    <!-- Campo de entrada de archivo -->
    <input type="file" id="imagenInput">
    <button onclick="guardarImagenPagoPara()">Guardar Imagen</button>
    <label><?echo$foto;?></label>
    </div>
    <!-- Botones para guardar imagen y descargar -->

    <button onclick="descargar('<?echo$param33;?>','<?echo$param34;?>','<?echo$param35;?>','<?echo$param36;?>','<?echo$param37;?>')">Descargar</button>
</div>
<? }?>
<?php




echo'<table  class="tabla">';

echo'<tr bgcolor="#074F91" style="color:#FFFFFF;">';
echo'<th colspan="18">TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A</th>';

echo'</tr>';

echo'<tr bgcolor="#074F91" style="color:#FFFFFF;">';
echo'<th colspan="20">NIT. 901.089.478-8</th>';

echo'</tr>';
echo'<tr bgcolor="#074F91" style="color:#FFFFFF;">';
echo'<th colspan="20">NOMINA '.$param36.' del mes '.$param34.' del  A&ntilde;o '.$ano.' </th>';

echo'</tr>';
echo'<tr bgcolor="#074F91" style="color:#FFFFFF;">';
echo'<th colspan="6" bgcolor="#FFFFFF"></th>';
echo'<th colspan="4">DEVENGOS</th>';
echo'<th colspan="8">DEDUCCIONES</th>';
echo'</tr>';


echo'<tr bgcolor="#074F91" style="color:#FFFFFF;">';
echo'<th></th>';

echo'<th>Nombres</th>';
echo'<th>Apellidos</th>';
echo'<th>No Cedula</th>';
// echo'<th>No Cuenta</th>';
echo'<th>Salario Basico</th>';
echo'<th>Dias</th>';
echo'<th>Sueldo</th>';
// echo'<th>Aux. Transporte</th>';
echo'<th>Incapac 66,67%</th>';
echo'<th>Vacaciones</th>';
echo'<th>Licencias</th>';
echo'<th>Total devengado</th>';
echo'<th>Fecha parafiscales</th>';
echo'<th>Salud</th>';

echo'<th>Pension</th>';
echo'<th>Dias no trabajados </th>';
echo'<th>Total deduccion</th>';
echo'<th>Descuentos</th>';
echo'<th>Valor quincena a pagar</th>';
echo'<th>Inicio contrato</th>';
echo'<th>Termina contrato</th>';
echo'</tr>';

// echo$fechas=$fechaactual."/".$fechafinal;
// echo $fechafinal;
// echo $fechaactual;

if($param37!=''){
	if($param37=='0') {
		$conde5="and hoj_tipocontrato='Empresa'";
	}else{
		$conde5=" and hoj_tipocontrato='$param37'";
	}
}else{$conde5=" and hoj_tipocontrato='Empresa'";}



$salarioBasicoTotalTodos=0;
$diasTotalesTodos=0;
$sueldoTodos=0;
$auxTransTodos=0;
$totalDevengTodos=0;
$saludTodos=0;
$pensionTodos=0;
$totaldeduccionesTodos=0;
$valorAPagarTodos=0;



if($param37=="Prestacion de Servicios"){


  }else{




if($param34 == 2 and $param36=='Segunda'){
    if($fin==29){
        $diasParaSumar=1;

    }else{
        $diasParaSumar=2;
    }


}else{

    $diasParaSumar=0;
}



//echo$sql="SELECT  `idhojadevida`,  `hoj_nombre`, `hoj_apellido`,hoj_cargo, `hoj_tipocontrato`,`hoj_cedula`,`hoj_fechaingreso`, `sed_nombre`,`hoj_fechanacimiento`, `hoj_cedula`,`hoj_direccion`, `hoj_celular`, `hoj_estado` FROM `hojadevida`  inner join sedes on hoj_sede=idsedes   where idhojadevida>0  and hoj_estado='Activo'   order by hoj_nombre asc ";
$sql="SELECT `idhojadevida`,  `hoj_nombre`, `hoj_apellido`,hoj_cargo, `hoj_tipocontrato`,`hoj_cedula`,`hoj_fechaingreso`, `sed_nombre`,`hoj_fechanacimiento`, `hoj_cedula`,`hoj_direccion`, `hoj_celular`, `hoj_estado`,hoj_sede,hoj_fechatermino,hoj_cuen,hoj_tcuenta,hoj_firma,hoj_estado FROM hojadevida
INNER JOIN sedes ON hoj_sede = idsedes
WHERE (idhojadevida > 0 ) $conde5 $conde4 $conde
ORDER BY hoj_nombre ASC";
$DB->Execute($sql); $va=(($compag-1)*$CantidadMostrar);
  while($rw1=mysqli_fetch_row($DB->Consulta_ID))
  {
    $totaldevengado=0;
    $totaldeduccion=0;
    $fechafin=$fechafinal;
    if($rw1[6]>=$fechaactual and $rw1[6]<=$fechafinal){
        // echo$rw1[6].$rw1[1];
        $mesdeingreso=true;
        $fechaAhora=$rw1[6];

    }else{
        $mesdeingreso=false;
        $fechaAhora=$fechaactual;
    }

      $id_p=$rw1[0];
      $va++; $p=$va%2;
    //   usu_estado = '1'and

      $user="SELECT `idusuarios` FROM `usuarios` WHERE `usu_identificacion`='$rw1[5]' and usu_ver_nomina='1'";
      $DB1->Execute($user);
      $idusuario=$DB1->recogedato(0);

      $ARL="SELECT `seg_fechaentrega` FROM `seguridadsocial` WHERE seg_idhojavida='$rw1[0]' and seg_nombre='ARL' ";
      $DB1->Execute($ARL);
      $parafi=$DB1->recogedato(0);
      
    //   $fechafinal=$fechafinal;

if ($rw1[14]==null) {



	$mesiniciocontrato=date("m", strtotime($rw1[6]));
	$añoiniciocontrato=date("Y", strtotime($rw1[6]));

	// echo"MES ".$sigmesiniciocontrato=date("m", strtotime($mesiniciocontrato. " +1 month"));

	$priemrdiadestemes=date("Y-m-d H:i:s", strtotime($añoiniciocontrato.'-'.$mesiniciocontrato.'-01'.' 00:00:00'."+1 month"));

	if($param36=='Completo'){
		$diaveinte=date("Y-m-d H:i:s",strtotime($añoiniciocontrato.'-'.$mesiniciocontrato.'-30'.' 23:59:59'."+31 days"));
	
	}else{
	
		$diaveinte=date("Y-m-d H:i:s",strtotime($añoiniciocontrato.'-'.$mesiniciocontrato.'-15'.' 23:59:59'."+1 month"));
	}

	// $quincediasatras = date("Y-m-d", strtotime($priemrdiadestemes . " +30 days")); 
	// $quincedidespues = date("Y-m-d", strtotime($rw1[6] . " +15 days")); 
	// $treintadidespues = date("Y-m-d", strtotime($rw1[6] . " +30 days")); 
	// echo"$fechaactual>=$priemrdiadestemes and $fechafinal<=$diaveinte";
	// if (($fechaactual>=$priemrdiadestemes and $fechafinal<=$diaveinte)) {

        if (( $rw1[6]>=$fechaactual and $rw1[6]<=$diaveinte)) {
		$color="#00bf19";
	}else{

		if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
	}

	
	$terminaContrato="";
	$InicioContrato=$rw1[6];
if($InicioContrato<=$fechafinal and $rw1[18] == 'Activo') {


	$activoEnNomina=true;

    
}else{
	$activoEnNomina=false;
	
}
	
}else{
	// $fechafinal=$rw1[14];
	$terminaContrato=$rw1[14];
	$mesterminocontrato=date("m", strtotime($rw1[14]));
	$añoterminocontrato=date("Y", strtotime($rw1[14]));
	$diaterminocontrato=date("d", strtotime($rw1[14]));


	$priemrDiaQuinceTermina=date("Y-m-d H:i:s", strtotime($añoterminocontrato.'-'.$mesterminocontrato.'-01'.' 00:00:00'));


	if ($fechaactual>= $priemrDiaQuinceTermina ) {
		
		$color="#D35400";

	}else{
		
		if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}

	}


	$nueva_fechaTerminaContrato = date("Y-m-d", strtotime($terminaContrato . "+30 days"));

	if ( $fechafinal<= $nueva_fechaTerminaContrato ) {
		
        
        if ($rw1[6]>=$fechafinal) {

            $activoEnNomina=false;
        }else{
            $activoEnNomina=true;
        }

	}else{
		$activoEnNomina=false;
		

	}

}
if($idusuario==551 ){

    $activoEnNomina=false;
}else{
    $activoEnNomina=$activoEnNomina;

}
if ($activoEnNomina) {





        //   echo "
        //   <td>".$rw1[1].$rw1[2]."</td>";
        //   echo "<td>".$rw1[4]."</td>";
        //   echo "<td>".$rw1[5]."</td>";

 

      if(empty($idusuario)){
            // echo "<td></td>";
			// echo "<td></td>";
			// echo "<td></td>";
			// echo "<td></td>";
			// echo "<td></td>";
			// echo "<td></td>";
			// echo "<td></td>";
			// echo "<td></td>";
			// echo "<td></td>";
			// echo "<td></td>";
			// echo "<td></td>";
			// echo "<td></td>";
			// echo "<td></td>";




			// echo "<td>$rw1[6]</td>";
			// echo "<td>$terminaContrato</td>";

      }else{
        echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
        echo "<td><input type='checkbox'  onchange='selecionado($idusuario,\"$fechaAhora\")' class='checkbox' id='".$idusuario."s' value='$idusuario'></td>";

        echo "<td>".$rw1[1]."</td>";//Nombres
        echo "<td>".$rw1[2]."</td>";//Apellidos
        echo "<td>".$rw1[5]."</td>";//Cedula

        //   echo "<td>No ".$rw1[16]."</td>";

      $valordediastrabajados=0;
      $sql2="SELECT  `idcargo`, `car_Cargo`, `car_Salario`, `car_Auxilio`, `car_otros` FROM `cargo` WHERE idcargo='$rw1[3]'";
      $DB1->Execute($sql2);
      $cargosaldo=mysqli_fetch_row($DB1->Consulta_ID);
      if($idusuario>=1){
        // echo$slq3="SELECT  sum(`deu_valor`) FROM `duedapromotor` WHERE `deu_fecha`>='$fechaactual' and `deu_fecha`<='$fechafinal' and deu_idpromotor='$idusuario' and deu_tipo in ('Prestamos','Descuadre')";
        $prestamo=0;
        $descuadre=0;
        $pago=0;
//Prestamos
        $slq3="SELECT deu_tipo, deu_valor FROM `duedapromotor` WHERE  deu_idpromotor='$idusuario'  ";
        $DB1->Execute($slq3);
        // $prestamostotal=mysqli_fetch_row($DB1->Consulta_ID);
        while($prestamostotal=mysqli_fetch_row($DB1->Consulta_ID))
        {
            if ($prestamostotal[0]=="Prestamos") {
                $prestamo =$prestamo+$prestamostotal[1];
            }elseif ($prestamostotal[0]=="Descuadre") {
                $descuadre =$descuadre+$prestamostotal[1];
            }elseif ($prestamostotal[0]=="Pagos") {
                $pago =$pago+$prestamostotal[1];
            }

        }


        // echo"Pagado".$pago;
        $prestamoTotal= $prestamo+$descuadre;
        $TotalDebe = $prestamoTotal-$pago;

//Pagos a la 15na, de prestamos
$pago1=0;
        $slq7="SELECT deu_tipo,deu_valor FROM `duedapromotor` WHERE  deu_idpromotor='$idusuario' and deu_fecha>='$fechaAhora' and deu_fecha<='$fechafin' and deu_pago_de in ('Basico','otros') ";
        $DB1->Execute($slq7);
        // $prestamostotal=mysqli_fetch_row($DB1->Consulta_ID);
        while($prestamostotal7=mysqli_fetch_row($DB1->Consulta_ID))
        {
            if ($prestamostotal7[0]=="Pagos") {
                $pago1 =$pago1+$prestamostotal7[1];
            }

        }



        $TotalPagoQuincena = $pago1;
        $TotalPagoQuincena_formateado = number_format($TotalPagoQuincena, 0, ',', '.');

//Dias no trabajados
        $fechasNoTrabajo_array = array();
        $diasnotrabajo=0;
        $notrabajo="SELECT seg_fechaingreso FROM `seguimiento_user`  where seg_motivo in ('Se devolvio','Sancionado','No trabajo') and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'   and seg_idusuario='$idusuario' ";
        $DB1->Execute($notrabajo);
        // $rw2=mysqli_fetch_row($DB1->Consulta_ID);
        // $diasnotrabajo=$rw2[0];

        while($rw2=mysqli_fetch_row($DB1->Consulta_ID))
        {

            $diasnotrabajo=$diasnotrabajo+1;
            $fechasNoTrabajo_array[] = $rw2[0];

        }

//Dias de Descanso
        $descansopago="SELECT count(*) FROM `seguimiento_user`  where seg_motivo in('descanso','IngresoHoras') and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'  and seg_idusuario='$idusuario' ";
        $DB1->Execute($descansopago);
        $rw6=mysqli_fetch_row($DB1->Consulta_ID);
        if(empty($rw6)){

            $diasDescanso=0;
        }else{

            $diasDescanso=$rw6[0];
        }

        $valorDeDiasDeDescanso=$diasvalor*$diasDescanso;
        $valorDeDiasDeDescanso_formateado = number_format($valorDeDiasDeDescanso, 0, ',', '.');

//Dias trabajados
        $diassitrabajo=0;
        $sitrabajo="SELECT count(*) FROM `seguimiento_user`  where seg_motivo ='Ingreso' and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'  and seg_idusuario='$idusuario' ";
        $DB1->Execute($sitrabajo);
        $rw4=mysqli_fetch_row($DB1->Consulta_ID);
        if(empty($rw4)){

            $diassitrabajo=0;
        }else{

            if($fin==31 and $param36=='Segunda' or $fin==31 and $param36=='Completo'){

                // if(($rw4[0]+$diasDescanso)<=14){
                //     $diassitrabajo=$rw4[0]+$diasDescanso;
                //     $diassitrabajoParaSumar=$rw4[0]+$diasDescanso;
                //     $diassitrabajoConAuxilio=$rw4[0];

                // }else{
                    if ($rw4[0]<=0 or $mesdeingreso==true) {
                        $dia31=0;

                        # code...
                    }else{

                        // $dia31=1;

                        if (($rw4[0]+$diasDescanso)==31 ) {
                            $dia31=1;
                            
                            # code...
                        }else{

                            // echo"segunda quince con 16 dias";
                            $dia31=0;
                        }
                    }



                    $diassitrabajo=$rw4[0]+$diasDescanso-($dia31+$diasnotrabajo);
                    $diassitrabajoParaSumar=$rw4[0]+$diasDescanso-$dia31;
                    $diassitrabajoConAuxilio=$rw4[0];
                    $diassitrabajoParaMostrar=$rw4[0]+$diasDescanso-$dia31;
                // }

            }elseif($fin==29 and $param36=='Segunda' or $fin==29 and $param36=='Completo' ){


                if ($rw4[0]<=0 or $mesdeingreso==true){
                    $dia29=0;

                    # code...
                }else{


                    $dia29=1;
                }

                $diassitrabajo=($rw4[0]+$diasDescanso+$dia29)-$diasnotrabajo;
                $diassitrabajoParaSumar=($rw4[0]+$diasDescanso+$dia29)-$diasnotrabajo;
                $diassitrabajoConAuxilio=$rw4[0]+$dia29;
                $diassitrabajoParaMostrar=($rw4[0]+$diasDescanso+$dia29)-$diasnotrabajo;
            // }

        }else{

                $diassitrabajo=$rw4[0]+$diasDescanso;
                $diassitrabajoParaSumar=$rw4[0]+$diasDescanso;
                $diassitrabajoConAuxilio=$rw4[0];
                $diassitrabajoParaMostrar=$rw4[0]+$diasDescanso;
            }



        }


        $diasvalor=($cargosaldo[2]/30);
        $valordediastrabajados=$diasvalor*$diassitrabajoParaSumar;
        $valordediastrabajados_formateado = number_format($valordediastrabajados, 0, ',', '.');

//Permisos y licencias
        $permisosLic=0;
        $nombreMotivo="";
        $valorPermisosLicBasico=0;
        $diasPerLicBas=0;
        $diasvalorporcentaje=0;
        $diasPerLicBasValor=0;
        $valorMitaddiasPerLi=0;
        $diasPerLicBasValortotal=0;
        $diasPerLicBasValortotalfinal=0;
        $fechasLice_array = array();
        $permisoLicencia="SELECT `seg_motivo`, `seg_descr`, `mot_salud`, `mot_pension`, `mot_auxtransporte`, `mot_porcbasico`, `mot_otrosDevengos`, seg_fechaingreso  FROM `seguimiento_user` INNER JOIN motivo_ingreso on mot_nombre=seg_motivo  where seg_motivo in('licencia de maternidad','LICENCIA POR LUTO','PERMISO NO REMUNERADO') and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'  and seg_idusuario='$idusuario' ";
        $DB1->Execute($permisoLicencia);
        ;
        while($rw9=mysqli_fetch_row($DB1->Consulta_ID))
        {
            if(empty($rw9)){

                $permisosLic=0;
            }else{

                $permisosLic=$permisosLic+1;
                $nombreMotivo=$rw9[0];

                if ($rw9[2]=="si" or $rw9[2]=="SI" ) {
                    $valorPermisosLicSalud=$valorPermisosLic+1;
                }
                if ($rw9[3]=="si" or $rw9[3]=="SI") {
                    $valorPermisosLicPension=$valorPermisosLic+1;
                }
                if($rw9[4]=="si" or $rw9[4]=="SI"){
                    $valorPermisosLicAux=$valorPermisosLic+1;

                }
                if($rw9[6]=="si" or $rw9[6]=="SI"){
                    $valorPermisosLicOtros=$valorPermisosLic+1;
                }

                if($rw9[5]!="0"){
                    $diasPerLicBas=$diasPerLicBas+1;



                    // if(strpos($rw9[5], ".") !== false) {

                        // $partes = explode(".", $rw9[5]);
                        // $numeroAntesDelPunto1 = $partes[0];
                        // $numeroDespuesDelPunto1 = $partes[1];

                        // // echo"Antes$numeroAntesDelPunto1*$diasvalorporcentaje";
                        // // echo"Despues$diasvalorporcentaje*$numeroDespuesDelPunto1";

                        // $diasPerLicBasValor=$numeroAntesDelPunto1*$diasvalorporcentaje;
                        // $valorMitaddiasPerLic=$diasvalorporcentaje*$numeroDespuesDelPunto1;

                        // $diasPerLicBasValortotal=$diasPerLicBasValor+$valorMitaddiasPerLic;
                        // // echo"Total dia $diasPerLicBasValortotal=$diasPerLicBasValor+$valorMitaddiasPerLic";

                        // $diasvalorporcentaje=$diasvalor*0.;
                    // } else {
                        $rw9[5];
                        $valorporcentaje=($rw9[5]/100)*$diasvalor;

                        // $diasPerLicBasValor=$rw6[0]*$diasvalorporcentaje;
                        // $diasPerLicBasValortotal=$diasPerLicBasValor;

                    // }
                    $diasPerLicBasValortotalfinal=$diasPerLicBasValortotalfinal+$valorporcentaje;


                }

                $fechasLice_array[]= $rw9[7];
            }


        }



        // $valorPermisosLicBasico=$diasvalor*$diasPerLicBas;

        $diasPerLicBasValortotalfinal_formateado = number_format($diasPerLicBasValortotalfinal, 0, ',', '.');

//Dias de incapacidad
        $diasincapacidad=0;
        $nuevo_array = array();
        $fechasInca_array = array();
        $incapacidad="SELECT seg_fechaingreso FROM `seguimiento_user`  where seg_motivo in ('Incapacidad','PAGO DE INCAPACIDAD AL 66') and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'  and seg_idusuario='$idusuario' ";
        $DB1->Execute($incapacidad);
        while($rw5=mysqli_fetch_row($DB1->Consulta_ID))
        {

            $diasincapacidad=$diasincapacidad+1;
            $fechasInca_array[] = $rw5[0];
        }
        
        // $rw5=mysqli_fetch_row($DB1->Consulta_ID);
        // if(empty($rw5)){

        //     $diasincapacidad=0;
        // }else{
        //     $diasincapacidad=$rw5[0];
        // }

        if($diasincapacidad>=2 ){



            // if( $param36=='Completo' ){
            //     $valorDiasIncapadidad=($diasvalor)*(4*0.6667);

            //     $valorDiasIncapadidad_formateado = number_format($valorDiasIncapadidad, 0, ',', '.');


            // }else{
                $valorDiasIncapadidad=($diasvalor)*(2*0.6667);
                $valorDiasIncapadidad_formateado = number_format($valorDiasIncapadidad, 0, ',', '.');
            // }
        }else{

            $valorDiasIncapadidad=($diasvalor)*($diasincapacidad*0.6667);

            $valorDiasIncapadidad_formateado = number_format($valorDiasIncapadidad, 0, ',', '.');
        }

        $horasdominicales="SELECT SUM(seg_horas_trabajadas) FROM `seguimiento_user`  WHERE  seg_motivo ='IngresoHoras' and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'  and seg_idusuario='$idusuario' ";
        $DB1->Execute($horasdominicales);
        $rw6=mysqli_fetch_row($DB1->Consulta_ID);



        }else{
            $diasnotrabajo=0;
            $prestamostotal=0;
            $diasvalor=0;
        }
        //VACACIONES
        $fechasVacaciones_array=array();
        $diasVacaciones=0;
        $Vacaciones="SELECT seg_fechaingreso FROM `seguimiento_user`  where seg_motivo ='Vacaciones' and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'  and seg_idusuario='$idusuario' ";
        $DB1->Execute($Vacaciones);
        // $rw8=mysqli_fetch_row($DB1->Consulta_ID);
        // if(empty($rw8)){

        //     $diasVacaciones=0;
        // }else{
        //     $diasVacaciones=$rw8[0];
        // }

        while($rw8=mysqli_fetch_row($DB1->Consulta_ID))
        {
            $diasVacaciones=$diasVacaciones+1;
            $fechasVacaciones_array[] = $rw8[0];
        }




//SALUD Y PENSION

$Salud=26000;
$Pension=26000;

$saludPorDia=26000/15;
$pensionPorDia=26000/15;

if ($terminaContrato=="" and $mesdeingreso==false) {
	if($param36=='Completo'){

		$valorSalud=$saludPorDia*30;
		$valorPension=$pensionPorDia*30;
	}elseif($param36=='Primera' or $param36=='Segunda'){
		$valorSalud=$saludPorDia*15;
		$valorPension=$pensionPorDia*15;
	}
}else{
	$valorSalud=$saludPorDia*$diassitrabajoParaSumar;
	$valorPension=$pensionPorDia*$diassitrabajoParaSumar;

}

$valortotalDeduccion=$valorSalud+$valorPension;

$valorSalud_formateado = number_format($valorSalud, 0, ',', '.');


$valorPension_formateado = number_format($valorPension, 0, ',', '.');

$valortotalDeduccion_formateado = number_format($valortotalDeduccion, 0, ',', '.');
//Pago por dias

//auxilio
$auxilioPorDia=$cargosaldo[3]/30;
    //Total auxilio 15na
    $totalauxilio=$auxilioPorDia*($diassitrabajoParaSumar);


//Total horas domfest
    if (strpos($rw6[0], ".") !== false) {

        $partes = explode(".", $rw6[0]);
        $numeroAntesDelPunto = $partes[0];

        $valorHorasDomini=$numeroAntesDelPunto*11064;
        $valorMitadDomini=$valorHorasDomini/2;

        $valorTotalHorasDomini=$valorHorasDomini+$valorMitadDomini;
    } else {

        $valorHorasDomini=$rw6[0]*11064;
        $valorTotalHorasDomini=$valorHorasDomini;

    }
//otros
$otrosPorDia=$cargosaldo[4]/30;
    //Total auxilio 15na
    $totalOtors=($otrosPorDia*($diassitrabajoParaSumar))+$valorTotalHorasDomini;
    $totalOtors_formateado = number_format($totalOtors, 0, ',', '.');

    $sueldo_formateado = number_format($cargosaldo[2], 0, ',', '.');

    $diasvalor_formateado = number_format($diasvalor, 0, ',', '.');

    $TotalDevengadoSinDeducc= ($valordediastrabajados+$valorDiasIncapadidad);
    $TotalDevengadoSinDeducc_formateado = number_format($TotalDevengadoSinDeducc, 0, ',', '.');
    $TotalDevengado= ($valordediastrabajados)-($valorSalud+$valorPension);
    $TotalDevengado_formateado = number_format($TotalDevengado, 0, ',', '.');

            echo "
            <td>".$sueldo_formateado."</td>
            <td>$diassitrabajoParaMostrar</td>
            ";
            echo "<td>".$valordediastrabajados_formateado."</td>";//Dias trabajados con dias de descanso
            // echo "<td>$".$totalauxilio."</td>";//total auxilio segun dias
            // echo "<td>$valorDiasIncapadidad_formateado</td>";
            echo "<td style='text-align: center;' ><details> <summary>$diasincapacidad</summary>";
            foreach ($fechasInca_array as $dato) {
                echo "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;'>".$dato 
                ."</div>";
            }
            echo"</details></td>";
            echo "<td style='text-align: center;' ><details> <summary>$diasVacaciones</summary>";
            foreach ($fechasVacaciones_array as $dato) {
                echo "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;'>".$dato 
                ."</div>";
            }
            echo"</details></td>";

            echo "<td style='text-align: center;' ><details> <summary>$permisosLic</summary> ";
            foreach ($fechasLice_array as $dato) {
                echo "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;'>".$dato 
                ."</div>";
            }
            echo"</details></td>";
            
            
            echo "<td style='background-color:#F4D03F'>$TotalDevengadoSinDeducc_formateado</td>";//Valor quincena
            echo "<td style='background-color:#85C1E9 '>$parafi</td>";
            echo "<td>$valorSalud_formateado</td>";

            echo "<td>$valorPension_formateado</td>";
            echo "<td style='text-align: center;' ><details> <summary>$diasnotrabajo </summary>";
            
            foreach ($fechasNoTrabajo_array as $dato) {
                echo "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;'>".$dato 
                ."</div>";
            }
            echo"</details></td>";
            echo "<td>".$valortotalDeduccion_formateado."</td>";
            echo "<td>-</td>";
            echo "<td style='background-color:#F4D03F'>$TotalDevengado_formateado</td>";//Valor quincena
            echo "<td>$rw1[6]</td>";
			echo "<td>$terminaContrato</td>";

            $salarioBasicoTotalTodos=$salarioBasicoTotalTodos+$cargosaldo[2];
            $diasTotalesTodos=$diasTotalesTodos+$diassitrabajo;
            $sueldoTodos=$sueldoTodos+$valordediastrabajados;
            $auxTransTodos=$auxTransTodos+$totalauxilio;
            $totalDevengTodos=$totalDevengTodos+$TotalDevengadoSinDeducc;
            $saludTodos=$saludTodos+$valorSalud;
            $pensionTodos=$pensionTodos+$valorPension;
            $totaldeduccionesTodos=$totaldeduccionesTodos+$valortotalDeduccion;
            $valorAPagarTodos=$valorAPagarTodos+$TotalDevengado;


     }
    }
  }


      }



      $salarioBasicoTotalTodos_formateado= number_format($salarioBasicoTotalTodos, 0, ',', '.');
      $diasTotalesTodos_formateado= number_format($diasTotalesTodos, 0, ',', '.');
      $sueldoTodos_formateado= number_format($sueldoTodos, 0, ',', '.');
      $auxTransTodos_formateado= number_format($auxTransTodos, 0, ',', '.');
      $totalDevengTodos_formateado= number_format($totalDevengTodos, 0, ',', '.');
      $saludTodos_formateado= number_format($saludTodos, 0, ',', '.');
      $pensionTodos_formateado= number_format($pensionTodos, 0, ',', '.');
      $totaldeduccionesTodos_formateado= number_format($totaldeduccionesTodos, 0, ',', '.');
      $valorAPagarTodos_formateado= number_format($valorAPagarTodos, 0, ',', '.');
      echo "<tr>";
    //   echo "<td>-</td>";
      echo "<td>-</td>";
      echo "<td>-</td>";

      echo "<td>-</td>";
      echo "<td>TOTAL</td>";
      echo "<td>$salarioBasicoTotalTodos_formateado</td>";
      echo "<td>-</td>";
    //   echo "<td>$diasTotalesTodos_formateado</td>";
      echo "<td>$sueldoTodos_formateado</td>";
      echo "<td></td>";
      echo "<td>-</td>";
      echo "<td>-</td>";
      echo "<td>$totalDevengTodos_formateado</td>";
      echo "<td>-</td>";

      echo "<td>$saludTodos_formateado</td>";
      echo "<td>$pensionTodos_formateado</td>";
      echo "<td>-</td>";
      echo "<td>$totaldeduccionesTodos_formateado</td>";
      echo "<td>-</td>";
      echo "<td>$valorAPagarTodos_formateado</td>";






      ?>

</table>



<script>

var fechaIni = document.getElementById('fechaini').value;
var fechafin = document.getElementById('fechafin').value;
var usuConfirma = document.getElementById('usuConfirma').value;


    // Función para guardar la imagen
    function guardarImagenPagoPara() {
        // // Aquí puedes implementar la lógica para guardar la imagen
        // let imagenInput = document.getElementById('imagenInput').files[0];
        var funcion = "guardarImagenPagoPara";
        var valor = "si";
        // console.log('Descargar');


        // datos = {"fechaIni":fechaIni,"fechafin":fechafin,"usuConfirma":usuConfirma,"funcion":funcion,"confirma":valor,"imagen":imagenInput};
		// $.ajax({
		// 		url: "guardaNomina.php",
		// 		type: "POST",
		// 		data: datos
		// 	}).done(function(respuesta){



		// 		// if (checkbox.checked) {
		// 		// 	boton.style.display = "inline-block"; // Muestra el botón si el checkbox está marcado
		// 		// } else {
		// 		// 	boton.style.display = "none"; // Oculta el botón si el checkbox no está marcado
		// 		// }
		// 	});
        // console.log('Guardar Imagen');



		const imagenInput = document.getElementById('imagenInput').files[0];
        // const idsSeleccionados = [1, 2, 3]; // Tu array de IDs seleccionados


            const formData = new FormData();
            formData.append('imagen', imagenInput);
            formData.append('fechaIni', fechaIni);
            formData.append('fechafin', fechafin);
            formData.append('usuConfirma', usuConfirma);
            formData.append('funcion', funcion);
            formData.append('confirma', valor);

            $.ajax({
                url: 'guardaNomina.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
					// idsSeleccionados = [];
					// document.getElementById('comproPago').value = '';
                    // console.log('Respuesta del servidor:');
                    // Aquí puedes manejar la respuesta del servidor si es necesario
					alert("Imagen cargada satisfactoriamente");

                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });



    }

    // Función para descargar


    function nomParafiscales(valor) {
        // Aquí puedes implementar la lógica para descargar
        var funcion = "nomParafiscales";
        console.log('Descargar');


        datos = {"fechaIni":fechaIni,"fechafin":fechafin,"usuConfirma":usuConfirma,"funcion":funcion,"confirma":valor};
		$.ajax({
				url: "guardaNomina.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){



				// if (checkbox.checked) {
				// 	boton.style.display = "inline-block"; // Muestra el botón si el checkbox está marcado
				// } else {
				// 	boton.style.display = "none"; // Oculta el botón si el checkbox no está marcado
				// }
			});




    }

    function selecionado(iduser,fecha) {
		var checkbox = document.getElementById(iduser+"s");
        // const checkbox = event.target;
        // const id = iduser;
var funcion="marcar_en_parafiscales";
        datos = {"idUsuario":iduser,"fechaini":fecha,"funcion":funcion,"valor":aux1};
			$.ajax({
					url: "guardaNomina.php",
					type: "POST",
					data: datos
				}).done(function(respuesta){
					
					// if (respuesta=="ok") {
						alert("Valor descontado");
					// }
					// Cambia el contenido del div
					div.innerHTML = "$"+respuesta;
					// if (checkbox.checked) {
					// 	boton.style.display = "inline-block"; // Muestra el botón si el checkbox está marcado
					// } else {
					// 	boton.style.display = "none"; // Oculta el botón si el checkbox no está marcado
					// }
				});
        // console.log("IDs seleccionados:", idsSeleccionados);
    }
    window.addEventListener('load', function() {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('content').style.display = 'block';
        });
</script>
<?php
include("footer.php");
?>