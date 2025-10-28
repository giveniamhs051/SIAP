<?php
// controllers/DashboardController.php

class DashboardController extends Controller {

    public function __construct() {
        // Cek apakah user sudah login, jika belum, paksa ke login
        if (!isset($_SESSION['user_id'])) {
             $this->redirect('AuthController', 'loginView');
        }
    }

    // Method index akan menampilkan dashboard sesuai role
    public function index() {
        $role = $_SESSION['user_role'] ?? 'guest'; // Ambil role dari session
        $nama = $_SESSION['user_nama'] ?? 'Pengguna'; // Ambil nama dari session

        // Data dasar yang akan dikirim ke view
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
            
            // --- MODIFIKASI DI SINI ---
            case 'penyewa':
                // Muat model produk
                $produkModel = $this->loadModel('ProdukModel');
                
                // Ambil data produk (contoh: 4 terlaris, 4 rekomendasi)
                $data['produkTerlaris'] = $produkModel->getProdukTerbaruAktif(4); // Ambil 4 produk terbaru sebagai 'terlaris'
                $data['rekomendasiProduk'] = $produkModel->getRekomendasiProduk(4); // Ambil 4 produk acak sebagai 'rekomendasi'
                
                // Muat view dashboard penyewa dengan data produk
                $this->loadView('dashboard_penyewa', $data); 
                break;
            // --- AKHIR MODIFIKASI ---

            default:
                // Jika role tidak dikenal, logout saja
                $this->redirect('AuthController', 'logout');
                break;
        }
    }

    // Tambahkan method lain untuk dashboard di sini jika perlu
}
?>