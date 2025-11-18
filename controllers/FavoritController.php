<?php
// controllers/FavoritController.php

class FavoritController extends Controller {

    private $favoritModel;

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

        $this->favoritModel = $this->loadModel('FavoritModel');
    }

    /**
     * Menampilkan halaman Daftar Favorite
     */
    public function index() {
        $penyewaId = $_SESSION['user_id'];
        
        // Ambil data produk lengkap yang sudah difavoritkan
        $data['favoritProduk'] = $this->favoritModel->getFavoritesByPenyewa($penyewaId);
        
        // Data untuk header
        $data['namaPengguna'] = $_SESSION['user_nama'] ?? 'Penyewa';
        $data['currentPage'] = 'favorit'; // Untuk menandai nav link aktif (jika diperlukan)

        // Muat view
        $this->loadView('favorit_penyewa', $data);
    }

    /**
     * Aksi untuk menambah/menghapus (toggle) favorit
     */
    public function toggle() {
        $id_barang = $_GET['id'] ?? null;
        $id_penyewa = $_SESSION['user_id'];

        if ($id_barang) {
            // Cek apakah sudah difavoritkan
            if ($this->favoritModel->isFavorite($id_penyewa, $id_barang)) {
                // Jika ya, hapus
                $this->favoritModel->removeFavorite($id_penyewa, $id_barang);
            } else {
                // Jika tidak, tambahkan
                $this->favoritModel->addFavorite($id_penyewa, $id_barang);
            }
        }

        // Kembalikan pengguna ke halaman sebelumnya
        $this->redirectBack();
    }
}
?>