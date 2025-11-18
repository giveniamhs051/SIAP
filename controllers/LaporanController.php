<?php
// controllers/LaporanController.php
class LaporanController extends Controller {

    private $laporanModel;

    public function __construct() {
        // Pastikan hanya VENDOR yang sudah login yang bisa akses
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
            $_SESSION['error_message'] = 'Anda harus login sebagai vendor untuk mengakses halaman ini.';
            $this->redirect('AuthController', 'loginView');
        }
        $this->laporanModel = $this->loadModel('LaporanModel');
    }

    /**
     * Menampilkan halaman form "Buat Laporan Kerusakan".
     * Link: index.php?c=LaporanController&m=index&id_pemesanan=XYZ
     */
    public function index() {
        if (!isset($_GET['id_pemesanan'])) {
            $_SESSION['error_message'] = 'ID Pemesanan tidak ditemukan.';
            $this->redirect('PesananController', 'index'); // Kembali ke daftar pesanan
        }
        
        $id_pemesanan = (int)$_GET['id_pemesanan'];
        $id_vendor = (int)$_SESSION['user_id'];

        // 1. Cek apakah sudah pernah dilaporkan
        if ($this->laporanModel->checkIfReportExists($id_pemesanan)) {
            $_SESSION['info_message'] = 'Pemesanan ini sudah pernah memiliki laporan kerusakan.';
            $this->redirect('PesananController', 'index');
        }

        // 2. Ambil data (Validasi pemesanan milik vendor & status 'selesai')
        $laporan_data = $this->laporanModel->getDataForReportPage($id_pemesanan, $id_vendor);

        if (!$laporan_data) {
            $_SESSION['error_message'] = 'Pemesanan tidak valid, belum selesai, atau bukan milik Anda.';
            $this->redirect('PesananController', 'index');
        }

        // 3. Tampilkan view jika semua valid
        $this->loadView('laporan_kerusakan_vendor', [
            'laporan_data' => $laporan_data,
            'id_pemesanan' => $id_pemesanan
        ]);
    }

    /**
     * Menyimpan data laporan kerusakan dari form POST
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('PesananController', 'index');
        }

        $id_pemesanan = (int)$_POST['id_pemesanan'];
        $id_vendor = (int)$_SESSION['user_id'];
        $deskripsi = trim($_POST['deskripsi_kerusakan'] ?? '');

        // Validasi input
        if (empty($deskripsi)) {
            $_SESSION['error_message'] = 'Deskripsi kerusakan tidak boleh kosong.';
            $this->redirect('LaporanController', 'index', ['id_pemesanan' => $id_pemesanan]);
        }
        if (!isset($_FILES['bukti_foto']) || $_FILES['bukti_foto']['error'] != UPLOAD_ERR_OK) {
             $_SESSION['error_message'] = 'Upload bukti foto wajib diisi.';
             $this->redirect('LaporanController', 'index', ['id_pemesanan' => $id_pemesanan]);
        }

        // --- Proses Upload File ---
        $file = $_FILES['bukti_foto'];
        $uploadDir = 'public/uploads/laporan/'; // Pastikan folder ini ada dan writable!
        
        // Buat folder jika belum ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Validasi file (tipe & ukuran)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error_message'] = 'Format foto tidak valid. Hanya JPG atau PNG.';
            $this->redirect('LaporanController', 'index', ['id_pemesanan' => $id_pemesanan]);
        }
        if ($file['size'] > 2097152) { // 2 MB
            $_SESSION['error_message'] = 'Ukuran foto terlalu besar. Maksimal 2MB.';
            $this->redirect('LaporanController', 'index', ['id_pemesanan' => $id_pemesanan]);
        }

        // Buat nama file unik
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = 'laporan_' . $id_pemesanan . '_' . $id_vendor . '_' . time() . '.' . $fileExt;
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            $_SESSION['error_message'] = 'Gagal memindahkan file upload.';
            $this->redirect('LaporanController', 'index', ['id_pemesanan' => $id_pemesanan]);
        }
        // --- Selesai Upload File ---

        $data_to_save = [
            'id_pemesanan' => $id_pemesanan,
            'id_vendor' => $id_vendor,
            'deskripsi_kerusakan' => $deskripsi,
            'bukti_foto' => $filePath // Simpan path-nya
        ];

        // Simpan ke DB
        if ($this->laporanModel->createLaporan($data_to_save)) {
            $_SESSION['success_message'] = 'Laporan kerusakan berhasil dikirim.';
            $this->redirect('PesananController', 'index'); 
        } else {
            // Hapus file yang sudah di-upload jika DB gagal
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $_SESSION['error_message'] = 'Terjadi kesalahan. Gagal menyimpan laporan.';
            $this->redirect('LaporanController', 'index', ['id_pemesanan' => $id_pemesanan]);
        }
    }
}
?>
