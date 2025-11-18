<?php
    // Helper & Pesan Session
    function e($string) { return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8'); }
    $error_message = $_SESSION['error_message'] ?? null;
    $success_message = $_SESSION['success_message'] ?? null;
    unset($_SESSION['error_message']);
    unset($_SESSION['success_message']);

    // Data dari controller
    $laporan_data = $laporan_data ?? null; // Objek berisi nama_barang, url_foto, nama_penyewa
    $id_pemesanan = $id_pemesanan ?? 0;
    $namaVendor = $_SESSION['user_nama'] ?? 'Vendor';
?>
