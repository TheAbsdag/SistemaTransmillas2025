<?php
	/* require("login_autentica.php");
	include("layout.php");  */
	$DB2 = new DB_mssql;
	$DB2->conectar();
	$nivel_acceso=$_SESSION['usuario_rol'];
	$id_usuario=$_SESSION['usuario_id'];
	if($param4=='ingresado'){
		$id_usuario=$iduser;
	}
?>

<script>
	function asignar(){
		var conver='';
		var obj;
		var chkdatos = document.getElementsByClassName("obtener");

		for(i=0;i<chkdatos.length;i++){
			if(chkdatos[i].checked){
				conver=conver+'"'+chkdatos[i].name+'"'+":"+'"'+chkdatos[i].value+'",';
			}
		}
		data=conver.substring(0,conver.length - 1);
		data='{'+data+'}';
		obj = data;
		console.log(obj);
		document.getElementById("data").value=obj;

		return true;
	}
</script>
<?php

if($param4=='covid19'){

	echo '<form action="nuevo_adminok.php" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="return asignar();" >';
	echo '<div id="contenedor1" style="display:flex;">';
	echo '<div id="primero" style="width: 100%; float:left;">';
	echo '<table class="table table-hover"><tr bgcolor="" class="tittle3"><td><picture> <img src="img/testcovid19.png" alt="test"  width="100%" height="50%" ></picture></td></tr><tr><td>';

	echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">TEST DE REPORTE DIARIO DE SINTOMATOLOGIA  </td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
	echo "<tr bgcolor='$color' class='text' id='covid1910'>";
	echo "<td colspan='2'>Ha sentido fatiga los últimos dos días?</td><td><input type='radio' name='covid191'  class='obtener optionCovid1'   value='1' required></td><td><input type='radio' name='covid191'  class='obtener'  value='2'></td>";
	echo "</tr>";
	echo "<tr bgcolor='$color' class='text' id='covid1920'>";
	echo "<td colspan='2'>Ha tenido fiebre mayor a 37,3?</td><td><input type='radio' name='covid192'  class='obtener optionCovid2'   value='1' required></td><td><input type='radio' name='covid192'  class='obtener'  value='2'></td>";
	echo "</tr>";
	echo "<tr bgcolor='$color' class='text' id='covid1930'>";
	echo "<td colspan='2'>Ha presentado tos seca?</td><td><input type='radio' name='covid193'  class='obtener optionCovid3'   value='1' required></td><td><input type='radio' name='covid193'  class='obtener'  value='2'></td>";
	echo "</tr>";
	echo "<tr bgcolor='$color' class='text' id='covid1940'>";
	echo "<td colspan='2'>Ha presentado dificultad para respirar?</td><td><input type='radio' name='covid194'  class='obtener optionCovid4'   value='1' required></td><td><input type='radio' name='covid194'  class='obtener'  value='2'></td>";
	echo "</tr>";
	echo "<tr bgcolor='$color' class='text' id='covid1950'>";
	echo "<td colspan='2'>Tiene dolor o molestia?</td><td><input type='radio' name='covid195'  class='obtener optionCovid5'   value='1' required></td><td><input type='radio' name='covid195'  class='obtener'  value='2'></td>";
	echo "</tr>";
	echo "<tr bgcolor='$color' class='text' id='covid1960'>";
	echo "<td colspan='2'>Tiene abundante secreción nasal?</td><td><input type='radio' name='covid196'  class='obtener optionCovid6'   value='1' required></td><td><input type='radio' name='covid196'  class='obtener'  value='2'></td>";
	echo "</tr>";
	echo "<tr bgcolor='$color' class='text' id='covid1970'>";
	echo "<td colspan='2'>Ha presentado dolor de garganta?</td><td><input type='radio' name='covid197'  class='obtener optionCovid7'   value='1' required></td><td><input type='radio' name='covid197'  class='obtener'  value='2'></td>";
	echo "</tr>";
	echo "<tr bgcolor='$color' class='text' id='covid1980'>";
	echo "<td colspan='2'>Realizo cambio de ropa de trabajo y esta se encuentra limpia?</td><td><input type='radio' name='covid198'  class='obtener'   value='1' required></td><td><input type='radio' name='covid198'  class='obtener optionCovid8'  value='2'></td>";
	echo "</tr>";
	echo "<tr bgcolor='$color' class='text' id='covid1990'>";
	echo "<td colspan='2'>realizo cambio de tapabocas convencional lavable suministrado por la empresa y este se encuentra limpio?</td><td><input type='radio' name='covid199'  class='obtener'   value='1' required></td><td><input type='radio' name='covid199'  class='obtener optionCovid9'  value='2'></td>";
	echo "</tr>";
	echo "<tr bgcolor='$color' class='text' id='temperatura'>";
	echo "<td colspan='4'>Temperarura:<input  name='param19' id='param19' value='$rw2[8]' style='width:395px'; class='text' ></td>";
	echo "</tr>";

	$FB->llena_texto("Imagen Temperatura:",20, 6, $DB, "", "", "",2, 0);


	if($nivel_acceso==3 or $mostrarpreguntas==1){

		echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">IMPLEMENTOS DE TRABAJO  </td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">CELULAR</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo "<tr bgcolor='$color' class='text' id='implementos10'>";
		echo "<td colspan='2'>Cuenta con celular con acceso a Internet?</td><td><input type='radio' name='implementos1'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos1'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos20'>";
		echo "<td colspan='2'>La bateria de su Celular se encuentra Cargada?</td><td><input type='radio' name='implementos2'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos2'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos30'>";
		echo "<td colspan='2'>Su celular cuenta con datos y minutos?</td><td><input type='radio' name='implementos3'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos3'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos40'>";
		echo "<td colspan='2'>Tiene usted el cargador de su Celular?</td><td><input type='radio' name='implementos4'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos4'  class='obtener'  value='2'></td>";
		echo "</tr>";
		/*echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">IMPRESORA</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo "<tr bgcolor='$color' class='text' id='implementos50'>";
		echo "<td colspan='2'>Cuenta con impresora suministrada por la Empresa?</td><td><input type='radio' name='implementos5'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos5'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='codigoimpresora'>";
		echo "<td colspan='4'>Cual es el Codigo de su Impresora:<input  name='param20' id='param20' value='$rw2[10]' style='width:395px'; class='text' ></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos60'>";
		echo "<td colspan='2'>La impresora se encuentra cargadal?</td><td><input type='radio' name='implementos6'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos6'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos70'>";
		echo "<td colspan='2'>Cuenta con suficiente papel para la impresora?</td><td><input type='radio' name='implementos7'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos7'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos80'>";
		echo "<td colspan='2'>Cuneta con el cargador de la Impresora?</td><td><input type='radio' name='implementos8'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos8'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos90'>";
		echo "<td colspan='2'>Verifico que la Impresora este configurada con su celular?</td><td><input type='radio' name='implementos9'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos9'  class='obtener'  value='2'></td>";
		echo "</tr>";*/

		echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">PESA</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo "<tr bgcolor='$color' class='text' id='implementos100'>";
		echo "<td colspan='2'>Cuenta con Pesa?</td><td><input type='radio' name='implementos10'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos10'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos110'>";
		echo "<td colspan='2'>Su Pesa cuenta con Bateria?</td><td><input type='radio' name='implementos11'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos11'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos120'>";
		echo "<td colspan='2'>Verifico que su Pesa cuente con bateria?</td><td><input type='radio' name='implementos12'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos12'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos130'>";
		echo "<td colspan='2'>Verifico que su Pesa este funcionando Perfectamente?</td><td><input type='radio' name='implementos13'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos13'  class='obtener'  value='2'></td>";
		echo "</tr>";

		echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">MALETA</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo "<tr bgcolor='$color' class='text' id='implementos140'>";
		echo "<td colspan='2'>Cuenta con Maleta?</td><td><input type='radio' name='implementos14'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos14'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='maleta'>";
		echo "<td colspan='4'>Ultima vez que desinfecto la maleta:<input  name='param21' id='param21' value='$rw2[11]' style='width:395px'; class='text' ></td>";
		echo "</tr>";

		/*echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">CARNET Y CARTA DE MOVILIDAD</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo "<tr bgcolor='$color' class='text' id='implementos160'>";
		echo "<td colspan='2'>Cuenta con Carnet?</td><td><input type='radio' name='implementos16'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos16'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos170'>";
		echo "<td colspan='2'>Cuenta con carta de movilidad?</td><td><input type='radio' name='implementos17'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos17'  class='obtener'  value='2'></td>";
		echo "</tr>";*/

		echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">PARAFISCALES O COPIA DE AFILIACION DE ARL</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo "<tr bgcolor='$color' class='text' id='implementos180'>";
		echo "<td colspan='2'>Tiene copia de pago de parafiscales?</td><td><input type='radio' name='implementos18'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos18'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos190'>";
		echo "<td colspan='2'>Tiene copia de Afiliacion ARL(Peronal Nuevo)?</td><td><input type='radio' name='implementos19'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos19'  class='obtener'  value='2'></td>";
		echo "</tr>";

	}

	echo '<tr bgcolor="#878787" class="tittle3"><td colspan="4" ><p align="center">YO COMO TRABAJADOR DE LA EMPRESA TRANSMILLAS ME COMPROMETO A:
	**Reportar mis síntomas en caso de presentar y asi mismo a las Entidades a las que haya lugar. </p><br>
	<p align="left">
	Al salir de la vivienda<br>
	1. Estar atento a las indicaciones de la autoridad local sobre restricciones a la movilidad y acceso a lugares públicos.  <br>
	2. Visitar solamente aquellos lugares estrictamente necesarios y evitar conglomeraciones de personas.  <br>
	3. Asignar un adulto para hacer las compras, que no pertenezca a ningún grupo de alto riesgo.  <br>
	4. Restringir las visitas a familiares y amigos y si alguno presenta cuadro respiratorio. <br>
	5. Evitar saludar con besos, abrazos o de mano.  <br>
	6. Utilizar tapabocas en áreas de afluencia masiva de personas, en el transporte público, supermercados, bancos, entre otros, así como en los casos de sintomatología respiratoria o si es persona en grupo de riesgo.
	<br>Al regresar a la vivienda<br>
	1. Retirar los zapatos a la entrada y lavar la suela con agua y jabón.  <br>
	2. Lavar las manos de acuerdo a los protocolos del Ministerio de Salud y Protección Social.  <br>
	3. Evitar saludar con beso, abrazo y dar la mano y buscar mantener siempre la distancia de más de dos metros entre personas. <br>
	4. Antes de tener contacto con los miembros de familia, cambiarse de ropa. <br>
	5. Mantener separada la ropa de trabajo de las prendas personales. <br>
	6. La ropa debe lavarse en la lavadora a más de 60 grados centígrados o a mano con agua caliente que no queme las manos y jabón, y secar por completo. No reutilizar ropa sin antes lavarla. <br>
	7. Bañarse con abundante agua y jabón. <br>
	8. Desinfectar con alcohol o lavar con agua y jabón los elementos que han sido manipulados al exterior de la vivienda. <br>
	9. Mantener la casa ventilada, limpiar y desinfectar áreas, superficies y objetos de manera regular. <br>
	10. Si hay alguna persona con síntomas de gripa en la casa, tanto la persona con síntomas de gripa como quienes cuidan de ella deben utilizar tapabocas de manera constante en el hogar. <br>
	<br>Al convivir con una persona de alto riesgo
	 Si el trabajador convive con personas mayores de 60 años, con enfermedades preexistentes de alto riesgo para el COVID-19, o con personal de servicios de salud, debe: <br>
	1. Mantener la distancia siempre mayor a dos metros. <br>
	2. Utilizar tapabocas en casa, especialmente al encontrarse en un mismo espacio que la persona a riesgo y al cocinar y servir la comida. <br>
	3. Aumentar la ventilación del hogar. <br>
	4. Asignar un baño y habitación individual para la persona que tiene riesgo, si es posible. Si no lo es, aumentar ventilación, limpieza y desinfección de superficies. <br>
	5. Cumplir a cabalidad con las recomendaciones de lavado de manos e higiene respiratoria impartidas por el Ministerio de Salud y Protección Social</p>
	</td></tr>';

	echo '<tr bgcolor="#ff0000" class="tittle3"><td colspan="4" >Declaro que toda la información suministrada en el test anterior es verídica, de caso contrario puede acarrear sanciones disciplinarias y su correspondiente aviso a las autoridades competentes que haya lugar.</td></tr>';

	if($param5=='valida'){

		$validatitulo='COVID 19';

	echo '<tr  bgcolor="#868A08" class="tittle3"><td colspan="4" width="4" align="center">VALIDA '.$validatitulo.'</td></tr>';
	echo "<tr bgcolor='$color' class='text' id='validapreopera'>";
	echo "<td colspan='4'><textarea colspan='4' name='param10' id='param10' value='' style='width:395px'; class='text' >$rw2[3]</textarea></td>";
	echo "</tr>";
	}

}else{
	$fechaactual=date('Y-m-d');
	if($param5=='nuevo'){

		$sql="SELECT `idvehiculos`, `veh_tipo`, `veh_placa`, `veh_marca`, `veh_modelo`, `veh_kilactual`, usu_nombre,usu_identificacion,usu_licencia,usu_fechalicencia,`veh_aceitekil`, `veh_dueño`,  `veh_fechaseguro`, `veh_fechategnomecanica`, `veh_fechamantenimiento` FROM `vehiculos` inner join usuarios on  usu_vehiculo=idvehiculos  where idusuarios=$id_usuario and (usu_estado=1 or usu_filtro=1) ";

	}else{
		$sql="SELECT `idvehiculos`, `veh_tipo`, `veh_placa`, `veh_marca`, `veh_modelo`, `veh_kilactual`, usu_nombre,usu_identificacion,usu_licencia,usu_fechalicencia,`veh_aceitekil`, `veh_dueño`,  `veh_fechaseguro`, `veh_fechategnomecanica`, `veh_fechamantenimiento` FROM `vehiculos` inner join usuarios  where idvehiculos='$idvehiculo' and idusuarios=$id_usuario ";
	}


$DB1->Execute($sql); $va=0;
while($rw1=mysqli_fetch_row($DB1->Consulta_ID))
{
	$id_p=$rw1[0]; $va++;
	$tipovehiculo=strtoupper($rw1[1]);
	$placa=strtoupper($rw1[2]);
	$marca=strtoupper($rw1[3]);
	$modelo=strtoupper($rw1[4]);
	$kmactual=strtoupper($rw1[5]);
	$user=explode(' ',$rw1[6],2);
	$usuarioname=$user[0].' '.$user[1];
	$cedula=strtoupper($rw1[7]);
	$licencia=strtoupper($rw1[8]);
	$fechalicencia=strtoupper($rw1[9]);
}


echo '<form action="nuevo_adminok.php" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="return asignar();" >';
echo '<div id="contenedor1" style="display:flex;">';
echo '<div id="primero" style="width: 95%; float:left;">';

echo '<table class="table table-hover">';

echo '<tr bgcolor="" class="tittle3"><td colspan="4" ><picture> <img src="img/testcovid19.png" alt="test"  width="100%" height="50%" ></picture></td></tr>';

echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">TEST DE REPORTE DIARIO DE SINTOMATOLOGIA  </td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
echo "<tr bgcolor='$color' class='text' id='covid1910'>";
echo "<td colspan='2'>Ha sentido fatiga los últimos dos días?</td><td><input type='radio' name='covid191'  class='obtener'   value='1' required></td><td><input type='radio' name='covid191'  class='obtener'  value='2'></td>";
echo "</tr>";
echo "<tr bgcolor='$color' class='text' id='covid1920'>";
echo "<td colspan='2'>Ha tenido fiebre mayor a 37,3?</td><td><input type='radio' name='covid192'  class='obtener'   value='1' required></td><td><input type='radio' name='covid192'  class='obtener'  value='2'></td>";
echo "</tr>";
echo "<tr bgcolor='$color' class='text' id='covid1930'>";
echo "<td colspan='2'>Ha presentado tos seca?</td><td><input type='radio' name='covid193'  class='obtener'   value='1' required></td><td><input type='radio' name='covid193'  class='obtener'  value='2'></td>";
echo "</tr>";
echo "<tr bgcolor='$color' class='text' id='covid1940'>";
echo "<td colspan='2'>Ha presentado dificultad para respirar?</td><td><input type='radio' name='covid194'  class='obtener'   value='1' required></td><td><input type='radio' name='covid194'  class='obtener'  value='2'></td>";
echo "</tr>";
echo "<tr bgcolor='$color' class='text' id='covid1950'>";
echo "<td colspan='2'>Tiene dolor o molestia?</td><td><input type='radio' name='covid195'  class='obtener'   value='1' required></td><td><input type='radio' name='covid195'  class='obtener'  value='2'></td>";
echo "</tr>";
echo "<tr bgcolor='$color' class='text' id='covid1960'>";
echo "<td colspan='2'>Tiene abundante secreción nasal?</td><td><input type='radio' name='covid196'  class='obtener'   value='1' required></td><td><input type='radio' name='covid196'  class='obtener'  value='2'></td>";
echo "</tr>";
echo "<tr bgcolor='$color' class='text' id='covid1970'>";
echo "<td colspan='2'>Ha presentado dolor de garganta?</td><td><input type='radio' name='covid197'  class='obtener'   value='1' required></td><td><input type='radio' name='covid197'  class='obtener'  value='2'></td>";
echo "</tr>";
echo "<tr bgcolor='$color' class='text' id='covid1980'>";
echo "<td colspan='2'>Realizo cambio de ropa de trabajo y esta se encuentra limpia?</td><td><input type='radio' name='covid198'  class='obtener'   value='1' required></td><td><input type='radio' name='covid198'  class='obtener'  value='2'></td>";
echo "</tr>";
echo "<tr bgcolor='$color' class='text' id='covid1990'>";
echo "<td colspan='2'>realizo cambio de tapabocas convencional lavable suministrado por la empresa y este se encuentra limpio?</td><td><input type='radio' name='covid199'  class='obtener'   value='1' required></td><td><input type='radio' name='covid199'  class='obtener'  value='2'></td>";
echo "</tr>";
echo "<tr bgcolor='$color' class='text' id='temperatura'>";
echo "<td colspan='4'>Temperarura:<input  name='param19' id='param19' value='$rw2[8]' style='width:395px'; class='text' ></td>";
echo "</tr>";
$FB->llena_texto("Imagen Temperatura:",20, 6, $DB, "", "", "",2, 0);

echo '<tr bgcolor="#878787" class="tittle3"><td colspan="4" ><p align="center">YO COMO TRABAJADOR DE LA EMPRESA TRANSMILLAS ME COMPROMETO A:
**Reportar mis síntomas en caso de presentar y asi mismo a las Entidades a las que haya lugar. </p><br>
<p align="left">
Al salir de la vivienda<br>
1. Estar atento a las indicaciones de la autoridad local sobre restricciones a la movilidad y acceso a lugares públicos.  <br>
2. Visitar solamente aquellos lugares estrictamente necesarios y evitar conglomeraciones de personas.  <br>
3. Asignar un adulto para hacer las compras, que no pertenezca a ningún grupo de alto riesgo.  <br>
4. Restringir las visitas a familiares y amigos y si alguno presenta cuadro respiratorio. <br>
5. Evitar saludar con besos, abrazos o de mano.  <br>
6. Utilizar tapabocas en áreas de afluencia masiva de personas, en el transporte público, supermercados, bancos, entre otros, así como en los casos de sintomatología respiratoria o si es persona en grupo de riesgo.
<br>Al regresar a la vivienda<br>
1. Retirar los zapatos a la entrada y lavar la suela con agua y jabón.  <br>
2. Lavar las manos de acuerdo a los protocolos del Ministerio de Salud y Protección Social.  <br>
3. Evitar saludar con beso, abrazo y dar la mano y buscar mantener siempre la distancia de más de dos metros entre personas. <br>
4. Antes de tener contacto con los miembros de familia, cambiarse de ropa. <br>
5. Mantener separada la ropa de trabajo de las prendas personales. <br>
6. La ropa debe lavarse en la lavadora a más de 60 grados centígrados o a mano con agua caliente que no queme las manos y jabón, y secar por completo. No reutilizar ropa sin antes lavarla. <br>
7. Bañarse con abundante agua y jabón. <br>
8. Desinfectar con alcohol o lavar con agua y jabón los elementos que han sido manipulados al exterior de la vivienda. <br>
9. Mantener la casa ventilada, limpiar y desinfectar áreas, superficies y objetos de manera regular. <br>
10. Si hay alguna persona con síntomas de gripa en la casa, tanto la persona con síntomas de gripa como quienes cuidan de ella deben utilizar tapabocas de manera constante en el hogar. <br>
<br>Al convivir con una persona de alto riesgo
 Si el trabajador convive con personas mayores de 60 años, con enfermedades preexistentes de alto riesgo para el COVID-19, o con personal de servicios de salud, debe: <br>
1. Mantener la distancia siempre mayor a dos metros. <br>
2. Utilizar tapabocas en casa, especialmente al encontrarse en un mismo espacio que la persona a riesgo y al cocinar y servir la comida. <br>
3. Aumentar la ventilación del hogar. <br>
4. Asignar un baño y habitación individual para la persona que tiene riesgo, si es posible. Si no lo es, aumentar ventilación, limpieza y desinfección de superficies. <br>
5. Cumplir a cabalidad con las recomendaciones de lavado de manos e higiene respiratoria impartidas por el Ministerio de Salud y Protección Social</p>
</td></tr>';

echo '<tr bgcolor="#ff0000" class="tittle3"><td colspan="4" >Declaro que toda la información suministrada en el test anterior es verídica, de caso contrario puede acarrear sanciones disciplinarias y su correspondiente aviso a las autoridades competentes que haya lugar.</td></tr>';

echo '<tr bgcolor="#868A08" class="tittle3"><td  colspan="4" >PRE-OPERACIONAL DE '.$tipovehiculo.'</td></tr>';

	echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" align="center">PLACA</td>
	<td colspan="1" align="center">MARCA</td>
	<td colspan="1"  align="center">MODELO</td>
	<td colspan="1" align="center">KM</td></tr>';

	$color="#EFEFEF";


	echo "<tr bgcolor='$color' class='text' style='background-color:$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"'
 onmouseout='this.style.backgroundColor=\"$color\"'>";
 echo "<td>$placa</td><td>$marca</td><td>".$modelo."</td><td>".$kmactual."</td>";
 echo "</tr>";
 echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" align="center">CONDUCTOR</td><td colspan="1" align="center">CEDULA</td><td colspan="1"  align="center">LICENCIA</td><td colspan="1" align="center">FECHA VENC</td></tr>';
 echo "<tr bgcolor='$color' class='text' style='background-color:$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"'
 onmouseout='this.style.backgroundColor=\"$color\"'>";
 echo "<td>$usuarioname</td><td>$cedula</td><td>".$licencia."</td><td>".$fechalicencia."</td>";
 echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" align="center">ITEM</td>
	   <td colspan="1" align="center">SI</td>
	   <td colspan="1"  align="center">NO</td>
	   <td colspan="1" align="center">N.A</td></tr>';

if($tipovehiculo=='MOTO'){

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" width="4" align="center">LLANTAS Y RINES</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td><td colspan="1" width="4" align="center">N.A</td></tr>';
	   echo "<tr bgcolor='$color' class='text'  id='llantas10' >";
	   echo "<td>Tienen rajaduras por un objeto condundecte?</td><td><input type='radio' name='llantas1'  class='obtener'   value='1' required></td><td><input type='radio' name='llantas1'  class='obtener'  value='2'></td><td><input type='radio' name='llantas1'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='llantas20'>";
	   echo "<td>Tienen grietas finas en los laterales?</td><td><input type='radio' name='llantas2'  class='obtener'   value='1' required></td><td><input type='radio' name='llantas2'  class='obtener'  value='2'></td><td><input type='radio' name='llantas2'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='llantas30'>";
	   echo "<td>Las llantas estan desinfladas?</td><td><input type='radio' name='llantas3'  class='obtener'   value='1' required></td><td><input type='radio' name='llantas3'  class='obtener'  value='2'></td><td><input type='radio' name='llantas3'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='llantas40'>";
	   echo "<td>Las llantas estan sobreinfladas?</td><td><input type='radio' name='llantas4'  class='obtener'   value='1' required></td><td><input type='radio' name='llantas4'  class='obtener'  value='2'></td><td><input type='radio' name='llantas4'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='llantas50'>";
	   echo "<td>Los rines y guardabarros estan en buen estado?</td><td><input type='radio' name='llantas5'  class='obtener'   value='1' required></td><td><input type='radio' name='llantas5'  class='obtener'  value='2'></td><td><input type='radio' name='llantas5'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='llantas60'>";
	   echo "<td>Funcionan correctamente la suspensión y frenos de llantas?</td><td><input type='radio' name='llantas6'  class='obtener'   value='1' required></td><td><input type='radio' name='llantas6'  class='obtener'  value='2'></td><td><input type='radio' name='llantas6'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" width="4" align="center">TRANSMISIÓN</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td><td colspan="1" width="4" align="center">N.A</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='transmision10'>";
	   echo "<td>La cadena brilla? (Necesita engrase)</td><td><input type='radio' name='transmision1'  class='obtener'   value='1' required></td><td><input type='radio' name='transmision1'  class='obtener'  value='2'></td><td><input type='radio' name='transmision1'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='transmision20'>";
	   echo "<td>La cadena esta mal tensionada? (se oye al rodar)</td><td><input type='radio' name='transmision2'  class='obtener'   value='1' required></td><td><input type='radio' name='transmision2'  class='obtener'  value='2'></td><td><input type='radio' name='transmision2'  class='obtener'  value='3'></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" width="4" align="center">LUCES Y ESPEJOS </td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td><td colspan="1" width="4" align="center">N.A</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='Luces10'>";
	   echo "<td>Funcionan correctamente las Luces de cruce y de frenado?</td><td><input type='radio' name='Luces1'  class='obtener'   value='1' required></td><td><input type='radio' name='Luces1'  class='obtener'  value='2'></td><td><input type='radio' name='Luces1'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='Luces20'>";
	   echo "<td>Los espejos estan en perfecto estado?</td><td><input type='radio' name='Luces2'  class='obtener'   value='1' required></td><td><input type='radio' name='Luces2'  class='obtener'  value='2'></td><td><input type='radio' name='Luces2'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='Luces30'>";
	   echo "<td>El manubrio está en óptimas condiciones?</td><td><input type='radio' name='Luces3'  class='obtener'   value='1' required></td><td><input type='radio' name='Luces3'  class='obtener'  value='2'></td><td><input type='radio' name='Luces3'  class='obtener'  value='3'></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" width="4" align="center">FUGAS</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td><td colspan="1" width="4" align="center">N.A</td></tr>';
       echo "<tr bgcolor='$color' class='text' id='fugas10'>";
	   echo "<td>Fugas en el liquido de suspensión y de frenos?</td><td><input type='radio' name='fugas1'  class='obtener'   value='1' required></td><td><input type='radio' name='fugas1'  class='obtener'  value='2'></td><td><input type='radio' name='fugas1'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='fugas20'>";
	   echo "<td>Fugas en el sistema de trasmisión (cardán, diferencial)?</td><td><input type='radio' name='fugas2'  class='obtener'   value='1' required></td><td><input type='radio' name='fugas2'  class='obtener'  value='2'></td><td><input type='radio' name='fugas2'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='fugas30'>";
	   echo "<td>fugas en el aceite del motor y liquido de refrigeración?</td><td><input type='radio' name='fugas3'  class='obtener'   value='1' required></td><td><input type='radio' name='fugas3'  class='obtener'  value='2'></td><td><input type='radio' name='fugas3'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='fugas40'>";
	   echo "<td>Fugas en el fluido que pasa por la caja de cambios?</td><td><input type='radio' name='fugas4'  class='obtener'   value='1' required></td><td><input type='radio' name='fugas4'  class='obtener'  value='2'></td><td><input type='radio' name='fugas4'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='fugas50'>";
	   echo "<td>Fugas en el tanque de combustible?</td><td><input type='radio' name='fugas5'  class='obtener'   value='1' required></td><td><input type='radio' name='fugas5'  class='obtener'  value='2'></td><td><input type='radio' name='fugas5'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='fugas60'>";
	   echo "<td>Fugas de gases en el mofle (deterioro)?</td><td><input type='radio' name='fugas6'  class='obtener'   value='1' required></td><td><input type='radio' name='fugas6'  class='obtener'  value='2'></td><td><input type='radio' name='fugas6'  class='obtener'  value='3'></td>";
	   echo "</tr>";


	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" width="4" align="center">MANDOS (CAMBIOS, FRENOS)</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td><td colspan="1" width="4" align="center">N.A</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='mandos10'>";
	   echo "<td>El embrague esta endurecido?</td><td><input type='radio' name='mandos1'  class='obtener'   value='1' required></td><td><input type='radio' name='mandos1'  class='obtener'  value='2'></td><td><input type='radio' name='mandos1'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='mandos20'>";
	   echo "<td>El cable de acelerador vuelve del todo a su punto inical?</td><td><input type='radio' name='mandos2'  class='obtener'   value='1' required></td><td><input type='radio' name='mandos2'  class='obtener'  value='2'></td><td><input type='radio' name='mandos2'  class='obtener'  value='3'></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" width="4" align="center">ENTORNO GENERAL </td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td><td colspan="1" width="4" align="center">N.A</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='entorno10'>";
	   echo "<td>La moto esta en buenas condiciones de limpieza?</td><td><input type='radio' name='entorno1'  class='obtener'   value='1' required></td><td><input type='radio' name='entorno1'  class='obtener'  value='2'></td><td><input type='radio' name='entorno1'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='entorno20'>";
	   echo "<td>Esta deteriorado el chasis de la moto? (oxidación, abolladuras, partes faltantes)?</td><td><input type='radio' name='entorno2'  class='obtener'   value='1' required></td><td><input type='radio' name='entorno2'  class='obtener'  value='2'></td><td><input type='radio' name='entorno2'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='entorno30'>";
	   echo "<td>Caja de Herramientas </td><td><input type='radio' name='entorno3'  class='obtener'   value='1' required></td><td><input type='radio' name='entorno3'  class='obtener'  value='2'></td><td><input type='radio' name='entorno3'  class='obtener'  value='3'></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" width="4" align="center">ELEMENTOS DE PROTECCIÓN  </td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td><td colspan="1" width="4" align="center">N.A</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='elementos10'>";
	   echo "<td>Se dispone de casco para moto en buen estado?</td><td><input type='radio' name='elementos1'  class='obtener'   value='1' required></td><td><input type='radio' name='elementos1'  class='obtener'  value='2'></td><td><input type='radio' name='elementos1'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementos20'>";
	   echo "<td>Se dispone de guantes para moto?</td><td><input type='radio' name='elementos2'  class='obtener'   value='1' required></td><td><input type='radio' name='elementos2'  class='obtener'  value='2'></td><td><input type='radio' name='elementos2'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementos30'>";
	   echo "<td>Se dispone de gafas protectoras?</td><td><input type='radio' name='elementos3'  class='obtener'   value='1' required></td><td><input type='radio' name='elementos3'  class='obtener'  value='2'></td><td><input type='radio' name='elementos3'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementos40'>";
	   echo "<td>Se dispone de chaleco reflectivo para las horas de la noche?</td><td><input type='radio' name='elementos4'  class='obtener'   value='1' required></td><td><input type='radio' name='elementos4'  class='obtener'  value='2'></td><td><input type='radio' name='elementos4'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementos50'>";
	   echo "<td>Se dispone de impermeable para temporadas de lluvias? </td><td><input type='radio' name='elementos5'  class='obtener'   value='1' required></td><td><input type='radio' name='elementos5'  class='obtener'  value='2'></td><td><input type='radio' name='elementos5'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementos60'>";
	   echo "<td >SE REALIZÒ LA LIMPIEZA Y DESINFECCION  DE LA MOTO?</td><td><input type='radio' name='elementos6'  class='obtener'   value='1' required></td><td><input type='radio' name='elementos6'  class='obtener'  value='2'></td><td><input type='radio' name='elementos6'  class='obtener'  value='3'></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#868A08" class="tittle3"><td colspan="4" >SI alguno de estos puntos tiene al menos como respuesta un SI, el trabajador debe de manera inmediata dar aviso de sus condciones al Jefe de operaciones de la empresa TRANSMILLAS EMPRESA DE CARGA Y LOGISTICA.</td></tr>';

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">CHECK LIST FATIGA   </td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='elementosp10'>";
	   echo "<td colspan='2'>TENGO SUEÑO?</td><td><input type='radio' name='elementosp1'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp1'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementosp20'>";
	   echo "<td colspan='2'>SIENTO LA VISTA CANSADA?</td><td><input type='radio' name='elementosp2'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp2'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementosp30'>";
	   echo "<td colspan='2'>ME ENCUENTRO TOMANDO MEDICAMENTOS QUE ME IMPIDAN OPERAR O ALTERE MI CONCENTRACIÓN?</td><td><input type='radio' name='elementosp3'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp3'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementosp40'>";
	   echo "<td colspan='2'>ME CUESTA ENFOCAR LA VISTA (VISIÓN BORROSA) O MANTENER  LOS OJOS ABIERTOS?</td><td><input type='radio' name='elementosp4'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp4'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementosp50'>";
	   echo "<td colspan='2'>SIENTO DIFICULTADES PARA CONCENTRARME O PERMANECER ALERTA?</td><td><input type='radio' name='elementosp5'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp5'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementosp60'>";
	   echo "<td colspan='2'>ME SIENTO EN MALAS CONDICIONES (FISICAS Y/O ANIMICAS) PARA REALIZAR MIS TAREAS?</td><td><input type='radio' name='elementosp6'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp6'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementosp70'>";
	   echo "<td colspan='2'>SE ENCUENTRA BAJO ALGÙN EFECTO DE ALCHOHOL O DROGAS?</td><td><input type='radio' name='elementosp7'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp7'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='klmactual'>";
	   echo "<td colspan='4'>KILOMETRAJE ACTUAL: <input  name='param12' id='param12' value='$rw2[9]' style='width:395px'; class='text' ></td>";
	   if ($preoperacional=='validarpreoperacional') {

		if ($rw2[13]=="") {
			
			$FB->llena_texto("Imagen Kilometraje:",30, 6, $DB, "", "", "",2,1);
		}else{
			$fotokilo="preoperacional/$rw2[13]";
			$FB->llena_texto("Imagen Kilometraje1",30, 6, $DB, "", "", "$fotokilo",2, 0);
		}
		
		}else if($preoperacional=='preoperacional') {
			$FB->llena_texto("Imagen Kilometraje:",30, 6, $DB, "", "", "",2, 1);
		}
		echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="4" width="4" align="center">OBSERVACIONES/ CONDICIONES REPORTADAS </td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='observacionesc'>";
	   echo "<td colspan='4'><textarea colspan='4'name='param7' id='param7' value='' style='width:395px'; class='text' >$rw2[0]</textarea></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="4" width="4" align="center">ACCIÓN CORRECTIVA</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='accioncorrectiva'>";
	   echo "<td colspan='4'><textarea colspan='4' name='param8' id='param8' value='' style='width:395px'; class='text' >$rw2[1]</textarea></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="4" width="4" align="center">RESPONSABLE</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='responsable'>";
	   echo "<td colspan='4'><input  name='param9' id='param9' value='$rw2[2]' style='width:395px'; class='text' ></td>";
	   echo "</tr>";


}elseif($tipovehiculo=='CARRO'){

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" width="4" align="center">DIRECCIONALES  </td><td colspan="1" width="4" align="center">B</td><td colspan="1" width="4" align="center">M</td><td colspan="1" width="4" align="center">N.A</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='direccionales10' >";
	   echo "<td>Frontales Plenas altas y/o bajas</td><td><input type='radio' name='direccionales1'  class='obtener'   value='1' required></td><td><input type='radio' name='direccionales1'  class='obtener'  value='2'></td><td><input type='radio' name='direccionales1'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='direccionales20' >";
	   echo "<td>Direccionales delanteras de parqueo</td><td><input type='radio' name='direccionales2'  class='obtener'   value='1' required></td><td><input type='radio' name='direccionales2'  class='obtener'  value='2'></td><td><input type='radio' name='direccionales2'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='direccionales30'>";
	   echo "<td>Direccionales traseras de parqueo </td><td><input type='radio' name='direccionales3'  class='obtener'   value='1' required></td><td><input type='radio' name='direccionales3'  class='obtener'  value='2'></td><td><input type='radio' name='direccionales3'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='direccionales40'>";
	   echo "<td>De Stop y señal trasera</td><td><input type='radio' name='direccionales4'  class='obtener'   value='1' required></td><td><input type='radio' name='direccionales4'  class='obtener'  value='2'></td><td><input type='radio' name='direccionales4'  class='obtener'  value='3'></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" width="4" align="center">CABINA  </td><td colspan="1" width="4" align="center">B</td><td colspan="1" width="4" align="center">M</td><td colspan="1" width="4" align="center">N.A</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='cabina10'>";
	   echo "<td>Espejo central o retrovisor</td><td><input type='radio' name='cabina1'  class='obtener'   value='1' required></td><td><input type='radio' name='cabina1'  class='obtener'  value='2'></td><td><input type='radio' name='cabina1'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='cabina20'>";
	   echo "<td>Espejos laterales</td><td><input type='radio' name='cabina2'  class='obtener'   value='1' required></td><td><input type='radio' name='cabina2'  class='obtener'  value='2'></td><td><input type='radio' name='cabina2'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='cabina30'>";
	   echo "<td>Alarma de retroceso </td><td><input type='radio' name='cabina3'  class='obtener'   value='1' required></td><td><input type='radio' name='cabina3'  class='obtener'  value='2'></td><td><input type='radio' name='cabina3'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='cabina40'>";
	   echo "<td>Cojineria</td><td><input type='radio' name='cabina4'  class='obtener'   value='1' required></td><td><input type='radio' name='cabina4'  class='obtener'  value='2'></td><td><input type='radio' name='cabina4'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='cabina50'>";
	   echo "<td>Vidrio frontal  </td><td><input type='radio' name='cabina5'  class='obtener'   value='1' required></td><td><input type='radio' name='cabina5'  class='obtener'  value='2'></td><td><input type='radio' name='cabina5'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='cabina60'>";
	   echo "<td>Nivel de agua del parabrisas</td><td><input type='radio' name='cabina6'  class='obtener'   value='1' required></td><td><input type='radio' name='cabina6'  class='obtener'  value='2'></td><td><input type='radio' name='cabina6'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='cabina70'>";
	   echo "<td>Vidrios Laterales o cortabrisas   </td><td><input type='radio' name='cabina7'  class='obtener'   value='1' required></td><td><input type='radio' name='cabina7'  class='obtener'  value='2'></td><td><input type='radio' name='cabina7'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='cabina80'>";
	   echo "<td>Vidrio trasero</td><td><input type='radio' name='cabina8'  class='obtener'   value='1' required></td><td><input type='radio' name='cabina8'  class='obtener'  value='2'></td><td><input type='radio' name='cabina8'  class='obtener'  value='3'></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" width="4" align="center">DISPOSITIVOS DE SEGURIDAD   </td><td colspan="1" width="4" align="center">B</td><td colspan="1" width="4" align="center">M</td><td colspan="1" width="4" align="center">N.A</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='dispositivos10'  >";
	   echo "<td>Pito</td><td><input type='radio' name='dispositivos1'  class='obtener'   value='1' required></td><td><input type='radio' name='dispositivos1'  class='obtener'  value='2'></td><td><input type='radio' name='dispositivos1'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='dispositivos20'>";
	   echo "<td>Pito de reversa </td><td><input type='radio' name='dispositivos2'  class='obtener'   value='1' required></td><td><input type='radio' name='dispositivos2'  class='obtener'  value='2'></td><td><input type='radio' name='dispositivos2'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='dispositivos30'>";
	   echo "<td>Freno de servicio </td><td><input type='radio' name='dispositivos3'  class='obtener'   value='1' required></td><td><input type='radio' name='dispositivos3'  class='obtener'  value='2'></td><td><input type='radio' name='dispositivos3'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='dispositivos40'>";
	   echo "<td>Freno de emergencia</td><td><input type='radio' name='dispositivos4'  class='obtener'   value='1' required></td><td><input type='radio' name='dispositivos4'  class='obtener'  value='2'></td><td><input type='radio' name='dispositivos4'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='dispositivos50'>";
	   echo "<td>Dirección/suspensión delantera  </td><td><input type='radio' name='dispositivos5'  class='obtener'   value='1' required></td><td><input type='radio' name='dispositivos5'  class='obtener'  value='2'></td><td><input type='radio' name='dispositivos5'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='dispositivos60'>";
	   echo "<td>Cinturón de seguridad</td><td><input type='radio' name='dispositivos6'  class='obtener'   value='1' required></td><td><input type='radio' name='dispositivos6'  class='obtener'  value='2'></td><td><input type='radio' name='dispositivos6'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='dispositivos70'>";
	   echo "<td>Estado general de puertas  </td><td><input type='radio' name='dispositivos7'  class='obtener'   value='1' required></td><td><input type='radio' name='dispositivos7'  class='obtener'  value='2'></td><td><input type='radio' name='dispositivos7'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='dispositivos80'>";
	   echo "<td>Limpia brisas y plumillas</td><td><input type='radio' name='dispositivos8'  class='obtener'   value='1' required></td><td><input type='radio' name='dispositivos8'  class='obtener'  value='2'></td><td><input type='radio' name='dispositivos8'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='dispositivos90'>";
	   echo "<td>Extintor (indique fecha de vencimiento en observaciones)  </td><td><input type='radio' name='dispositivos9'  class='obtener'   value='1' required></td><td><input type='radio' name='dispositivos9'  class='obtener'  value='2'></td><td><input type='radio' name='dispositivos9'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='dispositivos100'>";
	   echo "<td>Botiquin  </td><td><input type='radio' name='dispositivos10'  class='obtener'   value='1' required></td><td><input type='radio' name='dispositivos10'  class='obtener'  value='2'></td><td><input type='radio' name='dispositivos10'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='dispositivos110'>";
	   echo "<td>Asientos en buena condición</td><td><input type='radio' name='dispositivos11'  class='obtener'   value='1' required></td><td><input type='radio' name='dispositivos11'  class='obtener'  value='2'></td><td><input type='radio' name='dispositivos11'  class='obtener'  value='3'></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" width="4" align="center">INDICADORES   </td><td colspan="1" width="4" align="center">B</td><td colspan="1" width="4" align="center">M</td><td colspan="1" width="4" align="center">N.A</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='indicadores10'>";
	   echo "<td>Panel de Indicadores </td><td><input type='radio' name='indicadores1'  class='obtener'   value='1' required></td><td><input type='radio' name='indicadores1'  class='obtener'  value='2'></td><td><input type='radio' name='indicadores1'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='dispositivos20'>";
	   echo "<td>Aceite </td><td><input type='radio' name='indicadores2'  class='obtener'   value='1' required></td><td><input type='radio' name='indicadores2'  class='obtener'  value='2'></td><td><input type='radio' name='indicadores2'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='dispositivos30'>";
	   echo "<td>Agua  </td><td><input type='radio' name='indicadores3'  class='obtener'   value='1' required></td><td><input type='radio' name='indicadores3'  class='obtener'  value='2'></td><td><input type='radio' name='indicadores3'  class='obtener'  value='3'></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" width="4" align="center">LLANTAS   </td><td colspan="1" width="4" align="center">B</td><td colspan="1" width="4" align="center">M</td><td colspan="1" width="4" align="center">N.A</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='llantas10'>";
	   echo "<td>Estado General de llantas  </td><td><input type='radio' name='llantas1'  class='obtener'   value='1' required></td><td><input type='radio' name='llantas1'  class='obtener'  value='2'></td><td><input type='radio' name='llantas1'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='llantas20'>";
	   echo "<td>Llanta de repuesto </td><td><input type='radio' name='llantas2'  class='obtener'   value='1' required></td><td><input type='radio' name='llantas2'  class='obtener'  value='2'></td><td><input type='radio' name='llantas2'  class='obtener'  value='3'></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="1" width="4" align="center">HERRAMIENTAS MINIMAS    </td><td colspan="1" width="4" align="center">B</td><td colspan="1" width="4" align="center">M</td><td colspan="1" width="4" align="center">N.A</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='Herramientas10'>";
	   echo "<td>Gato </td><td><input type='radio' name='Herramientas1'  class='obtener'   value='1' required></td><td><input type='radio' name='Herramientas1'  class='obtener'  value='2'></td><td><input type='radio' name='Herramientas1'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='Herramientas20'>";
	   echo "<td>Cruceta  </td><td><input type='radio' name='Herramientas2'  class='obtener'   value='1' required></td><td><input type='radio' name='Herramientas2'  class='obtener'  value='2'></td><td><input type='radio' name='Herramientas2'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='Herramientas30'>";
	   echo "<td>Cinta de seguridad   </td><td><input type='radio' name='Herramientas3'  class='obtener'   value='1' required></td><td><input type='radio' name='Herramientas3'  class='obtener'  value='2'></td><td><input type='radio' name='Herramientas3'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='Herramientas40'>";
	   echo "<td>Conos  </td><td><input type='radio' name='Herramientas4'  class='obtener'   value='1' required></td><td><input type='radio' name='Herramientas4'  class='obtener'  value='2'></td><td><input type='radio' name='Herramientas4'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='Herramientas50'>";
	   echo "<td>Linterna   </td><td><input type='radio' name='Herramientas5'  class='obtener'   value='1' required></td><td><input type='radio' name='Herramientas5'  class='obtener'  value='2'></td><td><input type='radio' name='Herramientas5'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='Herramientas60'>";
	   echo "<td>Caja de Herramientas </td><td><input type='radio' name='Herramientas6'  class='obtener'   value='1' required></td><td><input type='radio' name='Herramientas6'  class='obtener'  value='2'></td><td><input type='radio' name='Herramientas6'  class='obtener'  value='3'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='Herramientas70'>";
	   echo "<td>SE REALIZÒ LA LIMPIEZA Y DESINFECCION  DEL VEHÌCULO?</td><td><input type='radio' name='Herramientas7'  class='obtener'   value='1' required></td><td><input type='radio' name='Herramientas7'  class='obtener'  value='2'></td><td><input type='radio' name='Herramientas7'  class='obtener'  value='3'></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#868A08" class="tittle3"><td colspan="4" >SI alguno de estos puntos tiene al menos como respuesta un SI, el trabajador debe de manera inmediata dar aviso de sus condciones al Jefe de operaciones de la empresa TRANSMILLAS EMPRESA DE CARGA Y LOGISTICA.</td></tr>';

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">CHECK LIST FATIGA   </td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='elementosp10'>";
	   echo "<td colspan='2'>TENGO SUEÑO?</td><td><input type='radio' name='elementosp1'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp1'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementosp20'>";
	   echo "<td colspan='2'>SIENTO LA VISTA CANSADA?</td><td><input type='radio' name='elementosp2'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp2'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementosp30'>";
	   echo "<td colspan='2'> ME ENCUENTRO TOMANDO MEDICAMENTOS QUE ME IMPIDAN OPERAR O ALTERE MI CONCENTRACIÓN?</td><td><input type='radio' name='elementosp3'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp3'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementosp40'>";
	   echo "<td colspan='2'>ME CUESTA ENFOCAR LA VISTA (VISIÓN BORROSA) O MANTENER  LOS OJOS ABIERTOS?</td><td><input type='radio' name='elementosp4'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp4'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementosp50'>";
	   echo "<td colspan='2'>SIENTO DIFICULTADES PARA CONCENTRARME O PERMANECER ALERTA?</td><td><input type='radio' name='elementosp5'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp5'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementosp60'>";
	   echo "<td colspan='2'>ME SIENTO EN MALAS CONDICIONES (FISICAS Y/O ANIMICAS) PARA REALIZAR MIS TAREAS?</td><td><input type='radio' name='elementosp6'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp6'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='elementosp70'>";
	   echo "<td colspan='2'>SE ENCUENTRA BAJO ALGÙN EFECTO DE ALCHOHOL O DROGAS?</td><td><input type='radio' name='elementosp7'  class='obtener'   value='1' required></td><td><input type='radio' name='elementosp7'  class='obtener'  value='2'></td>";
	   echo "</tr>";
	   echo "<tr bgcolor='$color' class='text' id='klmactual'>";
	   echo "<td colspan='4'>KILOMETRAJE ACTUAL:<input  name='param12' id='param12' value='$rw2[9]' style='width:395px'; class='text' ></td>";
	   if ($preoperacional=='validarpreoperacional') {

			if ($rw2[13]=="") {
				
				$FB->llena_texto("Imagen Kilometraje:",30, 6, $DB, "", "", "",2,1);
			}else{
				$fotokilo="preoperacional/$rw2[13]";
				$FB->llena_texto("Imagen Kilometraje:",30, 6, $DB, "", "", "$fotokilo",2, 0);
			}
			
		}else if($preoperacional=='preoperacional') {
			$FB->llena_texto("Imagen Kilometraje:",30, 6, $DB, "", "", "",2, 1);
		}
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="4" width="4" align="center">OBSERVACIONES/ CONDICIONES REPORTADAS </td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='observacionesc'>";
	   echo "<td colspan='4'><textarea colspan='4'name='param7' id='param7' value='' style='width:395px'; class='text' >$rw2[0]</textarea></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="4" width="4" align="center">ACCIÓN CORRECTIVA</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='accioncorrectiva'>";
	   echo "<td colspan='4'><textarea colspan='4' name='param8' id='param8' value='' style='width:395px'; class='text' >$rw2[1]</textarea></td>";
	   echo "</tr>";

	   echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="4" width="4" align="center">RESPONSABLE</td></tr>';
	   echo "<tr bgcolor='$color' class='text' id='responsable'>";
	   echo "<td colspan='4'><input  name='param9' id='param9' value='$rw2[2]' style='width:395px'; class='text' ></td>";
	   echo "</tr>";

}

if($nivel_acceso==3 or $param5=='valida'){

		echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">IMPLEMENTOS DE TRABAJO  </td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">CELULAR</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo "<tr bgcolor='$color' class='text' id='implementos10'>";
		echo "<td colspan='2'>Cuenta con celular con acceso a Internet?</td><td><input type='radio' name='implementos1'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos1'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos20'>";
		echo "<td colspan='2'>La bateria de su Celular se encuentra Cargada?</td><td><input type='radio' name='implementos2'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos2'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos30'>";
		echo "<td colspan='2'>Su celular cuenta con datos y minutos?</td><td><input type='radio' name='implementos3'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos3'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos40'>";
		echo "<td colspan='2'>Tiene usted el cargador de su Celular?</td><td><input type='radio' name='implementos4'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos4'  class='obtener'  value='2'></td>";
		echo "</tr>";
		/*echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">IMPRESORA</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo "<tr bgcolor='$color' class='text' id='implementos50'>";
		echo "<td colspan='2'>Cuenta con impresora suministrada por la Empresa?</td><td><input type='radio' name='implementos5'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos5'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='codigoimpresora'>";
		echo "<td colspan='4'>Cual es el Codigo de su Impresora:<input  name='param20' id='param20' value='$rw2[10]' style='width:395px'; class='text' ></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos60'>";
		echo "<td colspan='2'>La impresora se encuentra cargadal?</td><td><input type='radio' name='implementos6'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos6'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos70'>";
		echo "<td colspan='2'>Cuenta con suficiente papel para la impresora?</td><td><input type='radio' name='implementos7'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos7'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos80'>";
		echo "<td colspan='2'>Cuneta con el cargador de la Impresora?</td><td><input type='radio' name='implementos8'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos8'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos90'>";
		echo "<td colspan='2'>Verifico que la Impresora este configurada con su celular?</td><td><input type='radio' name='implementos9'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos9'  class='obtener'  value='2'></td>";
		echo "</tr>";*/

		echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">PESA</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo "<tr bgcolor='$color' class='text' id='implementos100'>";
		echo "<td colspan='2'>Cuenta con Pesa?</td><td><input type='radio' name='implementos10'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos10'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos110'>";
		echo "<td colspan='2'>Su Pesa cuenta con Bateria?</td><td><input type='radio' name='implementos11'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos11'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos120'>";
		echo "<td colspan='2'>Verifico que su Pesa cuente con bateria?</td><td><input type='radio' name='implementos12'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos12'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos130'>";
		echo "<td colspan='2'>Verifico que su Pesa este funcionando Perfectamente?</td><td><input type='radio' name='implementos13'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos13'  class='obtener'  value='2'></td>";
		echo "</tr>";

		echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">MALETA</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo "<tr bgcolor='$color' class='text' id='implementos140'>";
		echo "<td colspan='2'>Cuenta con Maleta?</td><td><input type='radio' name='implementos14'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos14'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='maleta'>";
		echo "<td colspan='4'>Ultima vez que desinfecto la maleta:<input  name='param21' id='param21' value='$rw2[11]' style='width:395px'; class='text' ></td>";
		echo "</tr>";

		/*echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">CARNET Y CARTA DE MOVILIDAD</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo "<tr bgcolor='$color' class='text' id='implementos160'>";
		echo "<td colspan='2'>Cuenta con Carnet?</td><td><input type='radio' name='implementos16'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos16'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos170'>";
		echo "<td colspan='2'>Cuenta con carta de movilidad?</td><td><input type='radio' name='implementos17'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos17'  class='obtener'  value='2'></td>";
		echo "</tr>";*/

		echo '<tr bgcolor="#074F91" class="tittle3"><td colspan="2" width="4" align="center">PARAFISCALES O COPIA DE AFILIACION DE ARL</td><td colspan="1" width="4" align="center">SI</td><td colspan="1" width="4" align="center">NO</td></tr>';
		echo "<tr bgcolor='$color' class='text' id='implementos180'>";
		echo "<td colspan='2'>Tiene copia de pago de parafiscales?</td><td><input type='radio' name='implementos18'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos18'  class='obtener'  value='2'></td>";
		echo "</tr>";
		echo "<tr bgcolor='$color' class='text' id='implementos190'>";
		echo "<td colspan='2'>Tiene copia de Afiliacion ARL(Peronal Nuevo)?</td><td><input type='radio' name='implementos19'  class='obtener'   value='1' required></td><td><input type='radio' name='implementos19'  class='obtener'  value='2'></td>";
		echo "</tr>";

}


		if($param4=='ingresado' or $param4=='covid19'){
			if($param4=='covid19'){
			$validatitulo='COVID 19';
			}else{
			$validatitulo='PREOPERACIONAL Y COVID 19';
			}
				echo '<tr  bgcolor="#868A08" class="tittle3"><td colspan="4" width="4" align="center">VALIDA '.$validatitulo.'</td></tr>';
				echo "<tr bgcolor='$color' class='text' id='validapreopera'>";
				echo "<td colspan='4'><textarea colspan='4' name='param10' id='param10' value='' style='width:395px'; class='text' >$rw2[3]</textarea></td>";
				echo "</tr>";
		}
	   echo '</table></td></tr></table></div>';
       echo '</div>';

}

$FB->llena_texto("data", 1, 13, $DB, "", "", "", 5, 0);
$FB->llena_texto("tabla", 1, 13, $DB, "", "", "$preoperacional", 5, 0);
$FB->llena_texto("param11", 1, 13, $DB, "", "", "$rw2[4]", 5, 0);
$FB->llena_texto("idvehiculo", 1, 13, $DB, "", "", "$id_p", 5, 0);
$FB->llena_texto("param1", 1, 13, $DB, "", "", "$id_p", 5, 0);
$FB->llena_texto("param2", 1, 13, $DB, "", "", "$tipovehiculo", 5, 0);
$FB->llena_texto("param3", 1, 13, $DB, "", "", "$tipovehiculo", 5, 0);
$FB->llena_texto("estado", 1, 13, $DB, "", "", "$param4", 5, 0);
$FB->llena_texto("fecha", 1, 13, $DB, "", "", "$fecha", 5, 0);
$FB->llena_texto("user", 1, 13, $DB, "", "", "$iduser", 5, 0);
$FB->llena_texto("campo", 1, 13, $DB, "", "", "$campo", 5, 0);

$FB->llena_texto("", 1, 142, $DB, "Guardar", "", 0, 12, 0);
require("footer.php");
?>
<script type="text/javascript">

	/* -------------------------------------------------------------------------- */
	/*                      Impedir paso en formulario covid                      */
	/* -------------------------------------------------------------------------- */
	$(".obtener").click(function(){
		if($(".optionCovid1").prop('checked') ||
			$(".optionCovid2").prop('checked') ||
			$(".optionCovid3").prop('checked') ||
			$(".optionCovid4").prop('checked') ||
			$(".optionCovid5").prop('checked') ||
			$(".optionCovid6").prop('checked') ||
			$(".optionCovid7").prop('checked') ||
			$(".optionCovid8").prop('checked') ||
			$(".optionCovid9").prop('checked')
		){
			$(".btnSaveCovid").prop('disabled', true);
			alert("Una o más opciones del formulario es incorrecta.");
		}else{
			$(".btnSaveCovid").prop('disabled', false);
		}
	});
	/* -------------------------------------------------------------------------- */

var valida = document.getElementById("estado").value;
var iduser = document.getElementById("user").value;
var fecha = document.getElementById("fecha").value;
var campo = document.getElementById("campo").value;
var tipovehiculo = document.getElementById("param3").value;

if(valida=='ingresado' || valida=='covid19'){
	if(tipovehiculo=='MOTO'){
			var valoresmoto =  new Array();
			valoresmoto['llantas1']='2';
			valoresmoto['llantas2']='2';
			valoresmoto['llantas3']='2';
			valoresmoto['llantas4']='2';
			valoresmoto['llantas5']='1';
			valoresmoto['llantas6']='1';
			valoresmoto['transmision1']='2';
			valoresmoto['transmision2']='2';
			valoresmoto['Luces1']='1';
			valoresmoto['Luces2']='1';
			valoresmoto['Luces3']='1';
			valoresmoto['fugas1']='2';
			valoresmoto['fugas2']='2';
			valoresmoto['fugas3']='2';
			valoresmoto['fugas4']='2';
			valoresmoto['fugas5']='2';
			valoresmoto['fugas6']='2';
			valoresmoto['mandos1']='2';
			valoresmoto['mandos2']='1';
			valoresmoto['entorno1']='1';
			valoresmoto['entorno2']='2';
			valoresmoto['entorno3']='1';
			valoresmoto['elementos1']='1';
			valoresmoto['elementos2']='1';
			valoresmoto['elementos3']='1';
			valoresmoto['elementos4']='1';
			valoresmoto['elementos5']='1';
			valoresmoto['elementos6']='1';
	}
	datos = {"user":iduser,"fecha":fecha,"campo":campo};
		$.ajax({
				url: "buscarpreoperacional.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){
				var obj = JSON.parse(respuesta);
				console.log(obj);
				if (respuesta != null) {

					for (var i in obj) {
						value=obj[i];

						var tamano = document.getElementsByName(i);
						//console.log(i+'0');

						for (b=0;b<tamano.length;b++){
							var valor = tamano[b].value
							if (valor==value){
							//	colort.style.backgroundColor="#66ff33";
							if(tipovehiculo=='CARRO'){
								if(tamano.length==2){

									var implementos=i.substring(0,11);

									if(i=='covid198' || i=='covid199' || implementos=='implementos'){
										if(value!=1){
											document.getElementById(i+'0').style.backgroundColor = "#e4605e";
											}
									}else if(value!=2){
										document.getElementById(i+'0').style.backgroundColor = "#e4605e";
									}

									var implementos=i.substring(1,11);

									if(i=='covid198' || i=='covid199'){

									}

								} else if(value!=1){
										document.getElementById(i+'0').style.backgroundColor = "#e4605e";
								}
							}

							if(valida=='covid19'){
								var implementos=i.substring(0,11);
								if(i=='covid198' || i=='covid199'|| implementos=='implementos'){
										if(value!=1){
											document.getElementById(i+'0').style.backgroundColor = "#e4605e";
											}
									}else if(value!=2){
									document.getElementById(i+'0').style.backgroundColor = "#e4605e";
									}
							}

							if(tipovehiculo=='MOTO'){

								if(tamano.length==2){
								var implementos=i.substring(0,11);

								if(i=='covid198' || i=='covid199'|| implementos=='implementos'){
										if(value!=1){
											document.getElementById(i+'0').style.backgroundColor = "#e4605e";
											}
									}else if(value!=2){
									document.getElementById(i+'0').style.backgroundColor = "#e4605e";
									}

								}else if(value!=valoresmoto[i]){
									document.getElementById(i+'0').style.backgroundColor = "#e4605e";
								}
							}

								tamano[b].checked=true;
							}
						}

					}

				}
		});
}

</script>