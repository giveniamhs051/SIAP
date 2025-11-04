<?php
// models/OrderModel.php

class OrderModel extends Model {

    /**
     * Membuat record pemesanan baru di database.
     * Status awal di-set ke 'menunggu'.
     *
     * @param array $data Data pesanan
     * @return int|false ID pemesanan baru jika berhasil, false jika gagal.
     */
    public function createPemesanan($data) {
        
        // TODO: Nanti tambahkan logika cek stok barang sebelum insert
        
        // Status awal 'menunggu' (sesuai PesananModel.php)
        $sql = "INSERT INTO pemesanan 
                (id_penyewa, id_barang, tanggal_mulai, tanggal_selesai, total_harga, status_pemesanan, jumlah_barang) 
                VALUES (?, ?, ?, ?, ?, 'menunggu', ?)";
        
        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (createPemesanan): " . $this->dbconn->error);
            return false;
        }

        $stmt->bind_param('iissdi',
            $data['id_penyewa'],
            $data['id_barang'],
            $data['tgl_mulai'],
            $data['tgl_selesai'],
            $data['total_harga'],
            $data['qty']
        );

        if ($stmt->execute()) {
            $new_id = $this->dbconn->insert_id;
            // TODO: Kurangi stok barang di tabel 'barang'
            $stmt->close();
            return $new_id;
        } else {
            error_log("Execute failed (createPemesanan): " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    /**
     * Mengambil detail pesanan berdasarkan ID, khusus untuk penyewa yang login.
     *
     * @param int $id_pemesanan ID Pesanan
     * @param int $id_penyewa ID Penyewa (dari session)
     * @return array|false Data pesanan
     */
    public function getOrderById($id_pemesanan, $id_penyewa) {
        // Join dengan barang dan vendor untuk info lengkap
        $sql = "SELECT 
                    p.*, 
                    b.nama_barang, 
                    b.url_foto,
                    v.nama_vendor,
                    v.alamat_vendor
                FROM pemesanan p
                JOIN barang b ON p.id_barang = b.id_barang
                JOIN vendor v ON b.id_vendor = v.id_vendor
                WHERE p.id_pemesanan = ? AND p.id_penyewa = ?
                LIMIT 1";

        $stmt = $this->dbconn->prepare($sql);
         if ($stmt === false) {
            error_log("Prepare failed (getOrderById - Penyewa): " . $this->dbconn->error);
            return false;
        }
        
        $stmt->bind_param('ii', $id_pemesanan, $id_penyewa);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $pesanan = $result->fetch_assoc();
            $stmt->close();
            return $pesanan;
        } else {
             error_log("Execute failed (getOrderById - Penyewa): " . $stmt->error);
             $stmt->close();
            return false;
        }
    }
}
?>
