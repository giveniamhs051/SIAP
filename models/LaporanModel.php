 <?php
// models/LaporanModel.php
class LaporanModel extends Model {

    /**
     * Mengambil data detail untuk halaman "Buat Laporan".
     * Memvalidasi bahwa pemesanan ini milik vendor yang login & statusnya sudah 'selesai'.
     */
    public function getDataForReportPage($id_pemesanan, $id_vendor) {
        $sql = "SELECT 
                    b.nama_barang, 
                    b.url_foto, 
                    py.nama_penyewa, 
                    p.id_pemesanan,
                    v.id_vendor
                FROM pemesanan p
                JOIN barang b ON p.id_barang = b.id_barang
                JOIN vendor v ON b.id_vendor = v.id_vendor
                JOIN penyewa py ON p.id_penyewa = py.id_penyewa
                WHERE 
                    p.id_pemesanan = ? 
                    AND v.id_vendor = ? 
                    AND p.status_pemesanan = 'selesai'"; // Hanya pemesanan selesai
        
        $stmt = $this->dbconn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed (getDataForReportPage): " . $this->dbconn->error);
            return false;
        }
        $stmt->bind_param("ii", $id_pemesanan, $id_vendor);
        
        if (!$stmt->execute()) {
            error_log("Execute failed (getDataForReportPage): " . $stmt->error);
            $stmt->close();
            return false;
        }

        $result = $stmt->get_result();
        $data = $result->fetch_object(); // Ambil sebagai objek
        $stmt->close();

        return $data ? $data : false; // Kembalikan data atau false
    }

    /**
     * Memeriksa apakah pemesanan ini sudah memiliki laporan kerusakan.
     */
    public function checkIfReportExists($id_pemesanan) {
        $stmt = $this->dbconn->prepare("SELECT id_laporan FROM laporan_kerusakan WHERE id_pemesanan = ? LIMIT 1");
        if (!$stmt) {
            error_log("Prepare failed (checkIfReportExists): " . $this->dbconn->error);
            return false;
        }
        $stmt->bind_param("i", $id_pemesanan);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        $stmt->close();

        return $count > 0;
    }

    /**
     * Menyimpan laporan kerusakan baru ke database.
     */
    public function createLaporan($data) {
        // Kolom DB: id_vendor, id_pemesanan, deskripsi_kerusakan, bukti_foto
        $sql = "INSERT INTO laporan_kerusakan (id_vendor, id_pemesanan, deskripsi_kerusakan, bukti_foto, tanggal_laporan) 
                VALUES (?, ?, ?, ?, CURDATE())";
        
        $stmt = $this->dbconn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed (createLaporan): " . $this->dbconn->error);
            return false;
        }

        // Bind 4 parameter: i(int), i(int), s(string), s(string)
        $stmt->bind_param("iiss", 
            $data['id_vendor'],
            $data['id_pemesanan'],
            $data['deskripsi_kerusakan'],
            $data['bukti_foto'] // Ini adalah path file
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            error_log("Execute failed (createLaporan): " . $stmt->error);
            $stmt->close();
            return false;
        }
    }
}
?>
