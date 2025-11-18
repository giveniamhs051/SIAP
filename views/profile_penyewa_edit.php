<?php
    // views/profile_penyewa_edit.php
    
    // Helper & Pesan Session
    function e($string) { return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8'); }
    $error_message = $_SESSION['error_message'] ?? null;
    $success_message = $_SESSION['success_message'] ?? null;
    unset($_SESSION['error_message']);
    unset($_SESSION['success_message']);

    // Data penyewa dari controller (variabel $penyewa sudah di-extract)
    $namaPenyewa = $penyewa->nama_penyewa ?? ($_SESSION['user_nama'] ?? 'Penyewa');
    $emailPenyewa = $penyewa->email ?? '';
    $teleponPenyewa = $penyewa->no_hp ?? '';
    
    // Navigasi (sama seperti dashboard penyewa)
    $navItems = [
        'beranda' => 'Beranda',
        'tentang' => 'Tentang Kami',
        'produk' => 'Produk',
        'lokasi' => 'Lokasi'
    ];
    // Tandai 'Profil' sebagai halaman aktif (meskipun tidak ada di nav utama)
    $currentPage = 'profil'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - SIAP Mendaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F8F9FA; }
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
                        'brand-gray': '#F8F9FA',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-gray">
    <div id="alert-container" class="fixed top-20 right-5 z-50 space-y-2"></div>

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
                <button class="text-gray-500 hover:text-brand-blue relative p-1">...</button>
                <button class="text-gray-500 hover:text-brand-blue p-1">...</button>
                <button class="text-gray-500 hover:text-brand-blue p-1">...</button>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @keydown.escape.window="open = false" id="user-menu-button" class="flex items-center text-sm font-medium text-gray-700 hover:text-brand-blue focus:outline-none">
                         <span class="w-9 h-9 bg-brand-blue rounded-full flex items-center justify-center text-white text-sm font-semibold mr-2">
                            <?php echo strtoupper(substr(e($namaPenyewa), 0, 1)); ?>
                        </span>
                        Halo, <?php echo explode(' ', e($namaPenyewa))[0]; ?>
                        <svg class="ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" 
                         @click.outside="open = false" 
                         x-transition 
                         class="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                         style="display: none;">
                        <a href="index.php?c=ProfileController&m=index" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-semibold bg-gray-100">Profil Anda</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pesanan Saya</a>
                        <a href="index.php?c=AuthController&m=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Keluar</a>
                    </div>
                </div>
                 <button id="mobile-menu-button" class="md:hidden">...</button>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-4 lg:px-6 py-8">
        <div class="bg-white p-6 sm:p-8 rounded-lg shadow-md max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Edit Profil Anda</h2>
            </div>

            <form action="index.php?c=ProfileController&m=updatePenyewa" method="POST">

                <div class="p-6 border rounded-lg space-y-6">
                    <div>
                        <label for="nama-penyewa" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama-penyewa" name="nama_penyewa" value="<?php echo e($namaPenyewa); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-brand-yellow focus:border-brand-yellow" required>
                    </div>
                    
                    <div>
                        <label for="email-penyewa" class="block text-sm font-medium text-gray-700 mb-1">Email</Ganti>
                        <input type="email" id="email-penyewa" name="email_penyewa" value="<?php echo e($emailPenyewa); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" readonly disabled title="Email tidak dapat diubah">
                         <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah.</p>
                    </div>
                    
                    <div>
                        <label for="nomor-telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="tel" id="nomor-telepon" name="nomor_telepon" value="<?php echo e($teleponPenyewa); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-brand-yellow focus:border-brand-yellow" required>
                    </div>
                </div>

                 <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-brand-yellow text-brand-blue font-semibold rounded-md hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-yellow">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </main>

    <script>
        // Fungsi Alert (copy dari profile_vendor_edit.php)
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