<?php
// controllers/OrderController.php

class OrderController extends Controller {

    private $produkModel;
    private $orderModel; // Model baru

    public function __construct() {
        // Cek login
        if (!isset($_SESSION['user_id'])) {
             $_SESSION['error_message'] = 'Anda harus login terlebih dahulu.';
             $this->redirect('AuthController', 'loginView');
        }
        
        // Hanya penyewa yang bisa order
        if ($_SESSION['user_role'] !== 'penyewa') {
             $_SESSION['error_message'] = 'Halaman ini hanya untuk penyewa.';
             $this->redirect('DashboardController', 'index');
        }

        $this->produkModel = $this->loadModel('ProdukModel');
        $this->orderModel = $this->loadModel('OrderModel'); // Buat model ini
    }

    /**
     * Menampilkan halaman checkout (sesuai gambar PNG).
     * Ini adalah halaman "Pemesanan.png"
     */
    public function checkoutView() {
        $id_barang = $_GET['id'] ?? null;
        $tgl_mulai = $_GET['tgl_mulai'] ?? null;
        $tgl_selesai = $_GET['tgl_selesai'] ?? null;
        $qty = $_GET['qty'] ?? 1;

        // Validasi input dari halaman detail
        if (!$id_barang || !$tgl_mulai || !$tgl_selesai || $qty <= 0) {
            $_SESSION['error_message'] = 'Detail pemesanan tidak lengkap. Silakan pilih tanggal sewa terlebih dahulu.';
            // Redirect kembali ke halaman detail produk
            // Cek dulu apakah ID barang ada sebelum di-redirect
            $redirectParams = $id_barang ? ['id' => $id_barang] : [];
            $this->redirect('ProdukController', 'detail', $redirectParams);
        }

        $produk = $this->produkModel->getProdukById($id_barang);
        if (!$produk) {
             $_SESSION['error_message'] = 'Produk tidak ditemukan.';
             $this->redirect('DashboardController', 'index');
        }
        
        // Hitung durasi dan total
        try {
            $date1 = new DateTime($tgl_mulai);
            $date2 = new DateTime($tgl_selesai);
            // Tambah 1 hari karena 18-20 Okt dihitung 3 hari (18, 19, 20)
            $interval = $date1->diff($date2)->days + 1; 
        } catch (Exception $e) {
            $interval = 0;
        }

        if ($interval <= 0) {
             $_SESSION['error_message'] = 'Tanggal sewa tidak valid.';
             $this->redirect('ProdukController', 'detail', ['id' => $id_barang]);
        }

        // Cek stok
        if ($qty > $produk['stok_barang']) {
             $_SESSION['error_message'] = 'Jumlah sewa melebihi stok yang tersedia (Stok: ' . $produk['stok_barang'] . ').';
             $this->redirect('ProdukController', 'detail', ['id' => $id_barang]);
        }
        
        $subtotal = ($produk['harga_sewa'] * $qty) * $interval;

        // Kirim data ke view checkout
        $data = [
            'namaPengguna' => $_SESSION['user_nama'],
            'produk' => $produk,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
            'qty' => $qty,
            'durasi_hari' => $interval,
            'subtotal' => $subtotal
        ];

        $this->loadView('pemesanan_penyewa', $data);
    }
    
    /**
     * Memproses pesanan dari halaman checkout (setelah modal dikonfirmasi).
     */
    public function processOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('DashboardController', 'index');
        }

        // Ambil data dari POST
        $data = [
            'id_penyewa' => $_SESSION['user_id'],
            'id_barang' => $_POST['id_barang'] ?? null,
            'id_vendor' => $_POST['id_vendor'] ?? null, // Kita perlu ini
            'tgl_mulai' => $_POST['tgl_mulai'] ?? null,
            'tgl_selesai' => $_POST['tgl_selesai'] ?? null,
            'total_harga' => $_POST['total_harga'] ?? 0,
            'metode_pembayaran' => $_POST['metode_pembayaran'] ?? null
            // Qty bisa ditambahkan jika skema DB mendukung
        ];

        // Validasi sederhana
        if (empty($data['id_barang']) || empty($data['tgl_mulai']) || empty($data['tgl_selesai']) || empty($data['metode_pembayaran']) || empty($data['id_vendor'])) {
             $_SESSION['error_message'] = 'Data pesanan tidak lengkap. Gagal memproses.';
             // Redirect kembali ke halaman checkout
             $this->redirect('OrderController', 'checkoutView', [
                'id' => $data['id_barang'], 
                'tgl_mulai' => $data['tgl_mulai'], 
                'tgl_selesai' => $data['tgl_selesai'],
                'qty' => $_POST['qty'] ?? 1 // Kirim ulang qty
             ]);
        }
        
        // Simpan ke DB
        $id_pemesanan_baru = $this->orderModel->createPemesanan($data);

        if ($id_pemesanan_baru) {
            // Berhasil, redirect ke halaman pembayaran
            $_SESSION['success_message'] = 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.';
            $this->redirect('OrderController', 'paymentView', [
                'id' => $id_pemesanan_baru,
                'metode' => $data['metode_pembayaran']
            ]);
        } else {
            // Gagal
             $_SESSION['error_message'] = 'Gagal membuat pesanan. Stok mungkin habis atau terjadi error.';
             $this->redirect('ProdukController', 'detail', ['id' => $data['id_barang']]);
        }
    }

    /**
     * Menampilkan halaman pembayaran (setelah checkout).
     */
    public function paymentView() {
        $id_pesanan = $_GET['id'] ?? null;
        $metode = $_GET['metode'] ?? 'Bank Transfer';

        if (!$id_pesanan) {
            $this->redirect('DashboardController', 'index');
        }

        // Ambil detail pesanan yang baru dibuat
        $pesanan = $this->orderModel->getOrderById($id_pesanan, $_SESSION['user_id']);

        if (!$pesanan) {
            $_SESSION['error_message'] = 'Pesanan tidak ditemukan.';
            $this->redirect('DashboardController', 'index');
        }

        $data = [
            'namaPengguna' => $_SESSION['user_nama'],
            'pesanan' => $pesanan,
            'metode_pembayaran' => $metode
        ];

        $this->loadView('pembayaran_penyewa', $data);
    }
}
?>
