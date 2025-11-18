<?php
// controllers/ProfileController.php
class ProfileController extends Controller {

    private $profileModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('AuthController', 'loginView');
        }
        $this->profileModel = $this->loadModel('ProfileModel');
    }

    /**
     * Menampilkan halaman edit profil sesuai role pengguna.
     */
    public function index() {
        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['user_role'] ?? 'guest';

        // --- MODIFIKASI DI SINI: Gunakan switch-case ---
        switch ($role) {
            case 'vendor':
                $vendorData = $this->profileModel->getVendorById($user_id);
                if ($vendorData) {
                    $this->loadView('profile_vendor_edit', ['vendor' => $vendorData]);
                } else {
                    $_SESSION['error_message'] = 'Data vendor tidak ditemukan.';
                    error_log("Gagal memuat data vendor untuk ID: " . $user_id);
                    $this->redirect('DashboardController', 'index');
                }
                break;
            
            case 'penyewa':
                // --- TAMBAHAN BARU ---
                $penyewaData = $this->profileModel->getPenyewaById($user_id); // (Method ini akan kita tambahkan di model)
                if ($penyewaData) {
                    $this->loadView('profile_penyewa_edit', ['penyewa' => $penyewaData]);
                } else {
                    $_SESSION['error_message'] = 'Data penyewa tidak ditemukan.';
                    error_log("Gagal memuat data penyewa untuk ID: " . $user_id);
                    $this->redirect('DashboardController', 'index');
                }
                break;
                // --- AKHIR TAMBAHAN ---

            default:
                $_SESSION['error_message'] = 'Halaman profil tidak tersedia untuk role Anda.';
                $this->redirect('DashboardController', 'index');
        }
    }

    /**
     * Memproses data update profil vendor dari form.
     */
    public function updateVendor() {
        if ($_SESSION['user_role'] !== 'vendor' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
             $this->redirect('DashboardController', 'index');
        }

        $vendor_id = $_SESSION['user_id'];
        $data = [
            'nama_vendor' => trim($_POST['nama_toko'] ?? ''),
            'no_telepon' => trim($_POST['nomor_telepon'] ?? ''),
            'alamat_vendor' => trim($_POST['alamat_toko'] ?? ''),
            'deskripsi_vendor' => trim($_POST['deskripsi_toko'] ?? '')
        ];

        // Validasi... (kode validasi tetap sama)
        if (empty($data['nama_vendor']) || empty($data['no_telepon'])) {
             $_SESSION['error_message'] = 'Nama Toko dan Nomor Telepon tidak boleh kosong.';
             $this->redirect('ProfileController', 'index');
        }

        if ($this->profileModel->updateVendorProfile($vendor_id, $data)) {
            if ($_SESSION['user_nama'] !== $data['nama_vendor']) {
                $_SESSION['user_nama'] = $data['nama_vendor'];
            }
             $_SESSION['success_message'] = 'Profil berhasil diperbarui.';
        } else {
             $_SESSION['error_message'] = 'Gagal memperbarui profil. Silakan coba lagi.';
        }
        $this->redirect('ProfileController', 'index');
    }

    /**
     * --- METHOD BARU: Memproses data update profil PENYEWA ---
     */
    public function updatePenyewa() {
        if ($_SESSION['user_role'] !== 'penyewa' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
             $this->redirect('DashboardController', 'index');
        }

        $penyewa_id = $_SESSION['user_id'];
        
        // Sesuaikan dengan field di tabel 'penyewa' (dari AuthModel)
        $data = [
            'nama_penyewa' => trim($_POST['nama_penyewa'] ?? ''),
            'no_hp' => trim($_POST['nomor_telepon'] ?? '')
        ];

        if (empty($data['nama_penyewa']) || empty($data['no_hp'])) {
             $_SESSION['error_message'] = 'Nama Lengkap dan Nomor Telepon tidak boleh kosong.';
             $this->redirect('ProfileController', 'index');
        }

        // Panggil method model baru (akan kita buat di langkah 3)
        if ($this->profileModel->updatePenyewaProfile($penyewa_id, $data)) {
            // Update nama di session jika berubah
            if ($_SESSION['user_nama'] !== $data['nama_penyewa']) {
                $_SESSION['user_nama'] = $data['nama_penyewa'];
            }
             $_SESSION['success_message'] = 'Profil berhasil diperbarui.';
        } else {
             $_SESSION['error_message'] = 'Gagal memperbarui profil. Silakan coba lagi.';
        }
        $this->redirect('ProfileController', 'index');
    }
}
?>