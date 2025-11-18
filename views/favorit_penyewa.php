<?php
// views/favorit_penyewa.php
$namaPenyewa = $namaPengguna ?? 'Penyewa';
$daftarFavorit = $daftarFavorit ?? []; // Data barang favorit dari controller

// Helper format Rupiah
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
function getCityFromAddress($address) {
    if (empty($address)) return 'N/A';
    $parts = explode(',', $address);
    $city = trim(end($parts)); 
    if (empty($city) && count($parts) >= 2) {
        $city = trim($parts[count($parts) - 2]);
    }
    return $city ?: 'N/A';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorit Saya - SIAP Mendaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    <style> body { font-family: 'Poppins', sans-serif; background-color: #F8F9FA; } </style>
    <script>
        tailwind.config = { theme: { extend: { colors: { 'brand-blue': '#1E3A5F', 'brand-yellow': '#FFBE00', 'brand-gray': '#F8F9FA' } } } }
    </script>
</head>
<body class="bg-brand-gray">

    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-4 lg:px-6 py-4 flex justify-between items-center">
            <a href="index.php?c=DashboardController&m=index" class="flex items-center">
                 <img src="src/logo-siap.png" class="h-10 mr-3" alt="Logo SIAP Mendaki" />
            </a>
            <div class="flex items-center space-x-4">
                <a href="index.php?c=DashboardController&m=index" class="text-gray-500 hover:text-brand-blue font-medium">Kembali ke Beranda</a>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @keydown.escape.window="open = false" class="flex items-center text-sm font-medium text-gray-700 hover:text-brand-blue focus:outline-none">
                         <span class="w-9 h-9 bg-brand-blue rounded-full flex items-center justify-center text-white text-sm font-semibold mr-2">
                            <?php echo strtoupper(substr(htmlspecialchars($namaPenyewa), 0, 1)); ?>
                        </span>
                        Halo, <?php echo explode(' ', htmlspecialchars($namaPenyewa))[0]; ?>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="index.php?c=ProfileController&m=index" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Anda</a>
                        <a href="index.php?c=AuthController&m=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Keluar</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-4 lg:px-6 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Favorit Saya</h1>

        <?php if (empty($daftarFavorit)): ?>
            <div class="text-center py-20 bg-white rounded-lg shadow-sm">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <h2 class="text-lg font-medium text-gray-600">Belum ada barang favorit</h2>
                <p class="text-gray-500 mb-6">Simpan barang yang Anda suka di sini agar mudah ditemukan nanti.</p>
                <a href="index.php?c=DashboardController&m=index" class="bg-brand-blue text-white px-6 py-2 rounded-lg hover:bg-opacity-90">Cari Barang</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($daftarFavorit as $produk): ?>
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow relative group">
                    <a href="index.php?c=FavoritController&m=remove&id=<?php echo $produk['id_barang']; ?>" class="absolute top-2 right-2 bg-white rounded-full p-1 shadow hover:bg-red-50 text-gray-400 hover:text-red-500 z-10" title="Hapus dari Favorit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <a href="index.php?c=ProdukController&m=detail&id=<?php echo $produk['id_barang']; ?>" class="block">
                        <img src="<?php echo htmlspecialchars($produk['url_foto'] ?? 'src/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($produk['nama_barang']); ?>" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-1 truncate"><?php echo htmlspecialchars($produk['nama_barang']); ?></h3>
                            <p class="text-brand-blue font-bold text-sm mb-2"><?php echo formatRupiah($produk['harga_sewa']); ?>/hari</p>
                            <div class="flex items-center text-xs text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                <?php echo htmlspecialchars(getCityFromAddress($produk['alamat_vendor'] ?? '')); ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>