<?php
// controllers/ProfilVendorController.php

class ProfilVendorController extends Controller {

    private $profilModel;

    public function __construct() {
        // 1. Cek Login & Role Vendor
        if (!isset($_SESSION['user_id'])) {
             $this->redirect('AuthController', 'loginView');
        }
        if ($_SESSION['user_role'] !== 'vendor') { // Pastikan role adalah 'vendor'
             $_SESSION['error_message'] = 'Anda tidak memiliki akses ke halaman ini.';
             $this->redirect('DashboardController', 'index');
        }

        // 2. Load Model Profil Vendor
        $this->profilModel = $this->loadModel('ProfilVendorModel');
    }

    /**
     * Menampilkan halaman profil vendor
     */
    public function index() {
        $vendorId = $_SESSION['user_id'];
        
        // Ambil data vendor saat ini dari model
        $vendorData = $this->profilModel->getVendorById($vendorId);
        
        if (!$vendorData) {
            // Handle jika data vendor tidak ditemukan (seharusnya tidak terjadi jika sudah login)
            $_SESSION['error_message'] = 'Data vendor tidak ditemukan.';
            $this->redirect('DashboardController', 'index');
        }

        // Data untuk view
        $data['vendor'] = $vendorData;
        $data['namaPengguna'] = $vendorData['nama_vendor'] ?? $_SESSION['user_nama']; // Ambil nama dari data profil jika ada

        // Muat view
        $this->loadView('profil_vendor', $data);
    }

    /**
     * Memproses form update profil vendor
     */
    public function updateProcess() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $vendorId = $_SESSION['user_id'];

            // 1. Ambil data vendor saat ini (terutama untuk path foto lama)
            $currentVendorData = $this->profilModel->getVendorById($vendorId);
            if (!$currentVendorData) {
                $_SESSION['error_message'] = 'Gagal memproses: Data vendor tidak ditemukan.';
                $this->redirect('ProfilVendorController', 'index');
            }
            $currentFotoPath = $currentVendorData['foto_profil_url'] ?? null;
            $foto_path = $currentFotoPath; // Default ke foto lama

             // 2. Proses Upload Foto Baru (jika ada)
            if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0 && $_FILES['foto_profil']['size'] > 0) {
                $target_dir = "public/uploads/profil_vendor/"; 
                if (!file_exists($target_dir) && !is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $file_extension = strtolower(pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION));
                // Nama file unik: vendor_<id>_<timestamp>.<ext>
                $safe_filename = "vendor_" . $vendorId . "_" . time() . "." . $file_extension; 
                $target_file = $target_dir . $safe_filename;
                
                // Validasi sederhana (tipe & ukuran)
                $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
                $max_size = 2 * 1024 * 1024; // 2MB

                if (in_array($file_extension, $allowed_types) && $_FILES['foto_profil']['size'] <= $max_size) {
                    if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $target_file)) {
                        // Jika berhasil upload foto baru, hapus foto lama (jika ada)
                        if ($currentFotoPath && file_exists($currentFotoPath)) {
                            unlink($currentFotoPath); 
                        }
                        $foto_path = $target_file; // Gunakan path foto baru
                    } else {
                         $_SESSION['error_message'] = 'Gagal mengupload foto profil baru.';
                         $this->redirect('ProfilVendorController', 'index'); // Gagal upload, jangan lanjutkan
                    }
                } else {
                    $_SESSION['error_message'] = 'Foto profil harus berupa JPG/PNG/WEBP dan maksimal 2MB.';
                     $this->redirect('ProfilVendorController', 'index'); // Gagal validasi, jangan lanjutkan
                }
            } elseif (isset($_POST['hapus_foto']) && $_POST['hapus_foto'] == '1') {
                 // 3. Proses Hapus Foto (jika tombol hapus diklik)
                 if ($currentFotoPath && file_exists($currentFotoPath)) {
                     unlink($currentFotoPath);
                 }
                 $foto_path = null; // Set path jadi null
            }

            // 4. Kumpulkan data dari form (selain foto)
            $updateData = [
                'nama_vendor' => trim($_POST['nama_toko'] ?? ''),
                'email_vendor' => trim($_POST['email_toko'] ?? ''), // Pastikan email unik jika diubah! (Validasi tambahan diperlukan)
                'no_telepon' => trim($_POST['nomor_telepon'] ?? ''),
                'alamat_vendor' => trim($_POST['alamat_toko'] ?? ''),
                'deskripsi_vendor' => trim($_POST['deskripsi_toko'] ?? ''),
                'foto_profil_url' => $foto_path // Path foto (bisa baru, lama, atau null)
            ];

            // 5. Validasi data lain (Contoh: nama tidak boleh kosong)
            if (empty($updateData['nama_vendor']) || empty($updateData['email_vendor'])) {
                $_SESSION['error_message'] = 'Nama Toko dan Email tidak boleh kosong.';
                $this->redirect('ProfilVendorController', 'index');
            }
             if (!filter_var($updateData['email_vendor'], FILTER_VALIDATE_EMAIL)) {
                 $_SESSION['error_message'] = 'Format Email Toko tidak valid.';
                 $this->redirect('ProfilVendorController', 'index');
             }
             // TODO: Tambahkan validasi apakah email baru sudah digunakan oleh vendor lain

            // 6. Update data ke database via Model
            if ($this->profilModel->updateVendorProfile($vendorId, $updateData)) {
                $_SESSION['success_message'] = 'Profil berhasil diperbarui!';
                // Update session jika nama atau email berubah
                $_SESSION['user_nama'] = $updateData['nama_vendor'];
                $_SESSION['user_email'] = $updateData['email_vendor']; 
            } else {
                $_SESSION['error_message'] = 'Gagal memperbarui profil.';
            }

            // 7. Redirect kembali ke halaman profil
            $this->redirect('ProfilVendorController', 'index');

        } else {
            // Jika bukan POST, tendang balik
            $this->redirect('ProfilVendorController', 'index');
        }
    }
}
?>