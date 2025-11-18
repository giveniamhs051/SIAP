<?php
// controllers/AdminController.php

class AdminController extends Controller {

    private $adminModel;

    public function __construct() {
        // 1. Cek Login
        if (!isset($_SESSION['user_id'])) {
             $this->redirect('AuthController', 'loginView');
        }
        
        // 2. Cek Role (HANYA ADMIN)
        if ($_SESSION['user_role'] !== 'admin') { 
             $_SESSION['error_message'] = 'Anda tidak memiliki akses ke halaman ini.';
             $this->redirect('DashboardController', 'index');
        }

        // 3. Load Model
        $this->adminModel = $this->loadModel('AdminModel');
    }

    /**
     * Menampilkan halaman 'Kelola Pengguna'.
     * Ini adalah method "direct2" yang akan dipanggil dari link.
     */
    public function kelolaPengguna() {
        // Ambil data dari model
        $data['daftarPengguna'] = $this->adminModel->getAllUsers();
        
        // Data lain untuk view
        $data['namaPengguna'] = $_SESSION['user_nama'] ?? 'Admin';

        // Muat view (UI)
        $this->loadView('admin_kelola_pengguna', $data);
    }
    
    /**
     * (Contoh) Method untuk Log Aktivitas
     */
     public function logAktivitas() {
        // Ambil filter dari URL (jika ada)
        $filters = [
            'role' => $_GET['role'] ?? null,
            'aksi' => $_GET['aksi'] ?? null,
            'tanggal_mulai' => null,
            'tanggal_selesai' => null,
        ];

        // Proses filter tanggal (jika menggunakan litepicker range)
        if (!empty($_GET['tanggal'])) {
            $dates = explode(' - ', $_GET['tanggal']);
            if (count($dates) == 2) {
                $filters['tanggal_mulai'] = $dates[0];
                $filters['tanggal_selesai'] = $dates[1];
            }
        }

        // Ambil data dari model dengan filter
        $data['daftarLog'] = $this->adminModel->getLogs($filters);
        
        $data['namaPengguna'] = $_SESSION['user_nama'] ?? 'Admin';
        $data['filterAktif'] = $filters; // Kirim filter kembali ke view

        // Muat view (UI)
        $this->loadView('admin_log_aktivitas', $data);
    }
}
?>
