<?php
    // Helper & Pesan Session
    function e($string) { return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8'); }
    $error_message = $_SESSION['error_message'] ?? null;
    $success_message = $_SESSION['success_message'] ?? null;
    unset($_SESSION['error_message']);
    unset($_SESSION['success_message']);

    // Data vendor dari controller (variabel $vendor sudah di-extract)
    $namaVendor = $vendor->nama_vendor ?? ($_SESSION['user_nama'] ?? 'Vendor');
    $emailVendor = $vendor->email_vendor ?? '';
    $teleponVendor = $vendor->no_telepon ?? '';
    $alamatVendor = $vendor->alamat_vendor ?? '';
    $deskripsiVendor = $vendor->deskripsi_vendor ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Vendor - SIAP Mendaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Styling dasar dan scrollbar (Sama seperti barang_vendor.php) */
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c4c4c4; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        /* Style tambahan untuk input number (opsional) */
        input[type='number']::-webkit-inner-spin-button,
        input[type='number']::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type='number'] { -moz-appearance: textfield; }
    </style>
    <script>
        // Konfigurasi Tailwind (SAMA DENGAN barang_vendor.php)
        tailwind.config = {
            theme: {
                extend: {
                     colors: {
                        'brand-blue': '#1E3A5F', // Biru gelap sidebar
                        'brand-yellow': '#FFBE00', // Kuning aktif
                        'brand-gray': '#F8F9FA', // Background abu konten
                        'sidebar-text': '#FFFFFF', // Teks putih sidebar
                        'sidebar-hover': '#2a528a', // Hover biru lebih terang
                        'header-bg': '#FFFFFF', // Background header putih
                        'button-detail': '#E5E7EB', // Warna tambahan jika perlu
                        'button-detail-hover': '#D1D5DB' // Warna tambahan jika perlu
                    }
                }
            }
        }
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-brand-gray">

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
                    <a href="#" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 opacity-75 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" /></svg>
                        Pesanan
                    </a>
                    <a href="#" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white transition-colors duration-200 group">
                       <svg class="w-5 h-5 mr-3 opacity-75 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                        Notifikasi
                    </a>
                    <a href="index.php?c=ProfileController&m=index" class="flex items-center px-4 py-2.5 rounded-lg bg-brand-yellow text-brand-blue font-semibold transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 18H7.5m3-6h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 12H7.5" /></svg>
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
        <main class="flex-1 overflow-y-auto bg-white"> <header class="h-16 bg-header-bg flex items-center justify-between px-6 border-b sticky top-0 z-10">
                 <div class="relative">
                    <input type="text" placeholder="Telusuri..." class="w-80 px-4 py-2 pl-10 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-brand-yellow text-sm">
                     <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                </div>
                 <div class="flex items-center space-x-5">
                    <button class="text-gray-500 hover:text-gray-700 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                        </button>

                     <div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" type="button" class="flex items-center cursor-pointer group">
                             <span class="w-9 h-9 bg-brand-blue rounded-full flex items-center justify-center text-white text-sm font-semibold mr-2"><?php echo strtoupper(substr(e($namaVendor), 0, 1)); ?></span>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-brand-blue"><?php echo e($namaVendor); ?></span>
                            <svg class="w-4 h-4 text-gray-400 ml-1 group-hover:text-brand-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-20"
                             style="display: none;"> <div class="py-1">
                                <a href="index.php?c=ProfileController&m=index" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Edit Profil
                                </a>
                                <a href="index.php?c=AuthController&m=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div> </div>
            </header>
            <div class="p-6">
                 <div class="bg-white p-8 rounded-lg shadow-md max-w-4xl mx-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Edit Profil Vendor</h2>
                    </div>

                    <form action="index.php?c=ProfileController&m=updateVendor" method="POST" enctype="multipart/form-data">

                        <div class="mb-8 p-6 border rounded-lg">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">Foto Profil Vendor</h3>
                            <div class="flex items-center space-x-6">
                                <img id="profile-preview" src="src/default-avatar.jpg" alt="Foto Profil" class="w-24 h-24 rounded-full object-cover bg-gray-200">
                                <div>
                                     <label for="upload-foto" class="cursor-pointer inline-flex items-center px-4 py-2 bg-brand-yellow text-brand-blue text-sm font-medium rounded-md hover:bg-yellow-400">
                                        Unggah Foto
                                    </label>
                                    <input id="upload-foto" name="foto_profil" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                                     <button type="button" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Hapus Foto
                                    </button>
                                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Ukuran maks: 2MB.</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 border rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="nama-toko" class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                                    <input type="text" id="nama-toko" name="nama_toko" value="<?php echo e($namaVendor); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-brand-yellow focus:border-brand-yellow" required>
                                </div>
                                <div>
                                    <label for="alamat-toko" class="block text-sm font-medium text-gray-700 mb-1">Alamat Toko</label>
                                    <input type="text" id="alamat-toko" name="alamat_toko" value="<?php echo e($alamatVendor); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-brand-yellow focus:border-brand-yellow">
                                </div>
                                <div>
                                    <label for="email-toko" class="block text-sm font-medium text-gray-700 mb-1">Email Toko</label>
                                    <input type="email" id="email-toko" name="email_toko" value="<?php echo e($emailVendor); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" readonly disabled title="Email tidak dapat diubah">
                                     <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah.</p>
                                </div>
                                <div>
                                    <label for="nomor-telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                    <input type="tel" id="nomor-telepon" name="nomor_telepon" value="<?php echo e($teleponVendor); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-brand-yellow focus:border-brand-yellow" required>
                                </div>
                            </div>
                             <div>
                                <label for="deskripsi-toko" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Toko</label>
                                <textarea id="deskripsi-toko" name="deskripsi_toko" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-brand-yellow focus:border-brand-yellow"><?php echo e($deskripsiVendor); ?></textarea>
                            </div>
                        </div>

                         <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-brand-yellow text-brand-blue font-semibold rounded-md hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-yellow">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
            <div id="alert-container" class="fixed top-20 right-5 z-50 space-y-2"></div>

        </main>
    </div>

    <script>
        // Fungsi preview gambar
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('profile-preview');
                output.src = reader.result;
            };
            if(event.target.files[0]){
                reader.readAsDataURL(event.target.files[0]);
            }
        }

         // Fungsi untuk menampilkan alert
         document.addEventListener('DOMContentLoaded', function() {
             function showAlert(message, isSuccess = true) {
                const alertContainer = document.getElementById('alert-container');
                const alertId = 'alert-' + Date.now();
                const alertColor = isSuccess ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
                const alertElement = document.createElement('div');
                alertElement.id = alertId;
                alertElement.className = `border px-4 py-3 rounded relative shadow ${alertColor}`;
                alertElement.setAttribute('role', 'alert');
                alertElement.innerHTML = `
                    <strong class="font-bold">${isSuccess ? 'Berhasil!' : 'Error!'}</strong>
                    <span class="block sm:inline">${message}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="closeAlert('${alertId}')">
                        <svg class="fill-current h-6 w-6 ${isSuccess ? 'text-green-500' : 'text-red-500'}" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.03a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                    </span>
                `;
                alertContainer.appendChild(alertElement);
                setTimeout(() => { closeAlert(alertId); }, 5000);
            }

             window.closeAlert = function(alertId) {
                 const alertToRemove = document.getElementById(alertId);
                 if(alertToRemove) { alertToRemove.remove(); }
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