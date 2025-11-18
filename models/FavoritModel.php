<?php
// models/FavoritModel.php

class FavoritModel extends Model {

    /**
     * Mengambil semua produk yang difavoritkan oleh satu penyewa.
     * @param int $id_penyewa ID penyewa yang sedang login
     * @return array Daftar produk favorit
     */
    public function getFavoritesByPenyewa($id_penyewa) {
        // Query ini mengambil data barang + vendor, tapi HANYA untuk barang
        // yang ada di tabel favorit milik si penyewa.
        $sql = "SELECT 
                    b.id_barang, 
                    b.nama_barang, 
                    b.harga_sewa, 
                    b.url_foto, 
                    v.alamat_vendor 
                FROM favorit f
                JOIN barang b ON f.id_barang = b.id_barang
                LEFT JOIN vendor v ON b.id_vendor = v.id_vendor
                WHERE f.id_penyewa = ? AND b.status_barang = 'aktif'
                ORDER BY f.tanggal_ditambahkan DESC";
        
        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (getFavoritesByPenyewa): " . $this->dbconn->error);
            return [];
        }
        
        $stmt->bind_param('i', $id_penyewa);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $produk = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $produk;
        }
        return [];
    }

    /**
     * Mengambil HANYA ID barang yang difavoritkan oleh penyewa.
     * Ini digunakan di dashboard untuk menandai barang yang sudah disukai.
     * @param int $id_penyewa
     * @return array [1, 5, 12]
     */
    public function getFavoriteIdsByPenyewa($id_penyewa) {
        $sql = "SELECT id_barang FROM favorit WHERE id_penyewa = ?";
        $stmt = $this->dbconn->prepare($sql);

        if ($stmt === false) {
            error_log("Prepare failed (getFavoriteIdsByPenyewa): " . $this->dbconn->error);
            return [];
        }

        $stmt->bind_param('i', $id_penyewa);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            // Ambil kolom 'id_barang' saja dan jadikan array flat
            $ids = array_column($result->fetch_all(MYSQLI_ASSOC), 'id_barang');
            $stmt->close();
            return $ids;
        }
        return [];
    }

    /**
     * Cek apakah 1 barang sudah difavoritkan oleh 1 penyewa
     * @param int $id_penyewa
     * @param int $id_barang
     * @return bool
     */
    public function isFavorite($id_penyewa, $id_barang) {
        $sql = "SELECT id_favorit FROM favorit WHERE id_penyewa = ? AND id_barang = ? LIMIT 1";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bind_param('ii', $id_penyewa, $id_barang);
        $stmt->execute();
        $result = $stmt->get_result();
        $is_fav = $result->num_rows > 0;
        $stmt->close();
        return $is_fav;
    }

    /**
     * Menambahkan barang ke favorit
     * @param int $id_penyewa
     * @param int $id_barang
     * @return bool
     */
    public function addFavorite($id_penyewa, $id_barang) {
        $sql = "INSERT INTO favorit (id_penyewa, id_barang) VALUES (?, ?)";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bind_param('ii', $id_penyewa, $id_barang);
        return $stmt->execute();
    }

    /**
     * Menghapus barang dari favorit
     * @param int $id_penyewa
     * @param int $id_barang
     * @return bool
     */
    public function removeFavorite($id_penyewa, $id_barang) {
        $sql = "DELETE FROM favorit WHERE id_penyewa = ? AND id_barang = ?";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bind_param('ii', $id_penyewa, $id_barang);
        return $stmt->execute();
    }
}
?>