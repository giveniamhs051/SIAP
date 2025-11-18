<?php
// models/AdminModel.php
class AdminModel extends Model {

    /**
     * Statistik untuk Dashboard Admin
     */
    public function getDashboardStats() {
        $stats = [];
        
        // Hitung total per role
        $roles = ['admin', 'vendor', 'penyewa'];
        foreach ($roles as $role) {
            $table = ($role == 'admin') ? 'admin' : (($role == 'vendor') ? 'vendor' : 'penyewa');
            $sql = "SELECT COUNT(*) as total FROM $table";
            $result = $this->dbconn->query($sql);
            $stats[$role] = $result->fetch_assoc()['total'];
        }

        // Hitung Log Hari Ini (asumsi tabel log_aktivitas ada)
        // Cek dulu apakah tabel ada untuk menghindari error jika user belum buat tabel
        $checkTable = $this->dbconn->query("SHOW TABLES LIKE 'log_aktivitas'");
        if ($checkTable && $checkTable->num_rows > 0) {
            $today = date('Y-m-d');
            $sqlLog = "SELECT COUNT(*) as total FROM log_aktivitas WHERE DATE(timestamp) = '$today'";
            $resultLog = $this->dbconn->query($sqlLog);
            $stats['logs_today'] = $resultLog->fetch_assoc()['total'];
        } else {
            $stats['logs_today'] = 0;
        }

        return $stats;
    }

    /**
     * Mengambil semua pengguna (Search supported)
     */
    public function getAllUsers($keyword = null) {
        $baseSql = "
            SELECT id_admin as user_id, nama_admin as nama, email_admin as email, no_telepon as telepon, 'admin' as role, 'aktif' as status FROM admin
            UNION
            SELECT id_vendor as user_id, nama_vendor as nama, email_vendor as email, no_telepon as telepon, 'vendor' as role, 'aktif' as status FROM vendor
            UNION
            SELECT id_penyewa as user_id, nama_penyewa as nama, email, no_hp as telepon, 'penyewa' as role, 'aktif' as status FROM penyewa
        ";

        if ($keyword) {
            $sql = "SELECT * FROM ($baseSql) AS all_users 
                    WHERE nama LIKE ? OR email LIKE ? 
                    ORDER BY role, nama";
        } else {
            $sql = "SELECT * FROM ($baseSql) AS all_users ORDER BY role, nama";
        }

        $stmt = $this->dbconn->prepare($sql);
        if ($keyword) {
            $param = "%" . $keyword . "%";
            $stmt->bind_param("ss", $param, $param);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $users;
    }

    /**
     * Create User Baru
     */
    public function createUser($data) {
        $role = $data['role'];
        if ($role == 'admin') {
            $sql = "INSERT INTO admin (nama_admin, email_admin, password, no_telepon) VALUES (?, ?, ?, ?)";
        } elseif ($role == 'vendor') {
            $sql = "INSERT INTO vendor (nama_vendor, email_vendor, password, no_telepon) VALUES (?, ?, ?, ?)";
        } else {
            $sql = "INSERT INTO penyewa (nama_penyewa, email, password, no_hp) VALUES (?, ?, ?, ?)";
        }

        $stmt = $this->dbconn->prepare($sql);
        $stmt->bind_param("ssss", $data['nama'], $data['email'], $data['password'], $data['telepon']);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    /**
     * Update User (Edit)
     */
    public function updateUser($data) {
        $role = $data['role'];
        $id = $data['user_id'];
        $passwordQuery = !empty($data['password']) ? ", password = ?" : "";
        
        if ($role == 'admin') {
            $sql = "UPDATE admin SET nama_admin = ?, email_admin = ?, no_telepon = ? $passwordQuery WHERE id_admin = ?";
        } elseif ($role == 'vendor') {
            $sql = "UPDATE vendor SET nama_vendor = ?, email_vendor = ?, no_telepon = ? $passwordQuery WHERE id_vendor = ?";
        } else {
            $sql = "UPDATE penyewa SET nama_penyewa = ?, email = ?, no_hp = ? $passwordQuery WHERE id_penyewa = ?";
        }

        $stmt = $this->dbconn->prepare($sql);
        
        if (!empty($data['password'])) {
            $stmt->bind_param("ssssi", $data['nama'], $data['email'], $data['telepon'], $data['password'], $id);
        } else {
            $stmt->bind_param("sssi", $data['nama'], $data['email'], $data['telepon'], $id);
        }

        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    /**
     * Delete User
     */
    public function deleteUser($id, $role) {
        $table = ($role == 'admin') ? 'admin' : (($role == 'vendor') ? 'vendor' : 'penyewa');
        $idCol = ($role == 'admin') ? 'id_admin' : (($role == 'vendor') ? 'id_vendor' : 'id_penyewa');
        
        $stmt = $this->dbconn->prepare("DELETE FROM $table WHERE $idCol = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // ... (getLogs tetap sama seperti sebelumnya) ...
     public function getLogs($filters = []) {
        // Pastikan tabel log_aktivitas sudah dibuat di database!
        $sql = "SELECT id_log, timestamp, user_nama, user_role, aksi, objek, ip_address 
                FROM log_aktivitas"; 

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
        
        // Filter berdasarkan Tanggal
        if (!empty($filters['tanggal_mulai']) && !empty($filters['tanggal_selesai'])) {
             $whereClauses[] = "DATE(timestamp) BETWEEN ? AND ?";
             $params[] = $filters['tanggal_mulai'];
             $params[] = $filters['tanggal_selesai'];
             $types .= "ss";
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }
        
        $sql .= " ORDER BY timestamp DESC LIMIT 100"; 

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