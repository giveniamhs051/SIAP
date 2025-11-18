<?php
// views/favorit_penyewa.php
$namaPenyewa = $namaPengguna ?? 'Penyewa';
$favoritProduk = $favoritProduk ?? [];
$currentPage = $currentPage ?? 'favorit'; // Untuk header

// Helper yang mungkin dibutuhkan (copy dari dashboard_penyewa.php)
function getCityFromAddress($address) {
    if (empty($address)) return 'N/A';
    $parts = explode(',', $address);
    $city = trim(end($parts)); 
    if (empty($city)) {
        if (count($parts) >= 2) $city = trim($parts[count($parts) - 2]);
        else $city = trim($parts[0]);
    }
    return $city ?: 'N/A';
}
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
$navItems = [
    'beranda' => 'Beranda',
    'tentang' => 'Tentang Kami',
    'produk' => 'Produk',
    'lokasi' => 'Lokasi'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorit Saya - SIAP Mendaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #FFFFFF; }
        .nav-link-active { color: #1E3A5F; font-weight: 600; border-bottom: 2px solid #1E3A5F; }
        .nav-link { color: #4B5563; border-bottom: 2px solid transparent; }
        .nav-link:hover { color: #111827; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-blue': '#1E3A5F',
                        'brand-yellow': '#FFBE00',
                        'brand-gray': '#F8F9FA',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white">

    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-4 lg:px-6 py-4 flex justify-between items-center">
            <a href="index.php?c=DashboardController&m=index" class="flex items-center">
                 <img src="src/logo-siap.png" class="h-10 mr-3" alt="Logo SIAP Mendaki" />
            </a>

            <ul class="hidden md:flex items-center space-x-8 font-medium">
                <?php foreach ($navItems as $pageKey => $pageName): ?>
                <li>
                    <a href="index.php?c=DashboardController&m=index&page=<?php echo $pageKey; ?>" 
                       class="py-2 nav-link <?php echo ($currentPage == $pageKey) ? 'nav-link-active' : ''; ?>">
                       <?php echo $pageName; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

            <div class="flex items-center space-x-4">
                 <button class="text-gray-500 hover:text-brand-blue relative p-1">
                    <span class="sr-only">Notifikasi</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                 </button>
                 
                 <a href="index.php?c=FavoritController&m=index" class="p-1" title="Daftar Favorit">
                    <span class="sr-only">Favorit</span>
                    <?php if ($currentPage == 'favorit'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-brand-blue">
                          <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.614 3 7.5 3c1.74 0 3.333.92 4.313 2.311.98-1.391 2.573-2.311 4.313-2.311 2.886 0 5.25 2.322 5.25 5.25 0 3.924-2.438 7.11-4.789 9.27a25.178 25.178 0 01-4.244 3.17 15.247 15.247 0 01-.383.218l-.022.012-.007.004-.004.001a.752.752 0 01-.21.035z" />
                        </svg>
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500 hover:text-brand-blue">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                    <?php endif; ?>
                 </a>

                 <button class="text-gray-500 hover:text-brand-blue p-1">
                    <span class="sr-only">Keranjang</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                 </button>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @keydown.escape.window="open = false" id="user-menu-button" class="flex items-center text-sm font-medium text-gray-700 hover:text-brand-blue focus:outline-none">
                         <span class="w-9 h-9 bg-brand-blue rounded-full flex items-center justify-center text-white text-sm font-semibold mr-2">
                            <?php echo strtoupper(substr(htmlspecialchars($namaPenyewa), 0, 1)); ?>
                        </span>
                        Halo, <?php echo explode(' ', htmlspecialchars($namaPenyewa))[0]; ?>
                        <svg class="ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition x-cloak
                         class="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Anda</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pesanan Saya</a>
                        <a href="index.php?c=AuthController&m=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Keluar</a>
                    </div>
                </div>
                <button id="mobile-menu-button" class="md:hidden">...</button>
            </div>
        </nav>
        <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg">...</div>
    </header>

    <main class="container mx-auto px-4 lg:px-6 py-8">
        <h1 class="text-3xl font-bold text-brand-blue mb-6">Daftar Favorite</h1>

        <?php if (empty($favoritProduk)): ?>
            <div class="text-center py-16 bg-gray-50 rounded-lg border border-dashed">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-20 h-20 text-gray-400 mx-auto mb-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Daftar Favorit Kosong</h2>
                <p class="text-gray-500 mb-6">Anda belum menambahkan barang apapun ke favorit.</p>
                <a href="index.php?c=DashboardController&m=index&page=produk" class="bg-brand-blue text-white font-semibold py-2 px-5 rounded-lg hover:bg-opacity-90">
                    Cari Produk
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                
                <?php foreach ($favoritProduk as $produk): ?>
                    <div classa="block bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden group relative">
                        <a href="index.php?c=ProdukController&m=detail&id=<?php echo $produk['id_barang']; ?>">
                            <img src="<?php echo htmlspecialchars($produk['url_foto'] ?? 'https://placehold.co/300x200/e2e8f0/cbd5e1?text=SIAP'); ?>" alt="<?php echo htmlspecialchars($produk['nama_barang']); ?>" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-1 group-hover:text-brand-blue transition-colors truncate" title="<?php echo htmlspecialchars($produk['nama_barang']); ?>"><?php echo htmlspecialchars($produk['nama_barang']); ?></h3>
                                <p class="text-brand-blue font-bold text-sm mb-2"><?php echo formatRupiah($produk['harga_sewa']); ?>/hari</p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 mr-1"><path fill-rule="evenodd" d="M8 1.75a.75.75 0 0 1 .75.75v2.5a.75.75 0 0 1-1.5 0V2.5A.75.75 0 0 1 8 1.75ZM6.25 5a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5h-3.5ZM4.5 8a.75.75 0 0 0 0 1.5h7a.75.75 0 0 0 0-1.5h-7ZM3 10.75a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" /></svg>
                                    <?php echo htmlspecialchars(getCityFromAddress($produk['alamat_vendor'])); ?>
                                </div>
                            </div>
                        </a>
                        <a href="index.php?c=FavoritController&m=toggle&id=<?php echo $produk['id_barang']; ?>" class="absolute top-3 right-3 bg-white/70 p-1.5 rounded-full z-10 backdrop-blur-sm hover:bg-white transition-all" title="Hapus dari favorit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-red-500">
                              <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.614 3 7.5 3c1.74 0 3.333.92 4.313 2.311.98-1.391 2.573-2.311 4.313-2.311 2.886 0 5.25 2.322 5.25 5.25 0 3.924-2.438 7.11-4.789 9.27a25.178 25.178 0 01-4.244 3.17 15.247 15.247 0 01-.383.218l-.022.012-.007.004-.004.001a.752.752 0 01-.21.035z" />
                            </svg>
                        </a>
                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if(mobileMenuButton && mobileMenu) {
                 mobileMenuButton.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
            }
            // Logic dropdown Alpine.js sudah ada di tag-nya
        });
    </script>
</body>
</html>