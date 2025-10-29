<?php
// models/ProfileModel.php
class ProfileModel extends Model {

    /**
     * Mengambil data vendor berdasarkan ID pengguna vendor dari session.
     * @param int $vendor_id ID vendor yang sedang login.
     * @return object|false Objek data vendor jika ditemukan, false jika tidak.
     */
    public function getVendorById($vendor_id) {
        // Ambil data dari tabel 'vendor'
        // Kolom: id_vendor, nama_vendor, email_vendor, no_telepon, alamat_vendor, deskripsi_vendor
        $stmt = $this->dbconn->prepare("SELECT id_vendor, nama_vendor, email_vendor, no_telepon, alamat_vendor, deskripsi_vendor FROM vendor WHERE id_vendor = ?");
        if (!$stmt) {
            error_log("Prepare failed (getVendorById): " . $this->dbconn->error);
            return false;
        }
        $stmt->bind_param("i", $vendor_id);
        if (!$stmt->execute()) {
            error_log("Execute failed (getVendorById): " . $stmt->error);
            $stmt->close();
            return false;
        }
        $result = $stmt->get_result();
        $vendor = $result->fetch_object(); // Menggunakan fetch_object agar mudah diakses di view
        $stmt->close();

        return $vendor ? $vendor : false;
    }

    /**
     * Memperbarui data profil vendor.
     * @param int $vendor_id ID vendor yang akan diupdate.
     * @param array $data Data baru vendor (nama_vendor, no_telepon, alamat_vendor, deskripsi_vendor). Email tidak bisa diubah.
     * @return bool True jika berhasil update, false jika gagal.
     */
    public function updateVendorProfile($vendor_id, $data) {
        // Validasi dasar data
        if (empty($data['nama_vendor']) || empty($data['no_telepon'])) {
             error_log("Update vendor profile error: Nama atau telepon kosong.");
             return false;
        }

        // Siapkan query UPDATE (Email tidak diupdate)
        // Kolom DB: nama_vendor, no_telepon, alamat_vendor, deskripsi_vendor
        $stmt = $this->dbconn->prepare("UPDATE vendor SET nama_vendor = ?, no_telepon = ?, alamat_vendor = ?, deskripsi_vendor = ? WHERE id_vendor = ?");
        if (!$stmt) {
             error_log("Prepare failed (updateVendorProfile): " . $this->dbconn->error);
             return false;
        }

        // Bind parameter (4 string, 1 integer)
        $stmt->bind_param("ssssi",
            $data['nama_vendor'],
            $data['no_telepon'],
            $data['alamat_vendor'], // Bisa null atau string kosong
            $data['deskripsi_vendor'], // Bisa null atau string kosong
            $vendor_id
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
             error_log("Execute failed (updateVendorProfile): " . $stmt->error);
             $stmt->close();
             return false;
        }
    }

    // Nanti bisa ditambahkan fungsi untuk update password atau foto profil
}
?>