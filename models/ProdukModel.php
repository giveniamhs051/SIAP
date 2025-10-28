<?php
// models/ProdukModel.php

class ProdukModel extends Model {

    /**
     * Mengambil daftar produk (barang) yang aktif.
     * Bisa ditambahkan logika 'terlaris' nanti (misal berdasarkan jumlah pemesanan).
     * Untuk sekarang, ambil beberapa produk terbaru yang aktif.
     * @param int $limit Jumlah produk yang ingin diambil
     * @return array Daftar produk
     */
    public function getProdukTerbaruAktif($limit = 8) {
        // Mengambil data barang yang statusnya 'aktif'
        // Join dengan vendor untuk mengambil alamat (lokasi) jika perlu
        $sql = "SELECT 
                    b.id_barang, 
                    b.nama_barang, 
                    b.harga_sewa, 
                    b.url_foto, 
                    v.alamat_vendor 
                FROM barang b
                LEFT JOIN vendor v ON b.id_vendor = v.id_vendor 
                WHERE b.status_barang = 'aktif' 
                ORDER BY b.id_barang DESC 
                LIMIT ?";
        
        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (getProdukTerbaruAktif): " . $this->dbconn->error);
            return [];
        }
        
        // Bind parameter limit (integer 'i')
        $stmt->bind_param('i', $limit);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $produk = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $produk;
        } else {
            error_log("Execute failed (getProdukTerbaruAktif): " . $stmt->error);
            $stmt->close();
            return [];
        }
    }

    /**
     * Mengambil daftar produk rekomendasi (placeholder).
     * Untuk sekarang, kita ambil produk secara acak yang aktif.
     * @param int $limit Jumlah produk yang ingin diambil
     * @return array Daftar produk rekomendasi
     */
    public function getRekomendasiProduk($limit = 8) {
         // Mengambil data barang yang statusnya 'aktif' secara acak
         // Join dengan vendor untuk mengambil alamat (lokasi) jika perlu
         $sql = "SELECT 
                    b.id_barang, 
                    b.nama_barang, 
                    b.harga_sewa, 
                    b.url_foto, 
                    v.alamat_vendor 
                FROM barang b
                LEFT JOIN vendor v ON b.id_vendor = v.id_vendor 
                WHERE b.status_barang = 'aktif' 
                ORDER BY RAND() -- Ambil secara acak
                LIMIT ?";

        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (getRekomendasiProduk): " . $this->dbconn->error);
            return [];
        }
        
        $stmt->bind_param('i', $limit);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $produk = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $produk;
        } else {
            error_log("Execute failed (getRekomendasiProduk): " . $stmt->error);
            $stmt->close();
            return [];
        }
    }

    // Bisa ditambahkan method lain seperti getProdukByKategori, searchProduk, dll.
}
?>