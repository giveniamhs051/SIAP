<?php
// controllers/PesananController.php

class PesananController extends Controller {

    private $pesananModel;

    public function __construct() {
        // 1. Cek Login & Role Vendor
        if (!isset($_SESSION['user_id'])) {
             $this->redirect('AuthController', 'loginView');
        }
        // Your AuthModel uses 'vendor' as role
        if ($_SESSION['user_role'] !== 'vendor') { 
             $_SESSION['error_message'] = 'Anda tidak memiliki akses ke halaman ini.';
             $this->redirect('DashboardController', 'index');
        }

        // 2. Load Model Pesanan
        $this->pesananModel = $this->loadModel('PesananModel');
    }

    /**
     * Menampilkan halaman daftar pesanan berdasarkan status
     */
    public function index() {
        // Ambil status dari URL, default ke 'Dikonfirmasi' sesuai mockup
        $status_ui = $_GET['status'] ?? 'Dikonfirmasi'; 
        
        // Daftar status UI yang valid
        $valid_statuses_ui = ['Menunggu Pembayaran', 'Dikonfirmasi', 'Disewa', 'Dikembalikan', 'Selesai'];
        
        // Jika status tidak valid, kembalikan ke default
        if (!in_array($status_ui, $valid_statuses_ui)) {
            $status_ui = 'Dikonfirmasi';
        }

        $vendorId = $_SESSION['user_id'];
        
        // Ambil data pesanan dari model berdasarkan vendor dan status UI
        $data['daftarPesanan'] = $this->pesananModel->getOrdersByVendorAndStatus($vendorId, $status_ui);
        
        // Data lain untuk view
        $data['namaPengguna'] = $_SESSION['user_nama'] ?? 'Vendor';
        $data['statusAktif'] = $status_ui; // Untuk menandai tombol status mana yang aktif

        // Muat view
        $this->loadView('pesanan_vendor', $data);
    }

    // --- Method Aksi ---

    // Aksi untuk status 'Menunggu Pembayaran' -> Konfirmasi Siap Diambil
    public function konfirmasiSiap() {
        $idPemesanan = $_GET['id'] ?? null;
        if ($idPemesanan && $_SESSION['user_role'] === 'vendor') {
            // Update status di DB ke 'dibayar' (yang kita map ke 'Dikonfirmasi')
            if ($this->pesananModel->updateOrderStatus($idPemesanan, 'dibayar', $_SESSION['user_id'])) {
                $_SESSION['success_message'] = 'Status pesanan #' . $idPemesanan . ' berhasil diubah menjadi Dikonfirmasi (Siap Diambil).';
            } else {
                $_SESSION['error_message'] = 'Gagal mengubah status pesanan #' . $idPemesanan . '.';
            }
        }
        // Redirect kembali ke halaman 'Menunggu Pembayaran'
        $this->redirect('PesananController', 'index', ['status' => 'Menunggu Pembayaran']);
    }

    // Aksi untuk status 'Dikonfirmasi' -> Konfirmasi Sudah Diambil
    public function konfirmasiDiambil() {
        $idPemesanan = $_GET['id'] ?? null;
        if ($idPemesanan && $_SESSION['user_role'] === 'vendor') {
             // Update status di DB ke 'disewa'
             if ($this->pesananModel->updateOrderStatus($idPemesanan, 'disewa', $_SESSION['user_id'])) {
                $_SESSION['success_message'] = 'Status pesanan #' . $idPemesanan . ' berhasil diubah menjadi Disewa.';
            } else {
                $_SESSION['error_message'] = 'Gagal mengubah status pesanan #' . $idPemesanan . '.';
            }
        }
         // Redirect kembali ke halaman 'Dikonfirmasi'
         $this->redirect('PesananController', 'index', ['status' => 'Dikonfirmasi']);
    }
    
    // Aksi untuk status 'Disewa' -> Konfirmasi Sudah Dikembalikan
    public function konfirmasiKembali() {
        $idPemesanan = $_GET['id'] ?? null;
        if ($idPemesanan && $_SESSION['user_role'] === 'vendor') {
             // Update status di DB ke 'Dikembalikan' (ASUMSI SUDAH DITAMBAHKAN DI ENUM)
             // Jika belum, update ini akan gagal. Alternatif: update ke 'selesai' lalu cek di tabel 'pengembalian'
             if ($this->pesananModel->updateOrderStatus($idPemesanan, 'Dikembalikan', $_SESSION['user_id'])) { 
                $_SESSION['success_message'] = 'Status pesanan #' . $idPemesanan . ' berhasil diubah menjadi Dikembalikan.';
                // Anda mungkin ingin menambahkan record ke tabel 'pengembalian' di sini juga
             } else {
                $_SESSION['error_message'] = 'Gagal mengubah status pesanan #' . $idPemesanan . '. Pastikan status "Dikembalikan" ada di database.';
             }
        }
         // Redirect kembali ke halaman 'Disewa'
         $this->redirect('PesananController', 'index', ['status' => 'Disewa']);
    }

    // Aksi untuk status 'Dikembalikan' -> Laporan Kerusakan
    public function laporanKerusakan() {
         $idPemesanan = $_GET['id'] ?? null;
         // TODO: Implementasi halaman/form laporan kerusakan
         // Anda perlu membuat LaporanController dan LaporanModel
         // $this->redirect('LaporanController', 'formKerusakan', ['id_pesanan' => $idPemesanan]);
         $_SESSION['info_message'] = "Fitur Laporan Kerusakan untuk pesanan #{$idPemesanan} belum diimplementasikan.";
         $this->redirect('PesananController', 'index', ['status' => 'Dikembalikan']);
    }

    // Aksi untuk status 'Dikembalikan' -> Konfirmasi Kondisi Baik (Selesaikan Pesanan)
    public function konfirmasiSelesai() {
        $idPemesanan = $_GET['id'] ?? null;
        if ($idPemesanan && $_SESSION['user_role'] === 'vendor') {
            // Update status di DB ke 'selesai'
            if ($this->pesananModel->updateOrderStatus($idPemesanan, 'selesai', $_SESSION['user_id'])) {
                $_SESSION['success_message'] = 'Pesanan #' . $idPemesanan . ' berhasil diselesaikan.';
                 // Update juga status di tabel 'pengembalian' jika perlu
            } else {
                $_SESSION['error_message'] = 'Gagal menyelesaikan pesanan #' . $idPemesanan . '.';
            }
        }
        // Redirect kembali ke halaman 'Dikembalikan'
        $this->redirect('PesananController', 'index', ['status' => 'Dikembalikan']);
    }


     // Aksi untuk status 'Selesai' -> Lihat Ulasan
    public function lihatUlasan() {
        $idPemesanan = $_GET['id'] ?? null;
        // TODO: Implementasi halaman/modal lihat ulasan
        // Anda perlu UlasanController dan UlasanModel
        // $this->redirect('UlasanController', 'viewForVendor', ['id_pesanan' => $idPemesanan]);
         $_SESSION['info_message'] = "Fitur Lihat Ulasan untuk pesanan #{$idPemesanan} belum diimplementasikan.";
         $this->redirect('PesananController', 'index', ['status' => 'Selesai']);
    }

    // Aksi untuk tombol 'Detail' (berlaku di semua status)
    public function detailPesanan() {
        $idPemesanan = $_GET['id'] ?? null;
        $statusAsal = $_GET['from_status'] ?? 'Dikonfirmasi'; // Ambil status asal
        // TODO: Tampilkan modal atau halaman detail pesanan
        // Ambil data lengkap pesanan, barang, penyewa
        // Jika statusAsal == 'Dikonfirmasi', sertakan logika untuk menampilkan QR Code
         $_SESSION['info_message'] = "Fitur Detail Pesanan untuk #{$idPemesanan} belum diimplementasikan.";
         // Redirect kembali ke status asal
         $this->redirect('PesananController', 'index', ['status' => $statusAsal]);
    }
    
}
?>