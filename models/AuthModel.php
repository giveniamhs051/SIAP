<?php
// models/AuthModel.php
// (Model.php sudah di-include oleh index.php, jadi tidak perlu require_once di sini)

class AuthModel extends Model {

    /**
     * Mencari pengguna berdasarkan email di tiga tabel: penyewa, vendor, admin.
     * Menggunakan UNION untuk menggabungkan hasil.
     *
     * @param string $email Email yang dicari.
     * @return array|false Data pengguna jika ditemukan (sebagai array asosiatif), atau false jika tidak ditemukan.
     */
    public function findUserByEmail($email) {
        $sql = "(SELECT id_penyewa as user_id, nama_penyewa as nama, email, password, no_hp as telepon, 'penyewa' as role FROM penyewa WHERE email = ?)
                UNION
                (SELECT id_vendor as user_id, nama_vendor as nama, email_vendor as email, password, no_telepon as telepon, 'vendor' as role FROM vendor WHERE email_vendor = ?)
                UNION
                (SELECT id_admin as user_id, nama_admin as nama, email_admin as email, password, no_telepon as telepon, 'admin' as role FROM admin WHERE email_admin = ?)";

        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
             error_log("Prepare failed (findUserByEmail): " . $this->dbconn->error); // Log error
             return false;
        }

        // Bind parameter 's' (string) untuk setiap tanda tanya (?)
        $stmt->bind_param('sss', $email, $email, $email);

        if (!$stmt->execute()) {
            error_log("Execute failed (findUserByEmail): " . $stmt->error); // Log error
            $stmt->close();
            return false;
        }

        $result = $stmt->get_result();
        $user = $result->fetch_assoc(); // Ambil satu baris hasil

        $stmt->close(); // Selalu tutup statement

        return $user ? $user : false; // Kembalikan data user atau false
    }

    /**
     * Membuat pengguna baru (Penyewa atau Vendor).
     *
     * @param array $data Data pengguna dari form registrasi, HARUS berisi keys: 'nama', 'email', 'password' (sudah di-hash), 'nomor_telepon', 'role' ('penyewa'/'vendor').
     * @return bool True jika berhasil membuat user, false jika gagal.
     */
    public function createUser($data) {
        $sql = '';
        $bind_types = 'ssss'; // 4 string: nama, email, password, telepon
        $bind_params = [];

        // Validasi $data dasar
        if (!isset($data['nama'], $data['email'], $data['password'], $data['nomor_telepon'], $data['role'])) {
             error_log("CreateUser error: Missing required data fields.");
             return false;
        }


        if ($data['role'] == 'penyewa') {
            // Kolom di DB: nama_penyewa, email, password, no_hp
            $sql = "INSERT INTO penyewa (nama_penyewa, email, password, no_hp) VALUES (?, ?, ?, ?)";
            $bind_params = [
                $data['nama'],
                $data['email'],
                $data['password'], // Password HARUS sudah di-hash dari controller
                $data['nomor_telepon']
            ];
        } elseif ($data['role'] == 'vendor') {
            // Kolom di DB: nama_vendor, email_vendor, password, no_telepon
            $sql = "INSERT INTO vendor (nama_vendor, email_vendor, password, no_telepon) VALUES (?, ?, ?, ?)";
            $bind_params = [
                $data['nama'],
                $data['email'],
                $data['password'], // Password HARUS sudah di-hash dari controller
                $data['nomor_telepon']
            ];
        } else {
            error_log("CreateUser error: Invalid role provided - " . $data['role']);
            return false; // Role tidak valid
        }

        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
             error_log("Prepare failed (createUser): " . $this->dbconn->error);
             return false;
        }

        // Bind parameter menggunakan splat operator (...)
        $stmt->bind_param($bind_types, ...$bind_params);

        if ($stmt->execute()) {
            $stmt->close();
            return true; // Berhasil
        } else {
             error_log("Execute failed (createUser): (" . $stmt->errno . ") " . $stmt->error); // Log error spesifik
             $stmt->close();
             return false; // Gagal
        }
    }
}
?>