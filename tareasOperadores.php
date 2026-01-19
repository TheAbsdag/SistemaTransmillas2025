<?php
require("login_autentica.php"); 
// include("layout.php");
$id_sedes= $_SESSION['usu_idsede'];
$id_usuario= $_SESSION['usuario_id'];
$id_nombre=$_SESSION['usuario_nombre'];
$nivel_acceso=$_SESSION['usuario_rol'];
$precioinicialkilos=$_SESSION['precioinicial'];
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php 

?>
<style>
  html, body {
    margin: 0;
    height: 100%;
    overflow: hidden; /* Evita scroll doble */
  }

  .iframe-container {
    width: 100%;
    height: 100vh; /* Ocupa toda la pantalla */
    display: flex;
  }

  .iframe-container iframe {
    flex: 1;
    border: none;
  }
</style>

<form id="redirectForm" action="https://sistema.transmillas.com/nueva_plataforma/controller/TareasController.php" method="post">
  <input type="hidden" name="acceso" value="<?= $nivel_acceso ?>">
  <input type="hidden" name="sede" value="<?= $id_sedes ?>">
  <input type="hidden" name="usuario" value="<?= $id_nombre ?>">
  <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
</form>

<script>
  // Enviar automáticamente el formulario al cargar la página
  document.getElementById("redirectForm").submit();
</script>