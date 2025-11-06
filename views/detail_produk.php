<?php
// views/detail_produk.php
// TATA LETAK BARU: Full-page 2-kolom, Kalender Inline + Logika Jadwal

$namaPenyewa = $namaPengguna ?? 'Penyewa';
$produk = $produk ?? null; // Data ini dikirim dari ProdukController

// Ambil pesan error/sukses dari session
$error_message = $_SESSION['error_message'] ?? null;
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);

// Helper format Rupiah
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// HELPER BARU: Diambil dari dashboard_penyewa.php untuk menampilkan lokasi
function getCityFromAddress($address) {
    if (empty($address)) return 'N/A';
    $parts = explode(',', $address);
    // Ambil bagian terakhir, trim spasi
    $city = trim(end($parts)); 
    if (empty($city)) {
        if (count($parts) >= 2) {
            $city = trim($parts[count($parts) - 2]);
        } else {
            $city = trim($parts[0]); // Fallback
        }
    }
    return $city ?: 'N/A';
}

$navItems = [
    'beranda' => 'Beranda',
    'tentang' => 'Tentang Kami',
    'produk' => 'Produk',
    'lokasi' => 'Lokasi'
];
$currentPage = 'produk'; // Anggap halaman ini bagian dari 'produk'

// Ambil tanggal tersedia dari DB, fallback ke hari ini jika tidak ada
$minDate = $produk['tanggal_tersedia'] ?? date('Y-m-d');

// --- MODIFIKASI BARU UNTUK JADWAL ---
// Ambil data jadwal_booked dari controller dan ubah ke format JSON
$jadwal_booked = $jadwal_booked ?? [];
$js_jadwal_booked = json_encode($jadwal_booked);
// --- AKHIR MODIFIKASI ---

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - <?php echo htmlspecialchars($produk['nama_barang'] ?? 'Produk'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https.cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #F8F9FA; /* Latar belakang abu-abu */
        }
        /* Style untuk Litepicker */
        .litepicker { 
            box-shadow: none; 
            border: 1px solid #e5e7eb; /* border-gray-200 */
            z-index: 10; 
        }
        [x-cloak] { display: none !important; }
         /* Style untuk active nav link */
        .nav-link-active {
            color: #1E3A5F;
            font-weight: 600;
            border-bottom: 2px solid #1E3A5F;
        }
        .nav-link {
            color: #4B5563;
            border-bottom: 2px solid transparent;
        }
        .nav-link:hover { color: #111827; }
    </style>
    <script>
        tailwind.config = {
            theme: { 
                extend: { 
                    colors: { 
                        'brand-blue': '#1E3A5F', 
                        'brand-yellow': '#FFBE00',
                        'brand-gray': '#F8F9FA'
                    } 
                } 
            }
        }
    </script>
</head>
<body class="bg-brand-gray">

    <div x-data="{ show: false, message: '', isSuccess: false }"
         x-init="
            <?php if ($error_message): ?>
                show = true; message = '<?php echo addslashes($error_message); ?>'; isSuccess = false;
                setTimeout(() => show = false, 4000);
            <?php endif; ?>
         "
         x-show="show"
         x-cloak
         x-transition
         class="fixed top-24 right-5 z-[100] w-full max-w-sm rounded-lg shadow-lg"
         :class="isSuccess ? 'bg-green-500' : 'bg-red-500'">
        
         <div class="p-4 flex items-center">
            <div class="flex-shrink-0">
                <svg x-show="!isSuccess" class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white" x-text="message"></p>
            </div>
            <button @click="show = false" class="ml-auto -mr-1.5 -my-1.5 p-1.5 text-white inline-flex">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
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
                    <div x-show="open" 
                         @click.outside="open = false" 
                         x-transition 
                         x-cloak
                         id="user-menu" 
                         class="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Anda</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pesanan Saya</a>
                        <a href="index.php?c=AuthController&m=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Keluar</a>
                    </div>
                </div>
                 <button id="mobile-menu-button" class="md:hidden">...</button>
            </div>
        </nav>
    </header>


    <main class="container mx-auto px-4 lg:px-6 py-8">

        <?php if ($produk): ?>
        
        <div class="lg:flex lg:gap-8" 
             x-data="{ 
                qty: 1, 
                stok: <?php echo $produk['stok_barang'] ?? 1; ?>,
                harga: <?php echo $produk['harga_sewa'] ?? 0; ?>,
                tglMulai: null,
                tglSelesai: null,
                duration: 0,
                picker: null,
                minDateDB: '<?php echo $minDate; ?>',
                
                // --- MODIFIKASI BARU: Ambil data jadwal dari PHP ---
                jadwalBooked: <?php echo $js_jadwal_booked; ?>
             }"
             x-init="
                // Inisialisasi Litepicker
                picker = new Litepicker({
                    element: document.getElementById('datepicker-inline'),
                    inlineMode: true,
                    singleMode: false,
                    allowRepick: true,
                    format: 'DD MMM YYYY',
                    
                    // --- MODIFIKASI BARU: Terapkan Logika Jadwal ---
                    minDate: minDateDB,      // Tanggal mulai tersedia dari vendor
                    lockDates: jadwalBooked, // Tanggal yang sudah dibooking
                    // --- AKHIR MODIFIKASI ---

                    numberOfMonths: 1,
                    buttonText: { apply: 'Terapkan', reset: 'Reset' },
                    onSelected: function(date1, date2) {
                        if (date1 && date2) {
                            this.tglMulai = date1.format('YYYY-MM-DD');
                            this.tglSelesai = date2.format('YYYY-MM-DD');
                            
                            // Hitung durasi (diff->days + 1)
                            let d1 = new Date(date1.dateInstance);
                            let d2 = new Date(date2.dateInstance);
                            let diffTime = Math.abs(d2 - d1);
                            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                            duration = diffDays + 1; // +1 untuk rentang inklusif
                        } else {
                            duration = 0;
                            tglMulai = null;
                            tglSelesai = null;
                        }
                    }
                });
             "
        >

            <div class="lg:w-2/3 w-full">
                <div class="bg-white p-5 sm:p-6 rounded-lg shadow-md">
                    <div class="mb-4">
                        <div class="w-full h-80 bg-brand-gray rounded-lg flex items-center justify-center mb-3 overflow-hidden">
                            <img src="<?php echo htmlspecialchars($produk['url_foto'] ?? 'https://placehold.co/600x400/e2e8f0/cbd5e1?text=Foto+Utama'); ?>" alt="Foto Utama" class="w-full h-full object-cover">
                        </div>
                        <div class="grid grid-cols-5 gap-2">
                            <div class="h-20 bg-brand-gray rounded flex items-center justify-center text-gray-500 overflow-hidden border-2 border-brand-blue">
                                <img src="<?php echo htmlspecialchars($produk['url_foto'] ?? 'https://placehold.co/100/e2e8f0/cbd5e1?text=Foto+1'); ?>" alt="Foto 1" class="w-full h-full object-cover">
                            </div>
                            <div class="h-20 bg-brand-gray rounded flex items-center justify-center text-gray-500 text-xs">Foto 2</div>
                            <div class="h-20 bg-brand-gray rounded flex items-center justify-center text-gray-500 text-xs">Foto 3</div>
                            <div class="h-20 bg-brand-gray rounded flex items-center justify-center text-gray-500 text-xs">Foto 4</div>
                            <div class="h-20 bg-brand-gray rounded flex items-center justify-center text-gray-500 text-xs">Foto 5</div>
                        </div>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($produk['nama_barang']); ?></h1>
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15.27L16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19 10 15.27z"/></svg>
                        <span class="text-sm text-gray-500">(12 Ulasan)</span>
                    </div>

                    <div class="flex items-center gap-4 py-4 border-t border-b my-4">
                        <img src="https://placehold.co/48x48/1E3A5F/FFFFFF?text=<?php echo strtoupper(substr(htmlspecialchars($produk['nama_vendor'] ?? 'V'), 0, 1)); ?>" alt="Logo Vendor" class="w-12 h-12 rounded-full bg-gray-200">
                        <div>
                            <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($produk['nama_vendor'] ?? 'Nama Vendor'); ?></h3>
                            <p class="text-sm text-gray-500 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M8 1.75a.75.75 0 0 1 .75.75v2.5a.75.75 0 0 1-1.5 0V2.5A.75.75 0 0 1 8 1.75ZM6.25 5a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5h-3.5ZM4.5 8a.75.75 0 0 0 0 1.5h7a.75.75 0 0 0 0-1.5h-7ZM3 10.75a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" /></svg>
                                <?php echo htmlspecialchars(getCityFromAddress($produk['alamat_vendor'])); ?>
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Produk</h2>
                        
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Deskripsi Barang</h3>
                            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                                <?php echo htmlspecialchars($produk['deskripsi_barang'] ?? 'Deskripsi tidak tersedia.'); ?>
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="lg:w-1/3 w-full mt-6 lg:mt-0">
                <div class="bg-white p-5 sm:p-6 rounded-lg shadow-md lg:sticky lg:top-24">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Atur Penyewaan</h2>
                    
                    <form action="index.php" method="GET" @submit.prevent="
                        // LANGSUNG ISI INPUT (biarpun kosong/null)
                        document.getElementById('form_qty').value = qty;
                        document.getElementById('form_tgl_mulai').value = tglMulai;
                        document.getElementById('form_tgl_selesai').value = tglSelesai;

                        // LANGSUNG SUBMIT TANPA IF/ELSE
                        $event.target.submit();
                    ">
                        <input type="hidden" name="c" value="OrderController">
                        <input type="hidden" name="m" value="checkoutView">
                        <input type="hidden" name="id" value="<?php echo $produk['id_barang']; ?>">
                        <input type="hidden" name="qty" id="form_qty">
                        <input type="hidden" name="tgl_mulai" id="form_tgl_mulai">
                        <input type="hidden" name="tgl_selesai" id="form_tgl_selesai">
                        
                        <p class="text-3xl font-bold text-brand-blue mb-4">
                            <?php echo formatRupiah($produk['harga_sewa']); ?> 
                            <span class="text-lg font-normal text-gray-600">/ hari</span>
                        </p>
                        
                        <div class="mb-4">
                            <h3 class="font-semibold text-gray-800 mb-2">Jumlah</h3>
                            <div class="flex items-center gap-3">
                                <button 
                                    type="button" 
                                    @click="qty = Math.max(1, qty - 1)" 
                                    class="w-8 h-8 bg-gray-200 text-gray-700 rounded-md font-bold text-lg flex items-center justify-center hover:bg-gray-300"
                                    :disabled="qty <= 1">
                                    -
                                </button>
                                <span class="text-lg font-semibold text-gray-900 w-8 text-center" x-text="qty"></span>
                                <button 
                                    type="button" 
                                    @click="qty = Math.min(stok, qty + 1)" 
                                    class="w-8 h-8 bg-gray-200 text-gray-700 rounded-md font-bold text-lg flex items-center justify-center hover:bg-gray-300"
                                    :disabled="qty >= stok">
                                    +
                                </button>
                                <span class="text-sm text-gray-500">Stok: <?php echo $produk['stok_barang']; ?></span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h3 class="font-semibold text-gray-800 mb-2">Pilih Tanggal Sewa</h3>
                            <div id="datepicker-inline"></div>
                        </div>


                        <div class="flex justify-between items-center my-5 py-4 border-t">
                            <span class="text-gray-600 text-base">Subtotal</span>
                            <span class="text-xl font-bold text-brand-blue" 
                                  x-text="duration > 0 ? 'Rp ' + (qty * harga * duration).toLocaleString('id-ID') : 'Pilih tanggal'">
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 -mt-6 mb-4 text-right" x-show="duration > 0" x-text="`(${qty} brg x ${duration} hari)`"></p>

                        <div class="grid grid-cols-1 gap-3">
                            <button 
                                type="submit" 
                                class="w-full bg-brand-yellow text-brand-blue font-semibold py-3 px-4 rounded-lg hover:opacity-90 transition-colors duration-300 focus:outline-none">
                                Sewa Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div> <?php else: ?>
            <div class="text-center py-20">
                <h1 class="text-2xl font-bold text-gray-700">Produk Tidak Ditemukan</h1>
                <p class="text-gray-500 mb-6">Produk yang Anda cari mungkin telah dihapus atau tidak tersedia.</p>
                <a href="index.php?c=DashboardController&m=index" class="bg-brand-blue text-white font-semibold py-2 px-5 rounded-lg hover:bg-opacity-90">
                    Kembali ke Beranda
                </a>
            </div>
        <?php endif; ?>

    </main>
    
    <script>
        // Script untuk dropdown header
        document.addEventListener('DOMContentLoaded', function () {
            // Logic untuk Alpine.js dropdown (jika tidak menggunakan Alpine)
             const userMenuButton = document.getElementById('user-menu-button');
             const userMenu = document.getElementById('user-menu');
             // Cek jika x-data tidak dipakai
             if (userMenuButton && userMenu && typeof Alpine === 'undefined') { 
                 userMenuButton.addEventListener('click', () => userMenu.classList.toggle('hidden'));
                 document.addEventListener('click', (event) => {
                     if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                         userMenu.classList.add('hidden');
                     }
                 });
             }
        });
    </script>
</body>
</html>
