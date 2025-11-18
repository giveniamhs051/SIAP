<?php
// models/AdminModel.php
class AdminModel extends Model {

    /**
     * Mengambil semua pengguna dari tabel admin, vendor, dan penyewa.
     * Menggunakan UNION untuk menggabungkan data.
     * @return array Daftar semua pengguna.
     */
    public function getAllUsers() {
        // Query ini menggabungkan 3 tabel pengguna
        // (Mirip dengan AuthModel, tapi mengambil semua data)
        $sql = "(SELECT 
                    id_admin as user_id, 
                    nama_admin as nama, 
                    email_admin as email, 
                    no_telepon as telepon, 
                    'admin' as role, 
                    'aktif' as status 
                FROM admin)
                UNION
                (SELECT 
                    id_vendor as user_id, 
                    nama_vendor as nama, 
                    email_vendor as email, 
                    no_telepon as telepon, 
                    'vendor' as role, 
                    'aktif' as status -- (Asumsi status, bisa diubah jika ada kolom status di DB)
                FROM vendor)
                UNION
                (SELECT 
                    id_penyewa as user_id, 
                    nama_penyewa as nama, 
                    email, 
                    no_hp as telepon, 
                    'penyewa' as role, 
                    'aktif' as status -- (Asumsi status)
                FROM penyewa)
                ORDER BY role, nama";

        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (getAllUsers): " . $this->dbconn->error);
            return [];
        }

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $users = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $users;
        } else {
            error_log("Execute failed (getAllUsers): " . $stmt->error);
            $stmt->close();
            return [];
        }
    }
    
    /**
     * --- METHOD BARU: Mengambil log aktivitas dengan filter ---
     * * @param array $filters Filter dari controller (e.g., ['tanggal_mulai' => ..., 'role' => ..., 'aksi' => ...])
     * @return array Daftar log aktivitas
     */
    public function getLogs($filters = []) {
        $sql = "SELECT id_log, timestamp, user_nama, user_role, aksi, objek, ip_address 
                FROM log_aktivitas"; // <-- Asumsi nama tabel log Anda

        $params = [];
        $types = "";
        $whereClauses = [];

        // Filter berdasarkan Role
        if (!empty($filters['role'])) {
            $whereClauses[] = "user_role = ?";
            $params[] = $filters['role'];
            $types .= "s";
        }

        // Filter berdasarkan Aksi
        if (!empty($filters['aksi'])) {
            $whereClauses[] = "aksi = ?";
            $params[] = $filters['aksi'];
            $types .= "s";
        }
        
        // Filter berdasarkan Tanggal (Range)
        if (!empty($filters['tanggal_mulai']) && !empty($filters['tanggal_selesai'])) {
             $whereClauses[] = "DATE(timestamp) BETWEEN ? AND ?";
             $params[] = $filters['tanggal_mulai'];
             $params[] = $filters['tanggal_selesai'];
             $types .= "ss";
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }
        
        $sql .= " ORDER BY timestamp DESC LIMIT 100"; // Ambil 100 log terbaru

        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (getLogs): " . $this->dbconn->error);
            return [];
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $logs = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $logs;
        } else {
            error_log("Execute failed (getLogs): " . $stmt->error);
            $stmt->close();
            return [];
        }
    }
}
?>

    // Nanti Anda bisa tambahkan fungsi lain di sini:
    // public function deleteUserById($id, $role) { ... }
    // public function updateUserStatus($id, $role, $status) { ... }
}
?>
