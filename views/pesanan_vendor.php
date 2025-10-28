<?php
// views/pesanan_vendor.php
$namaPengguna = $namaPengguna ?? 'Vendor';
$daftarPesanan = $daftarPesanan ?? [];
$statusAktif = $statusAktif ?? 'Dikonfirmasi';
// Pesan flash
$error_message = $_SESSION['error_message'] ?? null;
$success_message = $_SESSION['success_message'] ?? null;
$info_message = $_SESSION['info_message'] ?? null;
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);
unset($_SESSION['info_message']);

// Helper function format tanggal
function formatTanggalIndonesia($tanggal) { 
    if (!$tanggal || $tanggal === '0000-00-00') return '-';
    try {
        $date = date_create($tanggal);
        if (!$date) return '-';
        $bulan = [ 1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des' ];
        return date_format($date, 'j') . ' ' . $bulan[date_format($date, 'n')] . ' ' . date_format($date, 'Y');
    } catch (Exception $e) { return '-'; }
}

// Daftar Status UI
$statuses_ui = ['Menunggu Pembayaran', 'Dikonfirmasi', 'Disewa', 'Dikembalikan', 'Selesai'];

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
                        'brand-blue': '#1E3A5F', 
                        'brand-yellow': '#FFBE00', 
                        'brand-gray': '#F8F9FA',   // Background abu (dipakai di main)
                        'sidebar-text': '#FFFFFF', 
                        'sidebar-hover': '#2a528a', 
                        'header-bg': '#FFFFFF',    
                        'button-detail': '#E5E7EB', 
                        'button-detail-hover': '#D1D5DB' 
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white"> <div id="alert-container" class="fixed top-5 right-5 z-[100] space-y-2"></div>

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-brand-blue text-sidebar-text shadow-lg flex flex-col justify-between">
            <div>
                 <div class="h-20 flex items-center justify-center p-4 border-b border-gray-700/50">
                    <img src="src/logo-siap.png" alt="Logo SIAP Mendaki Putih" class="h-36"> 
                </div>
                <nav class="mt-4 px-3 space-y-1">
                    <a href="index.php?c=DashboardController&m=index" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 opacity-75 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                        Beranda
                    </a>
                    <a href="index.php?c=BarangController&m=index" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 opacity-75 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                        Barang
                    </a>
                    <a href="index.php?c=PesananController&m=index" class="flex items-center px-4 py-2.5 rounded-lg bg-brand-yellow text-brand-blue font-semibold transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" /></svg>
                        Pesanan
                    </a>
                    <a href="#" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white transition-colors duration-200 group">
                       <svg class="w-5 h-5 mr-3 opacity-75 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                        Notifikasi
                    </a>
                    <a href="#" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 opacity-75 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 18H7.5m3-6h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 12H7.5" /></svg>
                        Pengaturan
                    </a>
                </nav>
            </div>
            <div class="p-4 border-t border-gray-700/50">
                 <a href="index.php?c=AuthController&m=logout" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white transition-colors duration-200 group">
                    <svg class="w-5 h-5 mr-3 opacity-75 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" /></svg>
                    Keluar
                </a>
            </div>
        </aside>
        <main class="flex-1 overflow-y-auto bg-white">
            
            <header class="h-16 bg-header-bg flex items-center justify-between px-6 border-b sticky top-0 z-10">
                 <div class="relative">
                    <input type="text" placeholder="Telusuri Pesanan..." class="w-80 px-4 py-2 pl-10 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-brand-yellow text-sm">
                     <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                </div>
                 <div class="flex items-center space-x-5">
                      <button class="text-gray-500 hover:text-gray-700 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                         </svg>
                    </button>
                    <div class="flex items-center cursor-pointer group">
                         <span class="w-9 h-9 bg-brand-blue rounded-full flex items-center justify-center text-white text-sm font-semibold mr-2">
                            <?php echo strtoupper(substr(htmlspecialchars($namaPengguna ?? 'V'), 0, 1)); ?>
                        </span>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-brand-blue">
                            <?php echo htmlspecialchars($namaPengguna ?? 'Nama Toko'); ?>
                        </span>
                        <svg class="w-4 h-4 text-gray-400 ml-1 group-hover:text-brand-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </div>
                </div>
            </header>

            <div class="p-6">
                <div class="grid grid-cols-5 gap-4 mb-6">
                    <?php foreach ($statuses_ui as $status) : ?>
                        <?php 
                        $isActive = ($status == $statusAktif);
                        $bgColor = $isActive ? 'bg-brand-yellow' : 'bg-white';
                        $textColor = $isActive ? 'text-brand-blue font-semibold' : 'text-gray-600 hover:text-gray-800';
                        $shadow = $isActive ? 'shadow-md' : 'shadow hover:shadow-md';
                        $border = $isActive ? '' : 'border border-gray-200';
                        $urlStatus = urlencode($status); 
                        ?>
                        <a href="index.php?c=PesananController&m=index&status=<?php echo $urlStatus; ?>" 
                           class="<?php echo $bgColor; ?> <?php echo $textColor; ?> <?php echo $shadow; ?> <?php echo $border; ?> rounded-lg py-2.5 px-3 text-center transition-all duration-200 text-sm">
                           <?php echo $status; ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <h2 class="text-xl font-semibold text-gray-800 mb-4">Pesanan <?php echo htmlspecialchars($statusAktif); ?></h2>

                <div class="bg-white rounded-lg shadow-md overflow-x-auto border border-gray-100">
                    <table class="w-full text-left text-sm">
                         <thead class="bg-gray-50/50 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase tracking-wider text-xs">No. Pesanan</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase tracking-wider text-xs">Nama Penyewa</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase tracking-wider text-xs">Tanggal Sewa</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase tracking-wider text-xs">Tanggal Kembali</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase tracking-wider text-xs">Total Biaya</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase tracking-wider text-xs">Status Pesanan</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase tracking-wider text-xs">Aksi</th>
                            </tr>
                        </thead>
                         <tbody class="divide-y divide-gray-100">
                            <?php if (empty($daftarPesanan)): ?>
                                <tr>
                                    <td colspan="7" class="py-6 px-4 text-center text-gray-500 italic">Tidak ada pesanan dengan status "<?php echo htmlspecialchars($statusAktif); ?>".</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($daftarPesanan as $pesanan): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-gray-800">PESAN-<?php echo str_pad($pesanan['id_pemesanan'], 7, '0', STR_PAD_LEFT); ?></td>
                                        <td class="py-3 px-4 text-gray-700"><?php echo htmlspecialchars($pesanan['nama_penyewa'] ?? '-'); ?></td>
                                        <td class="py-3 px-4 text-gray-600"><?php echo formatTanggalIndonesia($pesanan['tanggal_sewa_mulai'] ?? null); ?></td>
                                        <td class="py-3 px-4 text-gray-600"><?php echo formatTanggalIndonesia($pesanan['tanggal_sewa_selesai'] ?? null); ?></td>
                                        <td class="py-3 px-4 text-gray-800 font-medium">Rp<?php echo number_format($pesanan['total_harga'] ?? 0, 0, ',', '.'); ?></td>
                                        <td class="py-3 px-4 text-gray-600">
                                            <?php echo htmlspecialchars($statusAktif); ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center space-x-1.5">
                                                <a href="index.php?c=PesananController&m=detailPesanan&id=<?php echo $pesanan['id_pemesanan']; ?>&from_status=<?php echo urlencode($statusAktif); ?>" 
                                                   class="px-3 py-1 bg-button-detail text-gray-700 rounded hover:bg-button-detail-hover text-xs font-medium transition-colors">
                                                   Detail
                                                </a>
                                                <?php switch ($statusAktif):
                                                    case 'Menunggu Pembayaran': ?>
                                                        <a href="index.php?c=PesananController&m=konfirmasiSiap&id=<?php echo $pesanan['id_pemesanan']; ?>" onclick="/*...*/" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs font-medium transition-colors">Siap Diambil</a>
                                                        <?php break; ?>
                                                    <?php case 'Dikonfirmasi': ?>
                                                        <a href="index.php?c=PesananController&m=konfirmasiDiambil&id=<?php echo $pesanan['id_pemesanan']; ?>" onclick="/*...*/" class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 text-xs font-medium transition-colors">Sudah Diambil</a>
                                                        <?php break; ?>
                                                    <?php case 'Disewa': ?>
                                                         <a href="index.php?c=PesananController&m=konfirmasiKembali&id=<?php echo $pesanan['id_pemesanan']; ?>" onclick="/*...*/" class="px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 text-xs font-medium transition-colors">Sudah Kembali</a>
                                                        <?php break; ?>
                                                    <?php case 'Dikembalikan': ?>
                                                         <a href="index.php?c=PesananController&m=konfirmasiSelesai&id=<?php echo $pesanan['id_pemesanan']; ?>" onclick="/*...*/" class="px-3 py-1 bg-teal-100 text-teal-700 rounded hover:bg-teal-200 text-xs font-medium transition-colors">Kondisi Baik</a>
                                                        <a href="index.php?c=PesananController&m=laporanKerusakan&id=<?php echo $pesanan['id_pemesanan']; ?>" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-xs font-medium transition-colors">Laporkan Kerusakan</a>
                                                        <?php break; ?>
                                                    <?php case 'Selesai': ?>
                                                         <a href="index.php?c=PesananController&m=lihatUlasan&id=<?php echo $pesanan['id_pemesanan']; ?>" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 text-xs font-medium transition-colors">Lihat Ulasan</a>
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
        // Script untuk menampilkan alert (Sama seperti sebelumnya)
         document.addEventListener('DOMContentLoaded', function() {
            function showAlert(message, type = 'success') { /* ... (fungsi sama) ... */ }
            // Tampilkan pesan dari PHP (Session)
            <?php if ($error_message): ?> showAlert('<?php echo addslashes($error_message); ?>', 'error'); <?php endif; ?>
            <?php if ($success_message): ?> showAlert('<?php echo addslashes($success_message); ?>', 'success'); <?php endif; ?>
             <?php if ($info_message): ?> showAlert('<?php echo addslashes($info_message); ?>', 'info'); <?php endif; ?>
        });
    </script>
</body>
</html>