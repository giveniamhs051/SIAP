<?php
// views/dashboard_penyewa.php
$namaPenyewa = $namaPengguna ?? 'Penyewa';
$produkTerlaris = $produkTerlaris ?? [];
$rekomendasiProduk = $rekomendasiProduk ?? [];

// Helper untuk mengambil nama kota dari alamat (contoh sederhana)
function getCityFromAddress($address) {
    if (empty($address)) return 'N/A';
    $parts = explode(',', $address);
    $city = trim(end($parts));
    if (empty($city) || strlen($city) > 50) {
        if (count($parts) >= 2) {
            $city = trim($parts[count($parts) - 2]);
        }
    }
     if (empty($city) || strlen($city) > 50) {
         return trim(explode(',', $address, 2)[0]);
     }
    return $city ?: 'N/A';
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - SIAP Mendaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 3px;}
        ::-webkit-scrollbar-thumb { background: #c4c4c4; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-blue': '#1E3A5F', // Warna biru tua utama
                        'brand-yellow': '#FFBE00',
                        'brand-gray': '#F8F9FA',
                        'header-text': '#FFFFFF', // Teks putih untuk header
                        'header-hover': '#EAB308', // Kuning sedikit gelap untuk hover
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white">

    <header class="bg-brand-blue text-header-text shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-4 lg:px-6 py-3 flex justify-between items-center">
            <a href="index.php?c=DashboardController&m=index" class="flex items-center">
                 <img src="src/logo-siap.png" class="h-16 mr-3" alt="Logo SIAP Mendaki Putih" />
            </a>

            <ul class="hidden md:flex items-center space-x-8 font-medium">
                <li><a href="index.php?c=DashboardController&m=index" class="hover:text-brand-yellow border-b-2 border-brand-yellow pb-1">Beranda</a></li>
                <li><a href="#" class="hover:text-brand-yellow pb-1 border-b-2 border-transparent hover:border-header-hover">Tentang Kami</a></li>
                <li><a href="#" class="hover:text-brand-yellow pb-1 border-b-2 border-transparent hover:border-header-hover">Produk</a></li>
                <li><a href="#" class="hover:text-brand-yellow pb-1 border-b-2 border-transparent hover:border-header-hover">Lokasi</a></li>
            </ul>

            <div class="flex items-center space-x-4">
                 <button class="text-header-text/80 hover:text-white relative p-1 rounded-full hover:bg-white/10">
                    <span class="sr-only">Notifikasi</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <span class="absolute top-0 right-0 flex h-3 w-3"><span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 ring-1 ring-brand-blue"></span></span>
                 </button>
                 <button class="text-header-text/80 hover:text-white p-1 rounded-full hover:bg-white/10">
                     <span class="sr-only">Favorit</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                 </button>
                 <button class="text-header-text/80 hover:text-white p-1 rounded-full hover:bg-white/10">
                    <span class="sr-only">Keranjang</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                 </button>

                <div class="relative">
                    <button id="user-menu-button" class="flex items-center text-sm font-medium text-header-text hover:text-brand-yellow focus:outline-none">
                         <span class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-brand-blue text-xs font-semibold mr-2">
                            <?php echo strtoupper(substr(htmlspecialchars($namaPenyewa), 0, 1)); ?>
                        </span>
                        Halo, <?php echo explode(' ', htmlspecialchars($namaPenyewa))[0]; ?>
                        <svg class="ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">/* Arrow Icon */</svg>
                    </button>
                    <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Anda</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pesanan Saya</a>
                        <a href="index.php?c=AuthController&m=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Keluar</a>
                    </div>
                </div>

                 <button id="mobile-menu-button" class="md:hidden text-header-text/80 hover:text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">/* Hamburger Icon */</svg>
                </button>
            </div>
        </nav>
        <div id="mobile-menu" class="hidden md:hidden bg-brand-blue border-t border-gray-700/50">
            <ul class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <li><a href="index.php?c=DashboardController&m=index" class="block px-3 py-2 rounded-md text-base font-medium text-brand-yellow bg-white/10">Beranda</a></li>
                <li><a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-header-text/80 hover:bg-sidebar-hover hover:text-white">Tentang Kami</a></li>
                <li><a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-header-text/80 hover:bg-sidebar-hover hover:text-white">Produk</a></li>
                <li><a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-header-text/80 hover:bg-sidebar-hover hover:text-white">Lokasi</a></li>
            </ul>
        </div>
    </header>

    <main class="container mx-auto px-4 lg:px-6 py-6">

        <div class="mb-6 flex items-center">
            <div class="relative flex-grow">
                 <input type="text" placeholder="Cari alat pendakian" class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-yellow text-sm">
                 <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">/* Search Icon */</svg>
            </div>
             <button class="ml-3 p-3 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">/* Filter Icon */</svg>
            </button>
        </div>

        <div class="mb-8 rounded-lg overflow-hidden relative h-64 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?q=80&w=2070&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-center items-start p-8 md:p-12">
                <h1 class="text-white text-3xl md:text-4xl font-bold mb-2 leading-tight">DISKON SPESIAL!</h1>
                <p class="text-white text-lg md:text-xl">Sewa Tenda Premium Mulai RP. 50.000/Hari</p>
            </div>
        </div>

        <section class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Produk Terlaris</h2>
                 <div class="flex space-x-2">
                    <button class="carousel-prev-terlaris bg-gray-200 hover:bg-gray-300 text-gray-700 p-2 rounded-full disabled:opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">/* Prev Arrow */</svg>
                    </button>
                     <button class="carousel-next-terlaris bg-gray-200 hover:bg-gray-300 text-gray-700 p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">/* Next Arrow */</svg>
                    </button>
                 </div>
            </div>
            <div id="produk-terlaris-container" class="flex space-x-4 overflow-x-auto pb-4 no-scrollbar">
                <?php if (empty($produkTerlaris)): ?>
                    <p class="text-gray-500 italic">Produk terlaris belum tersedia.</p>
                <?php else: ?>
                    <?php foreach ($produkTerlaris as $produk): ?>
                    <div class="flex-shrink-0 w-64 bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden group">
                        <img src="<?php echo htmlspecialchars($produk['url_foto'] ?? 'https://via.placeholder.com/300x200/cccccc/ffffff?text=Image+Not+Found'); ?>" alt="<?php echo htmlspecialchars($produk['nama_barang']); ?>" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-1 group-hover:text-brand-blue transition-colors truncate" title="<?php echo htmlspecialchars($produk['nama_barang']); ?>"><?php echo htmlspecialchars($produk['nama_barang']); ?></h3>
                            <p class="text-brand-blue font-bold text-sm mb-2">Rp <?php echo number_format($produk['harga_sewa'], 0, ',', '.'); ?>/hari</p>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 mr-1">/* Location Icon */</svg>
                                    <?php echo htmlspecialchars(getCityFromAddress($produk['alamat_vendor'] ?? 'N/A')); ?>
                                </span>
                                <button class="hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">/* Favorit Icon */</svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                 <?php endif; ?>
            </div>
        </section>

        <section>
             <div class="flex justify-between items-center mb-4">
                 <h2 class="text-xl font-semibold text-gray-800">Rekomendasi untuk Anda</h2>
                 <div class="flex space-x-2">
                    <button class="carousel-prev-rekomendasi bg-gray-200 hover:bg-gray-300 text-gray-700 p-2 rounded-full disabled:opacity-50">
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">/* Prev Arrow */</svg>
                    </button>
                     <button class="carousel-next-rekomendasi bg-gray-200 hover:bg-gray-300 text-gray-700 p-2 rounded-full">
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">/* Next Arrow */</svg>
                    </button>
                 </div>
            </div>
             <div id="rekomendasi-produk-container" class="flex space-x-4 overflow-x-auto pb-4 no-scrollbar">
                <?php if (empty($rekomendasiProduk)): ?>
                     <p class="text-gray-500 italic">Rekomendasi produk belum tersedia.</p>
                <?php else: ?>
                    <?php foreach ($rekomendasiProduk as $produk): ?>
                    <div class="flex-shrink-0 w-64 bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden group">
                         <img src="<?php echo htmlspecialchars($produk['url_foto'] ?? 'https://via.placeholder.com/300x200/dddddd/ffffff?text=Rekomendasi'); ?>" alt="<?php echo htmlspecialchars($produk['nama_barang']); ?>" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-1 group-hover:text-brand-blue transition-colors truncate" title="<?php echo htmlspecialchars($produk['nama_barang']); ?>"><?php echo htmlspecialchars($produk['nama_barang']); ?></h3>
                            <p class="text-brand-blue font-bold text-sm mb-2">Rp <?php echo number_format($produk['harga_sewa'], 0, ',', '.'); ?>/hari</p>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                 <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 mr-1">/* Location Icon */</svg>
                                    <?php echo htmlspecialchars(getCityFromAddress($produk['alamat_vendor'] ?? 'N/A')); ?>
                                </span>
                                <button class="hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">/* Favorit Icon */</svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <footer class="bg-gray-100 border-t mt-8">
        <div class="container mx-auto px-4 lg:px-6 py-6 text-center text-gray-600 text-sm">
            &copy; <?php echo date("Y"); ?> SIAP Mendaki. All rights reserved.
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dropdown Menu Logic
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            if (userMenuButton && userMenu) {
                 userMenuButton.addEventListener('click', () => userMenu.classList.toggle('hidden'));
                 document.addEventListener('click', (event) => {
                     if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                         userMenu.classList.add('hidden');
                     }
                 });
            }

            // Mobile Menu Logic
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if(mobileMenuButton && mobileMenu) {
                 mobileMenuButton.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
            }

            // Carousel Logic (Contoh)
            function setupCarousel(containerId, prevBtnClass, nextBtnClass) { /* ... Fungsi setupCarousel sama ... */ }
            setupCarousel('produk-terlaris-container', 'carousel-prev-terlaris', 'carousel-next-terlaris');
            setupCarousel('rekomendasi-produk-container', 'carousel-prev-rekomendasi', 'carousel-next-rekomendasi');
        });
    </script>
</body>
</html>