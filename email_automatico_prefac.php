<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var facturasVencidas = [];
function sendEmail(idfac,email,body,asunto,numero,linkFac){


        

    console.log(idfac+"_"+email+"_"+body);


    const formData = new FormData();
    var cond = 1;
    //agregar correo
    formData.append('correo', email);
    //agregar correo
    formData.append('body', body);
    formData.append('idfac', idfac);
    formData.append('cond', cond);
    formData.append('asunto', asunto);
    formData.append('numero', numero);
    formData.append('linkFac', linkFac);




    fetch('email_fac.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        console.log(result);
        // alert(result);

    })
    .catch(error => {
        console.error('Error:', error);
        
    }).finally(() => {

    });

}
</script>

<?php
$bd="u713516042_transmillas2"; 
$host="localhost";
$user="u713516042_jose2";
$pass="Dobarli23@transmillas";
$Usu_ses="vive";
$salt = "transmi2344fsdfd"; 

date_default_timezone_set("America/Bogota");


$link = mysqli_connect($host, $user, $pass) or die("Unable to Connect to '$host'");
mysqli_select_db($link, $bd) or die("Could not open the db '$bd'");




// Fecha actual
$hoy = new DateTime('now');
$dia = (int)$hoy->format('d');
    if ($dia < 21) {
        // Usar mes anterior
        $fechaInicio = new DateTime('first day of last month');
        $fechaFin = new DateTime('last day of last month');
    } else {
        // Usar mes actual
        $fechaInicio = new DateTime('first day of this month');
        $fechaFin = new DateTime('last day of this month');
    }
$fin = $fechaFin->format('Y-m-d');



    echo$sqlPre="SELECT `idfacturascreditos`, `fac_fechafactura`,`fac_credito`, `fac_numerofactura`, `fac_fechaprefac`,`fac_idservicios`, `fac_iduserpre`,`fac_numeroref`, `fac_fechafacturado`, `fac_fechavencimiento`, `fac_estado`,`fac_tipopago`,`fac_iduserfac`,fac_precio,`fac_fecharadicado`,fac_fechapago,fac_notacredito,fac_fecharafacturado,fac_pagoconfir,fac_userconfirmo,fac_fechacomfir,fac_valorpendiente,fac_preciofinal,fac_correoven, fac_nit,fac_correofac FROM `facturascreditos`  WHERE date(fac_fechafactura)>='2024-01-01' and date(fac_fechafactura)<='$fin' and fac_estado='Pre-Facturado' ORDER BY fac_numeroref ASC";
    $result5 = mysqli_query($link, $sqlPre);
    
    while ($rw1 = mysqli_fetch_row($result5)) {
            $id_p=$rw1[0];
            echo$sql2="SELECT `idcreditos`, `cre_nombre`,idhojadevida,cre_numero_auto FROM `creditos` INNER JOIN hojadevidacliente on hoj_clientecredito=idcreditos WHERE cre_nombre='$rw1[2]'";

            $result3 = mysqli_query($link, $sql2);
            $rw2=mysqli_fetch_row($result3);

            echo$correo="SELECT `cont_correo` FROM `contactofacturacion` WHERE cont_idhojavida ='$rw2[2]' and con_principal='1'";

            $result4 = mysqli_query($link, $correo);
            $ema=mysqli_fetch_row($result4);
            $numero=$rw2[3];
            if(file_exists("pre_facturas/{$rw1[3]}.xls")){

                $linkFac="pre_facturas/$rw1[3].xls";
                $mensaje="Estimado cliente envío archivo en excel con la relación de los servicios prestados para su respectiva aprobación esperando respuesta para generar la factura correspondiente";
                $email="$ema[0]";
                echo "<script>facturasVencidas.push({id: $id_p, email: '$email', mensaje: `$mensaje`, asunto: `$asunto`, numero: `$numero`,linkFac: `$linkFac`});</script>";


            }

    }






?>
<script>
function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function enviarCorreosSecuenciales() {
    for (let i = 0; i < facturasVencidas.length; i++) {
        const f = facturasVencidas[i];
        console.log(`Enviando correo ${i + 1} de ${facturasVencidas.length}...`);
        sendEmail(f.id, f.email, f.mensaje, f.asunto,f.numero,f.linkFac);
        await delay(15000); // Esperar 15 segundos antes del siguiente envío
    }
    console.log("Todos los correos han sido enviados.");
}

// Iniciar envío después de 2 segundos para asegurar carga completa
setTimeout(() => {
    if (facturasVencidas.length > 0) {
        enviarCorreosSecuenciales();
    } else {
        console.log("No hay facturas vencidas para enviar.");
    }
}, 2000);
</script>