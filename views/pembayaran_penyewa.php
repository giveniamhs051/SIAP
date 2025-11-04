<?php
// views/pembayaran_penyewa.php
$namaPenyewa = $namaPengguna ?? 'Penyewa';
$pesanan = $pesanan ?? null;
$metode = $metode_pembayaran ?? 'Bank Transfer';

// Logika untuk menampilkan instruksi pembayaran
$kode_pembayaran = 'VA-1234-' . str_pad($pesanan['id_pemesanan'], 6, '0', STR_PAD_LEFT);
$instruksi = "Silakan transfer ke nomor Virtual Account BNI berikut:";
$icon = "https://placehold.co/40x40/EBF8FF/1E3A5F?text=Bank"; // Icon Bank

if ($metode == 'E-wallet') {
     $kode_pembayaran = '081234567890 (a.n. SIAP Mendaki)';
     $instruksi = "Silakan bayar ke nomor OVO/Gopay/Dana berikut:";
     $icon = "https://placehold.co/40x40/FFFBEB/D97706?text=E"; // Icon E-wallet
} else if ($metode == 'COD') {
     $kode_pembayaran = 'Bayar di Tempat';
     $instruksi = "Silakan siapkan uang tunai dan bayar saat mengambil barang di:";
     $icon = "https://placehold.co/40x40/FEE2E2/DC2626?text=COD"; // Icon COD
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F8F9FA; }
    </style>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'brand-blue': '#1E3A5F', 'brand-yellow': '#FFBE00', 'brand-gray': '#F8F9FA' } } }
        }
    </script>
</head>
<body class="bg-brand-gray">

    <!-- Header (Sama seperti detail_produk.php) -->
    <header class="bg-white shadow-md sticky top-0 z-30">
       <!-- ... (Salin kode <header> lengkap dari views/detail_produk.php) ... -->
    </header>

    <main class="container mx-auto px-4 lg:px-6 py-12">
        <?php if ($pesanan): ?>
        <div class="max-w-lg mx-auto bg-white rounded-lg shadow-xl p-6 sm:p-8">
            
            <div class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-brand-yellow mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                
                <h1 class="text-xl font-semibold text-gray-800 mb-2">Segera Selesaikan Pembayaran</h1>
                <p class="text-sm text-gray-500 mb-4">Pesanan Anda (ID: <?php echo $pesanan['id_pemesanan']; ?>) akan diproses setelah pembayaran diterima.</p>
            </div>

            <div class="bg-brand-gray rounded-lg p-4 my-5 text-center">
                <p class="text-sm text-gray-600">Total Pembayaran</p>
                <p class="text-3xl font-bold text-brand-blue">Rp <?php echo number_format($pesanan['total_harga'], 0, ',', '.'); ?></p>
            </div>

            <div class="text-left bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center gap-3 mb-3 pb-3 border-b">
                    <img src="<?php echo $icon; ?>" alt="<?php echo $metode; ?>" class="w-10 h-10 rounded-full">
                    <p class="font-semibold text-gray-700 text-lg"><?php echo $metode; ?></p>
                </div>

                <p class="text-sm text-gray-500 mt-2 mb-1"><?php echo $instruksi; ?></p>
                
                <?php if ($metode == 'COD'): ?>
                     <p class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($pesanan['nama_vendor']); ?></p>
                     <p class="text-sm text-gray-600"><?php echo htmlspecialchars($pesanan['alamat_vendor']); ?></p>
                <?php else: ?>
                    <p class="text-2xl font-bold text-gray-800 tracking-wider bg-gray-100 p-3 rounded text-center my-2"><?php echo $kode_pembayaran; ?></p>
                <?php endif; ?>
            </div>

            <a href="index.php?c=DashboardController&m=index" class="mt-8 inline-block w-full text-center bg-brand-yellow text-brand-blue font-semibold py-3 px-6 rounded-lg hover:opacity-90 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-yellow">
                Kembali ke Beranda
            </a>
            <a href="#" class="mt-3 inline-block w-full text-center text-sm text-gray-600 hover:text-brand-blue">
                Cek Status Pesanan Saya
            </a>
        </div>
        <?php else: ?>
             <p class="text-center text-gray-600">Gagal memuat detail pembayaran.</p>
        <?php endif; ?>
    </main>
    
</body>
</html>
