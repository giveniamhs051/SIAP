<?php
// models/BarangModel.php

class BarangModel extends Model {

    /**
     * Mengambil semua barang milik satu vendor
     * @param int $vendorId ID vendor
     * @return array Daftar barang
     */
    public function getItemsByVendor($vendorId) {
        // PERBAIKAN: Mengurutkan berdasarkan id_barang (karena 'dibuat_pada' tidak ada)
        $sql = "SELECT * FROM barang WHERE id_vendor = ? ORDER BY id_barang DESC";
        
        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (getItemsByVendor): " . $this->dbconn->error);
            return [];
        }
        
        $stmt->bind_param('i', $vendorId);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $items = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $items;
        } else {
            error_log("Execute failed (getItemsByVendor): " . $stmt->error);
            $stmt->close();
            return [];
        }
    }

    /**
     * Menyimpan barang baru ke database
     * @param array $data Data barang dari controller
     * @return bool True jika sukses, false jika gagal
     */
    public function createItem($data) {
        // PERBAIKAN: Menyesuaikan nama kolom dengan skema DB Anda
        $sql = "INSERT INTO barang 
                (id_vendor, nama_barang, harga_sewa, stok_barang, deskripsi_barang, url_foto, tanggal_tersedia) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (createItem): " . $this->dbconn->error);
            return false;
        }

        // PERBAIKAN: Menyesuaikan tipe bind_param (integer, string, double, integer, string, string, string)
        $stmt->bind_param('isdisss',
            $data['id_vendor'],
            $data['nama_barang'],
            $data['harga_sewa'],
            $data['stok'], // data 'stok' dari controller akan dimasukkan ke 'stok_barang'
            $data['deskripsi'], // data 'deskripsi' dari controller
            $data['url_foto'], // data 'url_foto' dari controller
            $data['tgl_tersedia'] // data 'tgl_tersedia' dari controller
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            error_log("Execute failed (createItem): (" . $stmt->errno . ") " . $stmt->error);
            $stmt->close();
            return false;
        }
    }
}
?>