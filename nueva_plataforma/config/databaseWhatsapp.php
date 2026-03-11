<?php
// class Database {
//   public function connect() {
//     $host = "localhost";
//     $user = "u713516042_jose2";
//     $pass = "Dobarli23@transmillas";
//     $dbname = "u713516042_transmillas2";

//     $conn = new mysqli($host, $user, $pass, $dbname);
//     if ($conn->connect_error) {
//       die("Error de conexión: " . $conn->connect_error);
//     }
//     return $conn;
//   }
// }


class DatabaseWhatsapp {

    private static $conn = null;

    public function connect(): mysqli {

        if (self::$conn === null) {


                $host = "localhost";
                $user = "u713516042_whatsapTransm";
                $pass = "Transmillas2026@";
                $dbname = "u713516042_whatsapp";

            // $host   = "localhost";
            // $user   = "u713516042_jose2";
            // $pass   = "Dobarli23@transmillas";
            // $dbname = "u713516042_transmillas2";

            // 👇 Sin named arguments
            self::$conn = new mysqli($host, $user, $pass, $dbname);

            if (self::$conn->connect_error) {
                die("Error de conexión: " . self::$conn->connect_error);
            }

            self::$conn->set_charset("utf8mb4");

            // 🔥 Se registra solo una vez
            register_shutdown_function(function () {
                if (DatabaseWhatsapp::$conn !== null) {
                    DatabaseWhatsapp::$conn->close();
                    DatabaseWhatsapp::$conn = null;
                }
            });
        }

        return self::$conn;
    }
}
