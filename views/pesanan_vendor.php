<?php
// views/pesanan_vendor.php
// Variabel: $namaPengguna, $daftarPesanan, $statusAktif

// Pesan flash
$error_message = $_SESSION['error_message'] ?? null;
$success_message = $_SESSION['success_message'] ?? null;
$info_message = $_SESSION['info_message'] ?? null; // Untuk pesan info fitur belum ada
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);
unset($_SESSION['info_message']);

// Helper function untuk format tanggal Indonesia
function formatTanggalIndonesia($tanggal) {
    if (!$tanggal) return '-';
    $date = date_create($tanggal);
    if (!$date) return '-';
    // Daftar nama bulan
    $bulan = [ 1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember' ];
    // Format: 28 Oktober 2025
    return date_format($date, 'j') . ' ' . $bulan[date_format($date, 'n')] . ' ' . date_format($date, 'Y');
}

// Daftar Status untuk tombol filter
$statuses = ['Menunggu Pembayaran', 'Dikonfirmasi', 'Disewa', 'Dikembalikan', 'Selesai'];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - SIAP Mendaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c4c4c4; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-blue': '#174962',
                        'brand-yellow': '#FFBE00',
                        'brand-gray': '#F8F9FA'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-gray">

    <div id="alert-container" class="fixed top-5 right-5 z-[100] space-y-2"></div>

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-white shadow-lg flex flex-col justify-between" style="border-right: 1px solid #E5E7EB;">
            <div>
                <div class="h-20 flex items-center justify-center border-b p-4">
                     <img src="src/logo-siap.png" alt="Logo SIAP Mendaki" class="w-40">
                </div>
                <nav class="mt-4">
                    <a href="index.php?c=DashboardController&m=index" class="flex items-center px-6 py-3 m-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                        Dashboard
                    </a>
                    <a href="index.php?c=BarangController&m=index" class="flex items-center px-6 py-3 m-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                        Barang
                    </a>
                    <a href="index.php?c=PesananController&m=index" class="flex items-center px-6 py-3 m-2 text-brand-blue bg-brand-yellow rounded-lg font-semibold">
                        <svg class="w-6 h-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" /></svg>
                        Pesanan
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 m-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                        Notifikasi
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 m-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 18H7.5m3-6h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 12H7.5" /></svg>
                        Pengaturan
                    </a>
                </nav>
            </div>
            <div class="p-4 border-t">
                <a href="index.php?c=AuthController&m=logout" class="flex items-center justify-center px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg">
                    <svg class="w-6 h-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" /></svg>
                    Log Out
                </a>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto">
            
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6" style="border-bottom: 1px solid #E5E7EB;">
                <div class="relative">
                    <input type="text" placeholder="Telusuri Pesanan..." class="w-80 px-4 py-2 pl-10 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-yellow">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                    </button>
                    <div class="flex items-center">
                        <span class="w-10 h-10 bg-brand-blue rounded-full flex items-center justify-center text-white font-semibold">
                            <?php echo strtoupper(substr(htmlspecialchars($namaPengguna ?? 'V'), 0, 1)); ?>
                        </span>
                        <span class="ml-2 font-semibold text-gray-700">
                            <?php echo htmlspecialchars($namaPengguna ?? 'Vendor'); ?>
                        </span>
                        <svg class="w-5 h-5 text-gray-500 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </div>
                </div>
            </header>

            <div class="p-6">
                <div class="grid grid-cols-5 gap-4 mb-6">
                    <?php 
                    // Icon untuk setiap status (Contoh menggunakan Heroicons outline)
                    $statusIcons = [
                        'Menunggu Pembayaran' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto mb-1"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>',
                        'Dikonfirmasi' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto mb-1"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>',
                        'Disewa' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto mb-1"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>',
                        'Dikembalikan' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto mb-1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" /></svg>',
                        'Selesai' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto mb-1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>',
                    ];
                    ?>
                    <?php foreach ($statuses as $status) : ?>
                        <?php 
                        $isActive = ($status == $statusAktif);
                        $bgColor = $isActive ? 'bg-brand-yellow' : 'bg-white';
                        $textColor = $isActive ? 'text-brand-blue' : 'text-gray-600';
                        $shadow = $isActive ? 'shadow-lg' : 'shadow-md';
                        $urlStatus = urlencode($status); // Encode spasi dll.
                        ?>
                        <a href="index.php?c=PesananController&m=index&status=<?php echo $urlStatus; ?>" 
                           class="<?php echo $bgColor; ?> <?php echo $textColor; ?> <?php echo $shadow; ?> rounded-lg p-4 text-center hover:shadow-lg transition-shadow duration-200">
                           <?php echo $statusIcons[$status] ?? ''; ?>
                           <span class="text-sm font-semibold"><?php echo $status; ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>

                <h2 class="text-xl font-semibold text-gray-800 mb-4">Pesanan <?php echo htmlspecialchars($statusAktif); ?></h2>

                <div class="bg-white rounded-lg shadow-md overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 font-semibold text-gray-600">No. Pesanan</th>
                                <th class="py-3 px-4 font-semibold text-gray-600">Nama Penyewa</th>
                                <th class="py-3 px-4 font-semibold text-gray-600">Tanggal Sewa</th>
                                <th class="py-3 px-4 font-semibold text-gray-600">Tanggal Kembali</th>
                                <th class="py-3 px-4 font-semibold text-gray-600">Total Biaya</th>
                                <th class="py-3 px-4 font-semibold text-gray-600">Status Pesanan</th>
                                <th class="py-3 px-4 font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (empty($daftarPesanan)): ?>
                                <tr>
                                    <td colspan="7" class="py-4 px-4 text-center text-gray-500">Tidak ada pesanan dengan status "<?php echo htmlspecialchars($statusAktif); ?>".</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($daftarPesanan as $pesanan): ?>
                                    <tr>
                                        <td class="py-3 px-4 font-medium text-gray-700"><?php echo htmlspecialchars($pesanan['kode_pemesanan'] ?? '-'); ?></td>
                                        <td class="py-3 px-4 text-gray-700"><?php echo htmlspecialchars($pesanan['nama_penyewa'] ?? '-'); ?></td>
                                        <td class="py-3 px-4 text-gray-700"><?php echo formatTanggalIndonesia($pesanan['tanggal_sewa_mulai'] ?? null); ?></td>
                                        <td class="py-3 px-4 text-gray-700"><?php echo formatTanggalIndonesia($pesanan['tanggal_sewa_selesai'] ?? null); ?></td>
                                        <td class="py-3 px-4 text-gray-700">Rp<?php echo number_format($pesanan['total_harga'] ?? 0, 0, ',', '.'); ?></td>
                                        <td class="py-3 px-4 text-gray-500 text-xs italic"><?php echo htmlspecialchars($pesanan['status'] ?? '-'); ?></td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                <a href="index.php?c=PesananController&m=detailPesanan&id=<?php echo $pesanan['id_pemesanan']; ?>&from_status=<?php echo urlencode($statusAktif); ?>" 
                                                   class="px-3 py-1 border border-gray-300 text-gray-600 rounded hover:bg-gray-100 text-xs">
                                                   Detail
                                                </a>

                                                <?php switch ($statusAktif):
                                                    case 'Menunggu Pembayaran': ?>
                                                        <a href="index.php?c=PesananController&m=konfirmasiSiap&id=<?php echo $pesanan['id_pemesanan']; ?>" 
                                                           class="px-3 py-1 border border-blue-500 text-blue-600 rounded hover:bg-blue-50 text-xs">
                                                           Konfirmasi Siap
                                                        </a>
                                                        <?php break; ?>

                                                    <?php case 'Dikonfirmasi': ?>
                                                        <a href="index.php?c=PesananController&m=konfirmasiDiambil&id=<?php echo $pesanan['id_pemesanan']; ?>" 
                                                           class="px-3 py-1 border border-green-500 text-green-600 rounded hover:bg-green-50 text-xs">
                                                           Konfirmasi Diambil
                                                        </a>
                                                        <?php break; ?>
                                                    
                                                    <?php case 'Disewa': ?>
                                                         <a href="index.php?c=PesananController&m=konfirmasiKembali&id=<?php echo $pesanan['id_pemesanan']; ?>" 
                                                            class="px-3 py-1 border border-purple-500 text-purple-600 rounded hover:bg-purple-50 text-xs">
                                                            Konfirmasi Kembali
                                                         </a>
                                                        <?php break; ?>

                                                    <?php case 'Dikembalikan': ?>
                                                        <a href="index.php?c=PesananController&m=laporanKerusakan&id=<?php echo $pesanan['id_pemesanan']; ?>" 
                                                           class="px-3 py-1 border border-red-500 text-red-600 rounded hover:bg-red-50 text-xs">
                                                           Laporan Kerusakan
                                                        </a>
                                                        <?php break; ?>
                                                    
                                                    <?php case 'Selesai': ?>
                                                         <a href="index.php?c=PesananController&m=lihatUlasan&id=<?php echo $pesanan['id_pemesanan']; ?>" 
                                                            class="px-3 py-1 border border-yellow-500 text-yellow-600 rounded hover:bg-yellow-50 text-xs">
                                                            Lihat Ulasan
                                                         </a>
                                                        <?php break; ?>
                                                        
                                                <?php endswitch; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div> </main>
    </div>

    <script>
        // Script untuk menampilkan alert (sama seperti di view lain)
        document.addEventListener('DOMContentLoaded', function() {
            function showAlert(message, type = 'success') { // type bisa 'success', 'error', 'info'
                const alertContainer = document.getElementById('alert-container');
                const alertId = 'alert-' + Date.now();
                let alertColor = 'bg-green-100 border-green-400 text-green-700'; // Default success
                let alertTitle = 'Berhasil!';

                if (type === 'error') {
                    alertColor = 'bg-red-100 border-red-400 text-red-700';
                    alertTitle = 'Error!';
                } else if (type === 'info') {
                     alertColor = 'bg-blue-100 border-blue-400 text-blue-700';
                     alertTitle = 'Info';
                }
                
                const alertElement = document.createElement('div');
                alertElement.id = alertId;
                alertElement.className = `border px-4 py-3 rounded-lg relative shadow-md ${alertColor}`;
                alertElement.setAttribute('role', 'alert');
                alertElement.innerHTML = `
                    <strong class="font-bold">${alertTitle}</strong>
                    <span class="block sm:inline">${message}</span>
                `;
                
                alertContainer.appendChild(alertElement);
                
                setTimeout(() => {
                    const alertToRemove = document.getElementById(alertId);
                    if(alertToRemove) { 
                        alertToRemove.style.opacity = '0';
                        alertToRemove.style.transition = 'opacity 0.5s ease';
                        setTimeout(() => alertToRemove.remove(), 500);
                    }
                }, 4000); // Tampilkan selama 4 detik
            }

            // Tampilkan pesan dari PHP (Session)
            <?php if ($error_message): ?>
                showAlert('<?php echo addslashes($error_message); ?>', 'error');
            <?php endif; ?>
            <?php if ($success_message): ?>
                showAlert('<?php echo addslashes($success_message); ?>', 'success');
            <?php endif; ?>
             <?php if ($info_message): ?>
                showAlert('<?php echo addslashes($info_message); ?>', 'info');
            <?php endif; ?>
        });
    </script>
</body>
</html>