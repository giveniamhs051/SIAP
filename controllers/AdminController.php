<?php
// controllers/AdminController.php

class AdminController extends Controller {

    private $adminModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
             $this->redirect('AuthController', 'loginView');
        }
        if ($_SESSION['user_role'] !== 'admin') { 
             $_SESSION['error_message'] = 'Anda tidak memiliki akses ke halaman ini.';
             $this->redirect('DashboardController', 'index');
        }
        $this->adminModel = $this->loadModel('AdminModel');
    }

    /**
     * Menampilkan halaman 'Kelola Pengguna' dengan Pencarian.
     */
    public function kelolaPengguna() {
        $keyword = $_GET['q'] ?? null; // Ambil keyword pencarian

        $data['daftarPengguna'] = $this->adminModel->getAllUsers($keyword);
        $data['namaPengguna'] = $_SESSION['user_nama'] ?? 'Admin';
        $data['keyword'] = $keyword;

        $this->loadView('admin_kelola_pengguna', $data);
    }

    /**
     * Proses Tambah Pengguna Baru
     */
    public function tambahPengguna() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama' => $_POST['nama'],
                'email' => $_POST['email'],
                'telepon' => $_POST['telepon'],
                'role' => $_POST['role'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT) // Hash password
            ];

            // Validasi sederhana (cek email unique sebaiknya ditambahkan di model juga)
            if ($this->adminModel->createUser($data)) {
                $_SESSION['success_message'] = "Pengguna berhasil ditambahkan.";
            } else {
                $_SESSION['error_message'] = "Gagal menambahkan pengguna.";
            }
        }
        $this->redirect('AdminController', 'kelolaPengguna');
    }

    /**
     * Proses Hapus Pengguna
     */
    public function hapusPengguna() {
        $id = $_GET['id'] ?? null;
        $role = $_GET['role'] ?? null;

        if ($id && $role) {
            if ($this->adminModel->deleteUser($id, $role)) {
                $_SESSION['success_message'] = "Pengguna berhasil dihapus.";
            } else {
                $_SESSION['error_message'] = "Gagal menghapus pengguna.";
            }
        }
        $this->redirect('AdminController', 'kelolaPengguna');
    }
    
    public function logAktivitas() {
        // ... (kode log aktivitas sama seperti sebelumnya) ...
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