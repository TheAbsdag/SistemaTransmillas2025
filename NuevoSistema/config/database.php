<?php
class Database {
  public function connect() {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "tu_base_datos";

    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
      die("Error de conexión: " . $conn->connect_error);
    }
    return $conn;
  }
}