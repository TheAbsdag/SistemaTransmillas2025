<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php 
require("login_autentica.php"); 
include("layout.php");
if($rcrear==1) { $FB->nuevo("Usuario", $condecion, "configuracion.php?idmen=138"); } 
?>
  <style>
    body, html {
      height: 100%;
      margin: 0;
    }
    iframe {
      width: 100%;
      height: 100vh;
      border: none;
    }
  </style>

<iframe src="/nueva_plataforma/controller/ServiciosAutomaticosController.php" width="100%"
        style="border: none;"></iframe>
<script>
  function ajustarAlturaIframe() {
    const iframe = document.getElementById('iframeUsuarios');
    if (iframe) {
      iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
    }
  }

  // Ajustar altura cuando el iframe cargue
  document.getElementById('iframeUsuarios').addEventListener('load', ajustarAlturaIframe);

  // También puedes hacer un ajuste cada X segundos si el contenido cambia
  setInterval(ajustarAlturaIframe, 1000);
</script>