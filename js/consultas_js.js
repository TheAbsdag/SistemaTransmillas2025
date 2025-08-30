function ir_menu(destino, div)
{
	MostrarConsulta2(destino, div);
}
function cambion(valor, destino)
{
	destino=destino+"?ordby="+valor;
	document.location.href=destino;
}
function cambion1(valor, valor2, destino)
{
	destino=destino+"?ordby="+valor+"&ordby1="+valor2;
	document.location.href=destino;
}
function preguntaotra(valor, nombre)
{
	document.getElementById(nombre).disabled=true;
	if(valor=="Otra"){ document.getElementById(nombre).disabled=false; }
	if(valor=="Otro"){ document.getElementById(nombre).disabled=false; }
}
function noenter() {
    return !(window.event && window.event.keyCode == 13);
}
function valsinoev(valor, nombre)
{
	var elementos = eval(nombre);
	for (x=0;x<elementos.length;x++){
		if(valor=="Si"){ elementos[x].disabled=false; }
		else { elementos[x].disabled=true; }
	}
}
function aparecepreg(va, id_encuesta, orden, condi, div){
	valor=eval("param"+va).value;
	destino="resultados1.php?cond=42&valor="+valor+"&va="+va+"&id_encuesta="+id_encuesta+"&orden="+orden+"&condi="+condi;
	MostrarConsulta2(destino, div);
}
function llena_formula()
{
	p3=document.getElementById('param3').value;
	p4=document.getElementById('param4').value;
	p6=document.getElementById('param6').value;
	destino="resultados1.php?p3="+p3+"&p4="+p4+"&p6="+p6+"&cond=38";
	MostrarConsulta4(destino, "llega_su3")
}
function validatext(e)
{
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key).toLowerCase();
       letras = " ()*/+-.1234567890";
       especiales = "8-37-39-46";
       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
}

function validatext1(e)
{
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key).toLowerCase();
       letras = "";
       especiales = "8-37-39-46";
       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
}

function crea_formula2(valor, idfor, valor2, van)
{
	val=document.getElementById('param5').value;
	document.getElementById('param5').value=""+valor;
}

function format(input) //puntos onkeyup="format(this)" onchange="format(this)"
{
	var num = input.value.replace(/\./g,"");
	if(!isNaN(num)){
	num = num.toString().split("").reverse().join("").replace(/(?=\d*\.?)(\d{3})/g,"$1.");
	num = num.split("").reverse().join("").replace(/^[\.]/,"");
	input.value = num;
	}else{
	input.value = input.value.replace(/[^\d\.]*/g,"");
	}
}

//Código para colocar
//los indicadores de miles mientras se escribe
//script por tunait!  onkeyup='puntitos(this,this.value.charAt(this.value.length-1))'
function puntitos(donde,caracter){
	pat = /[\*,\+,\(,\),\?,\,$,\[,\],\^]/;
	valor = donde.value;
	largo = valor.length;
	crtr = true;
	if(isNaN(caracter) || pat.test(caracter) == true){
		if (pat.test(caracter)==true){
			caracter = ""+caracter;
		}
		carcter = new RegExp(caracter,"g");
		valor = valor.replace(carcter,"");
		donde.value = valor;
		crtr = false;
	}
	else{
		var nums = new Array();
		cont = 0;
		for(m=0;m<largo;m++){
			if(valor.charAt(m) == "." || valor.charAt(m) == " ")
				{continue;}
			else{
				nums[cont] = valor.charAt(m);
				cont++;
			}
		}
	}
	var cad1="",cad2="",tres=0;
	if(largo > 3 && crtr == true){
		for (k=nums.length-1;k>=0;k--){
			cad1 = nums[k];
			cad2 = cad1 + cad2;
			tres++;
			if((tres%3) == 0){
				if(k!=0){
					cad2 = "." + cad2;
				}
			}
		}
		donde.value = cad2;
	}
}


/*
Para llamar a la función conComas() yo lo hago con JQuery utilizando el evento keyup(), ésto eso, cada ves que se suelte una tecla al escribir en el campo se formateara el valor actual agregando alguna coma si hay más de trés digitos, seis dígitos, etc
$("#principal input:text").keyup(function () {
        var valActual = $(this).val();
        var nuevoValor = conComas(valActual);
        $(this).val(nuevoValor);
    });
 */

function autocomplet(cond, div, valor, nombre, id_param)
{
	if(valor.length>2)
	{
		destino="resultados1.php?cond="+cond+"&valor="+valor+"&div="+div+"&nombre="+nombre+"&id_param="+id_param;
		MostrarConsulta2(destino, div);
	}
}

function enviar_c(destino, div)
{
	alert(div);
	var Form = document.getElementById('form2');
	for(I = 0; I < Form.length; I++) {

		valor=document.getElementById("form2").elements[I].value;
		nombre=document.getElementById("form2").elements[I].name;
		if(nombre.substr(0,5)=="param"){
			destino+=nombre+"="+valor+"&";
		}
    }
	MostrarConsulta2(destino, div);
}

function set_item(item, nombre, div, cond, val1, id_param) {
	$('#'+nombre+'').val(item);
	$('#'+div+'').hide();
	destino="resultados1.php?cond="+cond+"&valor="+val1+"&id_param="+id_param+"&div="+div;
	MostrarConsulta2(destino, div);
}
function autocomplet1(valor, vale)
{
	if(valor.length>2)
	{
		llena_datos(vale);
	}
}
function autocomplet2(valor)
{
	if(valor.length>2)
	{
		llena_datos2();
	}
}

function showCheckboxes(nombre) {
	var checkboxes1 = document.getElementById(nombre);
    if (!expanded) {
    	checkboxes1.style.display = "block";
        expanded = true;
    } else {
    	checkboxes1.style.display = "none";
        expanded = false;
    }
}

function marcar(source)
{
	checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
	for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
	{
		if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
		{
			nombre=checkboxes[i].name;
			if(nombre==source+"[]"){
				if(checkboxes[i].checked==1){ checkboxes[i].checked=0; }
				else { checkboxes[i].checked=1; }
			}
		}
	}
}


function ir_mapa_dentro(id_pozo)
{
	destino="detalle_operacion.php?id_p="+id_pozo;
	document.location.href=destino;
}

function iras(idzona, destino)
{
	destino=destino+"?idzona="+idzona;
	document.location.href=destino;
}

function cambio_exp(valor, destino, div)
{
	destino="resultados1.php?valor="+valor+"&cond="+destino;
	MostrarConsulta2(destino, div);
}

function llena_abajo(cond, div, id)
{
	destino="resultados1.php?idproyecto="+id+"&cond="+cond;
	MostrarConsulta2(destino, div);
}

function cambio_estado(id, div, env)
{
	destino="detalle_encuestas.php?idestado="+id+"&env="+env;
	MostrarConsulta2(destino, div);
}

function cambio(valor, destino, condecion)
{
	destino=destino+"?param1="+valor+"&condecion="+condecion;
	document.location.href=destino;
}
function cambio2(valor, destino, tabla)
{
	destino=destino+"?param1="+valor+"&tabla="+tabla;
	document.location.href=destino;
}
function cambioParaUsers(valor, destino, tabla,aux1)
{
	destino=destino+"?param1="+valor+"&tabla="+tabla+"&id_param="+aux1;
	document.location.href=destino;
}
function cambio22(valor, destino, tabla, param)
{
	destino=destino+"?"+param+"="+valor+"&tabla="+tabla;
	document.location.href=destino;
}
function cambio1(valor, valor2, destino)
{
	destino=destino+"?param1="+valor+"&param2="+valor2;
	document.location.href=destino;
}

function cambio3(valor, valor2, valor3, destino)
{
	destino=destino+"?param1="+valor+"&param2="+valor2+"&param3="+valor3;
	document.location.href=destino;
}
function cambio4(nombre1,nombre2,destino)
{
	var valor=document.getElementById(nombre1).value;
	var valor2=document.getElementById(nombre2).value;
	destino=destino+"?"+nombre1+"="+valor+"&"+nombre2+"="+valor2;
	document.location.href=destino;
}

function irao(id_p, tabla)
{
	destino="resultados6.php?id_encuesta="+id_p+"&tabla="+tabla;
	window.open(destino,"_blank");
}

function irau(id_p, tabla)
{
	destino="carga.php?id_encuesta="+id_p+"&tabla="+tabla;
	window.open(destino,"_blank");
}

function irau1(id_p, tabla)
{
	destino="carga1.php?id_encuesta="+id_p+"&tabla="+tabla;
	window.open(destino,"_blank");
}

function irau3(id_p)
{
	destino="empresas.php?id_empresa="+id_p;
	window.open(destino,"_self");
}
function irau2(id_p)
{
	destino="proyectos.php?id_proyecto="+id_p;
	window.open(destino,"_self");
}
function irau4(id_p)
{
	destino="proyectos2.php?id_proyecto="+id_p;
	window.open(destino,"_self");
}
function irau5(id_p, tabla)
{
	destino="ieducativas1.php?ideducativa="+id_p;
	location.href=destino;
}
function llena_grafica(div)
{
	destino="llena_grafica.php";
	MostrarConsulta(destino, div);
}
function busqueda5(div, fecha1, fecha2)
{
	destino="detalle_cronograma1.php?fecha1="+fecha1+"&fecha2="+fecha2;
	MostrarConsulta(destino, div);
}
function busqueda2(valor, destino, div, verifica, fecha1, fecha2)
{
	envio=0;
	if(verifica==1){
		if(valor.length<2){ return; }
	}
	else if(verifica==3){
		envio=valor;
	}
	destino="detalle_cronograma.php?valor="+valor+"&cond="+destino+"&envio="+envio+"&fecha1="+fecha1+"&fecha2="+fecha2;
	MostrarConsulta(destino, div);
}

function busqueda(valor, destino, div, verifica)
{
	envio=0;
	if(verifica==1){
		if(valor.length<2){ return; }
	}
	else if(verifica==3){
		envio=valor;
	}
	destino="resultados2.php?valor="+valor+"&cond="+destino+"&envio="+envio;
	MostrarConsulta(destino, div);
}

function ir_detalle_pro (id, i)
{
	i=24*i+147;
	if(i>230){i=230;}
    $('#diva').css("top", i);
	MostrarConsulta("detalle_proyectos.php?id_proyectos="+id+"&i="+i, "diva");
}

function ir_detalle_pro4 (id,i,da,opc,va)
{
	i=19*i+100;
	da=60*da+290;
	if(i>230){i=230;}
	$('#divb').css("top", i);
	$('#divb').css("left", da);
	MostrarConsulta("detalle_campo1.php?id_campo="+id+"&i="+i+"&opc="+opc+"&va="+va, "divb");
}


function ir_detalle_pun (lat, lon, rep, fec)
{
	destino="detalle_ubicacion.php?lat="+lat+"&lon="+lon+"&rep="+rep+"&fec="+fec;
	window.open(destino,"ventana1","width=600, height=400, scrollbars=yes, menubar=no, location=no, resizable=no")
}


function ir_detalle_pro1 (id)
{
	MostrarConsulta("detalle_alarmas.php?id_proyectso="+id, "diva");
}

function ir_detalle_pro2 (id)
{
	MostrarConsulta("detalle_imagenes.php?id_proyectso="+id, "diva");
}


function busqueda1(valor, valor1, valor2, destino, div, verifica)
{
	destino="resultados2.php?param1="+valor+"&param2="+valor1+"&param3="+valor2+"&cond="+destino;
	MostrarConsulta(destino, div);
}

function llena_trabajo(valor, div, condecion, url)
{
	if(valor!=0){
		destino=url+"?tabla="+valor+"&cond=1&div="+div;
		window.open(destino, "area_trabajo");
	}
}

function valorservicio()
{
	// document.getElementById("param10").addEventListener("input", function() {
	// 	let valor = parseFloat(this.value);
	// 	if (valor > 0) {
			alert("Si colocas el peso RECUERDA VERIFICAR EL VOLUMEN");
		// }
	// });

	var tiposervicio=document.getElementById("param34").value;
	console.log(tiposervicio);
	if(tiposervicio!='1000'){

			var prestamo=document.getElementById("param44").value;
			var abono=document.getElementById("param55").value;
			var pordeclarado=document.getElementById("param6").value;
			var porprestamo=document.getElementById("param77").value;
			var peso=document.getElementById("param10").value;
			var volumen=document.getElementById("param20").value;
			//var prekilo=document.getElementById("param35").value;
			//var preadicional=document.getElementById("param36").value;
			var precioinicialkilos=document.getElementById("param53").value;

			var parametro1 = peso;
			var parametro2 = document.getElementById("param13").value;
			var parametro3 = document.getElementById("param9").value;
			var parametro4 = tiposervicio;
			var prekilo;
			var preadicional;
			// Llamada AJAX
			$.ajax({
				type: "POST",
				url: "buscarprecio.php",
				data: { param1: parametro1, param2: parametro2, param3: parametro3, param4: parametro4 },
				success: function(response) {
					// Manejar la respuesta del archivo PHP
					var valores = JSON.parse(response);
					var prekilo = valores.prekilo;
					var preadicional = valores.adicional;

					console.log(prekilo);
					console.log("---///----");
					console.log(preadicional);

					 // Por ejemplo, si quieres ejecutar una función después de recibir los valores
					 obtenerprecio(prekilo, preadicional,volumen,peso,prestamo,abono,pordeclarado,porprestamo,precioinicialkilos);
					}
			});

	}
}

function obtenerprecio(prekilo, preadicional,volumen,peso,prestamo,abono,pordeclarado,porprestamo,precioinicialkilos){

			if(volumen=='') { volumen=0; }
			if(peso=='') { peso=0; }
			if(prestamo=='') { prestamo=0; }
			if(abono=='') { abono=0; }
			if(pordeclarado=='') { pordeclarado=0; }else{
				pordeclarado=(parseInt(pordeclarado)*1)/100;
			}
			if(porprestamo=='') { porprestamo=0; }
			if(prekilo=='') { prekilo=0; }
			if(preadicional=='') { preadicional=0; }


			var kilos =parseInt(peso)+parseInt(volumen);
			console.log(kilos);
			console.log(peso);
			console.log(volumen);
			console.log("------");
			console.log(prekilo);
			console.log(preadicional);
			console.log(precioinicialkilos);

			if(parseInt(peso)>precioinicialkilos){
				var varor1=parseFloat(prekilo);
				var valor2=(parseInt(peso)+parseInt(volumen)-precioinicialkilos)*parseFloat(preadicional);
				var valortotal=parseFloat(varor1)+parseFloat(valor2);
			}else {
				var varor1=parseFloat(prekilo);
				var valor2=parseInt(volumen)*parseFloat(preadicional);
				var valortotal=parseFloat(varor1)+parseFloat(valor2);
			}
			console.log(valortotal);
			console.log(prestamo);
			console.log(abono);
			console.log(pordeclarado);
			console.log(porprestamo);

			var valorapagar=parseFloat(valortotal)+parseFloat(prestamo)-parseFloat(abono)+parseFloat(pordeclarado)+parseFloat(porprestamo);
			document.getElementById("param11").value=valorapagar;
			document.getElementById("param112").value=valorapagar;
}

function tipopago(valor, destino, div, nombre, profundidad, para)
{
	if (valor==1) {
		document.getElementById("param10").required = true;
		console.log("Peso obligatorio");
	}else if (valor==2){
		document.getElementById("param10").required = false;
		console.log("Peso no obligatorio");
	}else if (valor==3){
		document.getElementById("param10").required = false;
		console.log("Peso no obligatorio");
	}
if (valor==1) {
	alert("Verifica si este servicio tiene volumen antes de guardarr");

}
	var valorprecio=document.getElementById("param35").value;
	var valortservicio=document.getElementById("param34").value;
	document.getElementById(div).innerHTML="";
	destino="resultados1.php?param1="+valor+"&cond="+destino+"&param2="+valortservicio+"&param3="+valorprecio;
	MostrarConsulta2(destino, div);

}

function valorpagar(valor, destino, div, nombre, profundidad, para)
{
	if (valor==1) {
		document.getElementById("param26").required = true;
		console.log("Peso obligatorio");
	}else if (valor==2){
		document.getElementById("param26").required = false;
		console.log("Peso no obligatorio");
	}else if (valor==3){
		document.getElementById("param26").required = false;
		console.log("Peso no obligatorio");
	}

	var cuidadori=document.getElementById("param4").value;
	var cuidaddes=document.getElementById("param11").value;
	var teldestino =document.getElementById("param8").value;
	var telremitente =document.getElementById("param2").value;
	if(teldestino=='' || telremitente==''){
		alert('Ingrese los Telefonos por favor');
	}else {
	//var prestamo=document.getElementById("param16").value;
	var prestamo=0;
	var abono=document.getElementById("param17").value;
	var seguro=document.getElementById("param18").value;
	var peso=document.getElementById("param26").value;
	var volumen=document.getElementById("param27").value;
	var valortservicio=document.getElementById("param34").value;
	if(valor==2){
		//var nombre=document.getElementById("param113").value;
		var nombre='';
	}else{
		var nombre='';
	}


	document.getElementById(div).innerHTML="";
	destino="resultados1.php?param1="+valor+"&teldestino="+teldestino+"&telremitente="+telremitente+"&param2="+cuidadori+"&param3="+cuidaddes+"&param4="+prestamo+"&param5="+abono+"&param6="+seguro+"&param7="+peso+"&param8="+volumen+"&cond="+destino+"&valortservicio="+valortservicio+"&nombre="+nombre+"&";
	MostrarConsulta2(destino, div);
}
}

function calculocredito(valor, valor2,valor3,campo,estado)
{
    if(valor!=0 && valor!='' && valor2!=0 && valor2!=''){
        var ruta="param20="+valor+"&param21="+valor2+"&param22="+valor3;

        $.ajax({

        url: 'detalle_tiipocredito.php',
        type: 'Get',
        data: ruta,
        }).done(function(res){

            $('#respuesta').html(res)
        });
    }else{
        res='<div class="alert alert-danger" role="alert"><input name="'+campo+'" id="'+campo+'" type="hidden" onchange="" value="0">Seleccione las Ciudades</div>';
        $('#respuesta').html(res)
    }
}

function calculoo(valor, valor2,valor3,pagina,estado)
{
    if(valor!=0 && valor!='' && valor2!=0 && valor2!=''){
        var ruta="param20="+valor+"&param21="+valor2+"&param22="+valor3;

        $.ajax({

        url: 'detalle_cotizar.php',
        type: 'Get',
        data: ruta,
        }).done(function(res){

            $('#respuesta').html(res)
        });
    }else{
        res='<div class="alert alert-danger" role="alert"><input name="param8" id="param8" type="hidden" onchange="" value="0">Seleccione las Ciudades</div>';
        $('#respuesta').html(res)
    }
}

function creditosrecolecion(valor, destino, div, nombre, profundidad, para)
{

	console.log(valor);

	var param41=document.getElementById("param4").value;
	var param111=document.getElementById("param11").value;


	if(valor=='1'){
		console.log('credito');
		var tel1=document.getElementById("param2").value;
		var tel2=document.getElementById("param8").value;
		var telefonos=tel2+','+tel1;
	//	buscarservicio(param41,param111,'1','Compra');
		destino="resultados1.php?param1="+valor+"&cond="+destino+"&para="+telefonos;
		MostrarConsulta2(destino, div);
		}else{
			console.log('normal');
			buscarservicio(param41,param111,'','Compra');
			document.getElementById(div).innerHTML='<input name="param113" id="param113" type="hidden" onchange="" value="">';
		}


}

function habilitarcredito(valor, destino, div, nombre, profundidad, para)
{

	console.log(valor);
	if(valor==1){
		document.getElementById(div).innerHTML="";
		destino="resultados1.php?param1="+valor+"&cond="+destino;
		MostrarConsulta2(destino, div);
		}else{
			var param41=document.getElementById("param4").value;
			var param111=document.getElementById("param11").value;
			var param118=document.getElementById("param8").value;
			console.log(param111);
			calculoo(param41,param111,param118,'cotizar.php',1);
			document.getElementById(div).innerHTML='<input name="param8" id="param8" type="hidden" onchange="" value="0">';
		}

}

function buscarcliente(resul1)
{
	var div='llega_sub2';
	var tel=document.getElementById("param2").value;
	var destino="resultados1.php?param1='"+tel+"'&cond="+resul1;
	MostrarConsulta(destino, div);
}
function buscarcliente2(resul1)
{
	var div='llega_sub2';
	var tel=document.getElementById("param121").value;
	var destino="resultados1.php?param1='"+tel+"'&cond="+resul1;
	MostrarConsulta(destino, div);
}
function buscarguia(resul1)
{
	var div='llega_sub2';
	var numero=document.getElementById("param2").value;
	var busqueda=document.getElementById("param3").value;
	var destino="resultados1.php?param1='"+numero+"'&param2="+busqueda+"&cond="+resul1;
	MostrarConsulta(destino, div);
}
function valorpagaredit(valor, destino, div, nombre, profundidad, para)
{

	var cuidadori=document.getElementById("param4").value;
	var cuidaddes=document.getElementById("param11").value;
	var prestamo=document.getElementById("param16").value;
	var idservicio=document.getElementById("id_param2").value;
	//var prestamo=0;
	var abono=document.getElementById("param17").value;
	var seguro=document.getElementById("param18").value;
	var peso=document.getElementById("param26").value;
	var volumen=document.getElementById("param27").value;
	var valortservicio=document.getElementById("param37").value;
	var teldestino =document.getElementById("param8").value;
	var telremitente =document.getElementById("param2").value;
	console.log(teldestino);
	document.getElementById(div).innerHTML="";
	destino="resultados1.php?param1="+valor+"&param2="+cuidadori+"&param3="+cuidaddes+"&param4="+prestamo+"&param5="+abono+"&param6="+seguro+"&param7="+peso+"&param8="+volumen+"&cond="+destino+"&valortservicio="+valortservicio+"&idservicio="+idservicio+"&teldestino="+teldestino+"&telremitente="+telremitente;
	MostrarConsulta2(destino, div);
}
function cuentas(destino, div)
{
	var idsede=document.getElementById('param35').value;
	var fecha=document.getElementById('param34').value;
	destino="cuentasuser.php?param35="+idsede+"&param34="+fecha;
	MostrarConsulta4(destino, "destino_vesr")

}
function referenciasfamiliares(destino, div)
{
	var nombre=document.getElementById('param17').value;
	var parestesco=document.getElementById('param18').value;
	var ocupacion=document.getElementById('param19').value;
	var celular=document.getElementById('param20').value;
	var idhojavida=document.getElementById('param7').value;
	var documento=document.getElementById('param109');

	var archivo='';
	if (documento.name!='') {

		var documento2=documento.files[0];
		console.log(documento2.name);
		var data= new FormData();
		data.append("file",documento2);

		console.log(data);
	}else{
		var formData = new FormData();

	}

	destino="detalle_referenciasfami.php?param1="+nombre+"&param2="+parestesco+"&param3="+ocupacion+"&param4="+celular+"&param5"+data+"&param7="+idhojavida+"&param6=insertar";
	MostrarConsulta4(destino, "destino_vesr")

}
function cotizarguia(destino, div)
{

	var cuidadori=document.getElementById("param4").value;
	var cuidaddes=document.getElementById("param11").value;
	var prestamo=document.getElementById("param16").value;
	var abono=document.getElementById("param17").value;
	var seguro=document.getElementById("param18").value;
	var peso=document.getElementById("param26").value;
	var volumen=document.getElementById("param27").value;
	var valortservicio=document.getElementById("param37").value;
	var valor=$('input:radio[name=param28]:checked').val();
	var cliente=document.getElementById("param8").value;
	//var valor=0;
	destino="resultados1.php?param1="+valor+"&param2="+cuidadori+"&param3="+cuidaddes+"&param4="+prestamo+"&param5="+abono+"&param6="+seguro+"&param7="+peso+"&param8="+volumen+"&cond="+destino+"&valortservicio="+valortservicio+"&cliente="+cliente;
	MostrarConsulta4(destino, "destino_vesr")
}
function  verificar(ciudad){
	console.log(ciudad);
	var divvalor2= document.getElementById("mensaje2");

	datos = {"vlores":ciudad,"tipo":"validarciudad"};
	$.ajax({

			url: "validarciudad.php",
			type: "POST",
			data: datos
		}).done(function(respuesta){
			console.log(respuesta);
			if(respuesta.ciu_pagoorigen=='si'){
				divvalor2.innerHTML="<div class='alert  alert-danger'><strong>Atencion!</strong>  LA GUIA  SE DEBE PAGAR EN LA SEDE DE ORIGEN </div>";
			}
			//	mensaje.innerHTML="<div class='alert alert-success'><strong>Atencion!</strong>  LA GUIA  SE DEBE PAGAR EN LA SEDE DE ORIGEN </div>";
		});

	}
function llena_trabajopop(valor, div, condecion)
{
	url="resultados6.php";
	if(valor!=0){
		destino=url+"?tabla="+valor+"&cond=1&condecion="+condecion+"&div="+div;
		window.open(destino, target=div);
//		if(condecion==1) { MostrarConsulta(destino, div); }
//		if(condecion==2) { MostrarConsulta2(destino, div); }
//		if(condecion==3) { MostrarConsulta3(destino, div); }
//		if(condecion==4) { MostrarConsulta4(destino, div); }
//		if(condecion==5) { MostrarConsulta5(destino, div); }
	}
}

function llena_trabajopop1(valor, div, accion)
{
	url="resultados6.php";
	if(accion==3){url="resultados5.php";}
	destino=url+"?tabla="+valor+"&cond="+accion+"&div="+div;
	MostrarConsulta(destino, div);
}

function llena_trabajopop2(valor, div, accion, id_param)
{
	url="resultados6.php";
	if(accion==3){url="resultados5.php";}
	destino=url+"?tabla="+valor+"&cond="+accion+"&div="+div+"&id_param="+id_param;
	MostrarConsulta(destino, div);
}


function llena_trabajo3(valor, div, id_param, id_tabla, param)
{
	destino="resultados5.php?tabla="+valor+"&id_param="+id_param+"&id_tabla="+id_tabla+"&param="+param+"&div="+div;
	window.open(destino, target=div);
}


function busqueda4(div)
{
	p1="";//document.form1.param1.value;
	p2="";//document.form1.param2.value;
	p3=document.form1.param3.options[document.form1.param3.selectedIndex].value;
	p4=document.form1.param4.options[document.form1.param4.selectedIndex].value;
	p5=document.form1.param5.options[document.form1.param5.selectedIndex].value;
	p6=document.form1.param6.options[document.form1.param6.selectedIndex].value;
	destino="detalle_alarmas1.php?&param1="+p1+"&param2="+p2+"&param3="+p3+"&param4="+p4+"&param5="+p5+"&param6="+p6;
	window.open(destino, target=div);
}


function busqueda3(div)
{
	p1=document.form1.param1.value;
	p2=document.form1.param2.value;
	p3=document.form1.param3.options[document.form1.param3.selectedIndex].value;
	p4=document.form1.param4.options[document.form1.param4.selectedIndex].value;
	p5=document.form1.param5.options[document.form1.param5.selectedIndex].value;
	p6=document.form1.param6.options[document.form1.param6.selectedIndex].value;
	p7=document.form1.param7.options[document.form1.param7.selectedIndex].value;
	p8=document.form1.param8.options[document.form1.param8.selectedIndex].value;
	destino="rep_reporte1.php?&param1="+p1+"&param2="+p2+"&param3="+p3+"&param4="+p4+"&param5="+p5+"&param6="+p6+"&param7="+p7+"&param8="+p8;
	document.location.href=destino;
}

function llena_trabajo_dentro(valor, id_tabla, div, condecion)
{
	if(valor!=0){
		destino="resultados6.php?tabla="+id_tabla+"&cond=1&condecion="+condecion+"&div="+div+"&id_acta="+valor;
		window.open(destino, target=div);
	}
}

function llena_trabajo1(valor, div, id_param, id_tabla)
{
	destino="resultados5.php?tabla="+valor+"&id_param="+id_param+"&id_tabla="+id_tabla+"&div="+div;
	window.open(destino, target=div);
}

function valor_total(destino, valor, nombre, div)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino+"&nombre="+nombre;
	MostrarConsulta(destino, div);
}

function llena_trabajo2(valor, div, id_param)
{
	destino="resultados6.php?tabla="+valor+"&id_param="+id_param+"&cond=2&div="+div;
	window.open(destino, target=div);
}

function nuevo_valor(tabla, div, valor)
{
	destino="resultados5.php?tabla="+tabla+"&id_param="+valor;
	window.open(destino, target=div);
}

function nuevo_valor1(tabla, div, valor)
{
	destino="resultados5.php?tabla="+tabla+"&id_p="+valor;
	window.open(destino, target=div);
}

function nuevo_valor2(tabla, div, valor, cond)
{
	destino="resultados6.php?tabla="+tabla+"&id_p="+valor+"&cond=1";
	window.open(destino, target=div);
}

function edita_valor(tabla, div, valor)
{
	destino="resultados4.php?tabla="+tabla+"&id_param="+valor;
	MostrarConsulta(destino, div);
}

function elimina_valor(tabla, div, valor)
{
	destino="del_admin.php?tabla="+tabla+"&id_param="+valor;
	MostrarConsulta(destino, div);
}

function cambio_ajax1(valor, destino, div, nombre, profundidad)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino+"&nombre="+nombre;
	if(profundidad==1){ MostrarConsulta(destino, div); }
	else if(profundidad==2){ MostrarConsulta2(destino, div); }
	else if(profundidad==3){ MostrarConsulta3(destino, div); }
	else if(profundidad==4){ MostrarConsulta4(destino, div); }
	else if(profundidad==5){ MostrarConsulta5(destino, div); }
}

function cambio_ajax4(valor, valor2, destino, div,nombre, profundidad,para)
{
	destino="resultados1.php?param1="+valor+"&param2="+valor2+"&cond="+destino+"&para="+para+"&nombre="+nombre;
	//console.log(destino);
	if(profundidad==1){ MostrarConsulta(destino, div); }
	else if(profundidad==2){ MostrarConsulta2(destino, div); }
	else if(profundidad==3){ MostrarConsulta3(destino, div); }
	else if(profundidad==4){ MostrarConsulta4(destino, div); }
	else if(profundidad==5){ MostrarConsulta5(destino, div); }
}

function cambio_ajax2(valor, destino, div, nombre, profundidad, para)
{
	if (destino=="75") {
		verificarDineroAsignado();
	}
	

	destino="resultados1.php?param1="+valor+"&cond="+destino+"&nombre="+nombre+"&para="+para;
	console.log(destino);
	if(profundidad==1){ MostrarConsulta(destino, div); }
	else if(profundidad==2){ MostrarConsulta2(destino, div); }
	else if(profundidad==3){ MostrarConsulta3(destino, div); }
	else if(profundidad==4){ MostrarConsulta4(destino, div); }
	else if(profundidad==5){ MostrarConsulta5(destino, div); }

}

function verificarDineroAsignado() {
	$.ajax({
	  url: 'verificar_dinero.php',
	  type: 'GET',
	  dataType: 'json',
	  success: function(response) {
		const mensaje = response.mensaje?.trim();
		
		if (mensaje === 'Tienes dinero pendiente por aceptar' || mensaje === 'No tienes dinero comunicate con la oficina') {
		  alert("¡" + mensaje + "!");
  
		  // Acceder a los otros datos:
		  console.log("Guía: " + response.numguia);
		  console.log("Teléfono: " + response.telefono);
		  console.log("Tipo: " + response.tipo);
		  console.log("ID Servicio: " + response.idservi);
		  console.log("Función: " + response.funcion);

		  var numguia = response.numguia;
		  var telefono = response.telefono;
		  var tipo = response.tipo;
		  var idservi = response.idservi;
		  var funcion = "enviarAlertaWhat";
  
		  enviarAlertaWhatsappN(numguia, telefono, tipo, idservi, funcion);
  
		} else if (mensaje === 'ok') {
		  // Todo bien
		  alert("¡" + mensaje + "!");
		} else {
		  alert("Respuesta no reconocida.");
		}
	  },
	  error: function() {
		alert("Error al verificar el dinero.");
	  }
	});
  }
  function enviarAlertaWhatsappN(numguia, telefono, tipo, idservi, funcion) {
    $.ajax({
        url: 'enviarAlertasWhatsapp.php',
        type: 'POST',
        data: {
            numguia: numguia,
            telefono: telefono,
            tipo: tipo,
            idservi: idservi,
            funcion: funcion
        },
		success: function(respuesta) {
			if (respuesta.success) {
			  alert(respuesta.success); // Aquí solo verás: "Mensaje enviado con éxito"
			} else if (respuesta.error) {
			  alert("Error: " + respuesta.error);
			} else {
			  alert("Respuesta desconocida.");
			}
		  },
        error: function(xhr, status, error) {
            console.error("Error en AJAX:", error);
            alert("Ocurrió un error al enviar la alerta.");
        }
    });
}
function cambio_ajax21(valor, destino, div, nombre, profundidad, para)
{
	var valor2='0';
	if(valor=='Sede Origen'){
		valor2=document.getElementById('param1').value;

	}else if(valor=='Sede Destino'){
		valor2=document.getElementById('param2').value;
	} else {
		alert("Seleccione donde va a pagar");
	}
	if(valor2!=0){

		destino="resultados1.php?param1="+valor2+"&cond="+destino+"&nombre="+nombre+"&para="+para;
		if(profundidad==1){ MostrarConsulta(destino, div); }
		else if(profundidad==2){ MostrarConsulta2(destino, div); }
		else if(profundidad==3){ MostrarConsulta3(destino, div); }
		else if(profundidad==4){ MostrarConsulta4(destino, div); }
		else if(profundidad==5){ MostrarConsulta5(destino, div); }
	}

}


function cambioss(div, mod, alto, bajo, calto, cbajo, poz, vari, activo, critica)
{
	destino="resultados1.php?param1="+mod+"&cond=7&param2="+alto+"&param3="+bajo+"&param4="+calto+"&param5="+cbajo+"&poz="+poz+"&vari="+vari+"&activo="+activo+"&critica="+critica;
	MostrarConsulta(destino, div);

}
function ir(valor, pagina)
{
	destino=pagina+"?param1="+valor;
	document.location.href=destino;
}

function elimina_registro(nom, div, id_p)
{
    if(!confirm("Esta seguro de eliminar este registro?")) {
		return;
	}
	destino="resultados6.php?tabla="+nom+"&id_param="+id_p+"&cond=2&div="+div;
	MostrarConsulta4(destino, div);
}


function cambio_ajax11(valor, destino, div)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino;
	MostrarConsulta4(destino, div);
}

function cambio_ajax71(valor, destino, div,param)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino+"&para="+param;
	MostrarConsulta(destino, div);
}

function cambio_ajax133(valor, destino, div,param)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino+"&para="+param;
	MostrarConsulta2(destino, div);
}

function cambio_ajax13(valor, destino, div)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino;
	MostrarConsulta2(destino, div);
}

function cambio_ajax12(valor, destino, div)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino;
	MostrarConsulta3(destino, div);
}

function cambio_ajax15(valor, destino, div, param)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino+"&para="+param;
	MostrarConsulta5(destino, div);
}

function cambio_ajax31(valor, destino, div)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino;
	MostrarConsulta3(destino, div);
}

function cambio_ajax51(valor, destino, div)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino;
	MostrarConsulta3(destino, div);
}

function cambio_ajax111(valor, destino, div, param)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino+"&para="+param;
	MostrarConsulta4(destino, div);
}

function cambio_ajax122(valor, destino, div, param)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino+"&para="+param;
	MostrarConsulta3(destino, div);
}

function cambio_ajax23(valor, destino, div, param)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino+"&para="+param;
	MostrarConsulta3(destino, div);
}

function cambio_ajax3(valor, destino, div, param)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino+"&para="+param;
	MostrarConsulta2(destino, div);
}

function cambio_ajax5(valor, destino, div, param)
{
	destino="resultados1.php?param1="+valor+"&cond="+destino+"&para="+param;
	MostrarConsulta3(destino, div);
}


function cambio_ajax33(valor, destino, div, param)
{
	destino="resultados1.php?param2="+valor+"&cond="+destino+"&para="+param;
	MostrarConsulta21(destino, div);
}

function checkChoice(nombre, objeto, nomb1) {
    proyec="";
	for (i=0; i<eval(objeto).length; i++)
    {
		if (eval(objeto)[i].checked)
		{
			valor=eval(objeto)[i].value;
			proyec+=valor+"; ";
			valor=valor.trim();
		}
	}
	eval(nombre).value=proyec;
}

function checkChoiceN(nombre, nombre2)
{
    proyec="";
	for (i=0; i<eval(nombre).length; i++)
    {
		if (eval(nombre)[i].checked)
		{
			valor=eval(nombre)[i].value;
			proyec+=valor+", ";
		}
	}
	eval(nombre2).value=proyec;
}

function checkChoiceN1(valor, nombre2, qi)
{
	proyec=eval(nombre2).value;
	if(qi==1){
		valor=valor+",";
		proyec=proyec.replace(valor,"");
	}
	else {
		proyec=proyec+""+valor+",";
	}
	pro=proyec.split(",");
	proy="";
	for(i=0; i<pro.length; i++)
	{
		if(!isNaN(pro[i])){ proy+=parseInt(pro[i])+",";	}
	}
	proy=proy.replace(",NaN","");
	if(nombre2=="selideduca"){ eval("selproyectos").value=""; eval("seltiposp").value="";  }
	else if(nombre2=="selproyectos"){ eval("selideduca").value=""; eval("seltiposp").value="";  }
	else if(nombre2=="seltiposp"){ eval("selproyectos").value=""; eval("selideduca").value="";  }
	eval(nombre2).value=proy;
}

function checkChoice2(objeto) {
    proyec="";
	for (i=0; i<document.form11.proyectos.length; i++)
    {
		if (document.form11.proyectos[i].checked)
		{
			valor=document.form11.proyectos[i].value;
			proyec+=valor+", ";
		}
	}
	document.form11.selproyectos.value=proyec;
//	destino="resultados1.php?param1="+proyec+"&cond=15&para=0";
//	MostrarConsulta4a(destino, "mye1");
    myev="";
}

function checkChoice3(objeto) {
    myev="";
	alert("asd");
	for (i=0; i<document.form11.mye.length; i++)
    {
		if (document.form11.mye[i].checked)
		{
			valor=document.form11.mye[i].value;
			myev+=valor+", ";
		}
	}
	document.form11.selmye.value=myev;
}

function checkChoice4(objeto) {
    indica="";
	for (i=0; i<document.form11.indi.length; i++)
    {
		if (document.form11.indi[i].checked)
		{
			valor=document.form11.indi[i].value;
			indica+=valor+", ";
		}
	}
	document.form11.selindi.value=indica;
}

function checkChoice5(objeto) {
    params="";
	for (i=0; i<document.form11.paras.length; i++)
    {
		if (document.form11.paras[i].checked)
		{
			valor=document.form11.paras[i].value;
			params+=valor+", ";
		}
	}
	document.form11.selpara.value=params;
}

function checkChoice6(objeto) {
    proyec="";
	for (i=0; i<document.form11.proyectos.length; i++)
    {
		if (document.form11.proyectos[i].checked)
		{
			valor=document.form11.proyectos[i].value;
			proyec+=valor+", ";
		}
	}
	document.form11.selproyectos.value=proyec;
	destino="resultados1.php?param1="+proyec+"&cond=20&para=0";
	MostrarConsulta4a(destino, "parame2");
}

function checkChoice7(objeto, nombre) {
    proyec="";
	for (i=0; i<eval(objeto).length; i++)
    {
		if (eval(objeto)[i].checked)
		{
			valor=eval(objeto)[i].value;
			proyec+=valor+", ";
		}
	}
	eval(nombre).value=proyec;
}
function cambio_porRol(valor){


	if(valor==6){
		// echo"<div id='clientes'>";
		// var fila = document.getElementById("fila");
		document.getElementById("clientes").style.display = "table-row";
	 }else{
		// echo"<div id='todo1'>";
		document.getElementById("todo1").style.display = "block";
	}

	if(valor==3){
		document.getElementById("operador").style.display = "block";
		// echo"<div id='operador'>";
	}else{
		// echo"<div id='todo2'>";
		document.getElementById("todo2").style.display = "block";
	}

	if(valor!=6){
		document.getElementById("todos3").style.display = "block";
		// echo"<div id='todos3'>";
	}else{
		document.getElementById("clientes2").style.display = "block";

		// echo"<div id='clientes2'>";



	}




	}
	function comprobar(){

		var credito=document.getElementById("param1").value;
		var origen=document.getElementById("param2").value;
		var destino=document.getElementById("param3").value;
		var servicio=document.getElementById("param6").value;
		var servicioL=document.getElementById("param6");

		var funcion="precios credito";

		datos = {"credito":credito,"origen":origen,"destino":destino,"servicio":servicio,"funcion":funcion};
            $.ajax({
                        url: "comprobador.php",
                        type: "POST",
                        data: datos
                    }).done(function(respuesta){
						if (respuesta=="Se encontro un precio credito igual verifique e intente de nuevo") {
							servicioL.selectedIndex = "";
							alert(respuesta);
						}


            });



	}

	 // Función para verificar el estado de todos los selects con la clase "borrar"
	 function checkSelects() {
		let allSelectedYes = true;
		const selects = document.querySelectorAll('.borrar');

		selects.forEach(function (select) {
		  if (select.value !== 'SI') {
			allSelectedYes = false;
		  }
		});

		// Mostrar u ocultar el botón basado en el estado de los selects
		const button = document.getElementById('entregad');
		if (allSelectedYes) {
		  button.style.display = 'block';
		} else {
		  button.style.display = 'none';
		}


	  }


function verificarDinero(selectElement) {
    const valorSeleccionado = selectElement.value;

    if (valorSeleccionado === 'Sede Origen') {
		$.ajax({
			url: 'verificar_dinero.php',
			type: 'GET',
			dataType: 'json',
			success: function(response) {
			  const mensaje = response.mensaje?.trim();
			  
			  if (mensaje === 'Tienes dinero pendiente por aceptar' || mensaje === 'No tienes dinero comunicate con la oficina') {
				alert("¡" + mensaje + "!");
		
				// Acceder a los otros datos:
				console.log("Guía: " + response.numguia);
				console.log("Teléfono: " + response.telefono);
				console.log("Tipo: " + response.tipo);
				console.log("ID Servicio: " + response.idservi);
				
	  
				var numguia = response.numguia;
				var telefono = response.telefono;
				var tipo = response.tipo;
				var idservi = response.idservi;
				var funcion = "enviarAlertaWhat";
		
				enviarAlertaWhatsappN(numguia, telefono, tipo, idservi, funcion);
		
			  } else if (mensaje === 'ok') {
				alert("¡" + mensaje + "!");
			  } else {
				alert("Respuesta no reconocida.");
			  }
			},
			error: function() {
			  alert("Error al verificar el dinero.");
			}
		  });
    }
}
