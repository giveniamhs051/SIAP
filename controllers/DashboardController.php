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
            
            // --- MODIFIKASI DIMULAI DI SINI ---
            case 'penyewa':
                // Muat model produk
                $produkModel = $this->loadModel('ProdukModel');
                
                // Ambil parameter filter dari URL
                $query = $_GET['q'] ?? null;
                $lokasi = $_GET['lokasi'] ?? null;
                $page = $_GET['page'] ?? 'beranda';

                // Ambil data produk berdasarkan filter
                $data['produkTerlaris'] = $produkModel->getProdukTerbaruAktif(4, $query, $lokasi);
                $data['rekomendasiProduk'] = $produkModel->getRekomendasiProduk(4, $query, $lokasi);
                
                // Jika halaman 'produk' atau jika ada filter aktif, ambil semua produk
                if ($page == 'produk' || !empty($query) || !empty($lokasi)) {
                    $data['semuaProduk'] = $produkModel->getSemuaProduk($query, $lokasi);
                    
                    // Jika ada filter, paksa tampilkan halaman 'produk'
                    if (!empty($query) || !empty($lokasi)) {
                        $page = 'produk';
                    }
                }

                // Kirim data filter aktif ke view
                $data['currentPage'] = $page;
                $data['queryAktif'] = $query;
                $data['lokasiAktif'] = $lokasi;
                
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