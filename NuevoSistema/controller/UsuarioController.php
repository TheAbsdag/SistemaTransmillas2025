<?php
require_once "../model/UsuarioModel.php";

$modelo = new UsuarioModel();
$usuarios = $modelo->obtenerUsuarios();
$roles = $modelo->obtenerRoles();

include "../view/usuarios/index.php";