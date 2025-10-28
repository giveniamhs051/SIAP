<?php
// views/barang_vendor.php
$namaPengguna = $namaPengguna ?? 'Vendor';
$barang = $barang ?? []; // Default ke array kosong jika tidak ada data
// Pesan flash
$error_message = $_SESSION['error_message'] ?? null;
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Barang - SIAP Mendaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c4c4c4; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        input[type='number']::-webkit-inner-spin-button,
        input[type='number']::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type='number'] { -moz-appearance: textfield; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                     colors: {
                        'brand-blue': '#1E3A5F', // Biru gelap sidebar
                        'brand-yellow': '#FFBE00', // Kuning aktif
                        'brand-gray': '#F8F9FA',   // Background abu konten
                        'sidebar-text': '#FFFFFF', // Teks sidebar putih
                        'sidebar-hover': '#2a528a', // Hover sidebar
                        'header-bg': '#FFFFFF',    // Background header putih
                        'button-detail': '#E5E7EB', // Abu tombol detail
                        'button-detail-hover': '#D1D5DB' // Hover tombol detail
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-gray">

    <div id="alert-container" class="fixed top-5 right-5 z-[100] space-y-2"></div>

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-brand-blue text-sidebar-text shadow-lg flex flex-col justify-between">
            <div>
                 <div class="h-20 flex items-center justify-center p-4 border-b border-gray-700">
                    <img src="src/logo-siap.png" alt="Logo SIAP Mendaki Putih" class="h-36"> 
                </div>
                <nav class="mt-4 px-2 space-y-1">
                    <a href="index.php?c=DashboardController&m=index" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text hover:bg-sidebar-hover hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3 opacity-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                        Beranda
                    </a>
                    <a href="index.php?c=BarangController&m=index" class="flex items-center px-4 py-2.5 rounded-lg bg-brand-yellow text-brand-blue font-semibold transition-colors duration-200">
                         <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                        Barang
                    </a>
                    <a href="index.php?c=PesananController&m=index" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text hover:bg-sidebar-hover hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3 opacity-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" /></svg>
                        Pesanan
                    </a>
                    <a href="#" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text hover:bg-sidebar-hover hover:text-white transition-colors duration-200">
                       <svg class="w-5 h-5 mr-3 opacity-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                        Notifikasi
                    </a>
                    <a href="#" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text hover:bg-sidebar-hover hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3 opacity-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 18H7.5m3-6h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 12H7.5" /></svg>
                        Pengaturan
                    </a>
                </nav>
            </div>
            <div class="p-4 border-t border-gray-700">
                <a href="index.php?c=AuthController&m=logout" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text hover:bg-sidebar-hover hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3 opacity-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" /></svg>
                    Keluar
                </a>
            </div>
        </aside>
        <main class="flex-1 overflow-y-auto bg-white">
            
             <header class="h-16 bg-header-bg flex items-center justify-between px-6 border-b">
                 <div class="relative">
                    <input type="text" placeholder="Telusuri Barang..." class="w-80 px-4 py-2 pl-10 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-brand-yellow text-sm">
                     <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                </div>
                 <div class="flex items-center space-x-5">
                     <button class="text-gray-500 hover:text-gray-700 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
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
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Barang</h2>
                     <button id="openModalBtn" class="flex items-center bg-brand-blue text-white px-4 py-2 rounded-lg font-medium hover:bg-opacity-90 transition-colors text-sm">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Unggah Barang
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-x-auto border border-gray-200">
                    <table class="w-full text-left text-sm">
                         <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase tracking-wider">Harga Sewa</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase tracking-wider">Stok</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                         <tbody class="divide-y divide-gray-200">
                            <?php if (empty($barang)): ?>
                                <tr>
                                    <td colspan="5" class="py-6 px-4 text-center text-gray-500 italic">Anda belum mengunggah barang apapun.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($barang as $item): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center space-x-3">
                                                <img src="<?php echo htmlspecialchars($item['url_foto'] ?? 'src/placeholder.png'); ?>" alt="Foto Barang" class="w-10 h-10 rounded-md object-cover border">
                                                <div>
                                                    <p class="font-medium text-gray-800"><?php echo htmlspecialchars($item['nama_barang']); ?></p>
                                                    <p class="text-xs text-gray-500">ID: BRG-<?php echo str_pad($item['id_barang'], 4, '0', STR_PAD_LEFT); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-gray-700">Rp<?php echo number_format($item['harga_sewa'], 0, ',', '.'); ?>/hari</td>
                                        <td class="py-3 px-4 text-gray-700"><?php echo htmlspecialchars($item['stok_barang']); ?></td>
                                        <td class="py-3 px-4">
                                            <?php
                                                $status = htmlspecialchars($item['status_barang']);
                                                $statusText = 'Nonaktif';
                                                $statusClass = 'bg-red-100 text-red-700'; 
                                                if ($status == 'aktif') {
                                                    $statusText = 'Aktif';
                                                    $statusClass = 'bg-green-100 text-green-700';
                                                }
                                            ?>
                                            <span class="px-2.5 py-1 <?php echo $statusClass; ?> rounded-full text-xs font-semibold">
                                                <?php echo $statusText; ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                 <button class="text-blue-600 hover:text-blue-800 p-1 hover:bg-blue-100 rounded" title="Edit">
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                                </button>
                                                <button class="text-red-600 hover:text-red-800 p-1 hover:bg-red-100 rounded" title="Hapus">
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.576 0c-.342.052-.682.107-1.022.166m11.554 0A48.108 48.108 0 0 0 9.25 5.397M4.772 5.79m14.456 0a48.108 48.108 0 0 1-3.478-.397m-12.576 0c.342.052.682.107 1.022.166m-1.022-.165L5.84 3.12a2.25 2.25 0 0 1 2.244-2.077h6.832a2.25 2.25 0 0 1 2.244 2.077L19.228 5.79m-14.456 0A48.108 48.108 0 0 1 9.25 5.397" /></svg>
                                                </button>
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

    <div id="barangModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-y-auto">
         <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl">
            <div class="flex justify-between items-center p-5 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Unggah Barang Baru</h3>
                <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form action="index.php?c=BarangController&m=uploadProcess" method="POST" enctype="multipart/form-data">
                <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                    </div>
                <div class="flex items-center justify-end p-5 border-t space-x-3 bg-gray-50 rounded-b-lg">
                    <button id="cancelModalBtn" type="button" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" class="bg-brand-yellow text-brand-blue px-4 py-2 rounded-lg font-semibold hover:bg-opacity-90 transition-colors text-sm">
                        Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
    <script>
        // Script untuk Modal, Litepicker, dan Alert (Sama seperti sebelumnya)
        document.addEventListener('DOMContentLoaded', function() {
            // ... (Kode JS sama) ...
             const modal = document.getElementById('barangModal');
            const openBtn = document.getElementById('openModalBtn');
            const closeBtn = document.getElementById('closeModalBtn');
            const cancelBtn = document.getElementById('cancelModalBtn');

            if (openBtn) openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
            if (closeBtn) closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            if (cancelBtn) cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));
            window.addEventListener('click', (event) => { if (event.target == modal) modal.classList.add('hidden'); });

            // Litepicker (Single Date)
             new Litepicker({
                element: document.getElementById('datepicker'),
                singleMode: true, 
                allowRepick: true,
                format: 'YYYY-MM-DD', 
            });

            // Alert Function
            function showAlert(message, type = 'success') {
                 const alertContainer = document.getElementById('alert-container');
                 if (!alertContainer) return; 
                 // ... (Kode showAlert sama) ...
            }

            // Tampilkan pesan PHP
             <?php if ($error_message): ?> showAlert('<?php echo addslashes($error_message); ?>', 'error'); <?php endif; ?>
             <?php if ($success_message): ?> showAlert('<?php echo addslashes($success_message); ?>', 'success'); <?php endif; ?>
        });
    </script>
</body>
</html>