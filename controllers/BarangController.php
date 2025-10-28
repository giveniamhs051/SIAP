<?php
// controllers/BarangController.php

class BarangController extends Controller {

    private $barangModel;

    public function __construct() {
        // 1. Cek apakah user sudah login
        if (!isset($_SESSION['user_id'])) {
             $this->redirect('AuthController', 'loginView');
        }
        
        // 2. Cek apakah user adalah 'vendor'
        if ($_SESSION['user_role'] !== 'vendor') {
             // Jika bukan vendor, mungkin lempar ke dashboard sesuai role-nya
             // atau tampilkan error 'unauthorized'
             $_SESSION['error_message'] = 'Anda tidak memiliki akses ke halaman ini.';
             $this->redirect('DashboardController', 'index');
        }

        // 3. Load model yang akan sering dipakai
        $this->barangModel = $this->loadModel('BarangModel');
    }

    /**
     * Menampilkan halaman daftar barang (List View)
     */
    public function index() {
        $vendorId = $_SESSION['user_id'];
        
        // Ambil semua data barang milik vendor yang sedang login
        $data['barang'] = $this->barangModel->getItemsByVendor($vendorId);
        
        // Ambil data user untuk ditampilkan di header
        $data['namaPengguna'] = $_SESSION['user_nama'] ?? 'Vendor';
        
        // Muat view
        $this->loadView('barang_vendor', $data);
    }

    /**
     * Memproses form unggah barang baru
     */
    public function uploadProcess() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // --- 1. Proses Upload Foto ---
            $url_foto = null;
            if (isset($_FILES['foto_barang']) && $_FILES['foto_barang']['error'] == 0) {
                $target_dir = "public/uploads/barang/"; // Pastikan folder ini ada dan writable
                
                // Buat folder jika belum ada
                if (!file_exists($target_dir) && !is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $file_extension = strtolower(pathinfo($_FILES['foto_barang']['name'], PATHINFO_EXTENSION));
                $safe_filename = "brg_" . $_SESSION['user_id'] . "_" . time() . "." . $file_extension;
                $target_file = $target_dir . $safe_filename;
                
                $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($file_extension, $allowed_types)) {
                    if (move_uploaded_file($_FILES['foto_barang']['tmp_name'], $target_file)) {
                        $url_foto = $target_file; // PERBAIKAN: Simpan path relatif ke database
                    }
                }
            }
            // --- Akhir Proses Upload Foto ---

            // --- 2. PERBAIKAN: Proses Tanggal Tersedia (Single Date) ---
            // Litepicker akan mengirim format "YYYY-MM-DD"
            $tanggal_tersedia = trim($_POST['tanggal_tersedia'] ?? null);
            // --- Akhir Proses Tanggal ---

            // 3. Kumpulkan semua data untuk disimpan
            $data = [
                'id_vendor' => $_SESSION['user_id'],
                'nama_barang' => trim($_POST['nama_barang'] ?? ''),
                'harga_sewa' => trim($_POST['harga_sewa'] ?? 0),
                'stok' => trim($_POST['stok'] ?? 0),
                'deskripsi' => trim($_POST['deskripsi'] ?? ''),
                'tgl_tersedia' => $tanggal_tersedia, // PERBAIKAN
                'url_foto' => $url_foto // PERBAIKAN
            ];

            // 4. Simpan ke database
            if ($this->barangModel->createItem($data)) {
                $_SESSION['success_message'] = 'Barang berhasil ditambahkan!';
            } else {
                $_SESSION['error_message'] = 'Gagal menambahkan barang.';
            }

            // 5. Redirect kembali ke halaman list barang
            $this->redirect('BarangController', 'index');

        } else {
            // Jika diakses bukan via POST, tendang balik
            $this->redirect('BarangController', 'index');
        }
    }
}
?>