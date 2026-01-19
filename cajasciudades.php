<?php
require("login_autentica.php"); 
include("layout.php");
?>
<style>
    #link { color: #FFFFFF; } /* CSS link color */

	.errorval {
            color: red;
            display: none;
        }
  </style>
  <script>
/*   var json={datos:[{nombre :''},{apellido:''},{ciudad:''}]};
  var obj = JSON.parse(json); */

  var cantidad= new Array();


  function cuentas(valor,cuentas,para,para2){

		if(valor>=0){
			var  p1=document.getElementById('param117').value;
			var  p2=document.getElementById(para).value;
			cantidad[para2]=valor;
			var suma=valor*cuentas;		
			cantidad[para]=suma;
			var total =parseInt(suma)+parseInt(p1)-parseInt(p2);
			document.getElementById(para).value=suma;
			document.getElementById("param117").value=total;
		}	

	}
</script>
<?php
$conde3="";
$conde1="";
$condet1="";
$conde11="";
if($nivel_acceso==1 or $nivel_acceso==10 or $nivel_acceso==12 or $nivel_acceso==9){ $conde4=""; 	 } else { $conde4=" and idsedes=$id_sedes";  }

// $conde1="and usu_idsede='$param1'";  $conde1="and usu_idsede=$id_ciudad"; 
if(isset($_REQUEST["param1"])){ if($param1!=""){   $id_sedes=$param1; } } else {$param1=""; }
//if(isset($_REQUEST["param2"])){  if($param2!=""){ $conde1 ="and asi_idpromotor='$param2'";  $conde3="and cue_idoperador='$param2'";  }     } else {$param2="";  }

if($param4!=''){  $fechaactual=$param4;  } else { $param4=$fechaactual; }

$FB->titulo_azul1("Cuentas Sedes ",9,0,7);  
$FB->abre_form("form1","","post");
$conde1.="and asi_fechaconf like '$param4%'";
//$condet1.=" and asi_fechaconf like '$param4%'"; 
$conde3.=" and cue_fecha like '$fechaactual%'"; 

$conde2="and inner_sedes='$id_sedes'"; 



$FB->llena_texto("Sede:",1,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde4)", "", "$id_sedes", 1, 1);
$FB->llena_texto("Fecha de Busqueda:", 4, 10, $DB, "", "", "$fechaactual", 4, 0);
//$FB->llena_texto("Operario:",2,2, $DB, "SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE `roles_idroles` in (2,3,5) $conde2", "", $param2, 1, 0);
$FB->llena_texto("", 3, 142, $DB, "BUSCAR", "","", 1, 0);
$FB->cierra_form(); 


$FB->titulo_azul1("# PRE",1,0,7); 
$FB->titulo_azul1("PRESTAMOS",1,0,0); 
$FB->titulo_azul1("EXEDENTES",1,0,0); 
$FB->titulo_azul1("# CTD",1,0,0); 
$FB->titulo_azul1("CONTADO",1,0,0); 
$FB->titulo_azul1("# ACB",1,0,0); 
$FB->titulo_azul1("ALCOBRO",1,0,0); 
$FB->titulo_azul1("#PAGAS",1,0,0); 
$FB->titulo_azul1("VALOR",1,0,0); 

$FB->titulo_azul1("PENDIENTE",1,0,0); 
$FB->titulo_azul1("PRESTAMOS CREDITOS",1,0,0); 
$FB->titulo_azul1("PARA COMPRAS",1,0,0); 
$FB->titulo_azul1("ABONOS X COMPRAS",1,0,0); 
$FB->titulo_azul1("DEVOLUCIONES",1,0,0); 
$FB->titulo_azul1("GASTOS OPERADOR",1,0,0); 
$FB->titulo_azul1("# RMS",1,0,0); 
$FB->titulo_azul1("REMESAS",1,0,0); 
$FB->titulo_azul1("PXCOBRAR",1,0,0); 
$FB->titulo_azul1("PXPAGAR",1,0,0); 
$FB->titulo_azul1("PAGOSPXP",1,0,0); 
$FB->titulo_azul1("TRANSPASOS",1,0,0); 



$totalasignacion=0;
$totaltranspaso=0;
$totalacompras=0;
$totalarecogidas=0;
$totalcompras=0;
$totalgastos=0;
$ciudad='';

  $sql="SELECT `idasignaciondinero`,`asi_fecha`,`asi_valor`,  `asi_idautoriza`, `asi_idpromotor` ,asi_tipo,asi_descripcion
  FROM `asignaciondinero`  WHERE idasignaciondinero>0 and asi_idciudad=$id_sedes $conde1  ORDER BY asi_fecha, $ord $asc";
$DB1->Execute($sql); 
$va=0;
$va2=0;
$va3=0;
$va4=0;
$va5=0;
$va6=0;
$dineroentregado=0;
$asignaciones=array();
$idtranspaso=array();
$transpaso=array();
$gastos=array();
$descripcion=array();
while($rw1=mysqli_fetch_row($DB1->Consulta_ID))
{
	
	
	if($rw1[5]=='Asignar Dinero'){
		$va++;
		$asignaciones[$va]=number_format($rw1[2],0,".",".");	
		$totalasignacion=$totalasignacion+$rw1[2];

	}else if($rw1[5]=='entregado') {
		//$dineroentregado=number_format($rw1[2],0,".",".");
		$dineroentregado=$dineroentregado+$rw1[2];
	}else if($rw1[5]=='Gastos') {
		$va2++;
		$gastos[$va2]=number_format($rw1[2],0,".",".");	
		$descripcion[$va2]=$rw1[6];
		$totalgastos=$totalgastos+$rw1[2];
	}
}


 $mayor=max($va, $va2, $va3);
/* echo $sql5="SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE `idusuarios`='$param2' and   (usu_estado=1 or usu_filtro=1)";
$DB->Execute($sql5);
$nompromotor=$DB->recogedato(1); */
$va4=0;
$gastossede=0;
$enviados=0;
$retiro=0;

	$slq6="SELECT  sum(`caj_cantcom`),caj_tipotransacion FROM `cajamenor` WHERE `caj_feccom` like '$param4%' and caj_idciudadori='$id_sedes' group by caj_tipotransacion  ";
$DB1->Execute($slq6); 
while($rw5=mysqli_fetch_row($DB1->Consulta_ID))
{
	if($rw5[1]=='Gastos'){
		$gastossede=$rw5[0];
	}
	else if($rw5[1]=='Retiro de Banco'){

		$retiro=$rw5[0];
	
		}

}


 

$totalasignacion=number_format($totalasignacion,0,".",".");
	$totalgastos=number_format($totalgastos,0,".",".");
	
$presta=0;	
$npresta=0;	

$transidser2=array();
  $slq101="SELECT pag_idservicio,pag_guia,pag_valor FROM `pagoscuentas` inner join usuarios on idusuarios=pag_idoperario where `pag_fecha` like '$param4%' and usu_idsede='$id_sedes'";
$DB->Execute($slq101); 	
while($rw11=mysqli_fetch_row($DB->Consulta_ID))
{
	//$transidser.=$rw11[0].",".$transidser;
	array_push($transidser2,$rw11[0]);
	$transvalor=str_replace('.','', $rw11[2])+$transvalor;
	
} 	
	
$transidser=implode(', ', $transidser2);
if($transidser==''){
$transidser=0;
}

   $slq10="SELECT sum(replace(ser_valorprestamo, '.', '')) as prestamos ,count(idservicios) FROM `servicios` inner join cuentaspromotor on idservicios=cue_idservicio inner join ciudades on idciudades=cue_idciudadori WHERE cue_fecharecogida like '$param4%' and  ser_estado!=100   and inner_sedes=$id_sedes and ser_valorprestamo>0 order by cue_fecharecogida ";
 $DB->Execute($slq10); 	
while($rw0=mysqli_fetch_row($DB->Consulta_ID))
{
	$presta=$rw0[0];
	$npresta=$rw0[1];
}	
	 
 $slq1s="SELECT (sum(cue_porprestamo)+ sum(cue_prestamo)+sum(cue_valorflete)+sum(cue_pordeclarado)-sum(cue_abono)) valor,count(idcuentaspromotor) FROM `cuentaspromotor`  inner join ciudades on idciudades=cue_idciudaddes WHERE  `cue_fecha` like '$param4%'  and  cue_estado>=8 and cue_estado<=14  and cue_tipoevento=3 and inner_sedes=$id_sedes and cue_idservicio not in ($transidser) order by `cue_fecha` ";
 $DB->Execute($slq1s); 
while($rw=mysqli_fetch_row($DB->Consulta_ID))
{
	$alcobroo=$rw[0];
	$nalcobro=$rw[1];
} 
	
 $slq1s="SELECT (sum(cue_porprestamo)+ sum(cue_prestamo)+sum(cue_valorflete)+sum(cue_pordeclarado)-sum(cue_abono)) valor,count(idcuentaspromotor) FROM `cuentaspromotor`  inner join ciudades on idciudades=cue_idciudaddes WHERE `cue_fecha` like '$param4%'  and cue_estado=10  and cue_tipoevento=3 and inner_sedes=$id_sedes and cue_idservicio not in ($transidser)  order by `cue_fecha` ";
$DB->Execute($slq1s); 
while($rw1=mysqli_fetch_row($DB->Consulta_ID))
{
	$palcobroo=$rw1[0];
	$pnalcobro=$rw1[1];
}
	
$slq2="SELECT (sum(cue_valorflete)+sum(cue_pordeclarado)) valor,count(idcuentaspromotor) FROM `cuentaspromotor`  inner join ciudades on idciudades=cue_idciudadori WHERE `cue_fecharecogida` like '$param4%'  and cue_estado<=14  and cue_tipoevento=1 and cue_pendientecobrar=0 and inner_sedes=$id_sedes and cue_idservicio not in ($transidser) order by `cue_fecha` ";

$DB->Execute($slq2); 
while($rw2=mysqli_fetch_row($DB->Consulta_ID))
{
	$contado=$rw2[0];
	$ncontado=$rw2[1];
} 
	
 
 $valorenviar=0;
$totalaentregar=0;
$totalcancelada=0;
$nremesas=0;
$totalremesas=0;
 $pendiente=$alcobroo-$palcobroo;
 
 //  $slq12="SELECT sum(`ser_valorabono`) FROM `servicios` INNER JOIN guias ON idservicios=gui_idservicio INNER JOIN usuarios on idusuarios=gui_idusuario where  usu_idsede=$id_sedes  and gui_fechacreacion like '$param4%' and ser_estado<100 ";
   $slq12="SELECT sum(abo_valor) FROM `abonosguias` WHERE abo_idsede=$id_sedes  and abo_fecha like '$param4%' and `abo_estado`='abono'";
   $DB->Execute($slq12); 
 $valorabono=$DB->recogedato(0);

 $slq122="SELECT sum(abo_valor) FROM `abonosguias` WHERE abo_idsede=$id_sedes   and abo_fecha like '$param4%' and `abo_estado`='devolucion'";
$DB->Execute($slq122); 
$devoluciong=$DB->recogedato(0);


/*  $slq123="SELECT sum(abo_valor) FROM `abonosguias` inner join servicios on abo_idservicio=idservicios  WHERE abo_idsede=$id_sedes   and abo_fecha like '$param4%' and `abo_estado`='devolucion' and (ser_guiare='' or ser_estado=100)"; //devolucion de guias canceladas o sin numero de guias.
$DB->Execute($slq123); 
$devoluciong2=$DB->recogedato(0); */

  $sql="SELECT sum(`gas_cantcom`), count(idgastos) FROM `gastos` WHERE idgastos>0  and (gas_idciudadori='$id_sedes' and gas_pagar='Sede Origen' and gas_feccom like '$param4%')  or (gas_idciudaddes='$id_sedes' and gas_pagar='Sede Destino' and  gas_fechavalida like '$param4%' and gas_nomvalida!='' )  ORDER BY idgastos";
 $DB->Execute($sql); 
 $rw12=mysqli_fetch_row($DB->Consulta_ID);
 $totalremesas=$rw12[0];
 $nremesas=$rw12[1];
 $exedente=0;
  $slq2="SELECT (sum(cue_porprestamo)+sum(cue_prestamo)-sum(cue_abono)) valor FROM `cuentaspromotor`  inner join ciudades on idciudades=cue_idciudaddes WHERE `cue_fecha` like '$param4%'  and cue_estado=10  and cue_tipoevento=1 and cue_prestamo>0 and inner_sedes=$id_sedes  order by `cue_fecha` ";
 $DB->Execute($slq2); 
 while($rw2=mysqli_fetch_row($DB->Consulta_ID))
 {
	 $exedente=$rw2[0];
 } 

 $slq2="SELECT (sum(cue_porprestamo)+sum(cue_prestamo)-sum(cue_abono)) valor FROM `cuentaspromotor`  inner join ciudades on idciudades=cue_idciudaddes WHERE `cue_fecha` like '$param4%'  and cue_estado=10  and cue_tipoevento=2 and cue_prestamo>0 and inner_sedes=$id_sedes  order by `cue_fecha` ";
 $DB->Execute($slq2); 
 while($rw2=mysqli_fetch_row($DB->Consulta_ID))
 {
	 $prestamocredito=$rw2[0];
 } 


$FB->titulo_azul1(" $npresta",1,0,10); 
//$FB->titulo_azul1("$ $presta",1,0,0); 
$presta=number_format($presta,0,".",".");	
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param1,\"detalleprestamos\",\"$param4\")';  style='cursor: pointer;' title='Detalle prestamos' >$ $presta</td>";
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param1,\"detalleexcedente\",\"$param4\")';  style='cursor: pointer;' title='Detalle excedente' >$ $exedente</td>";

$FB->titulo_azul1(" $ncontado",1,0,0); 
$contado=number_format($contado,0,".",".");	
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param1,\"detallecontado\",\"$param4\")';  style='cursor: pointer;' title='Detalle Contado' >$ $contado</td>";
//$FB->titulo_azul1("$ $contado",1,0,0); 
$FB->titulo_azul1("$nalcobro",1,0,0); 
$alcobroo=number_format($alcobroo,0,".",".");	
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param1,\"detallealcobro\",\"$param4\")';  style='cursor: pointer;' title='Detalle Contado' >$ $alcobroo</td>";
//$FB->titulo_azul1("$ $alcobroo",1,0,0); 
$FB->titulo_azul1("$ $pnalcobro",1,0,0); 
$palcobroo=number_format($palcobroo,0,".",".");	
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param1,\"detallpagasalcobro\",\"$param4\")';  style='cursor: pointer;' title='Detalle Contado' >$ $palcobroo</td>";
//$FB->titulo_azul1("$ $palcobroo",1,0,0); 
$pendiente=number_format($pendiente,0,".",".");	
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param1,\"detallependiente\",\"$param4\")';  style='cursor: pointer;' title='Detalle Contado' >$ $pendiente</td>";
//$FB->titulo_azul1("$ $pendiente",1,0,0); 

$prestamoscreditos=number_format($prestamocredito,0,".",".");	
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param1,\"detalleprestamoscreditos\",\"$param4\")';  style='cursor: pointer;' title='Detalle prestamos creditos' >$ $prestamoscreditos</td>";
//$FB->titulo_azul1("$ $pendiente",1,0,0); 

//$FB->titulo_azul1("$ $totalasignacion",1,0,0); 
//$totalasignacion=number_format($totalasignacion,0,".",".");	
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6(\"$param1\",\"detallecompras\",\"$param4\")';  style='cursor: pointer;' title='Detalle compras' >$ $totalasignacion</td>";
$valorabono=number_format($valorabono,0,".",".");	
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6(\"$param1\",\"Abonossedes\",\"$param4\")';  style='cursor: pointer;' title='Detalle Abonos' >$ $valorabono</td>";
$devoluciong=number_format($devoluciong,0,".",".");	
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6(\"$param1\",\"Devolucionsedes\",\"$param4\")';  style='cursor: pointer;' title='Detalle Devoluciones' >$ $devoluciong</td>";

//$FB->titulo_azul1("$ $valorabono",1,0,0); 

//$FB->titulo_azul1("$ $totalgastos",1,0,0); 
//$totalgastos=number_format($totalgastos,0,".",".");	
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6(\"$param1\",\"detallegastos\",\"$param4\")';  style='cursor: pointer;' title='Detalle Gastos' >$ $totalgastos</td>";

$FB->titulo_azul1("# $nremesas",1,0,0); 
$totalremesas=number_format($totalremesas,0,".",".");	
//$FB->titulo_azul1("$ $totalremesas",1,0,0); 
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6(\"$param1\",\"detalleremesas\",\"$param4\")';  style='cursor: pointer;' title='Detalle Remesas' >$ $totalremesas</td>";

$slq2="SELECT (sum(cue_porprestamo)+ sum(cue_prestamo)+sum(cue_valorflete)+sum(cue_pordeclarado)) valor,count(idcuentaspromotor) FROM `cuentaspromotor`  inner join usuarios on idusuarios=cue_idoperpendiente WHERE `cue_fechapcobrar` like '$param4%'  and cue_estado<=14  and cue_tipoevento=1 and cue_pendientecobrar=2 and usu_idsede=$id_sedes and cue_idservicio not in ($transidser)   order by `cue_fecha` ";
$DB->Execute($slq2); 
$pxc=$DB->recogedato(0);
$pxcobrar=number_format($pxc,0,".",".");


echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6(\"$param1\",\"detallepxc\",\"$param4\")';  style='cursor: pointer;' title='Detalle pxc' >$ $pxcobrar</td>";


$slq2="SELECT sum(REPLACE(ser_valorpendiente, '.', '')) valor,count(idservicios) FROM `servicios` inner join cuentaspromotor on cue_idservicio=idservicios  inner join ciudades on idciudades=cue_idciudadori WHERE `cue_fecharecogida` like '$param4%'  and cue_estado<=14  and ser_valorpendiente>0  and cue_pendientecobrar in (3,5) and inner_sedes=$id_sedes  order by `cue_fecha` ";
$DB->Execute($slq2); 
$pxca=$DB->recogedato(0);
$pxcancelar=number_format($pxca,0,".",".");
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6(\"$param1\",\"detallepxca\",\"$param4\")';  style='cursor: pointer;' title='Detalle pxc' >$ $pxcancelar</td>";

$slq2="SELECT sum(REPLACE(ser_valorpendiente, '.', '')) valor,count(idservicios) FROM `servicios` inner join cuentaspromotor on cue_idservicio=idservicios  inner join ciudades on idciudades=cue_idciudadori WHERE `cue_fechapcobrar` like '$param4%'  and cue_estado<=14  and ser_valorpendiente>0  and cue_pendientecobrar in (3,5) and inner_sedes=$id_sedes  order by `cue_fecha` ";
$DB->Execute($slq2); 
$pxpca=$DB->recogedato(0);
$pagospxp=number_format($pxpca,0,".",".");
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6(\"$param1\",\"detallepxp\",\"$param4\")';  style='cursor: pointer;' title='Detalle pxc' >$ $pagospxp</td>";


$sqlt="SELECT  sum(`asi_valor`), count(idasignaciondinero)
FROM `asignaciondinero` WHERE idasignaciondinero>0 and asi_tipo='Transpaso Dinero' and asi_idciudad=$id_sedes  $conde1 ORDER BY $conde, $ord $asc";
$DB->Execute($sqlt); 
$rwt=mysqli_fetch_row($DB->Consulta_ID);
$totaltranspaso=$rwt[0];
$ntranspaso=$rwt[1];
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6(\"$param1\",\"detalletranspaso\",\"$param4\")';  style='cursor: pointer;' title='Detalle pxc' >$ $totaltranspaso</td>";

$diaanterior=0;

$totalasignacion=str_replace('.','', $totalasignacion);
/* echo $contado."<br>";
echo $palcobroo."<br>";
echo $valorabono."<br>";
echo $totalgastos."<br>"; 
SELECT idclientesdir,cli_nombre, cli_direccion, COUNT(*) as num_repeticiones FROM clientesdir u 
    GROUP BY cli_nombre, cli_direccion 
    HAVING COUNT(*)>1

*/
$totalgastos=str_replace(".","", $totalgastos);
$contado=str_replace(".","", $contado);
$palcobroo=str_replace(".","", $palcobroo);
$valorabono=str_replace(".","", $valorabono);
//$devoluciong3=str_replace(".","", $devoluciong2); //devoluciones canceladas o sin numero de guia
$devoluciong3=str_replace(".","", $devoluciong); //devoluciones canceladas o sin numero de guia
$pendiente=str_replace(".","", $pendiente);
$presta=str_replace(".","", $presta);
$prestamoscreditos=str_replace(".","", $prestamoscreditos);
$comprasprestamos=$totalasignacion-$presta;
$totalremesas=str_replace(".","", $totalremesas);
$totalsededia=$contado+$palcobroo+$valorabono-$devoluciong3-$totalgastos-$totalremesas+$comprasprestamos+$pxc+$pxca+$pagospxp+$exedente+$prestamoscreditos;
// echo"$totalsededia=$contado+$palcobroo+$valorabono-$devoluciong3-$totalgastos-$totalremesas+$comprasprestamos+$pxc+$pxca+$pagospxp+$exedente+$prestamoscreditos";


$totalsededia=number_format($totalsededia,0,".",".");	

echo "</table><table><tr><td><table class='table table-hover'>";
$FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("TOTAL",1,0,0); 
$FB->titulo_azul1("DINERO DEL",1,0,0); 
$FB->titulo_azul1("DIA:",1,0,0); 
$FB->titulo_azul1("_________",1,0,0); 
$FB->titulo_azul1("$ $totalsededia",1,0,0); 

 $slq8="SELECT  sum(`asi_valor`) FROM `asignaciondinero` inner join usuarios on idusuarios=asi_idautoriza WHERE `asi_fecha` like '$param4%'  and asi_idciudad=$id_sedes and roles_idroles not in (2,3) and asi_tipo='entregado'";
$DB1->Execute($slq8); 
 $dinerorecogido=$DB1->recogedato(0);
 
 
$FB->titulo_azul1(" DINERO ",1,0,10); 
$FB->titulo_azul1("RECOGIDO",1,0,0); 	
$FB->titulo_azul1("POR EL",1,0,0); 	
$FB->titulo_azul1("ADMINISTRADOR",1,0,0); 	
$FB->titulo_azul1("_________",1,0,0); 
$dirreco=number_format($dinerorecogido,0,".",".");
//$FB->titulo_azul1("$ $dirreco",1,0,0); 
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param1,\"detallerecogido\",\"$param4\")';  style='cursor: pointer;' title='Detalle Contado' >$ $dirreco</td>";



$nuevafecha = strtotime ( '-1 day' , strtotime ($param4)) ;
$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
 $slq10="SELECT  `cus_dinerosede`,cus_fecha FROM `cuentassede`  WHERE  cus_idsede='$id_sedes' and  cus_fecha<='$param4' order by  idcuentassede DESC limit 1";
$DB1->Execute($slq10); 
$rw22=mysqli_fetch_row($DB1->Consulta_ID);
 $dineroanterior=$rw22[0];
 $dineroanteriorfecha=$rw22[1];


$FB->titulo_azul1("DINERO DEL",1,0,10); 
$FB->titulo_azul1("DIA",1,0,0); 
$FB->titulo_azul1(" ANTERIOR:",1,0,0); 
$FB->titulo_azul1("$dineroanteriorfecha",1,0,0); 
$FB->titulo_azul1("_________",1,0,0); 

/* $FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("GASTOS ",1,0,0); 
$FB->titulo_azul1(" OPERADOR:",1,0,0); 
$FB->titulo_azul1("$totalgastos",1,0,0); 
$FB->titulo_azul1("_________",1,0,0); 

$FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("GASTOS",1,0,0); 
$FB->titulo_azul1(" REMESAS:",1,0,0); 
$FB->titulo_azul1("$totalremesas",1,0,0); 
$FB->titulo_azul1("_________",1,0,0);  */

$diranterior=number_format($dineroanterior,0,".",".");
$FB->titulo_azul1("$ $diranterior",1,0,0); 

$FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("_________",1,0,0); 
$FB->titulo_azul1("GASTOS ",1,0,0); 
$FB->titulo_azul1("SEDE:",1,0,0); 
$FB->titulo_azul1("_________",1,0,0); 
$gasede=number_format($gastossede,0,".",".");
//$FB->titulo_azul1("$ $gasede",1,0,0); 
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param1,\"detallegastossede\",\"$param4\")';  style='cursor: pointer;' title='Detalle gastos sede' >$ $gasede</td>";


$slqs="SELECT sum(`deu_valor`) FROM `duedapromotor` WHERE `deu_fecha`='$param4' and deu_idciudad='$id_sedes' and deu_tipo='Prestamos' order by `deu_fecha` ";
$DB1->Execute($slqs); 
$prestamos=$DB1->recogedato(0);

$FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("_________",1,0,0); 
$FB->titulo_azul1("PRESTAMOS ",1,0,0); 
$FB->titulo_azul1("OPERADORES:",1,0,0); 
$FB->titulo_azul1("_________",1,0,0); 
$prestamosp=number_format($prestamos,0,".",".");
//$FB->titulo_azul1("$ $prestamosp",1,0,0); 
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param1,\"detalleprestamosoper\",\"$param4\")';  style='cursor: pointer;' title='Detalle Prestamos' >$ $prestamosp</td>";


$slq4="SELECT  sum(`deu_valor`) FROM `duedapromotor` WHERE `deu_fecha`='$param4' and deu_idciudad='$id_sedes'  and deu_tipo='Pagos'";
$DB1->Execute($slq4); 
 $pagosdia=$DB1->recogedato(0);

$FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("ABONO",1,0,0); 
$FB->titulo_azul1("DEUDA ",1,0,0); 
$FB->titulo_azul1("OPERADORES:",1,0,0); 
$FB->titulo_azul1("_________",1,0,0); 
$pagosdiap=number_format($pagosdia,0,".",".");
//$FB->titulo_azul1("$ $pagosdiap",1,0,0); 
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param1,\"detallegastosper\",\"$param4\")';  style='cursor: pointer;' title='Detalle Abonos Operador' >$ $pagosdiap</td>";


$slq4="SELECT  sum(`caj_valor`) FROM `cajamenor` WHERE `caj_fecharegistro` like '$param4%' and caj_idciudadori='$id_sedes' and caj_tipotransacion in ('Consignacion','Envio de Dinero Efectivo') ";
$DB1->Execute($slq4); 
 $enviados=$DB1->recogedato(0);

$FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("_________",1,0,0); 
$FB->titulo_azul1("DINEROS ",1,0,0); 
$FB->titulo_azul1("ENVIADOS:",1,0,0); 
$FB->titulo_azul1("_________",1,0,0); 
$dinenviados=number_format($enviados,0,".",".");
//$FB->titulo_azul1("$ $dinenviados",1,0,0); 
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param1,\"detalleenviados\",\"$param4\")';  style='cursor: pointer;' title='Detalle dinero enviados' >$ $dinenviados</td>";


  $slq4="SELECT  sum(`caj_cantcom`) FROM `cajamenor` WHERE `caj_feccom` like '$param4%' and caj_idciudaddes='$id_sedes' and caj_tipotransacion in ('Envio de Dinero Efectivo') ";
$DB1->Execute($slq4); 
 $recibidos=$DB1->recogedato(0);


$FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("_________",1,0,0); 
$FB->titulo_azul1("DINERO",1,0,0); 
$FB->titulo_azul1("RECIBIDO:",1,0,0); 
$FB->titulo_azul1("EFECTIVO",1,0,0); 
$dinrec=number_format($recibidos,0,".",".");
$FB->titulo_azul1("$ $dinrec",1,0,0); 

$slq4="SELECT  sum(`caj_cantcom`) FROM `cajamenor` WHERE `caj_feccom` like '$param4%' and caj_idciudaddes='$id_sedes' and caj_tipotransacion in ('Consignacion') ";
$DB1->Execute($slq4); 
 $recibidosconsi=$DB1->recogedato(0);


$FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("_________",1,0,0); 
$FB->titulo_azul1("DINERO",1,0,0); 
$FB->titulo_azul1("RECIBIDO:",1,0,0); 
$FB->titulo_azul1("CONSIGNACION",1,0,0); 
$dinreconsi=number_format($recibidosconsi,0,".",".");
$FB->titulo_azul1("$ $dinreconsi",1,0,0); 

$FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("_________",1,0,0); 
$FB->titulo_azul1("DINERO",1,0,0); 
$FB->titulo_azul1("TRANSFERIDO:",1,0,0); 
$FB->titulo_azul1("CUENTAS",1,0,0); 
$dinerotranferido=number_format($transvalor,0,".",".");
//$FB->titulo_azul1("$ $dinerotranferido",1,0,0); 
echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6(\"$param1\",\"detalletranferido\",\"$param4\")';  style='cursor: pointer;' title='Detalle Transferido' >$ $dinerotranferido</td></tr>";



$descuadre=0;
$slq4="SELECT   (sum(`caj_valor`) - sum(`caj_cantcom`)) as resta FROM `cajamenor` WHERE `caj_feccom` like '$param4%' and caj_idciudadori='$id_sedes' and caj_tipotransacion in ('Consignacion','Envio de Dinero Efectivo') ";
$DB1->Execute($slq4); 
 $descuadre=$DB1->recogedato(0);


$FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("DESCUADRE",1,0,0); 
$FB->titulo_azul1("DINERO",1,0,0); 
$FB->titulo_azul1("ENVIADOS:",1,0,0); 
$FB->titulo_azul1("_________",1,0,0); 
$dindescuadre=number_format($descuadre,0,".",".");

$FB->titulo_azul1("$ $dindescuadre",1,0,0);  


$FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("_________",1,0,0); 
$FB->titulo_azul1("RETIRO",1,0,0); 
$FB->titulo_azul1("BANCOS:",1,0,0); 
$FB->titulo_azul1("_________",1,0,0); 
$retirobancos=number_format($retiro,0,".",".");

$FB->titulo_azul1("$ $retirobancos",1,0,0);  

$FB->titulo_azul1("_________",1,0,10); 

$FB->titulo_azul1("DINERO",1,0,0); 
$FB->titulo_azul1("PARA ",1,0,0); 
$FB->titulo_azul1("COMPRAS:",1,0,0); 
$FB->titulo_azul1("_________",1,0,0); 
$totalasignacion2=number_format($totalasignacion,0,".",".");
$FB->titulo_azul1("$ $totalasignacion2",1,0,0);  

  $slq9="SELECT  `sed_base` FROM `sedes` WHERE  idsedes='$id_sedes' ";
$DB1->Execute($slq9); 
 $dinerobase=$DB1->recogedato(0);

$FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("DINERO",1,0,0); 
$FB->titulo_azul1("BASE DE",1,0,0); 
$FB->titulo_azul1("LA SEDE:",1,0,0); 
$FB->titulo_azul1("_________",1,0,0); 
$dinbase=number_format($dinerobase,0,".",".");
$FB->titulo_azul1("$ $dinbase",1,0,0);  

$totaldeuda=$dinerorecogido+$retiro+$dineroanterior+$dinerobase-$gastossede+$recibidos-$enviados+$descuadre-$totalasignacion-$prestamos+$pagosdia;
/* 
$data = array(dinerorecogido => "$dinerorecogido", retirobanco => "$retiro", dineroanterior => "$dineroanterior",
dinerobase => "$dinerobase", gastossede => "$gastossede", dinerorecibidos => "$recibidos", dineroenviados => "$enviados", totalsededia => "$totalsededia", dinerosede => "$totaldeuda");
 */

$totaldinerosede=number_format($totaldeuda,0,".",".");
$FB->titulo_azul1("_________",1,0,10); 
$FB->titulo_azul1("TOTAL",1,0,0); 
$FB->titulo_azul1("DINERO EN ",1,0,0); 
$FB->titulo_azul1("SEDE:",1,0,0); 
$FB->titulo_azul1("_________",1,0,0); 
$FB->titulo_azul1("$ $totaldinerosede",1,0,0); 
$totaldeuda=$totaldeuda-$dinerobase;

$data = array('dinerorecogido' => "$dinerorecogido", 'retirobanco' => "$retiro", 'dineroanterior' => "$dineroanterior",'dinerobase' => "$dinerobase", 'gastossede' => "$gastossede", 'dinerorecibidos' => "$recibidos", 'dineroenviados' => "$enviados", 'totalsededia' => "$totalsededia",'dinerosede' => "$totaldeuda");
//header('Content-Type: application/json');
 $valorenviar= json_encode($data);
 $hoy = date("Y-m-d");  
 $rvalida=0;
  $sqlvalida="SELECT count(*) FROM serviciosdia where ser_estado in (8,11) and ser_llego='SI'  and inner_sedes=$id_sedes and (ser_fechafinal<='$fechaactual' or ser_estentrega='NO EN SEDE' ) ";
 $DB1->Execute($sqlvalida); 
 $rvalida=$DB1->recogedato(0);

if($rvalida<=0 or $nivel_acceso==1){

	if($fechaactual==$hoy or $nivel_acceso==1){
		$FB->titulo_azul1("Hacer ",1,0,10); 
		$FB->titulo_azul1("Cierre del",1,0,0); 
		$FB->titulo_azul1("dia",1,0,0); 
		$FB->titulo_azul1("<a onclick='cerrarcuentas($totaldeuda,\"Cierre del dia\",$id_sedes,$valorenviar)';  style='cursor: pointer;' title='Entregar valor' ><img src='img/usar.png'></a>", 1,0,0); 
	}

} else{

	$FB->titulo_azul1("Debe validar las guias que estan en sede para poder hacer el cierre de la caja ",1,0,10); 
}

echo "</table></td><td colspan='10'><table class='table table-hover'><tr bgcolor='#04B404' class='tittle3'>
<td colspan='10'>Cuentas de Dinero</td></tr>";
echo '<tr bgcolor="#074F91" class="tittle3">
<td colspan="2" width="0" align="center">Moneda:</td>
<td colspan="4" width="0" align="center">Cantidad:</td>
<td colspan="4" width="0" align="center">Total:</td></tr>';
	echo "</tr><tr><td colspan='2'> 50</td><td align='right' colspan='4' ><input name='param5' id='param5'  type='text' value=''  onkeypress='return noenter();' autocomplete='off' onkeyup='cuentas(this.value,50,\"param6\",\"param5\");' ></td>";
	echo '<td colspan="4"><input name="param6" id="param6" type="text" value="0"></td></tr>';
	echo "</tr><tr><td colspan='2'> 100</td><td align='right' colspan='4' ><input name='param7' id='param7'  type='text' value=''  onkeypress='return noenter();' autocomplete='off' onkeyup='cuentas(this.value,100,\"param8\",\"param7\");' ></td>";
	echo '<td colspan="4"><input name="param8" id="param8" type="text" value="0"></td></tr>';
	echo "</tr><tr><td colspan='2'> 200</td><td align='right' colspan='4' ><input name='param9' id='param9'  type='text' value=''  onkeypress='return noenter();' autocomplete='off' onkeyup='cuentas(this.value,200,\"param10\",\"param9\");' ></td>";
	echo '<td colspan="4"><input name="param10" id="param10" type="text" value="0"></td></tr>';
	echo "</tr><tr><td colspan='2'> 500</td><td align='right' colspan='4' ><input name='param11' id='param11'  type='text' value=''  onkeypress='return noenter();' autocomplete='off' onkeyup='cuentas(this.value,500,\"param12\",\"param11\");' ></td>";
	echo '<td colspan="4"><input name="param12" id="param12" type="text" value="0"></td></tr>';
	echo "</tr><tr><td colspan='2'> 1.000</td><td align='right' colspan='4' ><input name='param13' id='param13'  type='text' value=''  onkeypress='return noenter();' autocomplete='off' onkeyup='cuentas(this.value,1000,\"param14\",\"param13\");' ></td>";
	echo '<td colspan="4"><input name="param14" id="param14" type="text" value="0"></td></tr>';
	echo "</tr><tr><td colspan='2'> 2.000</td><td align='right' colspan='4' ><input name='param15' id='param15'  type='text' value=''  onkeypress='return noenter();' autocomplete='off' onkeyup='cuentas(this.value,2000,\"param16\",\"param15\");' ></td>";
	echo '<td colspan="4"><input name="param16" id="param16" type="text" value="0"></td></tr>';
	echo "</tr><tr><td colspan='2'> 5.000</td><td align='right' colspan='4' ><input name='param17' id='param17'  type='text' value=''  onkeypress='return noenter();' autocomplete='off' onkeyup='cuentas(this.value,5000,\"param18\",\"param17\");' ></td>";
	echo '<td colspan="4"><input name="param18" id="param18" type="text" value="0"></td></tr>';
	echo "</tr><tr><td colspan='2'> 10.000</td><td align='right' colspan='4' ><input name='param19' id='param19'  type='text' value=''  onkeypress='return noenter();' autocomplete='off' onkeyup='cuentas(this.value,10000,\"param20\",\"param19\");' ></td>";
	echo '<td colspan="4"><input name="param20" id="param20" type="text" value="0"></td></tr>';
	echo "</tr><tr><td colspan='2'> 20.000</td><td align='right' colspan='4' ><input name='param21' id='param21'  type='text' value=''  onkeypress='return noenter();' autocomplete='off' onkeyup='cuentas(this.value,20000,\"param22\",\"param21\");' ></td>";
	echo '<td colspan="4"><input name="param22" id="param22" type="text" value="0"></td></tr>';
	echo "</tr><tr><td colspan='2'> 50.000</td><td align='right' colspan='4' ><input name='param23' id='param23'  type='text' value=''  onkeypress='return noenter();' autocomplete='off' onkeyup='cuentas(this.value,50000,\"param24\",\"param23\");' ></td>";
	echo '<td colspan="4"><input name="param24" id="param24" type="text" value="0"></td></tr>';
	echo "</tr><tr><td colspan='2'> 100.000</td><td align='right' colspan='4' ><input name='param25' id='param25'  type='text' value=''  onkeypress='return noenter();' autocomplete='off' onkeyup='cuentas(this.value,100000,\"param26\",\"param25\");' ></td>";
	echo '<td colspan="4"><input name="param26" id="param26" type="text" value="0"></td></tr>';
	$FB->titulo_azul1("TOTAL:",1,0,7); 
	echo '<td align="right">Total $:<input name="param117" id="param117" class="form-control" type="text" value="0"></td>';

	echo "</td></tr></table>";
//}
include("footer.php"); ?>

<script>

function cerrarcuentas(totaldeuda,caso,idsedes,jsonvalores)
{

var obj;
var conver='';
console.log(cantidad);

if (cantidad != null) {
		

for (indice in cantidad)
	{
		conver=conver+'"'+indice+'"'+":"+'"'+cantidad[indice]+'",';
	} 
	data=conver.substring(0,conver.length - 1);
	data='{'+data+'}';
	obj = data;
}else{
	obj =null;
}
	pop_dis26(totaldeuda,caso,idsedes,jsonvalores,obj);

}

var sedec = document.getElementById("param1").value;
var fechac = $("input#param4").val();
if(sedec!=0 && sedec!=''){
	datos = {"sede":sedec,"fecha":fechac};				
		$.ajax({
				url: "buscarcuentas.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){
				var obj = JSON.parse(respuesta);
				if (respuesta != null) {				
					var suma=0;
					for (var i in obj) {
						document.getElementById(i).value=obj[i];
						if(parseInt(obj[i])>=50){
							suma=parseInt(suma)+parseInt(obj[i]);
						}
						
						//document.getElementById(i).disabled = false;
					}
					document.getElementById("param117").value=suma;
				}			
		});
}


function verifica(){
		const numberInput = document.getElementById('dinero');
		const referenceNumber = document.getElementById('valorentrega').value;
		const warning = document.getElementById('errorval');
		const minLength = 1; 
		
			const value = numberInput.value;
	
			if (value.length >= minLength) {
				const numericValue = parseFloat(value);
	
				if (!isNaN(numericValue) && numericValue < referenceNumber) {
					warning.style.display = 'block';
					numberInput.value=referenceNumber;
				} else {
					warning.style.display = 'none';
				}
			} else {
				warning.style.display = 'none';
			}
		
		
		
		}
</script>