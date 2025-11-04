<?php
// controllers/ProdukController.php

class ProdukController extends Controller {

    private $produkModel;

    public function __construct() {
        // Cek login
        if (!isset($_SESSION['user_id'])) {
             $this->redirect('AuthController', 'loginView');
        }
        
        // Hanya penyewa yang bisa akses halaman ini
        if ($_SESSION['user_role'] !== 'penyewa') {
             $_SESSION['error_message'] = 'Halaman ini hanya untuk penyewa.';
             $this->redirect('DashboardController', 'index');
        }

        $this->produkModel = $this->loadModel('ProdukModel');
    }

    /**
     * Menampilkan halaman detail produk.
     */
    public function detail() {
        $id_barang = $_GET['id'] ?? null;
        if (!$id_barang) {
            $_SESSION['error_message'] = 'Produk tidak ditemukan.';
            $this->redirect('DashboardController', 'index');
        }

        // Ambil data produk dari model (method getProdukById sudah kita tambahkan)
        $data['produk'] = $this->produkModel->getProdukById($id_barang);
        
        if (!$data['produk']) {
             $_SESSION['error_message'] = 'Detail produk tidak ditemukan.';
             $this->redirect('DashboardController', 'index');
        }
        
        $data['namaPengguna'] = $_SESSION['user_nama'] ?? 'Penyewa';

        // Tampilkan view detail produk
        $this->loadView('detail_produk', $data);
    }
}
?>
