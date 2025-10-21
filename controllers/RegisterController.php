<?php
// File: app/controllers/RegisterController.php
// Deskripsi: Controller untuk menangani logika registrasi pengguna baru.

class RegisterController extends Controller {
    public function index() {
        $this->view('register');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Ambil data dari form
            $data = [
                'nama' => trim($_POST['nama']),
                'nomor_telepon' => trim($_POST['nomor_telepon']),
                'email' => trim($_POST['email']),
                'password' => $_POST['password'],
                'confirm_password' => $_POST['confirm_password'],
                'role' => $_POST['role'], // 'penyewa' atau 'vendor'
            ];

            // Validasi sederhana
            if (empty($data['nama']) || empty($data['email']) || empty($data['password']) || empty($data['role'])) {
                $_SESSION['error_message'] = 'Semua kolom wajib diisi.';
                header('Location: /index.php?url=register');
                exit();
            }

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_message'] = 'Format email tidak valid.';
                header('Location: /index.php?url=register');
                exit();
            }

            if ($data['password'] !== $data['confirm_password']) {
                $_SESSION['error_message'] = 'Konfirmasi kata sandi tidak cocok.';
                header('Location: /index.php?url=register');
                exit();
            }
            
            if (strlen($data['password']) < 8) {
                $_SESSION['error_message'] = 'Kata sandi minimal 8 karakter.';
                header('Location: /index.php?url=register');
                exit();
            }

            // Cek apakah email sudah terdaftar
            $userModel = $this->model('UserModel');
            if ($userModel->findByEmail($data['email'])) {
                $_SESSION['error_message'] = 'Email sudah terdaftar.';
                header('Location: /index.php?url=register');
                exit();
            }
            
            // Hash password sebelum disimpan
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            // Simpan pengguna baru ke database
            if ($userModel->createUser($data)) {
                $_SESSION['success_message'] = 'Registrasi berhasil! Silakan masuk.';
                header('Location: /index.php?url=login');
                exit();
            } else {
                $_SESSION['error_message'] = 'Terjadi kesalahan saat registrasi. Coba lagi.';
                header('Location: /index.php?url=register');
                exit();
            }

        } else {
            header('Location: /index.php?url=register');
            exit();
        }
    }
}
?>
