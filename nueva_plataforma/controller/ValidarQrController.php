<?php

require_once "../model/ValidarQrModel.php";

$modelo = new ValidarQrModel();

$numeroGuia=$_GET['guia'];
$piezas=$_GET['pieza'];



$guia = $modelo->buscarServicioPorGuia($numeroGuia);
include "../view/validarQr/index.php";
