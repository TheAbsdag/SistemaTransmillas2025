<?php
require_once "../config/database.php";

class Dispositivos {
        private $db;

        public function __construct() {
            $this->db = (new Database())->connect();
        }
    public function obtenerDispositivo($idUsuario, $deviceId)
    {
        $sql = "SELECT authorized
                FROM user_devices
                WHERE user_id = ? AND device_id = ? AND active = 1
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is", $idUsuario, $deviceId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function registrarDispositivo($idUsuario, $deviceId, $fingerprint,$platform, $ip, $ua )
    {
        $sql = "INSERT INTO user_devices
                (user_id, device_id, fingerprint, ip_last, user_agent,device_type, authorized, active)
                VALUES (?, ?, ?, ?, ?,?, 0, 1)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "isssss",
            $idUsuario,
            $deviceId,
            $fingerprint,
            $platform,
            $ip,
            $ua
        );
        $stmt->execute();
    }
    public function verificarDispositivoLogin($idUsuario, $deviceId)
    {
        $sql = "SELECT authorized, active
                FROM user_devices
                WHERE user_id = ?
                AND device_id = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is", $idUsuario, $deviceId);
        $stmt->execute();

        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            return 'NO_VINCULADO';
        }

        $row = $res->fetch_assoc();

        if ((int)$row['active'] === 0) {
            return 'BLOQUEADO';
        }

        if ((int)$row['authorized'] === 0) {
            return 'PENDIENTE';
        }

        return 'AUTORIZADO';
    }
}