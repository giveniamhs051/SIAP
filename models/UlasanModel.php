 <?php
// models/UlasanModel.php
class UlasanModel extends Model {

    /**
     * @param int $id_pemesanan ID pemesanan dari URL.
     * @param int $id_penyewa ID penyewa dari session.
     * @return object|false Data barang & vendor jika valid, false jika tidak.
     */
    public function getDataForReviewPage($id_pemesanan, $id_penyewa) {
        $sql = "SELECT 
                    b.nama_barang, 
                    b.url_foto, 
                    v.nama_vendor, 
                    b.id_vendor 
                FROM pemesanan p
                JOIN barang b ON p.id_barang = b.id_barang
                JOIN vendor v ON b.id_vendor = v.id_vendor
                WHERE 
                    p.id_pemesanan = ? 
                    AND p.id_penyewa = ? 
                    AND p.status_pemesanan = 'selesai'";
        
        $stmt = $this->dbconn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed (getDataForReviewPage): " . $this->dbconn->error);
            return false;
        }
        $stmt->bind_param("ii", $id_pemesanan, $id_penyewa);
        
        if (!$stmt->execute()) {
            error_log("Execute failed (getDataForReviewPage): " . $stmt->error);
            $stmt->close();
            return false;
        }

        $result = $stmt->get_result();
        $data = $result->fetch_object(); // Ambil sebagai objek
        $stmt->close();

        return $data ? $data : false; // Kembalikan data atau false
    }

    /**
     * Memeriksa apakah sebuah pemesanan sudah pernah diulas sebelumnya.
     *
     * @param int $id_pemesanan ID pemesanan yang akan dicek.
     * @return bool True jika sudah ada ulasan, false jika belum.
     */
    public function checkIfAlreadyReviewed($id_pemesanan) {
        $stmt = $this->dbconn->prepare("SELECT id_ulasan FROM ulasan WHERE id_pemesanan = ? LIMIT 1");
        if (!$stmt) {
            error_log("Prepare failed (checkIfAlreadyReviewed): " . $this->dbconn->error);
            return false; // Anggap belum jika query gagal
        }
        $stmt->bind_param("i", $id_pemesanan);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        $stmt->close();

        return $count > 0;
    }

    /**
     * Menyimpan ulasan baru ke database.
     *
     * @param array $data Data ulasan dari controller.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function createUlasan($data) {
        // Kolom di DB: id_pemesanan, id_penyewa, id_vendor, rating, komentar
        $sql = "INSERT INTO ulasan (id_pemesanan, id_penyewa, id_vendor, rating, komentar, tanggal_ulasan) 
                VALUES (?, ?, ?, ?, ?, CURDATE())";
        
        $stmt = $this->dbconn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed (createUlasan): " . $this->dbconn->error);
            return false;
        }

        // Bind 5 parameter: i(int), i(int), i(int), i(int), s(string)
        $stmt->bind_param("iiiis", 
            $data['id_pemesanan'],
            $data['id_penyewa'],
            $data['id_vendor'],
            $data['rating'],
            $data['komentar']
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            error_log("Execute failed (createUlasan): " . $stmt->error);
            $stmt->close();
            return false;
        }
    }
}
?>
