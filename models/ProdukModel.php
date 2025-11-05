<?php
// models/ProdukModel.php

class ProdukModel extends Model {

    /**
     * Helper private function untuk membangun klausa WHERE dan bind-params
     */
    private function buildFilterClauses($query, $lokasi) {
        $sqlWhere = " WHERE b.status_barang = 'aktif' ";
        $params = [];
        $types = "";

        if ($query) {
            $sqlWhere .= " AND b.nama_barang LIKE ? ";
            $params[] = "%" . $query . "%";
            $types .= "s";
        }
        if ($lokasi) {
            $sqlWhere .= " AND v.alamat_vendor LIKE ? ";
            $params[] = "%" . $lokasi . "%";
            $types .= "s";
        }
        
        return ['sqlWhere' => $sqlWhere, 'params' => $params, 'types' => $types];
    }

    /**
     * Mengambil daftar produk (barang) yang aktif, dengan filter.
     * @param int $limit Jumlah produk yang ingin diambil
     * @param string|null $query Kata kunci pencarian
     * @param string|null $lokasi Filter lokasi
     * @return array Daftar produk
     */
    public function getProdukTerbaruAktif($limit = 8, $query = null, $lokasi = null) {
        
        $filter = $this->buildFilterClauses($query, $lokasi);
        
        $sql = "SELECT 
                    b.id_barang, 
                    b.nama_barang, 
                    b.harga_sewa, 
                    b.url_foto, 
                    v.alamat_vendor 
                FROM barang b
                LEFT JOIN vendor v ON b.id_vendor = v.id_vendor "
                . $filter['sqlWhere'] . // Tambahkan klausa WHERE
                " ORDER BY b.id_barang DESC 
                  LIMIT ?";
        
        // Tambahkan limit ke params
        $params = $filter['params'];
        $types = $filter['types'];
        
        $params[] = $limit;
        $types .= "i";
        
        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (getProdukTerbaruAktif): " . $this->dbconn->error);
            return [];
        }
        
        // Bind parameter
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
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
     * Mengambil daftar produk rekomendasi (acak), dengan filter.
     * @param int $limit Jumlah produk yang ingin diambil
     * @param string|null $query Kata kunci pencarian
     * @param string|null $lokasi Filter lokasi
     * @return array Daftar produk rekomendasi
     */
    public function getRekomendasiProduk($limit = 8, $query = null, $lokasi = null) {
        
        $filter = $this->buildFilterClauses($query, $lokasi);
        
        $sql = "SELECT 
                    b.id_barang, 
                    b.nama_barang, 
                    b.harga_sewa, 
                    b.url_foto, 
                    v.alamat_vendor 
                FROM barang b
                LEFT JOIN vendor v ON b.id_vendor = v.id_vendor "
                . $filter['sqlWhere'] . // Tambahkan klausa WHERE
                " ORDER BY RAND() -- Ambil secara acak
                  LIMIT ?";

        // Tambahkan limit ke params
        $params = $filter['params'];
        $types = $filter['types'];
        
        $params[] = $limit;
        $types .= "i";

        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (getRekomendasiProduk): " . $this->dbconn->error);
            return [];
        }
        
        // Bind parameter
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
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

    /**
     * Mengambil SEMUA produk untuk halaman 'Produk' atau hasil pencarian.
     * @param string|null $query Kata kunci pencarian
     * @param string|null $lokasi Filter lokasi
     * @return array Daftar semua produk
     */
    public function getSemuaProduk($query = null, $lokasi = null) {
        
        $filter = $this->buildFilterClauses($query, $lokasi);
        
        $sql = "SELECT 
                    b.id_barang, 
                    b.nama_barang, 
                    b.harga_sewa, 
                    b.url_foto, 
                    v.alamat_vendor 
                FROM barang b
                LEFT JOIN vendor v ON b.id_vendor = v.id_vendor "
                . $filter['sqlWhere'] . // Tambahkan klausa WHERE
                " ORDER BY b.id_barang DESC"; // Tanpa LIMIT

        $params = $filter['params'];
        $types = $filter['types'];

        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (getSemuaProduk): " . $this->dbconn->error);
            return [];
        }
        
        // Bind parameter
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $produk = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $produk;
        } else {
            error_log("Execute failed (getSemuaProduk): " . $stmt->error);
            $stmt->close();
            return [];
        }
    }


    /**
     * Mengambil detail satu produk (barang) berdasarkan ID.
     * Di-join dengan vendor untuk info alamat & nama vendor.
     * @param int $id_barang ID Barang
     * @return array|false Data produk jika ditemukan
     */
    public function getProdukById($id_barang) {
        $sql = "SELECT 
                    b.*,  -- Ambil semua kolom dari tabel barang
                    v.nama_vendor,
                    v.alamat_vendor 
                FROM barang b
                LEFT JOIN vendor v ON b.id_vendor = v.id_vendor 
                WHERE b.id_barang = ? AND b.status_barang = 'aktif'
                LIMIT 1"; // Pastikan only 1 hasil

        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (getProdukById): " . $this->dbconn->error);
            return false;
        }
        
        $stmt->bind_param('i', $id_barang);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $produk = $result->fetch_assoc(); // Ambil sebagai array asosiatif
            $stmt->close();
            return $produk ? $produk : false; // Kembalikan data produk or false
        } else {
            error_log("Execute failed (getProdukById): " . $stmt->error);
            $stmt->close();
            return false;
        }
    }
    
    /**
     * Mengambil semua rentang tanggal yang sudah dibooking untuk 1 barang.
     * Ini digunakan untuk menonaktifkan tanggal di kalender penyewa.
     *
     * @param int $id_barang ID Barang
     * @return array Array berisi rentang tanggal, e.g., [['2025-11-10', '2025-11-12'], ['2025-11-15', '2025-11-16']]
     */
    public function getJadwalBooked($id_barang) {
        // Ambil pesanan yang statusnya masih aktif (belum 'selesai' atau 'dibatalkan')
        // Sesuaikan status ini jika Anda memiliki status 'dibatalkan'
        $sql = "SELECT tanggal_mulai, tanggal_selesai 
                FROM pemesanan 
                WHERE id_barang = ? 
                AND status_pemesanan IN ('menunggu', 'dibayar', 'disewa')";

        $stmt = $this->dbconn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (getJadwalBooked): " . $this->dbconn->error);
            return [];
        }

        $stmt->bind_param('i', $id_barang);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $rentang = $result->fetch_all(MYSQLI_NUM); // Ambil sebagai array numerik
            $stmt->close();
            // Hasil: [ ['2025-11-10', '2025-11-12'], ['2025-11-15', '2025-11-16'] ]
            return $rentang;
        } else {
            error_log("Execute failed (getJadwalBooked): " . $stmt->error);
            $stmt->close();
            return [];
        }
    }
}
?>