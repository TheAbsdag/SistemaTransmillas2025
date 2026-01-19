<?php
// ticket_renovado.php
// Versión visual renovada con Bootstrap 5. No se cambió la lógica ni las consultas, solo la presentación.

// includes originales
// require("login_autentica.php");
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
$vis = @$_GET['vis'];
@$id_param = isset($id_param) ? $id_param : @$_REQUEST['idservicios'];

// traer datos del servicio (misma lógica que tenía el archivo original)
$sql = "SELECT `idclientes`,`ser_consecutivo`, `cli_nombre`,  `ser_destinatario`, `ser_telefonocontacto`,`ciu_nombre`,
 `ser_direccioncontacto`, `ser_paquetedescripcion`, `ser_piezas`,`ser_clasificacion`, `ser_valorprestamo`, 
 `ser_valorabono`, `ser_valorseguro`,`ser_resolucion`, `ser_pendientecobrar`,ser_valor,ser_peso,`cli_idciudad`,`ser_ciudadentrega`,
 `ser_tipopaq` ,`cli_telefono`, `cli_direccion`,ser_volumen,ser_verificado,ser_prioridad,ser_guiare,ser_estado,ser_devolverreci,ser_fecharegistro,ser_descripcion,cli_iddocumento FROM serviciosdia where idservicios=$id_param ";
$DB->Execute($sql);
$rw = mysqli_fetch_array($DB->Consulta_ID);

// reemplazos por GET si vienen
if (isset($_GET['peso']) && $_GET['peso']!="") $rw[16] = $_GET['peso'];
if (isset($_GET['volumen']) && $_GET['volumen']!="") $rw[22] = $_GET['volumen'];
if (isset($_GET['seguro']) && $_GET['seguro']!="") $rw[12] = $_GET['seguro'];
if (isset($_GET['valorf']) && $_GET['valorf']!="") $rw[15] = $_GET['valorf'];

// determinar sede/ciudad según tipo imprimir
if ($imprimir == "Entrega" or $imprimir == "Entregar") {
    $sql0 = "SELECT idciudades,inner_sedes FROM `ciudades`  where  ciu_nombre=$rw[5]";
    $DB1->Execute($sql0);
    $rw0 = mysqli_fetch_array($DB1->Consulta_ID);

    $sql2 = "SELECT `idsedes`, `sed_nombre`, `sed_telefono`, `sed_direccion` FROM `sedes` WHERE idsedes=$rw0[1]";
    $DB1->Execute($sql2);
    $rw2 = mysqli_fetch_array($DB1->Consulta_ID);

} elseif ($imprimir == "Recogida") {
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
$Usuariog = isset($userg[0])?($userg[0]." ".@$userg[1]):$usuguia;
if ($rw[9] == 'Credito') {
  $fechatiempo = $rw[28];
}

// tiposervicio
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

$colorTP = "";
$textoTP = "";
if ( $credito == 'Pendiente por Cobrar' or $rw[9] == 'Credito' or $rw[9] == "Al Cobro") {
  $colorTP = "bg-danger text-white rounded p-2";
  $textoTP = "<b>Falta pago</b>";
} else if($credito == 'Contado'){
  $colorTP = "bg-success text-white rounded p-2";
  $textoTP = "Pagada";
}

// valores y formateos (misma lógica)
$sql = "SELECT `pre_porcentaje` FROM `prestamo` WHERE `pre_inicio`<'$rw[10]' and `pre_final`>='$rw[10]'";
$DB->Execute($sql);
$porprestamo = $DB->recogedato(0);
$dosporcentaje = explode(" ", $porprestamo);
if (@$dosporcentaje[1] == '%') {
  $porprestamo = ($rw[10] * @$dosporcentaje[0]) / 100;
}
@$totalprestamo = $rw[10] + $porprestamo - $abono;
@$totalflete = $rw[15] + $seguro;

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

// preparar imagen firma si existe (misma lógica abajo cuando se imprime)

// variables para la imagen guardada
// codigo de remesa
$code = $rw[1];

// ---------------------
// OUTPUT: HTML renovado con Bootstrap 5
// ---------------------
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Remesa #<?php echo htmlspecialchars($rw[1]); ?> - Transmillas</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

  <!-- jQuery y html2canvas (se mantienen para la descarga a imagen) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="js/html2canvas.js"></script>

  <style>
    body { background: #f7fafc; }
    .ticket-card{ max-width:900px; margin:20px auto; transform-origin: center top; }
    .brand{ letter-spacing:1px }
    .small-muted{ font-size:0.85rem; color:#6c757d }
    .invoice-title{ font-weight:700; font-size:1.25rem }
    .data-row th{ width:38%; }
    .signature-box{ width:100%; height:160px; border:1px dashed #ced4da; display:flex; align-items:center; justify-content:center; background:#fff }
    .zoom-controls{ position: fixed; bottom: 20px; right: 20px; z-index:2000 }
    .zoom-controls button{ width:44px; height:44px }
    .menbrete{ background-image: url('img/menbrete.jpg'); background-repeat:no-repeat; background-position: top right; background-size: 200px auto }
  </style>
</head>
<body>

<input id="idServicio" name="idServicio" type="hidden" value="<?php echo $id_param; ?>">
<input id="factura" name="factura" type="hidden" value="<?php echo $rw[1]; ?>">
<input id="tipo" name="tipo" type="hidden" value="<?php echo $imprimir; ?>">

<div id="imagen" class="ticket-card menbrete">
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <img src="img/logofactura.png" alt="logo" style="height:60px">
          <div class="brand mt-2">Transmillas logística y transportadora S.A.S.</div>
          <div class="small-muted">NIT:901089478-8</div>
          <div class="small-muted">Sucursal: <?php echo htmlspecialchars(@$rw2[1]); ?></div>
          <div class="small-muted"><?php echo htmlspecialchars(@$rw2[3]); ?></div>
        </div>
        <div class="text-end">
          <div class="invoice-title">REMESA #<?php echo htmlspecialchars($rw[1]); ?></div>
          <div class="small-muted">Destino: <?php echo htmlspecialchars($rw[5]); ?></div>
          <div class="small-muted"><?php echo htmlspecialchars(@$fechatiempo); ?></div>
          <div class="mt-2"><img src="img/whatsappp.png" class="icono"> TEL: 310 8093773</div>
        </div>
      </div>

      <hr>

      <div class="row">
        <div class="col-md-6">
          <h6 class="mb-2">Remitente</h6>
          <table class="table table-borderless small data-row">
            <tr><th>Nombre</th><td><?php echo htmlspecialchars($rw[2]); ?></td></tr>
            <tr><th>Teléfono</th><td>*******</td></tr>
            <tr><th>Ciudad</th><td><?php echo htmlspecialchars(@$rw3[0]); ?></td></tr>
            <tr><th>Dirección</th><td><?php echo htmlspecialchars($rw[21]); ?></td></tr>
            <tr><th>CC/NIT</th><td><?php echo htmlspecialchars($rw[33]); ?></td></tr>
          </table>
        </div>
        <div class="col-md-6">
          <h6 class="mb-2">Destinatario</h6>
          <table class="table table-borderless small data-row">
            <tr><th>Nombre</th><td><?php echo htmlspecialchars($rw[3]); ?></td></tr>
            <tr><th>Teléfono</th><td>*******</td></tr>
            <tr><th>Ciudad</th><td><?php echo htmlspecialchars($rw[5]); ?></td></tr>
            <tr><th>Dirección</th><td><?php echo htmlspecialchars($rw[6]); ?></td></tr>
            <tr><th>CC/NIT</th><td><?php echo htmlspecialchars(@$rw5[0]); ?></td></tr>
          </table>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-6">
          <table class="table table-sm">
            <tr><th>Tipo</th><td><?php echo htmlspecialchars($rw[19]); ?></td></tr>
            <tr><th>Dice tener</th><td><?php echo htmlspecialchars($rw[7]); ?></td></tr>
            <tr><th>Piezas</th><td><?php echo htmlspecialchars($rw[8]); ?></td></tr>
            <tr class="<?php echo $colorTP;?>"><th>Tipo pago</th><td><?php echo htmlspecialchars($credito); ?> <?php echo $textoTP; ?></td></tr>
            <tr><th>Pago en</th><td><?php echo htmlspecialchars($pagoen); ?></td></tr>
            <tr><th>Servicio</th><td><?php echo htmlspecialchars($tiposervicio); ?> / Entrega de 24-48 horas</td></tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table table-sm">
            <?php if ($rw[16] <= 0): ?>
              <tr class="table-danger"><th>Peso</th><td><strong>No ha sido pesado</strong></td></tr>
            <?php else: ?>
              <?php if ($rw[16] >= 30): ?>
                <tr><th>Peso Kg</th><td><?php echo htmlspecialchars($rw[16]); ?></td></tr>
              <?php endif; ?>
            <?php endif; ?>
            <tr><th>Volumen</th><td><?php echo htmlspecialchars($rw[22]); ?></td></tr>
            <tr><th>Verificado</th><td>SI <?php echo ($rw[23]==1? '&#9632;': '&#9633;') ?> &nbsp; NO <?php echo ($rw[23]==0? '&#9632;': '&#9633;') ?></td></tr>
            <tr><th>Estado paquete</th><td><?php echo htmlspecialchars($rw[29]); ?></td></tr>
          </table>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <p class="mb-1"><strong>Valor declarado del envío:</strong> $ <?php echo htmlspecialchars($rw[12]); ?></p>
              <p class="mb-1">Valor seguro: $ <?php echo htmlspecialchars($seguro); ?></p>
              <p class="mb-1">Valor flete: $ <?php echo htmlspecialchars($valorflete ?? 'PENDIENTE POR LIQUIDAR'); ?></p>
            </div>
            <div class="col-md-6 text-center align-self-center">
              <h4>TOTAL FLETE: $ <?php echo htmlspecialchars($totalflete); ?></h4>
            </div>
          </div>

          <div class="mt-3">
            <p>¡GRACIAS POR SU CONFIANZA!<br>El cliente acepta las condiciones de transporte.<br>Consulte nuestra política en <a href="https://www.transmillas.com/politica.php">transmillas.com/politica.php</a></p>
            <p class="text-danger small"><strong>Aviso:</strong> Transmillas no se hace responsable por daños o suciedad en mercancías sin embalaje (ej: colchones, vidrios u objetos frágiles). El embalaje es responsabilidad del cliente.</p>
          </div>
        </div>
      </div>

      <!-- Firma (si existe) -->
      <?php
      // misma lógica original para traer la firma según tipo de impresion
      if ($imprimir == "Recogida") {
        $sql1 = "SELECT firma,`nombre`, `numero_documento`,`correo_electronico`, `telefono`,tipo FROM firma_clientes WHERE tipo_firma = 'Recogida' and id_guia='$id_param' order by id desc limit 1";
        $resultado = $DB1->Execute($sql1);
        $fila = mysqli_fetch_assoc($resultado);
        if ($fila) {
          $imagen = $fila['firma'];
          $tipo = $fila['tipo'];
          if ($tipo == 'imagen') {
            $imagen_base64 = ($enviarcorreo!=2 and $enviarcorreo!=3) ? 'tmp_img/imagen_' . $rw[1] . '.png' : 'data:image/png;base64,'. base64_encode($imagen);
          } else {
            $imagen_base64 = $imagen;
          }
          echo "<div class=\"mt-3\"><h6>Firma entrega</h6><div class=\"signature-box\"><img src='$imagen_base64' style='max-height:150px; max-width:100%'></div><p>Quien entrega: ".htmlspecialchars($fila['nombre'])." - CC: " . htmlspecialchars($fila['numero_documento'])."</p></div>";
        }
      }

      if ($imprimir == "Entrega" or $imprimir == "Entregar") {
        $sql1 = "SELECT firma,`nombre`, `numero_documento`,`correo_electronico`, `telefono`,tipo FROM firma_clientes WHERE tipo_firma = 'Entrega' and id_guia='$id_param' order by id desc limit 1";
        $resultado = $DB1->Execute($sql1);
        $fila = mysqli_fetch_assoc($resultado);
        if ($fila) {
          $imagen = $fila['firma'];
          $tipo = $fila['tipo'];
          if ($tipo == 'imagen') {
            $imagen_base64 = ($enviarcorreo!=2 and $enviarcorreo!=3) ? 'tmp_img/imagen_' . $rw[1] . '.png' : 'data:image/png;base64,'. base64_encode($imagen);
          } else {
            $imagen_base64 = $imagen;
          }
          echo "<div class=\"mt-3\"><h6>Firma recibe</h6><div class=\"signature-box\"><img src='$imagen_base64' style='max-height:150px; max-width:100%'></div><p>Quien recibe: ".htmlspecialchars($fila['nombre'])." - CC: " . htmlspecialchars($fila['numero_documento'])."</p></div>";
        }
      }
      ?>

      <!-- Bancos / medios autorizados -->
      <div class="mt-4 text-center">
        <p class="mb-1"><strong>¡PAGUE SOLO POR NUESTROS MEDIOS AUTORIZADOS!</strong></p>
        <img src="img/bancolombia.png" style="height:50px;" alt="banco">
        <img src="img/superTrans.png" style="height:60px; margin-left:20px" alt="superTrans">
      </div>

    </div>
  </div>
</div>

<!-- Controles de zoom -->
<div class="zoom-controls">
  <div class="btn-group-vertical">
    <button class="btn btn-primary" onclick="zoomIn()"><i class="bi bi-zoom-in"></i></button>
    <button class="btn btn-secondary" onclick="zoomOut()"><i class="bi bi-zoom-out"></i></button>
  </div>
</div>

<script>
  var scale = 1;
  function zoomIn(){ scale += 0.1; document.getElementById('imagen').style.transform = 'scale('+scale+')'; }
  function zoomOut(){ if(scale>0.5){ scale -= 0.1; document.getElementById('imagen').style.transform = 'scale('+scale+')'; } }

  // mantener la funcionalidad que descarga la imagen de la remesa
  $(function() {
    var factura = $('#factura').val();
    var idServicio = $('#idServicio').val();
    var tipo = $('#tipo').val();
    factura = factura+'_'+tipo+'.jpg';
    var vis = '<?php echo $vis;?>';

    html2canvas(document.getElementById('imagen')).then(function(canvas) {
      // crear fondo blanco (misma idea que antes)
      var canvasWithWhiteBackground = document.createElement('canvas');
      canvasWithWhiteBackground.width = canvas.width;
      canvasWithWhiteBackground.height = canvas.height;
      var ctx = canvasWithWhiteBackground.getContext('2d');
      ctx.fillStyle = '#fff'; ctx.fillRect(0,0,canvasWithWhiteBackground.width, canvasWithWhiteBackground.height);
      ctx.drawImage(canvas,0,0);
      var dataURL = canvasWithWhiteBackground.toDataURL('image/jpeg');
      if (vis=="adm") {
        // no descargar en modo admin
      } else {
        var link = document.createElement('a');
        link.href = dataURL; link.download = factura; link.click();
      }
    }).catch(function(err){ console.log('html2canvas error', err); });
  });
</script>

<!-- Bootstrap JS (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
