<?php
// controllers/DashboardController.php

class DashboardController extends Controller {

    public function __construct() {
        // Cek apakah user sudah login, jika belum, paksa ke login
        if (!isset($_SESSION['user_id'])) {
             // Simpan pesan error jika perlu
             // $_SESSION['error_message'] = 'Anda harus login untuk mengakses halaman ini.';
             $this->redirect('AuthController', 'loginView');
        }
    }

    // Method index akan menampilkan dashboard sesuai role
    public function index() {
        $role = $_SESSION['user_role'] ?? 'guest'; // Ambil role dari session
        $nama = $_SESSION['user_nama'] ?? 'Pengguna'; // Ambil nama dari session

        // Data yang akan dikirim ke view
        $data = [
            'namaPengguna' => $nama,
            'rolePengguna' => $role
        ];

        // Tampilkan view dashboard yang sesuai
        switch ($role) {
            case 'admin':
                $this->loadView('dashboard_admin', $data);
                break;
            case 'vendor':
                $this->loadView('dashboard_vendor', $data);
                break;
            case 'penyewa':
                $this->loadView('dashboard_penyewa', $data);
                break;
            default:
                // Jika role tidak dikenal, logout saja
                $this->redirect('AuthController', 'logout');
                break;
        }
    }

    // Tambahkan method lain untuk dashboard di sini jika perlu
}
?>