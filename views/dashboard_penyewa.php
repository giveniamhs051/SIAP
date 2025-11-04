<?php
// views/dashboard_penyewa.php
$namaPenyewa = $namaPengguna ?? 'Penyewa';
$currentPage = $currentPage ?? 'beranda';

// Data untuk halaman 'beranda'
$produkTerlaris = $produkTerlaris ?? [];
$rekomendasiProduk = $rekomendasiProduk ?? [];

// Data untuk halaman 'produk'
$semuaProduk = $semuaProduk ?? [];

// Helper untuk mengambil nama kota dari alamat (contoh sederhana)
function getCityFromAddress($address) {
// ... (fungsi getCityFromAddress tetap sama) ...
    if (empty($address)) return 'N/A';
    $parts = explode(',', $address);
    // Ambil bagian terakhir, trim spasi
    $city = trim(end($parts)); 
    if (empty($city)) {
        // Jika bagian terakhir kosong (misal "Jakarta,"), ambil sebelumnya
        if (count($parts) >= 2) {
            $city = trim($parts[count($parts) - 2]);
        } else {
            $city = trim($parts[0]); // Fallback
        }
    }
    return $city ?: 'N/A';
}

// Helper untuk format Rupiah
function formatRupiah($angka) {
// ... (fungsi formatRupiah tetap sama) ...
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Daftar Halaman
$navItems = [
// ... (navItems tetap sama) ...
    'beranda' => 'Beranda',
    'tentang' => 'Tentang Kami',
    'produk' => 'Produk',
    'lokasi' => 'Lokasi'
];

// === TAMBAHAN UNTUK FILTER ===
$lokasiMalang = ['Lowokwaru', 'Klojen', 'Blimbing', 'Kedungkandang', 'Sukun'];
// Ambil nilai filter yang sedang aktif dari URL
$lokasiAktif = $_GET['lokasi'] ?? null;
$queryAktif = $_GET['q'] ?? null;
// =============================

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penyewa - SIAP Mendaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Script Alpine.js untuk dropdown interaktif -->
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <style>
/* ... (style tetap sama) ... */
        body { font-family: 'Poppins', sans-serif; background-color: #FFFFFF; }
        /* Style untuk scrollbar horizontal (carousel) */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Style untuk active nav link */
        .nav-link-active {
            color: #1E3A5F; /* Ganti dengan warna brand-blue Anda */
            font-weight: 600;
            border-bottom: 2px solid #1E3A5F; /* Ganti dengan warna brand-blue Anda */
        }
        .nav-link {
            color: #4B5563; /* text-gray-600 */
            border-bottom: 2px solid transparent;
        }
        .nav-link:hover {
            color: #111827; /* text-gray-900 */
        }
    </style>
    <script>
/* ... (tailwind.config tetap sama) ... */
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-blue': '#1E3A5F', // Biru tua utama (dari gambar login)
                        'brand-yellow': '#FFBE00', // Kuning (dari gambar login)
                        'brand-gray': '#F8F9FA', // Latar belakang abu-abu
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white">

    <!-- HEADER / NAVBAR -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-4 lg:px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="index.php?c=DashboardController&m=index" class="flex items-center">
                 <img src="src/logo-siap.png" class="h-10 mr-3" alt="Logo SIAP Mendaki" />
                 <!-- <span class="text-xl font-bold text-brand-blue">SIAP Mendaki</span> -->
            </a>

            <!-- Menu Desktop -->
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

            <!-- Ikon Kanan & Dropdown Profil -->
            <div class="flex items-center space-x-4">
                <button class="text-gray-500 hover:text-brand-blue relative p-1">
                    <span class="sr-only">Notifikasi</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <!-- <span class="absolute top-0 right-0 flex h-3 w-3"><span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 ring-1 ring-white"></span></span> -->
                 </button>
                 <button class="text-gray-500 hover:text-brand-blue p-1">
                     <span class="sr-only">Favorit</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                 </button>
                 <button class="text-gray-500 hover:text-brand-blue p-1">
                    <span class="sr-only">Keranjang</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                 </button>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button id="user-menu-button" class="flex items-center text-sm font-medium text-gray-700 hover:text-brand-blue focus:outline-none">
                         <span class="w-9 h-9 bg-brand-blue rounded-full flex items-center justify-center text-white text-sm font-semibold mr-2">
                            <?php echo strtoupper(substr(htmlspecialchars($namaPenyewa), 0, 1)); ?>
                        </span>
                        Halo, <?php echo explode(' ', htmlspecialchars($namaPenyewa))[0]; ?>
                        <svg class="ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Anda</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pesanan Saya</a>
                        <a href="index.php?c=AuthController&m=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Keluar</a>
                    </div>
                </div>

                 <!-- Tombol Mobile Menu -->
                 <button id="mobile-menu-button" class="md:hidden text-gray-500 hover:text-brand-blue focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </nav>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg">
            <ul class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                 <?php foreach ($navItems as $pageKey => $pageName): ?>
                <li>
                    <a href="index.php?c=DashboardController&m=index&page=<?php echo $pageKey; ?>" 
                       class="block px-3 py-2 rounded-md text-base font-medium <?php echo ($currentPage == $pageKey) ? 'text-brand-blue bg-gray-100 font-semibold' : 'text-gray-600 hover:bg-gray-50'; ?>">
                       <?php echo $pageName; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main class="container mx-auto px-4 lg:px-6 py-6">

        <?php
        // PHP Switch untuk ganti konten halaman
        switch ($currentPage):

            // ===================================
            // === HALAMAN BERANDA (DEFAULT) ===
            // ===================================
            case 'beranda':
        ?>
            <!-- Search Bar (sesuai gambar) -->
            <!-- MODIFIKASI: Dibungkus dengan <form> -->
            <form action="index.php" method="GET" class="mb-6">
                <!-- Data tersembunyi untuk routing -->
                <input type="hidden" name="c" value="DashboardController">
                <input type="hidden" name="m" value="index">
                <input type="hidden" name="page" value="beranda">

                <input type="hidden" name="lokasi" value="<?php echo htmlspecialchars($lokasiAktif ?? ''); ?>">
            
                <div class="flex items-center gap-3">
                    <div class="relative flex-grow">
                         <!-- MODIFIKASI: Tambah name="q" dan value="$queryAktif" -->
                         <input type="text" name="q" value="<?php echo htmlspecialchars($queryAktif ?? ''); ?>" placeholder="Cari alat pendakian (Contoh: Tenda, Carrier)" class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-blue text-sm">
                         <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                         </svg>
                    </div>
                    
                    <!-- Filter Button & Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <!-- Tombol Filter - MODIFIKASI: type="button" agar tidak submit form -->
                        <button type="button" @click="open = !open" class="p-3 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 18H7.5m3-6h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 12H7.5" />
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-72 sm:w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-20"
                             style="display: none;">
                            
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900">Filter Lokasi</h3>
                            </div>
                            
                            <hr class="border-gray-200">
                            
                            <div class="p-4">
                                <h4 class="font-semibold text-gray-800 mb-3">Malang</h4>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($lokasiMalang as $lokasi): ?>
                                        <?php
                                        $isActive = ($lokasiAktif == $lokasi);
                                        $class = $isActive 
                                            ? 'bg-brand-blue text-white' 
                                            : 'bg-gray-200 text-gray-700 hover:bg-gray-300';
                                        ?>
                                        <!-- MODIFIKASI: Ubah <a> jadi <button type="submit"> -->
                                        <button type="submit" name="lokasi" value="<?php echo $lokasi; ?>"
                                           class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors <?php echo $class; ?>">
                                            <?php echo $lokasi; ?>
                                        </button>
                                    <?php endforeach; ?>
                                    
                                    <!-- Tombol Reset -->
                                    <?php if ($lokasiAktif): ?>
                                    <!-- MODIFIKASI: Ubah <a> jadi <button type="submit"> -->
                                    <button type="submit" name="lokasi" value=""
                                       class="px-4 py-1.5 rounded-full text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                                        Reset
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Filter -->
                </div>
            </form>
            <!-- End Search/Filter Form -->
            
            <!-- Hero Banner -->
            <div class="mb-8 rounded-lg overflow-hidden relative h-64 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?q=80&w=2070&auto=format&fit=crop');">
                <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-center items-start p-8 md:p-12">
                    <h1 class="text-white text-3xl md:text-4xl font-bold mb-2 leading-tight">DISKON SPESIAL!</h1>
                    <p class="text-white text-lg md:text-xl">Sewa Tenda Premium Mulai RP. 50.000/Hari</p>
                </div>
            </div>

            <!-- Bagian Produk Terlaris -->
            <section class="mb-8">
<!-- ... (Rest of Produk Terlaris section remains the same) ... -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Produk Terlaris</h2>
                    <div class="flex space-x-2">
                        <button class="carousel-prev-terlaris bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-full disabled:opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                        </button>
                         <button class="carousel-next-terlaris bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                        </button>
                     </div>
                </div>
                <div id="produk-terlaris-container" class="flex space-x-4 overflow-x-auto pb-4 no-scrollbar scroll-smooth">
                    <?php if (empty($produkTerlaris)): ?>
                        <p class="text-gray-500 italic">Produk terlaris tidak ditemukan (filter aktif?).</p>
                    <?php else: ?>
                        <?php foreach ($produkTerlaris as $produk): ?>
                        <div class="flex-shrink-0 w-64">
                            <a href="index.php?c=ProdukController&m=detail&id=<?php echo $produk['id_barang']; ?>" class="block bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden group">
                                <img src="<?php echo htmlspecialchars($produk['url_foto'] ?? 'https://placehold.co/300x200/e2e8f0/cbd5e1?text=SIAP'); ?>" alt="<?php echo htmlspecialchars($produk['nama_barang']); ?>" class="w-full h-40 object-cover">
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-1 group-hover:text-brand-blue transition-colors truncate" title="<?php echo htmlspecialchars($produk['nama_barang']); ?>"><?php echo htmlspecialchars($produk['nama_barang']); ?></h3>
                                    <p class="text-brand-blue font-bold text-sm mb-2"><?php echo formatRupiah($produk['harga_sewa']); ?>/hari</p>
                                    <div class="flex justify-between items-center text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 mr-1"><path fill-rule="evenodd" d="M8 1.75a.75.75 0 0 1 .75.75v2.5a.75.75 0 0 1-1.5 0V2.5A.75.75 0 0 1 8 1.75ZM6.25 5a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5h-3.5ZM4.5 8a.75.75 0 0 0 0 1.5h7a.75.75 0 0 0 0-1.5h-7ZM3 10.75a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" /></svg>
                                            <?php echo htmlspecialchars(getCityFromAddress($produk['alamat_vendor'])); ?>
                                        </span>
                                        <button class="hover:text-red-500 p-1 -m-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                     <?php endif; ?>
                </div>
            </section>

            <!-- Bagian Rekomendasi -->
            <section>
<!-- ... (Rest of Rekomendasi section remains the same) ... -->
                 <div class="flex justify-between items-center mb-4">
                     <h2 class="text-xl font-semibold text-gray-800">Rekomendasi untuk Anda</h2>
                     <div class="flex space-x-2">
                        <button class="carousel-prev-rekomendasi bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-full disabled:opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                        </button>
                         <button class="carousel-next-rekomendasi bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                        </button>
                     </div>
                </div>
                 <div id="rekomendasi-produk-container" class="flex space-x-4 overflow-x-auto pb-4 no-scrollbar scroll-smooth">
                    <?php if (empty($rekomendasiProduk)): ?>
                         <p class="text-gray-500 italic">Rekomendasi produk tidak ditemukan (filter aktif?).</p>
                    <?php else: ?>
                        <?php foreach ($rekomendasiProduk as $produk): ?>
                        <div class="flex-shrink-0 w-64">
                            <a href="index.php?c=ProdukController&m=detail&id=<?php echo $produk['id_barang']; ?>" class="block bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden group">
                                 <img src="<?php echo htmlspecialchars($produk['url_foto'] ?? 'https://placehold.co/300x200/e2e8f0/cbd5e1?text=SIAP'); ?>" alt="<?php echo htmlspecialchars($produk['nama_barang']); ?>" class="w-full h-40 object-cover">
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-1 group-hover:text-brand-blue transition-colors truncate" title="<?php echo htmlspecialchars($produk['nama_barang']); ?>"><?php echo htmlspecialchars($produk['nama_barang']); ?></h3>
                                    <p class="text-brand-blue font-bold text-sm mb-2"><?php echo formatRupiah($produk['harga_sewa']); ?>/hari</p>
                                    <div class="flex justify-between items-center text-xs text-gray-500">
                                         <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 mr-1"><path fill-rule="evenodd" d="M8 1.75a.75.75 0 0 1 .75.75v2.5a.75.75 0 0 1-1.5 0V2.5A.75.75 0 0 1 8 1.75ZM6.25 5a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5h-3.5ZM4.5 8a.75.75 0 0 0 0 1.5h7a.75.75 0 0 0 0-1.5h-7ZM3 10.75a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" /></svg>
                                            <?php echo htmlspecialchars(getCityFromAddress($produk['alamat_vendor'])); ?>
                                        </span>
                                        <button class="hover:text-red-500 p-1 -m-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        
        <?php
            break; // Akhir case 'beranda'

            // ===================================
            // === HALAMAN TENTANG KAMI ===
            // ===================================
            case 'tentang':
        ?>
            <div class="bg-white rounded-lg shadow-md p-8 lg:p-12">
                <h1 class="text-3xl font-bold text-brand-blue mb-4">Tentang SIAP Mendaki</h1>
                <div class="flex flex-col lg:flex-row gap-8 items-center">
                    <div class="lg:w-1/2 space-y-4 text-gray-700 leading-relaxed">
                        <p><strong>SIAP Mendaki</strong> adalah platform digital terdepan yang merevolusi cara para petualang mempersiapkan pendakian mereka. Kami adalah jembatan antara vendor penyedia alat pendakian berkualitas dengan para penyewa yang siap menjelajahi alam.</p>
                        <p>Misi kami adalah mempermudah setiap pendakian dengan menyediakan akses yang cepat, aman, dan terpercaya ke berbagai perlengkapan, mulai dari tenda, carrier, hingga alat masak. Kami percaya bahwa petualangan besar dimulai dari persiapan yang mudah.</p>
                        <p>Dengan SIAP Mendaki, Anda tidak only menyewa alat, tapi juga mendapatkan ketenangan pikiran. Setiap vendor terverifikasi, setiap transaksi aman, dan setiap perlengkapan siap mengantar Anda ke puncak impian.</p>
                    </div>
                    <div class="lg:w-1/2">
                        <!-- GAMBAR LOKAL UNTUK TENTANG KAMI -->
                        <img src="src/tentang-kami.jpg" alt="Petualang melihat pemandangan gunung" class="rounded-lg shadow-lg object-cover w-full h-80" onerror="this.src='https://placehold.co/600x400/1E3A5F/FFFFFF?text=Tentang+Kami'">
                    </div>
                </div>
            </div>
        <?php
            break; // Akhir case 'tentang'

            // ===================================
            // === HALAMAN PRODUK ===
            // ===================================
            case 'produk':
        ?>
            <div class="bg-white">
                <!-- Form Pencarian untuk Halaman Produk -->
                <form action="index.php" method="GET" class="mb-6">
                    <input type="hidden" name="c" value="DashboardController">
                    <input type="hidden" name="m" value="index">
                    <input type="hidden" name="page" value="produk">
                    
                    <h1 class="text-3xl font-bold text-brand-blue mb-6">Semua Produk</h1>
                    
                    <div class="flex items-center gap-3">
                        <div class="relative flex-grow">
                             <input type="text" name="q" value="<?php echo htmlspecialchars($queryAktif ?? ''); ?>" placeholder="Cari di semua produk..." class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-blue text-sm">
                             <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                             </svg>
                        </div>
                        <button type="submit" class="p-3 bg-brand-blue text-white rounded-lg hover:bg-opacity-90">
                            Cari
                        </button>
                    </div>
                </form>
                <!-- End Form Pencarian -->

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php if (empty($semuaProduk)): ?>
                        <p class="text-gray-500 italic col-span-full">Tidak ada produk yang cocok dengan pencarian Anda.</p>
                    <?php else: ?>
                        <?php foreach ($semuaProduk as $produk): ?>
                        <!-- Card Produk (Template sama seperti di beranda) -->
                        <a href="index.php?c=ProdukController&m=detail&id=<?php echo $produk['id_barang']; ?>" class="block bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden group">
                            <img src="<?php echo htmlspecialchars($produk['url_foto'] ?? 'https://placehold.co/300x200/e2e8f0/cbd5e1?text=SIAP'); ?>" alt="<?php echo htmlspecialchars($produk['nama_barang']); ?>" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-1 group-hover:text-brand-blue transition-colors truncate" title="<?php echo htmlspecialchars($produk['nama_barang']); ?>"><?php echo htmlspecialchars($produk['nama_barang']); ?></h3>
                                <p class="text-brand-blue font-bold text-sm mb-2"><?php echo formatRupiah($produk['harga_sewa']); ?>/hari</p>
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 mr-1"><path fill-rule="evenodd" d="M8 1.75a.75.75 0 0 1 .75.75v2.5a.75.75 0 0 1-1.5 0V2.5A.75.75 0 0 1 8 1.75ZM6.25 5a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5h-3.5ZM4.5 8a.75.75 0 0 0 0 1.5h7a.75.75 0 0 0 0-1.5h-7ZM3 10.75a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" /></svg>
                                        <?php echo htmlspecialchars(getCityFromAddress($produk['alamat_vendor'])); ?>
                                    </span>
                                    <button class="hover:text-red-500 p-1 -m-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                                    </button>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php
            break; // Akhir case 'produk'

            // ===================================
            // === HALAMAN LOKASI (MODIFIED) ===
            // ===================================
            case 'lokasi':
        ?>
            <div class="bg-white rounded-lg shadow-md p-8 lg:p-12">
                <h1 class="text-3xl font-bold text-brand-blue mb-6">Lokasi Vendor Kami</h1>
                <p class="text-gray-700 leading-relaxed mb-6">Saat ini, seluruh vendor kami beroperasi dan terpusat di wilayah <strong>Kota Malang</strong>. Temukan kami di area berikut:</p>
                
                <!-- Placeholder Peta -->
                <div class="bg-gray-200 w-full h-96 rounded-lg flex items-center justify-center mb-6 overflow-hidden">
                    <!-- GAMBAR LOKAL UNTUK LOKASI -->
                    <img src="src/lokasi-malang.jpg" alt="Tugu Balai Kota Malang" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/800x400/1E3A5F/FFFFFF?text=Lokasi+Malang'">
                </div>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Area Jangkauan di Kota Malang</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                    <div class="bg-gray-100 p-4 rounded-lg text-center font-medium text-gray-700">Lowokwaru</div>
                    <div class="bg-gray-100 p-4 rounded-lg text-center font-medium text-gray-700">Blimbing</div>
                    <div class="bg-gray-100 p-4 rounded-lg text-center font-medium text-gray-700">Sukun</div>
                    <div class="bg-gray-100 p-4 rounded-lg text-center font-medium text-gray-700">Klojen</div>
                    <div class="bg-gray-100 p-4 rounded-lg text-center font-medium text-gray-700">Kedungkandang</div>
                </div>
            </div>
        <?php
            break; // Akhir case 'lokasi'
        
        endswitch; // Akhir dari switch
        ?>

    </main>

    <!-- FOOTER -->
    <footer class="bg-brand-gray border-t mt-12">
<!-- ... (Footer section remains the same) ... -->
        <div class="container mx-auto px-4 lg:px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-gray-600">
                <div>
                    <h3 class="text-lg font-semibold text-brand-blue mb-2">SIAP Mendaki</h3>
                    <p class="text-sm">Platform #1 untuk sewa perlengkapan pendakian yang aman, mudah, dan terpercaya.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-brand-blue mb-2">Tautan Cepat</h3>
                    <ul class="space-y-1 text-sm">
                        <li><a href="index.php?c=DashboardController&m=index&page=tentang" class="hover:underline">Tentang Kami</a></li>
                        <li><a href="index.php?c=DashboardController&m=index&page=produk" class="hover:underline">Semua Produk</a></li>
                        <li><a href="#" class="hover:underline">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-brand-blue mb-2">Hubungi Kami</h3>
                    <ul class="space-y-1 text-sm">
                        <li>Email: support@siapmendaki.com</li>
                        <li>Telepon: (021) 123-4567</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-300 pt-6 text-center text-sm text-gray-500">
                &copy; <?php echo date("Y"); ?> SIAP Mendaki. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // === Dropdown Menu Logic ===
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

            // === Mobile Menu Logic ===
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if(mobileMenuButton && mobileMenu) {
                 mobileMenuButton.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
            }

            // === Carousel Logic ===
            function setupCarousel(containerId, prevBtnClass, nextBtnClass) {
                const container = document.getElementById(containerId);
                const prevBtn = document.querySelector('.' + prevBtnClass);
                const nextBtn = document.querySelector('.' + nextBtnClass);

                if (!container || !prevBtn || !nextBtn) return;

                const scrollAmount = container.clientWidth / 2; // Scroll setengah lebar container

                prevBtn.addEventListener('click', () => {
                    container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                });

                nextBtn.addEventListener('click', () => {
                    container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                });

                // Cek status tombol (disabled jika di awal/akhir)
                const checkButtons = () => {
                    if (!container) return; // Tambahkan penjaga jika container tidak ada
                    prevBtn.disabled = container.scrollLeft === 0;
                    nextBtn.disabled = container.scrollLeft + container.clientWidth >= container.scrollWidth - 10; // Toleransi 10px
                };
                
                container.addEventListener('scroll', checkButtons);
                checkButtons(); // Cek saat awal load
            }
            
            // Setup untuk kedua carousel
            setupCarousel('produk-terlaris-container', 'carousel-prev-terlaris', 'carousel-next-terlaris');
            setupCarousel('rekomendasi-produk-container', 'carousel-prev-rekomendasi', 'carousel-next-rekomendasi');
        });
    </script>
</body>
</html>

