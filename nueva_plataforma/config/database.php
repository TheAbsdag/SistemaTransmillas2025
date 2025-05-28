<?php
class Database {
  public function connect() {
    $host = "localhost";
    $user = "u713516042_jose2";
    $pass = "Dobarli23@transmillas";
    $dbname = "u713516042_transmillas2";

    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
      die("Error de conexión: " . $conn->connect_error);
    }
    return $conn;
  }
}
