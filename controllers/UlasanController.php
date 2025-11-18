<?php
// controllers/UlasanController.php
class UlasanController extends Controller {

    private $ulasanModel;

    public function __construct() {
        // Pastikan hanya penyewa yang sudah login yang bisa akses
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'penyewa') {
            $_SESSION['error_message'] = 'Anda harus login sebagai penyewa untuk mengakses halaman ini.';
            $this->redirect('AuthController', 'loginView');
        }
        $this->ulasanModel = $this->loadModel('UlasanModel');
    }

    /**
     * Menampilkan halaman form ulasan.
     * Link-nya akan seperti: index.php?c=UlasanController&m=index&id_pemesanan=XYZ
     */
    public function index() {
        // Ambil ID pemesanan dari URL
        if (!isset($_GET['id_pemesanan'])) {
            $_SESSION['error_message'] = 'ID Pemesanan tidak ditemukan.';
            $this->redirect('DashboardController', 'index'); // Arahkan ke dashboard penyewa
        }
        
        $id_pemesanan = (int)$_GET['id_pemesanan'];
        $id_penyewa = (int)$_SESSION['user_id'];

        // 1. Cek apakah sudah diulas
        if ($this->ulasanModel->checkIfAlreadyReviewed($id_pemesanan)) {
            $_SESSION['info_message'] = 'Pemesanan ini sudah pernah Anda ulas.';
            // TODO: Ganti redirect ini ke halaman History Penyewa Anda
            $this->redirect('DashboardController', 'index'); 
        }

        // 2. Ambil data (sekaligus validasi kepemilikan & status 'selesai')
        $data_review = $this->ulasanModel->getDataForReviewPage($id_pemesanan, $id_penyewa);

        if (!$data_review) {
            // Ini terjadi jika pemesanan tidak ditemukan, bukan milik penyewa, atau belum 'selesai'
            $_SESSION['error_message'] = 'Pemesanan tidak valid atau belum selesai.';
            // TODO: Ganti redirect ini ke halaman History Penyewa Anda
            $this->redirect('DashboardController', 'index');
        }

        // 3. Tampilkan view jika semua valid
        $this->loadView('ulasan_penyewa', [
            'data_review' => $data_review,
            'id_pemesanan' => $id_pemesanan
        ]);
    }

    /**
     * Menyimpan data ulasan dari form POST
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('DashboardController', 'index');
        }

        // Ambil data dari form
        $data = [
            'id_pemesanan' => (int)$_POST['id_pemesanan'],
            'id_vendor' => (int)$_POST['id_vendor'],
            'id_penyewa' => (int)$_SESSION['user_id'],
            'rating' => (int)$_POST['rating'],
            'komentar' => trim($_POST['komentar'])
        ];

        // Validasi sederhana
        if (empty($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            $_SESSION['error_message'] = 'Rating (bintang) wajib diisi.';
            $this->redirect('UlasanController', 'index', ['id_pemesanan' => $data['id_pemesanan']]);
        }
        if (empty($data['komentar'])) {
            $_SESSION['error_message'] = 'Komentar ulasan tidak boleh kosong.';
            $this->redirect('UlasanController', 'index', ['id_pemesanan' => $data['id_pemesanan']]);
        }

        // Cek lagi (security) apakah sudah diulas
        if ($this->ulasanModel->checkIfAlreadyReviewed($data['id_pemesanan'])) {
             $_SESSION['info_message'] = 'Pemesanan ini sudah pernah Anda ulas.';
             $this->redirect('DashboardController', 'index'); // Ganti ke history
        }

        // Simpan ulasan
        if ($this->ulasanModel->createUlasan($data)) {
            $_SESSION['success_message'] = 'Ulasan Anda berhasil dikirim. Terima kasih!';
            // TODO: Ganti redirect ini ke halaman History Penyewa Anda
            $this->redirect('DashboardController', 'index'); 
        } else {
            $_SESSION['error_message'] = 'Terjadi kesalahan. Gagal menyimpan ulasan.';
            $this->redirect('UlasanController', 'index', ['id_pemesanan' => $data['id_pemesanan']]);
        }
    }
}
?>
