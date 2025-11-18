<?php
// models/LogModel.php

class LogModel extends Model {

    /**
     * Mencatat aktivitas ke database
     * @param string $aksi Jenis aksi (LOGIN, CREATE, UPDATE, DELETE)
     * @param string $objek Keterangan objek yang dimanipulasi
     */
    public function catat($aksi, $objek) {
        // Ambil data user dari session (jika ada)
        $user_nama = $_SESSION['user_nama'] ?? 'System/Guest';
        $user_role = $_SESSION['user_role'] ?? 'unknown';
        
        // Ambil IP Address user
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $sql = "INSERT INTO log_aktivitas (user_nama, user_role, aksi, objek, ip_address) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->dbconn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssss", $user_nama, $user_role, $aksi, $objek, $ip_address);
            $stmt->execute();
            $stmt->close();
            return true;
        }
        return false;
    }
}
?>