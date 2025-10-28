<?php
// views/barang_vendor.php
// Variabel $namaPengguna (dari controller)
// Variabel $barang (array data barang dari controller)

// Cek pesan flash dari session
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
        /* Sembunyikan panah di input number */
        input[type='number']::-webkit-inner-spin-button,
        input[type='number']::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type='number'] { -moz-appearance: textfield; }
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
                    <a href="index.php?c=BarangController&m=index" class="flex items-center px-6 py-3 m-2 text-brand-blue bg-brand-yellow rounded-lg font-semibold">
                        <svg class="w-6 h-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                        Barang
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 m-2 text-gray-600 hover:bg-gray-100 rounded-lg">
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
                    <input type="text" placeholder="Pencarian Barang..." class="w-80 px-4 py-2 pl-10 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-yellow">
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
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Barang</h2>
                    <button id="openModalBtn" class="flex items-center bg-brand-blue text-white px-4 py-2 rounded-lg font-semibold hover:bg-opacity-90 transition-colors">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Unggah
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 text-sm font-semibold text-gray-600">Nama Barang</th>
                                <th class="py-3 px-4 text-sm font-semibold text-gray-600">Harga</th>
                                <th class="py-3 px-4 text-sm font-semibold text-gray-600">Stok</th>
                                <th class="py-3 px-4 text-sm font-semibold text-gray-600">Status</th>
                                <th class="py-3 px-4 text-sm font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (empty($barang)): ?>
                                <tr>
                                    <td colspan="5" class="py-4 px-4 text-center text-gray-500">Anda belum mengunggah barang apapun.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($barang as $item): ?>
                                    <tr>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center space-x-3">
                                                <img src="<?php echo htmlspecialchars($item['url_foto'] ?? 'src/placeholder.png'); ?>" alt="Foto Barang" class="w-12 h-12 rounded-lg object-cover">
                                                <div>
                                                    <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($item['nama_barang']); ?></p>
                                                    <p class="text-xs text-gray-500">ID: BRG-<?php echo $item['id_barang']; ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-gray-700">Rp<?php echo number_format($item['harga_sewa'], 0, ',', '.'); ?></td>
                                        <td class="py-3 px-4 text-gray-700"><?php echo htmlspecialchars($item['stok_barang']); ?></td>
                                        <td class="py-3 px-4">
                                            <?php
                                                $status = htmlspecialchars($item['status_barang']);
                                                $statusText = 'Nonaktif';
                                                $statusClass = 'bg-red-100 text-red-700'; // Default 'Nonaktif'
                                                if ($status == 'aktif') {
                                                    $statusText = 'Aktif';
                                                    $statusClass = 'bg-green-100 text-green-700';
                                                }
                                            ?>
                                            <span class="px-3 py-1 <?php echo $statusClass; ?> rounded-full text-xs font-semibold">
                                                ● <?php echo $statusText; ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                <button class="text-blue-600 hover:text-blue-800" title="Edit">
                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                                </button>
                                                <button class="text-red-600 hover:text-red-800" title="Hapus">
                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.576 0c-.342.052-.682.107-1.022.166m11.554 0A48.108 48.108 0 0 0 9.25 5.397M4.772 5.79m14.456 0a48.108 48.108 0 0 1-3.478-.397m-12.576 0c.342.052.682.107 1.022.166m-1.022-.165L5.84 3.12a2.25 2.25 0 0 1 2.244-2.077h6.832a2.25 2.25 0 0 1 2.244 2.077L19.228 5.79m-14.456 0A48.108 48.108 0 0 1 9.25 5.397" /></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
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
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Barang</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="foto_barang" class="relative cursor-pointer bg-white rounded-md font-medium text-brand-blue hover:text-brand-blue focus-within:outline-none">
                                        <span>Unggah file di sini</span>
                                        <input id="foto_barang" name="foto_barang" type="file" class="sr-only" required accept="image/png, image/jpeg, image/webp">
                                    </label>
                                    <p class="pl-1">atau tarik dan lepas</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, WEBP hingga 2MB</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <input type="text" id="nama_barang" name="nama_barang" placeholder="Contoh: Tenda Camping Imut" class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-brand-yellow focus:border-brand-yellow" required>
                        </div>
                        <div>
                            <label for="harga_sewa" class="block text-sm font-medium text-gray-700">Harga Sewa (per hari)</label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rp</span>
                                <input type="number" id="harga_sewa" name="harga_sewa" placeholder="20000" class="pl-10 pr-3 py-2 block w-full bg-gray-50 border border-gray-300 rounded-lg focus:ring-brand-yellow focus:border-brand-yellow" required>
                            </div>
                        </div>
                    </div>

                     <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                            <input type="number" id="stok" name="stok" placeholder="10" class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-brand-yellow focus:border-brand-yellow" required>
                        </div>
                        <div>
                            <label for="datepicker" class="block text-sm font-medium text-gray-700">Tanggal Tersedia</label>
                            <input type="text" id="datepicker" name="tanggal_tersedia" placeholder="dd/mm/yyyy" class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-brand-yellow focus:border-brand-yellow" required>
                        </div>
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Barang</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan kondisi dan detail barang di sini..." class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-brand-yellow focus:border-brand-yellow"></textarea>
                    </div>

                </div>

                <div class="flex items-center justify-end p-5 border-t space-x-3">
                    <button id="cancelModalBtn" type="button" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="bg-brand-yellow text-brand-blue px-4 py-2 rounded-lg font-semibold hover:bg-opacity-90 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- Logika Modal ---
            const modal = document.getElementById('barangModal');
            const openBtn = document.getElementById('openModalBtn');
            const closeBtn = document.getElementById('closeModalBtn');
            const cancelBtn = document.getElementById('cancelModalBtn');

            if (openBtn) {
                openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
            }
            if (closeBtn) {
                closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            }
            if (cancelBtn) {
                cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));
            }
            window.addEventListener('click', (event) => {
                if (event.target == modal) {
                    modal.classList.add('hidden');
                }
            });

            // --- PERBAIKAN: Logika Litepicker (Single Date) ---
            new Litepicker({
                element: document.getElementById('datepicker'),
                singleMode: true, // Diubah menjadi true
                allowRepick: true,
                format: 'YYYY-MM-DD', // Format sesuai database
            });

            // --- Logika Alert/Notifikasi ---
            function showAlert(message, isSuccess = true) {
                const alertContainer = document.getElementById('alert-container');
                const alertId = 'alert-' + Date.now();
                const alertColor = isSuccess ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
                
                const alertElement = document.createElement('div');
                alertElement.id = alertId;
                alertElement.className = `border px-4 py-3 rounded-lg relative shadow-md ${alertColor}`;
                alertElement.setAttribute('role', 'alert');
                alertElement.innerHTML = `
                    <strong class="font-bold">${isSuccess ? 'Berhasil!' : 'Error!'}</strong>
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
                }, 4000);
            }

            // Tampilkan pesan dari PHP (Session)
            <?php if ($error_message): ?>
                showAlert('<?php echo addslashes($error_message); ?>', false);
            <?php endif; ?>
            <?php if ($success_message): ?>
                showAlert('<?php echo addslashes($success_message); ?>', true);
            <?php endif; ?>

        });
    </script>
</body>
</html>