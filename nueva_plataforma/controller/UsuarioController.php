<?php
require_once "../model/UsuarioModel.php";

$modelo = new UsuarioModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $rol = $_POST['rol'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $usuarios = $modelo->obtenerUsuarios($rol, $estado);
    echo json_encode($usuarios);
    exit;
}

$roles = $modelo->obtenerRoles();
include "../view/usuarios/index.php";
