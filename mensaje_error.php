<?php

$error_login_ms[0]="No se pudo conectar con la Base de datos";
$error_login_ms[1]="No se pudo realizar consulta a la Base de datos";
$error_login_ms[2]="Contrase&ntilde;a o Nombre de Usuario no valido";
$error_login_ms[3]="Contrase&ntilde;a no valida";
$error_login_ms[4]="Nombre de Usuario no valido";
$error_login_ms[5]="No está autorizado para realizar esta acción o entrar en esta p&aacute;gina";
$error_login_ms[6]="Acceso no autorizado! Registrese";
$error_login_ms[8]="Sesion cerrada";
$error_login_ms[9]="Usuario Agregado, por favor ingrese su nombre de usuario y contrase&ntilde;a";

$error_login_ms['DISPOSITIVO_NO_VINCULADO'] =
  'Este dispositivo no está vinculado a tu cuenta. Solicita autorización.';

$error_login_ms['DISPOSITIVO_PENDIENTE'] =
  'Este dispositivo está pendiente de autorización por el administrador.';

$error_login_ms['DISPOSITIVO_BLOQUEADO'] =
  'Este dispositivo fue bloqueado. Contacta al administrador.';
if(@$_REQUEST['error_login']==10){ $error_login_ms[10]="Su contrase&ntilde;a ha sido enviada a su correo electr&oacute;nico "; }
$error_login_ms[11]="Este correo electr&oacute;nico no existe en nuestra base de datos";
?>