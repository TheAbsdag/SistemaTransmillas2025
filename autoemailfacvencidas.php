<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var facturasVencidas = [];
function sendEmail(idfac,email,body,asunto){


        

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




    fetch('email_facvencida.php', {
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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$bd="u713516042_transmillas2"; 
$host="localhost";
$user="u713516042_jose2";
$pass="Dobarli23@transmillas";
$Usu_ses="vive";
$salt = "transmi2344fsdfd"; 

date_default_timezone_set("America/Bogota");


 $link = mysqli_connect($host, $user, $pass) or die("Unable to Connect to '$host'");
mysqli_select_db($link, $bd) or die("Could not open the db '$bd'");



$fechaActual=date('Y-m-d');




echo$sql="SELECT `idfacturascreditos`, `fac_fechafactura`,`fac_credito`, `fac_numerofactura`, `fac_fechaprefac`,`fac_idservicios`, `fac_iduserpre`,`fac_numeroref`, `fac_fechafacturado`, `fac_fechavencimiento`, `fac_estado`,`fac_tipopago`,`fac_iduserfac`,fac_precio,`fac_fecharadicado`,fac_fechapago,fac_notacredito,fac_fecharafacturado,fac_pagoconfir,fac_userconfirmo,fac_fechacomfir,fac_valorpendiente,fac_preciofinal,fac_correoven, fac_nit,fac_correofac,fac_correo_auto FROM `facturascreditos` WHERE date(fac_fechafactura)>='2024-01-01' and (fac_tipopago='Pendiente' or fac_tipopago is null) and fac_estado='Facturado'   ORDER BY fac_numeroref ASC ";
$html="";
$resultado="";
// $DB->Execute($sql); 
$result2 = mysqli_query($link, $sql);
$guias=0;
$val=0;
	while($rw1=mysqli_fetch_row($result2))
	{
        $fechaVence=$rw1[9];
        $numero=$rw1[7];
        $id_p=$rw1[0];
        if ($rw1[2]=="EXTERNOS") {


        $email="$rw1[26]";
        
            # code...
        
            $mensaje="Estimado cliente le recordamos que la factura # ".$numero."  se encuentra vencida,si ya realizó su pago por favor enviar el soporte a  esté correo";
            $asunto="Factura Vencida Transmillas #".$numero."";
        


            if ( $fechaActual>=$fechaVence) {
                if ($email!="") {
                    echo$numero;
                    echo"Es externo Pendiente ".$id_p."-".$email."-".$mensaje."<br>";
                    // echo"<script>sendEmail($id_p,\"$email\",\"$mensaje\",\"$asunto\");</script>"; 
                    echo "<script>facturasVencidas.push({id: $id_p, email: '$email', mensaje: `$mensaje`, asunto: `$asunto`});</script>";
                    $val++;
                    // echo"".$id_p.$email.$mensaje;
                    $resultado.="Factura $numero Vencida el $fechaVence Se envia al correo $email Alerta <br> \n";


                    $sql3="SELECT fac_correoven FROM `facturascreditos`  WHERE idfacturascreditos='$id_p'";
                    $result5 = mysqli_query($link, $sql3);
                    $cuen=mysqli_fetch_row($result5);
                    
                    $nummensajes=$cuen[0]+1;
                    // $sqlsqlupdate = "UPDATE `facturascreditos` SET fac_correoven='$nummensajes'  WHERE idfacturascreditos='$id_p'";
                    // $update = mysqli_query($link, $sqlsqlupdate);
                }else {
                    $resultado.="Factura $numero Vencida el $fechaVence No se encontro correo del credito $rw1[2]  <br> \n";
                }
                
                
            }
        
        }else{
            $sql2="SELECT `idcreditos`, `cre_nombre`,idhojadevida FROM `creditos` INNER JOIN hojadevidacliente on hoj_clientecredito=idcreditos WHERE cre_nombre='$rw1[2]'";

            $result3 = mysqli_query($link, $sql2);
            $rw2=mysqli_fetch_row($result3);

            $correo="SELECT `cont_correo` FROM `contactofacturacion` WHERE cont_idhojavida ='$rw2[2]' and con_principal='1'";

            $result4 = mysqli_query($link, $correo);
            $ema=mysqli_fetch_row($result4);




            $email="$ema[0]";
            $mensaje="Estimado cliente le recordamos que la factura # ".$numero."  se encuentra vencida,si ya realizó su pago por favor enviar el soporte a  esté correo";
            $asunto="Factura Vencida Transmillas #".$numero."";
            
 

                if ( $fechaActual>=$fechaVence) {
                    if ($email!="") {
                        echo$numero;
                        echo$rw2[1]." Pendiente ".$id_p."-".$email."-".$mensaje."<br>";
                        // echo"<script>sendEmail($id_p,\"$email\",\"$mensaje\",\"$asunto\");</script>"; 
                        echo "<script>facturasVencidas.push({id: $id_p, email: '$email', mensaje: `$mensaje`, asunto: `$asunto`});</script>";

                        // echo"".$id_p.$email.$mensaje;
                        $val++;
                        $resultado.="Factura $numero Vencida el $fechaVence Se envia al correo $email Alerta <br> \n";


                        $sql3="SELECT fac_correoven FROM `facturascreditos`  WHERE idfacturascreditos='$id_p'";
                        $result5 = mysqli_query($link, $sql3);
                        $cuen=mysqli_fetch_row($result5);
                        
                        $nummensajes=$cuen[0]+1;
                        $sqlsqlupdate = "UPDATE `facturascreditos` SET fac_correoven='$nummensajes'  WHERE idfacturascreditos='$id_p'";
                        $update = mysqli_query($link, $sqlsqlupdate);
                    }else {
                        $resultado.="Factura $numero Vencida el $fechaVence No se encontro correo del credito $rw1[2]  <br> \n";
                    }
                    
                    
                }


            // }
        }


        $resultado.= "_".$fechaActual;
    }

    Echo$val;
    // echo$resultado;


?>
<script>
function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function enviarCorreosSecuenciales() {
    for (let i = 0; i < facturasVencidas.length; i++) {
        const f = facturasVencidas[i];
        console.log(`Enviando correo ${i + 1} de ${facturasVencidas.length}...`);
        sendEmail(f.id, f.email, f.mensaje, f.asunto);
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
