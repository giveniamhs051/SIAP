<?php
// views/profil_vendor.php
// Variabel dari controller: $vendor (array data vendor), $namaPengguna
$vendor = $vendor ?? []; // Default ke array kosong jika tidak ada

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
    <title>Edit Profil Vendor - SIAP Mendaki</title>
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
        /* Style untuk preview gambar */
        .profile-img-preview {
            width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 2px solid #eee;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-blue': '#1E3A5F',
                        'brand-yellow': '#FFBE00',
                        'brand-gray': '#F8F9FA',
                        'sidebar-text': '#FFFFFF',
                        'sidebar-hover': '#2a528a',
                        'header-bg': '#FFFFFF',
                        'button-detail': '#E5E7EB',
                        'button-detail-hover': '#D1D5DB',
                        'input-bg': '#F3F4F6', // Warna background input
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-gray"> <div id="alert-container" class="fixed top-5 right-5 z-[100] space-y-2"></div>

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-brand-blue text-sidebar-text shadow-lg flex flex-col justify-between">
            <div>
                 <div class="h-20 flex items-center justify-center p-4 border-b border-gray-700/50">
                    <img src="src/logo-siap-white.png" alt="Logo SIAP Mendaki Putih" class="h-10">
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
                    <a href="index.php?c=PesananController&m=index" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 opacity-75 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" /></svg>
                        Pesanan
                    </a>
                    <a href="#" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white transition-colors duration-200 group">
                       <svg class="w-5 h-5 mr-3 opacity-75 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                        Notifikasi
                    </a>
                    <a href="index.php?c=ProfilVendorController&m=index" class="flex items-center px-4 py-2.5 rounded-lg bg-brand-yellow text-brand-blue font-semibold transition-colors duration-200 group">
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
        <main class="flex-1 overflow-y-auto bg-white">

             <header class="h-16 bg-header-bg flex items-center justify-between px-6 border-b sticky top-0 z-10">
                 <div class="relative">
                    <input type="text" placeholder="Telusuri..." class="w-80 px-4 py-2 pl-10 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-brand-yellow text-sm">
                     <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                </div>
                 <div class="flex items-center space-x-5">
                      <button class="text-gray-500 hover:text-gray-700 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                         </svg>
                    </button>
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center cursor-pointer group">
                             <span class="w-9 h-9 bg-brand-blue rounded-full flex items-center justify-center text-white text-sm font-semibold mr-2">
                                <?php echo strtoupper(substr(htmlspecialchars($namaPengguna ?? 'V'), 0, 1)); ?>
                            </span>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-brand-blue">
                                <?php echo htmlspecialchars($namaPengguna ?? 'Nama Toko'); ?>
                            </span>
                            <svg class="w-4 h-4 text-gray-400 ml-1 group-hover:text-brand-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                        </button>
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-20">
                             <a href="index.php?c=ProfilVendorController&m=index" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Profil</a>
                             <a href="index.php?c=AuthController&m=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Keluar</a>
                        </div>
                    </div>
                </div>
            </header>

             <div class="p-6">
                <div class="text-right mb-4">
                    <button class="text-brand-blue font-medium text-sm border border-brand-blue px-4 py-1.5 rounded-md hover:bg-brand-blue hover:text-white transition-colors">
                        Edit Profil
                    </button>
                </div>

                <form action="index.php?c=ProfilVendorController&m=updateProcess" method="POST" enctype="multipart/form-data">
                     <div class="bg-white rounded-lg shadow border border-gray-100 p-6 mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Foto Profil Vendor</label>
                        <div class="flex items-center space-x-6">
                            <img id="profile-preview" src="<?php echo htmlspecialchars(!empty($vendor['foto_profil_url']) ? $vendor['foto_profil_url'] : 'https://via.placeholder.com/80/EBF4FF/BFDBFE?text=Foto'); ?>" alt="Foto Profil" class="profile-img-preview bg-gray-100">
                            <div class="space-x-3">
                                <label for="foto_profil" class="cursor-pointer bg-brand-yellow text-brand-blue px-4 py-2 rounded-lg font-semibold hover:opacity-90 transition-opacity text-sm">
                                    Unggah Foto
                                </label>
                                <input id="foto_profil" name="foto_profil" type="file" class="hidden" accept="image/png, image/jpeg, image/webp">
                                <button type="button" id="hapus-foto-btn" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors text-sm">
                                    Hapus Foto
                                </button>
                                <input type="hidden" name="hapus_foto" id="hapus_foto_input" value="0"> </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, WEBP. Ukuran maks: 2MB.</p>
                    </div>

                     <div class="bg-white rounded-lg shadow border border-gray-100 p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                             <div>
                                <label for="nama_toko" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                                <input type="text" id="nama_toko" name="nama_toko" value="<?php echo htmlspecialchars($vendor['nama_vendor'] ?? ''); ?>" class="mt-1 block w-full px-3 py-2 bg-input-bg border-gray-300 rounded-md focus:ring-brand-yellow focus:border-brand-yellow text-sm" required>
                            </div>
                             <div>
                                <label for="alamat_toko" class="block text-sm font-medium text-gray-700">Alamat Toko</label>
                                <input type="text" id="alamat_toko" name="alamat_toko" value="<?php echo htmlspecialchars($vendor['alamat_vendor'] ?? ''); ?>" class="mt-1 block w-full px-3 py-2 bg-input-bg border-gray-300 rounded-md focus:ring-brand-yellow focus:border-brand-yellow text-sm">
                            </div>
                             <div>
                                <label for="email_toko" class="block text-sm font-medium text-gray-700">Email Toko</label>
                                <input type="email" id="email_toko" name="email_toko" value="<?php echo htmlspecialchars($vendor['email_vendor'] ?? ''); ?>" class="mt-1 block w-full px-3 py-2 bg-input-bg border-gray-300 rounded-md focus:ring-brand-yellow focus:border-brand-yellow text-sm" required>
                            </div>
                             <div>
                                <label for="nomor_telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                <input type="tel" id="nomor_telepon" name="nomor_telepon" value="<?php echo htmlspecialchars($vendor['no_telepon'] ?? ''); ?>" class="mt-1 block w-full px-3 py-2 bg-input-bg border-gray-300 rounded-md focus:ring-brand-yellow focus:border-brand-yellow text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="deskripsi_toko" class="block text-sm font-medium text-gray-700">Deskripsi Toko</label>
                            <textarea id="deskripsi_toko" name="deskripsi_toko" rows="5" class="mt-1 block w-full px-3 py-2 bg-input-bg border-gray-300 rounded-md focus:ring-brand-yellow focus:border-brand-yellow text-sm"><?php echo htmlspecialchars($vendor['deskripsi_vendor'] ?? ''); ?></textarea>
                        </div>

                         <div class="mt-6 text-right">
                             <button type="submit" class="bg-brand-yellow text-brand-blue px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition-opacity text-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                 </form>

            </div> </main>
        </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- Logika Alert ---
            function showAlert(message, type = 'success') {
                 const alertContainer = document.getElementById('alert-container');
                 if (!alertContainer) return;
                 const alertId = 'alert-' + Date.now();
                 let alertColor = 'bg-green-100 border-green-400 text-green-700';
                 let alertTitle = 'Berhasil!';
                 if (type === 'error') { alertColor = 'bg-red-100 border-red-400 text-red-700'; alertTitle = 'Error!'; }
                 else if (type === 'info') { alertColor = 'bg-blue-100 border-blue-400 text-blue-700'; alertTitle = 'Info'; }
                 const alertElement = document.createElement('div');
                 alertElement.id = alertId;
                 alertElement.className = `border px-4 py-3 rounded-lg relative shadow-md ${alertColor} opacity-0 transform translate-x-10 transition-all duration-300 ease-out`;
                 alertElement.setAttribute('role', 'alert');
                 alertElement.innerHTML = `
                    <strong class="font-bold">${alertTitle}</strong>
                    <span class="block sm:inline ml-2">${message}</span>
                    <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">...</button> 
                 `; // Isi button sama
                 alertContainer.appendChild(alertElement);
                 setTimeout(() => { alertElement.classList.remove('opacity-0', 'translate-x-10'); alertElement.classList.add('opacity-100', 'translate-x-0'); }, 50);
                 setTimeout(() => { /* ... Logika fade out sama ... */ }, 4000);
            }

             // Tampilkan pesan PHP
             <?php if ($error_message): ?> showAlert('<?php echo addslashes($error_message); ?>', 'error'); <?php endif; ?>
             <?php if ($success_message): ?> showAlert('<?php echo addslashes($success_message); ?>', 'success'); <?php endif; ?>

             // --- Logika Dropdown User Menu ---
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

             // --- Logika Preview & Hapus Foto Profil ---
             const fileInput = document.getElementById('foto_profil');
             const previewImg = document.getElementById('profile-preview');
             const hapusFotoBtn = document.getElementById('hapus-foto-btn');
             const hapusFotoInput = document.getElementById('hapus_foto_input');
             const defaultImageSrc = 'https://via.placeholder.com/80/EBF4FF/BFDBFE?text=Foto'; // Gambar default

             if(fileInput && previewImg) {
                 fileInput.addEventListener('change', function(e) {
                     const file = e.target.files[0];
                     if (file) {
                         const reader = new FileReader();
                         reader.onload = function(event) {
                             previewImg.src = event.target.result;
                             hapusFotoInput.value = '0'; // Jika upload baru, batalkan status hapus
                         }
                         reader.readAsDataURL(file);
                     }
                 });
             }

             if(hapusFotoBtn && previewImg && fileInput && hapusFotoInput) {
                 hapusFotoBtn.addEventListener('click', function() {
                     previewImg.src = defaultImageSrc; // Kembalikan ke gambar default
                     fileInput.value = ''; // Kosongkan input file
                     hapusFotoInput.value = '1'; // Tandai untuk dihapus di controller
                 });
             }
        });
    </script>
</body>
</html>