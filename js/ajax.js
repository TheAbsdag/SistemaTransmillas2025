//Desarrollado por Jesus Li��n
//ribosomatic.com
//Puedes hacer lo que quieras con el c�digo
//pero visita la web cuando te acuerdes

function objetoAjax(){
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
  		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function espera() 
{ 
    divResultado.innerHTML = ajax.responseText; 
} 
function espera2() 
{ 
    divResultado1.innerHTML = ajax1.responseText; 
} 
function espera3() 
{ 
    divResultado3.innerHTML = ajax3.responseText; 
} 
function espera4() 
{ 
    divResultado4.innerHTML = ajax4.responseText; 
} 
function espera5() 
{ 
    divResultado5.innerHTML = ajax5.responseText; 
}

function MostrarConsulta(datos, otro){
	divResultado = document.getElementById(otro);
	showdiv(otro);
	ajax=objetoAjax();
	evalScripts: true;
	ajax.open("GET", datos, true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState!=4) {
			//divResultado1.innerHTML = ajax1.responseText;
			divResultado.innerHTML = '<img src="img/loading_gif.gif">'; 
		}
		else{ 
		    window.setTimeout('espera();',50); //pretendemos demorar la respuesta unos segundos 
		} 
	}
	ajax.send(null);
	return true;
}

function MostrarConsulta2(datos1, otro1){
	
	divResultado1 = document.getElementById(otro1);
	showdiv(otro1);
	ajax1=objetoAjax();
	ajax1.open("GET", datos1);
	ajax1.onreadystatechange=function() {
		if (ajax1.readyState!=4) {
			//divResultado1.innerHTML = ajax1.responseText;
			divResultado1.innerHTML = '<img src="img/loading_gif.gif">'; 
		}
		else{ 
		    window.setTimeout('espera2();',50); //pretendemos demorar la respuesta unos segundos 
		} 
	}
	ajax1.send(null);
	return true;
}


function MostrarConsulta3(datos3, otro3){
	divResultado3 = document.getElementById(otro3);
	showdiv(otro3);
	ajax3=objetoAjax();
	ajax3.open("GET", datos3);
	ajax3.onreadystatechange=function() {
		if (ajax3.readyState!=4) {
			//divResultado1.innerHTML = ajax1.responseText;
			divResultado3.innerHTML = '<img src="img/loading_gif.gif">'; 
		}
		else{ 
		    window.setTimeout('espera3();',50); //pretendemos demorar la respuesta unos segundos 
		} 
	}
	ajax3.send(null);
	return true;
}

function MostrarConsulta4(datos4, otro4){
	divResultado4 = document.getElementById(otro4);
	showdiv(otro4);
	ajax4=objetoAjax();
	ajax4.open("GET", datos4);
	ajax4.onreadystatechange=function() {
		if (ajax4.readyState!=4) {
			//divResultado1.innerHTML = ajax1.responseText;
			divResultado4.innerHTML = '<img src="img/loading_gif.gif">'; 
		}
		else{ 
		    window.setTimeout('espera4();',50); //pretendemos demorar la respuesta unos segundos 
		} 
	}
	ajax4.send(null);
	return true;
}

function MostrarConsulta2a(datos1, otro1){
	divResultado1 = document.getElementById(otro1);
	ajax1=objetoAjax();
	ajax1.open("GET", datos1);
	ajax1.onreadystatechange=function() {
		if (ajax1.readyState!=4) {
			//divResultado1.innerHTML = ajax1.responseText;
			divResultado1.innerHTML = '<img src="img/loading_gif.gif">'; 
		}
		else{ 
		    window.setTimeout('espera2();',50); //pretendemos demorar la respuesta unos segundos 
		} 
	}
	ajax1.send(null);
	return true;
}

function MostrarConsulta3a(datos3, otro3){
	divResultado3 = document.getElementById(otro3);
	ajax3=objetoAjax();
	ajax3.open("GET", datos3);
	ajax3.onreadystatechange=function() {
		if (ajax3.readyState!=4) {
			//divResultado1.innerHTML = ajax1.responseText;
			divResultado3.innerHTML = '<img src="img/loading_gif.gif">'; 
		}
		else{ 
		    window.setTimeout('espera3();',50); //pretendemos demorar la respuesta unos segundos 
		} 
	}
	ajax3.send(null);
	return true;
}

function MostrarConsulta4a(datos4, otro4){
	divResultado4 = document.getElementById(otro4);
	ajax4=objetoAjax();
	ajax4.open("GET", datos4);
	ajax4.onreadystatechange=function() {
		if (ajax4.readyState!=4) {
			//divResultado1.innerHTML = ajax1.responseText;
			divResultado4.innerHTML = '<img src="img/loading_gif.gif">'; 
		}
		else{ 
		    window.setTimeout('espera4();',50); //pretendemos demorar la respuesta unos segundos 
		} 
	}
	ajax4.send(null);
	return true;
}

function MostrarConsulta5(datos5, otro5){
	divResultado5 = document.getElementById(otro5);
	showdiv(otro5);
	ajax5=objetoAjax();
	ajax5.open("GET", datos5);
	ajax5.onreadystatechange=function() {
		if (ajax5.readyState!=4) {
			//divResultado1.innerHTML = ajax1.responseText;
			divResultado5.innerHTML = '<img src="img/loading_gif.gif">'; 
		}
		else{ 
		    window.setTimeout('espera5();',50); //pretendemos demorar la respuesta unos segundos 
		} 
	}
	ajax5.send(null);
	return true;
}

function showdiv(hideShow) {
	if (document.getElementById) { // DOM3 = IE5, NS6 
		document.getElementById(hideShow).style.display = 'block'; 
		document.getElementById(hideShow).style.visibility = 'visible'; 
	} 
	else { 
		if (document.layers) { // Netscape 4 
			document.hideShow.visibility = 'visible'; 
		} 
		else { // IE 4 
			document.all.hideShow.style.visibility = 'visible'; 
		} 
	} 
}

function escondertodo(pass)
{
    var divs = document.getElementsByTagName('div'); 
    for(i=0;i<divs.length;i++){ 
    	fe=divs[i].id.match(pass);
	    if(fe==pass){//if they are 'see' divs 
	        if (document.getElementById) // DOM3 = IE5, NS6 
	    	{	
       			divs[i].style.display = 'none'; 
				divs[i].style.visibility="hidden";
			}// show/hide }
		    else {
			    if (document.layers) // Netscape 4 
			    {	document.layers[divs[i]].display = 'hidden'; }
			    else // IE 4 
			    {	document.all.hideShow.divs[i].visibility = 'hidden'; }
		    }	
	    }
    }
} 