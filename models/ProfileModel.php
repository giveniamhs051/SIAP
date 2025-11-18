<?php
// models/ProfileModel.php
class ProfileModel extends Model {

    /**
     * Mengambil data vendor... (method ini sudah ada)
     */
    public function getVendorById($vendor_id) {
        // ... (kode tetap sama)
        $stmt = $this->dbconn->prepare("SELECT id_vendor, nama_vendor, email_vendor, no_telepon, alamat_vendor, deskripsi_vendor FROM vendor WHERE id_vendor = ?");
        // ... (kode tetap sama)
        $result = $stmt->get_result();
        $vendor = $result->fetch_object();
        $stmt->close();
        return $vendor ? $vendor : false;
    }

    /**
     * Memperbarui data profil vendor. (method ini sudah ada)
     */
    public function updateVendorProfile($vendor_id, $data) {
        // ... (kode tetap sama)
        $stmt = $this->dbconn->prepare("UPDATE vendor SET nama_vendor = ?, no_telepon = ?, alamat_vendor = ?, deskripsi_vendor = ? WHERE id_vendor = ?");
        // ... (kode tetap sama)
        return true;
    }

    /**
     * --- METHOD BARU: Mengambil data penyewa berdasarkan ID pengguna ---
     * @param int $penyewa_id ID penyewa yang sedang login.
     * @return object|false Objek data penyewa jika ditemukan, false jika tidak.
     */
    public function getPenyewaById($penyewa_id) {
        // Kolom di tabel 'penyewa': id_penyewa, nama_penyewa, email, no_hp
        $stmt = $this->dbconn->prepare("SELECT id_penyewa, nama_penyewa, email, no_hp FROM penyewa WHERE id_penyewa = ?");
        if (!$stmt) {
            error_log("Prepare failed (getPenyewaById): " . $this->dbconn->error);
            return false;
        }
        $stmt->bind_param("i", $penyewa_id);
        if (!$stmt->execute()) {
            error_log("Execute failed (getPenyewaById): " . $stmt->error);
            $stmt->close();
            return false;
        }
        $result = $stmt->get_result();
        $penyewa = $result->fetch_object();
        $stmt->close();

        return $penyewa ? $penyewa : false;
    }

    /**
     * --- METHOD BARU: Memperbarui data profil penyewa ---
     * @param int $penyewa_id ID penyewa yang akan diupdate.
     * @param array $data Data baru (nama_penyewa, no_hp). Email tidak bisa diubah.
     * @return bool True jika berhasil update, false jika gagal.
     */
    public function updatePenyewaProfile($penyewa_id, $data) {
        if (empty($data['nama_penyewa']) || empty($data['no_hp'])) {
             error_log("Update penyewa profile error: Nama atau telepon kosong.");
             return false;
        }

        // Kolom DB: nama_penyewa, no_hp
        $stmt = $this->dbconn->prepare("UPDATE penyewa SET nama_penyewa = ?, no_hp = ? WHERE id_penyewa = ?");
        if (!$stmt) {
             error_log("Prepare failed (updatePenyewaProfile): " . $this->dbconn->error);
             return false;
        }

        // Bind parameter (2 string, 1 integer)
        $stmt->bind_param("ssi",
            $data['nama_penyewa'],
            $data['no_hp'],
            $penyewa_id
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
             error_log("Execute failed (updatePenyewaProfile): " . $stmt->error);
             $stmt->close();
             return false;
        }
    }

    
}
?>