<?php
// models/PesananModel.php

class PesananModel extends Model {

    /**
     * Mengambil daftar pesanan untuk vendor tertentu dengan status UI tertentu.
     * @param int $vendorId ID Vendor
     * @param string $status_ui Status dari UI (e.g., 'Menunggu Pembayaran')
     * @return array Daftar pesanan
     */
    public function getOrdersByVendorAndStatus($vendorId, $status_ui) {
        
        // --- Mapping Status UI ke Status DB ---
        $status_db = $status_ui; // Default jika sama
        if ($status_ui === 'Menunggu Pembayaran') {
            $status_db = 'menunggu';
        } elseif ($status_ui === 'Dikonfirmasi') {
            $status_db = 'dibayar';
        } elseif ($status_ui === 'Disewa') {
            $status_db = 'disewa';
        } elseif ($status_ui === 'Selesai') {
            $status_db = 'selesai';
        } elseif ($status_ui === 'Dikembalikan') {
            // PENTING: Jika 'Dikembalikan' belum ada di ENUM DB, query ini tidak akan return apa-apa
            // Jika sudah ada, uncomment baris di bawah dan comment baris return []
            // $status_db = 'Dikembalikan'; 
             error_log("Warning: Status 'Dikembalikan' requested but might not exist in pemesanan.status_pemesanan ENUM.");
             return []; // Kembalikan array kosong jika status DB tidak valid/tersedia
        } else {
             error_log("Error: Status UI tidak dikenal - " . $status_ui);
             return []; // Status UI tidak dikenal
        }
        // --- End Mapping ---

        // Menggunakan kolom dari skema DB Anda
        $sql = "SELECT 
                    p.id_pemesanan, 
                    p.id_pemesanan as kode_pemesanan, -- Menggunakan ID sebagai kode sementara
                    py.nama_penyewa, 
                    p.tanggal_mulai as tanggal_sewa_mulai, -- Alias agar cocok dgn view lama
                    p.tanggal_selesai as tanggal_sewa_selesai, -- Alias
                    p.total_harga, 
                    p.status_pemesanan as status -- Alias
                FROM pemesanan p
                JOIN barang b ON p.id_barang = b.id_barang
                JOIN penyewa py ON p.id_penyewa = py.id_penyewa 
                WHERE b.id_vendor = ? AND p.status_pemesanan = ?
                ORDER BY p.id_pemesanan DESC"; // Urutkan berdasarkan ID terbaru

        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (getOrdersByVendorAndStatus): " . $this->dbconn->error);
            return [];
        }

        $stmt->bind_param('is', $vendorId, $status_db); // Bind dengan status DB hasil mapping

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $orders = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $orders;
        } else {
            error_log("Execute failed (getOrdersByVendorAndStatus): " . $stmt->error);
            $stmt->close();
            return [];
        }
    }

    /**
     * Mengupdate status pesanan di tabel pemesanan.
     * @param int $idPemesanan ID Pesanan yang akan diupdate
     * @param string $newStatusDb Status baru (nilai ENUM di DB: 'menunggu', 'dibayar', 'disewa', 'selesai', 'Dikembalikan')
     * @param int $vendorId ID Vendor pemilik barang pesanan ini (untuk keamanan)
     * @return bool True jika berhasil, false jika gagal
     */
    public function updateOrderStatus($idPemesanan, $newStatusDb, $vendorId) {
        // Validasi status baru ada di ENUM (jika 'Dikembalikan' belum ditambahkan, ini akan mencegah error)
        $allowedDbStatuses = ['menunggu', 'dibayar', 'disewa', 'selesai', 'Dikembalikan']; // Tambahkan 'Dikembalikan' jika sudah di DB
        if (!in_array($newStatusDb, $allowedDbStatuses)) {
             error_log("Error: Attempted to update status to an invalid value: " . $newStatusDb);
             return false;
        }

        // Query ini memastikan hanya vendor pemilik barang yang bisa update status
         $sql = "UPDATE pemesanan p
                JOIN barang b ON p.id_barang = b.id_barang
                SET p.status_pemesanan = ? 
                WHERE p.id_pemesanan = ? AND b.id_vendor = ?";

        $stmt = $this->dbconn->prepare($sql);
         if ($stmt === false) {
            error_log("Prepare failed (updateOrderStatus): " . $this->dbconn->error);
            return false;
        }

        $stmt->bind_param('sii', $newStatusDb, $idPemesanan, $vendorId);

        if ($stmt->execute()) {
            $affected_rows = $stmt->affected_rows;
            $stmt->close();
            // Berhasil jika query jalan DAN ada baris yang terupdate (atau memang statusnya sudah sama)
            // Cukup return true jika execute berhasil, karena bisa jadi status sudah sama sebelumnya.
            return true; 
        } else {
            error_log("Execute failed (updateOrderStatus): (" . $stmt->errno . ") " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

}
?>