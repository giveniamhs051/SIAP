<?php
// views/detail_produk.php
// Menggunakan UI dari "Detail Barang.png"
// MODIFIKASI: Dibuat full-screen di mobile, dan card di desktop

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
    
    <!-- CSS untuk Litepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>
    <!-- Alpine.js untuk notifikasi dan counter -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <!-- Script Litepicker -->
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

    <style>
        /* MODIFIKASI: Latar belakang body hanya biru di sm ke atas */
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #FFFFFF; /* Putih di mobile */
        }
        @media (min-width: 640px) {
            body {
                background-color: #1E3A5F; /* Biru di desktop */
            }
        }
        /* Style untuk Litepicker */
        .litepicker { 
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1); 
            border: none; z-index: 99; 
        }
        [x-cloak] { display: none !important; }
    </style>
    <script>
        tailwind.config = {
            theme: { 
                extend: { 
                    colors: { 
                        'brand-blue': '#1E3A5F', 
                        'brand-yellow': '#FFBE00', // Warna tombol Sewa Sekarang
                        'brand-gray': '#F8F9FA'  // Background Foto Utama
                    } 
                } 
            }
        }
    </script>
</head>
<!-- 
  MODIFIKASI <body>:
  - Menghapus flex, items-center, justify-center, min-h-screen, p-4
  - Menambahkannya kembali HANYA untuk layar sm (tablet) ke atas.
-->
<body class="sm:flex sm:items-center sm:justify-center sm:min-h-screen sm:p-4">

    <!-- === NOTIFIKASI ALERT === -->
    <div x-data="{ show: false, message: '', isSuccess: false }"
         x-init="
            <?php if ($error_message): ?>
                show = true; message = '<?php echo addslashes($error_message); ?>'; isSuccess = false;
                setTimeout(() => show = false, 4000);
            <?php endif; ?>
         "
         x-show="show"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-full"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-full"
         class="fixed top-5 right-5 z-[100] w-full max-w-sm rounded-lg shadow-lg"
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
    <!-- === AKHIR NOTIFIKASI ALERT === -->

    <?php if ($produk): ?>
    <!-- 
      MODIFIKASI Card Detail Barang:
      - Dibuat full-screen (min-h-screen) di mobile
      - Dibuat jadi card (sm:rounded-2xl, sm:shadow-2xl, sm:max-w-md, sm:min-h-0) di desktop
    -->
    <div 
        class="bg-white w-full min-h-screen p-6 relative sm:rounded-2xl sm:shadow-2xl sm:min-h-0 sm:max-w-md" 
        x-data="{ 
            qty: 1, 
            stok: <?php echo $produk['stok_barang'] ?? 1; ?>,
            tglMulai: null,
            tglSelesai: null,
            picker: null
        }"
        x-init="
            // Inisialisasi Litepicker
            picker = new Litepicker({
                element: document.getElementById('btn-lihat-tanggal'),
                singleMode: false,
                allowRepick: true,
                format: 'DD MMM YYYY',
                minDate: new Date(),
                numberOfMonths: 1,
                buttonText: { apply: 'Terapkan', reset: 'Reset' },
                onSelected: function(date1, date2) {
                    if (date1 && date2) {
                        tglMulai = date1.format('YYYY-MM-DD');
                        tglSelesai = date2.format('YYYY-MM-DD');
                        // Update teks tombol
                        document.getElementById('btn-lihat-tanggal').innerText = date1.format('DD MMM') + ' - ' + date2.format('DD MMM YYYY');
                    }
                }
            });
        "
    >
        <!-- Tombol Close (Kembali ke halaman dashboard) -->
        <a href="index.php?c=DashboardController&m=index" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </a>

        <!-- Galeri Foto -->
        <div class="mb-4">
            <!-- Foto Utama -->
            <div class="w-full h-64 bg-brand-gray rounded-lg flex items-center justify-center mb-3">
                 <img src="<?php echo htmlspecialchars($produk['url_foto'] ?? 'https://placehold.co/400x300/e2e8f0/cbd5e1?text=Foto+Utama'); ?>" alt="Foto Utama" class="w-full h-full object-cover rounded-lg">
            </div>
            <!-- Thumbnail -->
            <div class="grid grid-cols-4 gap-2">
                <div class="h-16 bg-brand-gray rounded flex items-center justify-center text-gray-500">
                    <img src="<?php echo htmlspecialchars($produk['url_foto'] ?? 'https://placehold.co/100/e2e8f0/cbd5e1?text=Foto+1'); ?>" alt="Foto 1" class="w-full h-full object-cover rounded">
                </div>
                <div class="h-16 bg-brand-gray rounded flex items-center justify-center text-gray-500 text-xs">Foto 2</div>
                <div class="h-16 bg-brand-gray rounded flex items-center justify-center text-gray-500 text-xs">Foto 3</div>
                <div class="h-16 bg-brand-gray rounded flex items-center justify-center text-gray-500 text-xs">Foto 4</div>
            </div>
        </div>

        <!-- Info Produk -->
        <h1 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($produk['nama_barang']); ?></h1>
        <div class="flex items-center gap-2 mt-1 mb-2">
            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15.27L16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19 10 15.27z"/></svg>
            <span class="text-sm text-gray-500">(12 Ulasan)</span>
        </div>

        <div class="flex justify-between items-center mb-4">
            <p class="text-2xl font-bold text-brand-blue">
                <?php echo formatRupiah($produk['harga_sewa']); ?> 
                <span class="text-lg font-normal text-gray-600">/ hari</span>
            </p>
            <button class="text-gray-400 hover:text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
            </button>
        </div>

        <!-- Deskripsi -->
        <div class="mb-4">
            <h2 class="font-semibold text-gray-800 mb-1">Deskripsi Barang</h2>
            <p class="text-sm text-gray-600 leading-relaxed">
                <?php echo htmlspecialchars($produk['deskripsi_barang'] ?? 'Deskripsi tidak tersedia.'); ?>
            </p>
        </div>

        <!-- Jumlah -->
        <div class="mb-6">
            <h2 class="font-semibold text-gray-800 mb-2">Jumlah</h2>
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
                <span class="text-sm text-gray-500">Stok tersedia: <?php echo $produk['stok_barang']; ?></span>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <!-- Form ini akan mengirim data ke OrderController -->
        <form action="index.php" method="GET" @submit.prevent="
            if (!tglMulai) {
                // Jika tanggal belum dipilih, tampilkan paksa kalender
                picker.show(); 
            } else {
                // Jika tanggal SUDAH dipilih, isi input tersembunyi dan kirim
                document.getElementById('form_qty').value = qty;
                document.getElementById('form_tgl_mulai').value = tglMulai;
                document.getElementById('form_tgl_selesai').value = tglSelesai;
                $event.target.submit(); // Lanjutkan submit form
            }
        ">
            <!-- Hidden inputs untuk dikirim ke controller -->
            <input type="hidden" name="c" value="OrderController">
            <input type="hidden" name="m" value="checkoutView">
            <input type="hidden" name="id" value="<?php echo $produk['id_barang']; ?>">
            <input type="hidden" name="qty" id="form_qty">
            <input type="hidden" name="tgl_mulai" id="form_tgl_mulai">
            <input type="hidden" name="tgl_selesai" id="form_tgl_selesai">
            
            <div class="grid grid-cols-2 gap-3">
                <button 
                    type="button"
                    id="btn-lihat-tanggal"
                    class="w-full bg-brand-blue text-white font-semibold py-3 px-4 rounded-lg hover:bg-opacity-90 transition-colors duration-300 focus:outline-none">
                    Lihat Tanggal Tersedia
                </button>
                <button 
                    type="submit" 
                    class="w-full bg-brand-yellow text-brand-blue font-semibold py-3 px-4 rounded-lg hover:opacity-90 transition-colors duration-300 focus:outline-none">
                    Sewa Sekarang
                </button>
            </div>
        </form>

    </div>
    <?php else: ?>
        <p class="text-center text-white">Produk tidak ditemukan.</p>
    <?php endif; ?>

</body>
</html>
