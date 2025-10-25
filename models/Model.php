<?php
// models/Model.php
class Model {
    protected $dbconn;

    public function __construct() {
        // --- Konfigurasi Database ---
        $host = 'localhost'; // Host database Anda
        $dbuser = 'root';      // Username database Anda
        $dbpass = '';          // Password database Anda
        $dbname = 'siap';      // Nama database SIAP Anda
        $dbport = '3306';      // Port default MySQL di XAMPP
        // -------------------------

        // Membuat koneksi mysqli
        // @ digunakan untuk menekan warning default PHP jika koneksi gagal
        @$this->dbconn = new mysqli($host, $dbuser, $dbpass, $dbname, $dbport);

        // Menampilkan pesan error yang lebih jelas jika koneksi gagal
        if ($this->dbconn->connect_errno) {
            die('Database Connection Failure: (' . $this->dbconn->connect_errno . ') ' . $this->dbconn->connect_error);
        }

        // Opsional: Set charset ke utf8mb4 untuk dukungan emoji dan karakter internasional
        $this->dbconn->set_charset("utf8mb4");
    }

    // Fungsi __destruct akan otomatis dipanggil saat objek tidak lagi digunakan
    // Kita gunakan untuk menutup koneksi database secara otomatis
    public function __destruct() {
        if ($this->dbconn) {
            $this->dbconn->close();
        }
    }
}
?>