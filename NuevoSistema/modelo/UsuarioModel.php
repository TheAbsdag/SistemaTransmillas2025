<?php
require_once "../config/database.php";

class UsuarioModel {
  private $db;

  public function __construct() {
    $this->db = (new Database())->connect();
  }

  public function obtenerUsuarios($filtroRol = '', $filtroEstado = '') {
    $sql = "SELECT u.*, r.rol_nombre FROM usuarios u
            INNER JOIN roles r ON r.idroles = u.roles_idroles
            WHERE idusuarios != 1";

    if ($filtroRol !== '') {
      $sql .= " AND idroles = '$filtroRol'";
    }

    if ($filtroEstado !== '') {
      $sql .= " AND usu_estado = '$filtroEstado'";
    }

    $sql .= " ORDER BY usu_nombre ASC";

    return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
  }

  public function obtenerRoles() {
    $sql = "SELECT idroles, rol_nombre FROM roles ORDER BY rol_nombre";
    return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
  }
}