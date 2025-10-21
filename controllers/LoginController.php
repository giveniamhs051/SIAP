<?php
// File: app/controllers/LoginController.php
// Deskripsi: Controller untuk menangani logika login.

class LoginController extends Controller {

    public function index() {
        // Jika sudah login, redirect ke dashboard yang sesuai
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard($_SESSION['user_role']);
        }
        $this->view('login');
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = $this->model('UserModel');
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Login berhasil, set session
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_nama'] = $user['nama'];
                $_SESSION['user_role'] = $user['role'];
                
                $this->redirectToDashboard($user['role']);
            } else {
                // Login gagal
                $_SESSION['error_message'] = 'Email atau kata sandi salah.';
                header('Location: /index.php?url=login'); // Gunakan path absolut jika perlu
                exit();
            }
        } else {
            // Jika bukan POST request, kembali ke halaman login
            header('Location: /index.php?url=login');
            exit();
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /index.php?url=login');
        exit();
    }
    
    private function redirectToDashboard($role) {
        switch ($role) {
            case 'admin':
                header('Location: /index.php?url=admin/dashboard');
                break;
            case 'vendor':
                header('Location: /index.php?url=vendor/dashboard');
                break;
            case 'penyewa':
                header('Location: /index.php?url=penyewa/dashboard');
                break;
            default:
                header('Location: /index.php?url=login');
                break;
        }
        exit();
    }
}

// Dummy controller untuk dashboard, idealnya ini file terpisah
class AdminController extends Controller { public function dashboard() { $this->view('dashboard_admin'); } }
class VendorController extends Controller { public function dashboard() { $this->view('dashboard_vendor'); } }
class PenyewaController extends Controller { public function dashboard() { $this->view('dashboard_penyewa'); } }
?>