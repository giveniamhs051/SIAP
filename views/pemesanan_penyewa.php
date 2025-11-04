<?php
// views/pemesanan_penyewa.php
$namaPenyewa = $namaPengguna ?? 'Penyewa';
$produk = $produk ?? null;
$tgl_mulai = $tgl_mulai ?? date('Y-m-d');
$tgl_selesai = $tgl_selesai ?? date('Y-m-d');
$qty = $qty ?? 1;
$durasi_hari = $durasi_hari ?? 1;
$subtotal = $subtotal ?? 0;

// Helper format tanggal
function formatTanggalSewa($tgl) {
    try {
        $date = date_create($tgl);
        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return date_format($date, 'j') . ' ' . $bulan[date_format($date, 'n')];
    } catch (Exception $e) { return '-'; }
}
$masa_sewa_display = formatTanggalSewa($tgl_mulai) . ' - ' . formatTanggalSewa($tgl_selesai) . ' ' . date('Y', strtotime($tgl_mulai));
// Hitung harga satuan berdasarkan subtotal, durasi, dan qty
$harga_satuan = ($durasi_hari > 0 && $qty > 0) ? ($subtotal / $durasi_hari / $qty) : $produk['harga_sewa'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Alpine.js untuk modal -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F8F9FA; }
        /* Custom radio button style */
        input[type="radio"]:checked + label {
            border-color: #1E3A5F;
            box-shadow: 0 0 0 2px rgba(30, 58, 95, 0.5); /* Biru brand-blue */
        }
        /* Transisi untuk modal */
        [x-cloak] { display: none !important; }
    </style>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'brand-blue': '#1E3A5F', 'brand-yellow': '#FFBE00', 'brand-gray': '#F8F9FA' } } }
        }
    </script>
</head>
<body class="bg-brand-gray" x-data="{ modalOpen: false }">

    <!-- Header (Sama seperti detail_produk.php) -->
    <header class="bg-white shadow-md sticky top-0 z-30">
       <!-- ... (Salin kode <header> lengkap dari views/detail_produk.php) ... -->
    </header>

    <main class="container mx-auto px-4 lg:px-6 py-8 max-w-3xl">
        <!-- Form harus di-submit oleh modal, jadi kita beri ID -->
        <form id="checkoutForm" action="index.php?c=OrderController&m=processOrder" method="POST">
            <!-- Data tersembunyi untuk dikirim -->
            <input type="hidden" name="id_barang" value="<?php echo $produk['id_barang']; ?>">
            <input type="hidden" name="tgl_mulai" value="<?php echo $tgl_mulai; ?>">
            <input type="hidden" name="tgl_selesai" value="<?php echo $tgl_selesai; ?>">
            <input type="hidden" name="total_harga" value="<?php echo $subtotal; ?>">
            <input type="hidden" name="qty" value="<?php echo $qty; ?>">

            <!-- 1. Ringkasan Pesanan -->
            <h1 class="text-2xl font-semibold text-gray-800 mb-4">Ringkasan Pesanan</h1>
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md flex flex-col sm:flex-row items-center gap-5 mb-8">
                <img src="<?php echo htmlspecialchars($produk['url_foto']); ?>" alt="<?php echo htmlspecialchars($produk['nama_barang']); ?>" class="w-full sm:w-48 h-32 object-cover rounded-lg">
                <div class="flex-grow">
                    <h2 class="text-xl font-semibold text-gray-900"><?php echo htmlspecialchars($produk['nama_barang']); ?> (<?php echo $qty; ?>)</h2>
                    <p class="text-gray-600">Rp <?php echo number_format($harga_satuan, 0, ',', '.'); ?>/hari</p>
                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($produk['alamat_vendor'] ? explode(',', $produk['alamat_vendor'])[0] : 'Lokasi'); ?></p>
                </div>
            </div>

            <!-- 2. Detail Pesanan -->
            <h1 class="text-2xl font-semibold text-gray-800 mb-4">Detail Pesanan</h1>
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md mb-8 overflow-x-auto">
                <table class="w-full min-w-max text-left">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-2 px-3 text-sm font-medium text-gray-500">Produk</th>
                            <th class="py-2 px-3 text-sm font-medium text-gray-500">Harga satuan</th>
                            <th class="py-2 px-3 text-sm font-medium text-gray-500">Masa sewa</th>
                            <th class="py-2 px-3 text-sm font-medium text-gray-500">Jumlah</th>
                            <th class="py-2 px-3 text-sm font-medium text-gray-500 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-3 px-3 font-medium text-gray-700"><?php echo htmlspecialchars($produk['nama_barang']); ?></td>
                            <td class="py-3 px-3 text-gray-600">Rp <?php echo number_format($harga_satuan, 0, ',', '.'); ?></td>
                            <td class="py-3 px-3 text-gray-600"><?php echo $masa_sewa_display; ?> (<?php echo $durasi_hari; ?> hari)</td>
                            <td class="py-3 px-3 text-gray-600"><?php echo $qty; ?></td>
                            <td class="py-3 px-3 text-gray-800 font-semibold text-right">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- 3. Metode Pembayaran -->
            <h1 class="text-2xl font-semibold text-gray-800 mb-4">Metode Pembayaran</h1>
            <div class="space-y-4 mb-8">
                <!-- Bank Transfer -->
                <input type="radio" name="metode_pembayaran" value="Bank Transfer" id="bank_transfer" class="sr-only" required>
                <label for="bank_transfer" class="flex items-center gap-4 p-5 bg-white rounded-lg shadow-md border-2 border-transparent cursor-pointer transition-all hover:border-gray-300">
                    <img src="https://placehold.co/40x40/EBF8FF/1E3A5F?text=Bank" alt="Bank" class="w-10 h-10 rounded-full">
                    <span class="text-lg font-medium text-gray-700">Bank Transfer</span>
                </label>
                
                <!-- E-wallet -->
                <input type="radio" name="metode_pembayaran" value="E-wallet" id="e_wallet" class="sr-only">
                <label for="e_wallet" class="flex items-center gap-4 p-5 bg-white rounded-lg shadow-md border-2 border-transparent cursor-pointer transition-all hover:border-gray-300">
                    <img src="https://placehold.co/40x40/FFFBEB/D97706?text=E" alt="E-wallet" class="w-10 h-10 rounded-full">
                    <span class="text-lg font-medium text-gray-700">E-wallet</span>
                </label>

                <!-- COD -->
                <input type="radio" name="metode_pembayaran" value="COD" id="cod" class="sr-only">
                <label for="cod" class="flex items-center gap-4 p-5 bg-white rounded-lg shadow-md border-2 border-transparent cursor-pointer transition-all hover:border-gray-300">
                     <img src="https://placehold.co/40x40/FEE2E2/DC2626?text=COD" alt="COD" class="w-10 h-10 rounded-full">
                    <span class="text-lg font-medium text-gray-700">Cash on Delivery</span>
                </label>
            </div>

            <!-- 4. Tombol Konfirmasi -->
            <div class="flex justify-center mt-6">
                <!-- Tombol ini memicu modal, bukan submit -->
                <button type="button" @click="modalOpen = true" class="w-full max-w-md bg-brand-blue text-white font-semibold py-3 px-6 rounded-lg hover:bg-opacity-90 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue shadow-lg">
                    Konfirmasi dan Bayar
                </button>
            </div>
        </form>
    </main>

    <!-- Modal Konfirmasi -->
    <div x-show="modalOpen" 
         x-cloak
         @keydown.escape.window="modalOpen = false"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-sm p-6 text-center"
             @click.outside="modalOpen = false"
             x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90">
            
            <svg class="w-16 h-16 text-brand-yellow mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>

            <h3 class="text-xl font-semibold text-gray-800 mb-2">Konfirmasi Pesanan</h3>
            <p class="text-gray-600 mb-6">Anda yakin ingin menyewa barang ini?</p>

            <div class="flex justify-center gap-4">
                <button type="button" @click="modalOpen = false" class="flex-1 bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg font-medium hover:bg-gray-300 transition-colors text-sm">
                    Kembali
                </button>
                <!-- Tombol Lanjut ini yang akan submit form -->
                <button type="button" 
                        @click="document.getElementById('checkoutForm').submit()" 
                        class="flex-1 bg-brand-blue text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-opacity-90 transition-opacity text-sm">
                    Lanjut
                </button>
            </div>
        </div>
    </div>
    
</body>
</html>
