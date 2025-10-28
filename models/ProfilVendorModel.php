<?php
// models/ProfilVendorModel.php

class ProfilVendorModel extends Model {

    /**
     * Mengambil data vendor berdasarkan ID.
     * @param int $vendorId ID vendor
     * @return array|false Data vendor atau false jika tidak ditemukan
     */
    public function getVendorById($vendorId) {
        // Ambil SEMUA kolom yang relevan dari tabel vendor
        // Termasuk kolom 'foto_profil_url' yang baru ditambahkan
        $sql = "SELECT 
                    id_vendor, 
                    nama_vendor, 
                    email_vendor, 
                    no_telepon, 
                    alamat_vendor, 
                    deskripsi_vendor,
                    foto_profil_url 
                FROM vendor 
                WHERE id_vendor = ?";
        
        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (getVendorById): " . $this->dbconn->error);
            return false;
        }
        
        $stmt->bind_param('i', $vendorId);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $vendor = $result->fetch_assoc();
            $stmt->close();
            return $vendor ?: false; // Kembalikan data atau false jika tidak ada hasil
        } else {
            error_log("Execute failed (getVendorById): " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    /**
     * Memperbarui data profil vendor.
     * @param int $vendorId ID vendor yang akan diupdate
     * @param array $data Data baru (nama_vendor, email_vendor, no_telepon, alamat_vendor, deskripsi_vendor, foto_profil_url)
     * @return bool True jika sukses, false jika gagal
     */
    public function updateVendorProfile($vendorId, $data) {
        $sql = "UPDATE vendor 
                SET 
                    nama_vendor = ?, 
                    email_vendor = ?, 
                    no_telepon = ?, 
                    alamat_vendor = ?, 
                    deskripsi_vendor = ?,
                    foto_profil_url = ? 
                WHERE id_vendor = ?";
        
        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (updateVendorProfile): " . $this->dbconn->error);
            return false;
        }

        // Bind parameter: ssssssi (6 string, 1 integer)
        $stmt->bind_param('ssssssi',
            $data['nama_vendor'],
            $data['email_vendor'],
            $data['no_telepon'],
            $data['alamat_vendor'],
            $data['deskripsi_vendor'],
            $data['foto_profil_url'], // Bisa jadi null jika foto dihapus
            $vendorId
        );

        if ($stmt->execute()) {
            // Cek apakah ada baris yang benar-benar terupdate
            $affected_rows = $stmt->affected_rows;
            $stmt->close();
            // Dianggap sukses jika query berhasil jalan, 
            // meskipun tidak ada perubahan data (affected_rows = 0)
            return true; 
        } else {
            error_log("Execute failed (updateVendorProfile): (" . $stmt->errno . ") " . $stmt->error);
            $stmt->close();
            return false;
        }
    }
}
?>