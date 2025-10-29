<?php
// controllers/ProfileController.php
class ProfileController extends Controller {

    private $profileModel;

    public function __construct() {
        // Cek login. Jika tidak login, redirect ke AuthController.
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('AuthController', 'loginView');
        }
        // Load model yang diperlukan
        $this->profileModel = $this->loadModel('ProfileModel');
    }

    /**
     * Menampilkan halaman edit profil sesuai role pengguna.
     * Saat ini fokus pada Vendor.
     */
    public function index() {
        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['user_role'] ?? 'guest';

        // Hanya vendor yang bisa edit profil vendor
        if ($role === 'vendor') {
            $vendorData = $this->profileModel->getVendorById($user_id);

            if ($vendorData) {
                // Kirim data vendor ke view
                $this->loadView('profile_vendor_edit', ['vendor' => $vendorData]);
            } else {
                // Handle jika data vendor tidak ditemukan (seharusnya tidak terjadi jika login berhasil)
                $_SESSION['error_message'] = 'Data vendor tidak ditemukan.';
                 error_log("Gagal memuat data vendor untuk ID: " . $user_id);
                 $this->redirect('DashboardController', 'index'); // Kembali ke dashboard
            }
        } else {
            // Jika bukan vendor, redirect ke dashboardnya masing-masing
            $_SESSION['error_message'] = 'Hanya vendor yang dapat mengakses halaman ini.';
            $this->redirect('DashboardController', 'index');
        }
    }

    /**
     * Memproses data update profil vendor dari form.
     */
    public function updateVendor() {
         // Pastikan hanya vendor yang bisa update dan request method = POST
        if ($_SESSION['user_role'] !== 'vendor' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
             $this->redirect('DashboardController', 'index');
        }

        $vendor_id = $_SESSION['user_id'];

        // Ambil data dari POST (Sanitasi dasar bisa ditambahkan)
        $data = [
            'nama_vendor' => trim($_POST['nama_toko'] ?? ''),
            'no_telepon' => trim($_POST['nomor_telepon'] ?? ''),
            'alamat_vendor' => trim($_POST['alamat_toko'] ?? ''), // Null coalescing jika kosong
            'deskripsi_vendor' => trim($_POST['deskripsi_toko'] ?? '') // Null coalescing jika kosong
        ];

        // Validasi sederhana di controller
        if (empty($data['nama_vendor'])) {
             $_SESSION['error_message'] = 'Nama Toko tidak boleh kosong.';
             $this->redirect('ProfileController', 'index'); // Kembali ke form edit
        }
         if (empty($data['no_telepon'])) {
             $_SESSION['error_message'] = 'Nomor Telepon tidak boleh kosong.';
             $this->redirect('ProfileController', 'index'); // Kembali ke form edit
        }
        // Bisa tambahkan validasi lain (panjang karakter, format nomor telepon, dll.)

        // Coba update data via model
        if ($this->profileModel->updateVendorProfile($vendor_id, $data)) {
            // Jika berhasil, update juga nama di session jika berubah
            if ($_SESSION['user_nama'] !== $data['nama_vendor']) {
                $_SESSION['user_nama'] = $data['nama_vendor'];
            }
             $_SESSION['success_message'] = 'Profil berhasil diperbarui.';
             $this->redirect('ProfileController', 'index'); // Kembali ke halaman edit profil
        } else {
             $_SESSION['error_message'] = 'Gagal memperbarui profil. Silakan coba lagi.';
             $this->redirect('ProfileController', 'index'); // Kembali ke form edit
        }
    }

    // Anda bisa menambahkan method untuk menangani upload foto profil di sini nanti
    // public function uploadPhoto() { ... }
    // public function deletePhoto() { ... }

}
?>