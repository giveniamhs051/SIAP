<?php
// controllers/AuthController.php
// (Controller.php sudah di-include oleh index.php)

class AuthController extends Controller {

    private $authModel; // Properti untuk menyimpan instance model

    public function __construct() {
        // Otomatis load AuthModel saat AuthController dibuat
        $this->authModel = $this->loadModel('AuthModel');
    }

    // Menampilkan halaman login
    public function loginView($error = null) {
        // Data untuk dikirim ke view (misalnya pesan error)
        $data = ['error' => $error];
        $this->loadView('login', $data);
    }

    // Memproses data login dari form
    public function loginProcess() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Validasi input sederhana
            if (empty($email) || empty($password)) {
                $_SESSION['error_message'] = 'Email dan password wajib diisi.';
                $this->redirect('AuthController', 'loginView');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                 $_SESSION['error_message'] = 'Format email tidak valid.';
                 $this->redirect('AuthController', 'loginView');
            }


            // Cari user berdasarkan email
            $user = $this->authModel->findUserByEmail($email);

            // Verifikasi password
            if ($user && password_verify($password, $user['password'])) {
                // Login berhasil
                // Simpan data user penting ke session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_nama'] = $user['nama'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_email'] = $user['email']; // Simpan email juga jika perlu

                // Redirect ke dashboard yang sesuai
                 $this->redirect('DashboardController', 'index');
            } else {
                // Login gagal
                $_SESSION['error_message'] = 'Email atau password salah.';
                $this->redirect('AuthController', 'loginView');
            }
        } else {
            // Jika bukan POST, redirect kembali ke form login
            $this->redirect('AuthController', 'loginView');
        }
    }

    // Menampilkan halaman register
    public function registerView($error = null) {
        $data = ['error' => $error];
        $this->loadView('register', $data);
    }

    // Memproses data register dari form
    public function registerProcess() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Ambil data dari form (gunakan null coalescing ?? untuk keamanan)
            $data = [
                'nama' => trim($_POST['nama'] ?? ''),
                'nomor_telepon' => trim($_POST['nomor_telepon'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'role' => $_POST['role'] ?? '', // 'penyewa' atau 'vendor'
            ];

            // --- Validasi Input ---
            if (empty($data['nama']) || empty($data['email']) || empty($data['password']) || empty($data['confirm_password']) || empty($data['role']) || empty($data['nomor_telepon'])) {
                $_SESSION['error_message'] = 'Semua kolom wajib diisi.';
                $this->redirect('AuthController', 'registerView');
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_message'] = 'Format email tidak valid.';
                $this->redirect('AuthController', 'registerView');
            }
            if (strlen($data['password']) < 8) {
                $_SESSION['error_message'] = 'Password minimal 8 karakter.';
                $this->redirect('AuthController', 'registerView');
            }
            if ($data['password'] !== $data['confirm_password']) {
                $_SESSION['error_message'] = 'Konfirmasi password tidak cocok.';
                $this->redirect('AuthController', 'registerView');
            }
            if ($data['role'] !== 'penyewa' && $data['role'] !== 'vendor') {
                 $_SESSION['error_message'] = 'Role tidak valid.';
                 $this->redirect('AuthController', 'registerView');
            }
            // --- Akhir Validasi ---

            // Cek apakah email sudah ada
            if ($this->authModel->findUserByEmail($data['email'])) {
                $_SESSION['error_message'] = 'Email sudah terdaftar. Silakan gunakan email lain.';
                $this->redirect('AuthController', 'registerView');
            }

            // Hash password sebelum disimpan
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            // Hapus confirm_password karena tidak disimpan di DB
            unset($data['confirm_password']);

            // Coba buat user baru
            if ($this->authModel->createUser($data)) {
                // Registrasi berhasil
                $_SESSION['success_message'] = 'Registrasi berhasil! Silakan login.';
                $this->redirect('AuthController', 'loginView');
            } else {
                // Registrasi gagal (kemungkinan error database)
                $_SESSION['error_message'] = 'Terjadi kesalahan saat registrasi. Mohon coba lagi.';
                $this->redirect('AuthController', 'registerView');
            }

        } else {
            // Jika bukan POST, redirect ke form register
            $this->redirect('AuthController', 'registerView');
        }
    }

    // Proses logout
    public function logout() {
        // Hapus semua data session
        session_unset();
        session_destroy();

        // Redirect ke halaman login
        $this->redirect('AuthController', 'loginView');
    }
}
?>