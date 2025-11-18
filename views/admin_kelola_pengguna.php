<?php
// views/admin_kelola_pengguna.php
$namaPengguna = $namaPengguna ?? 'Admin';
$daftarPengguna = $daftarPengguna ?? []; // Data ini didapat dari AdminController

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
    <title>Kelola Pengguna - SIAP Mendaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c4c4c4; border-radius: 6px; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                     colors: {
                        'brand-blue': '#1E3A5F', 'brand-yellow': '#FFBE00', 'brand-gray': '#F8F9FA',   
                        'sidebar-text': '#FFFFFF', 'sidebar-hover': '#2a528a', 'header-bg': '#FFFFFF'
                    }
                }
            }
        }
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-brand-gray">
    <div id="alert-container" class="fixed top-5 right-5 z-[100] space-y-2"></div>
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-brand-blue text-sidebar-text shadow-lg flex flex-col justify-between">
            <div>
                 <div class="h-20 flex items-center justify-center p-4 border-b border-gray-700/50">
                    <img src="src/logo-siap.png" alt="Logo SIAP Mendaki Putih" class="h-36"> 
                </div>
                <nav class="mt-4 px-3 space-y-1">
                    <a href="index.php?c=DashboardController&m=index" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 opacity-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">...</svg>
                        Beranda
                    </a>
                    <a href="index.php?c=AdminController&m=kelolaPengguna" class="flex items-center px-4 py-2.5 rounded-lg bg-brand-yellow text-brand-blue font-semibold transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">...</svg>
                        Kelola Pengguna
                    </a>
                    <a href="index.php?c=AdminController&m=logAktivitas" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 opacity-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">...</svg>
                        Log Aktivitas
                    </a>
                </nav>
            </div>
            <div class="p-4 border-t border-gray-700/50">
                 <a href="index.php?c=AuthController&m=logout" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white transition-colors duration-200 group">
                    <svg class="w-5 h-5 mr-3 opacity-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">...</svg>
                    Keluar
                </a>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto bg-white">
            <header class="h-16 bg-header-bg flex items-center justify-between px-6 border-b sticky top-0 z-10">
                 <div class="relative">
                    <input type="text" placeholder="Telusuri Pengguna..." class="w-80 px-4 py-2 pl-10 bg-gray-100 rounded-full ... text-sm">
                     <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" ...>...</svg>
                </div>
                 <div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" type="button" class="flex items-center cursor-pointer group">
                         <span class="w-9 h-9 bg-brand-blue rounded-full ... mr-2"><?php echo strtoupper(substr(htmlspecialchars($namaPengguna), 0, 1)); ?></span>
                        <span class="text-sm font-medium ..."><?php echo htmlspecialchars($namaPengguna); ?></span>
                        <svg class="w-4 h-4 text-gray-400 ml-1 ..." ...>...</svg>
                    </button>
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 ... z-20" style="display: none;"> 
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                            <a href="index.php?c=AuthController&m=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Kelola Data Pengguna</h2>
                    <button id="openModalBtn" class="flex items-center bg-brand-blue text-white px-4 py-2 rounded-lg ... text-sm">
                        <svg class="w-5 h-5 mr-2" ...>...</svg>
                        Tambah Pengguna
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-x-auto border border-gray-100">
                    <table class="w-full text-left text-sm">
                         <thead class="bg-gray-50/50 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 ... text-xs">Nama Pengguna</th>
                                <th class="py-3 px-4 ... text-xs">Role</th>
                                <th class="py-3 px-4 ... text-xs">No. Telepon</th>
                                <th class="py-3 px-4 ... text-xs">Status</th>
                                <th class="py-3 px-4 ... text-xs">Aksi</th>
                            </tr>
                        </thead>
                         <tbody class="divide-y divide-gray-100">
                            <?php if (empty($daftarPengguna)): ?>
                                <tr>
                                    <td colspan="5" class="py-6 px-4 text-center text-gray-500 italic">Tidak ada data pengguna.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($daftarPengguna as $pengguna): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center space-x-3">
                                                <span class="w-9 h-9 bg-gray-200 ...">
                                                    <?php echo strtoupper(substr(htmlspecialchars($pengguna['nama']), 0, 1)); ?>
                                                </span>
                                                <div>
                                                    <p class="font-medium text-gray-800"><?php echo htmlspecialchars($pengguna['nama']); ?></p>
                                                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($pengguna['email']); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-gray-700">
                                            <?php
                                                $role = htmlspecialchars($pengguna['role']);
                                                $roleClass = 'bg-gray-100 text-gray-700';
                                                if ($role == 'admin') $roleClass = 'bg-red-100 text-red-700';
                                                if ($role == 'vendor') $roleClass = 'bg-blue-100 text-blue-700';
                                                if ($role == 'penyewa') $roleClass = 'bg-green-100 text-green-700';
                                            ?>
                                            <span class="px-2.5 py-1 <?php echo $roleClass; ?> rounded-full text-xs font-semibold">
                                                <?php echo ucfirst($role); ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-gray-700"><?php echo htmlspecialchars($pengguna['telepon']); ?></td>
                                        <td class="py-3 px-4">
                                            <?php
                                                $status = htmlspecialchars($pengguna['status']);
                                                $statusClass = ($status == 'aktif') ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                                                $statusText = ($status == 'aktif') ? 'Aktif' : 'Nonaktif';
                                            ?>
                                            <span class="px-2.5 py-1 <?php echo $statusClass; ?> rounded-full text-xs font-semibold">
                                                <?php echo $statusText; ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-1.5">
                                                 <button class="text-blue-600 ..." title="Edit">
                                                    <svg class="w-4 h-4" ...>...</svg>
                                                </button>
                                                <button class="text-red-600 ..." title="Hapus">
                                                    <svg class="w-4 h-4" ...>...</svg>
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
    <script>
        // Script untuk Alert (di-copy dari profile_vendor_edit.php)
         document.addEventListener('DOMContentLoaded', function() {
             function showAlert(message, isSuccess = true) {
                const alertContainer = document.getElementById('alert-container');
                const alertId = 'alert-' + Date.now();
                const alertColor = isSuccess ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
                const alertElement = document.createElement('div');
                alertElement.id = alertId;
                alertElement.className = `border px-4 py-3 rounded relative shadow ${alertColor}`;
                alertElement.setAttribute('role', 'alert');
                alertElement.innerHTML = `<strong class="font-bold">${isSuccess ? 'Berhasil!' : 'Error!'}</strong> <span class="block sm:inline">${message}</span> ...`;
                alertContainer.appendChild(alertElement);
                setTimeout(() => { closeAlert(alertId); }, 5000);
            }
             window.closeAlert = function(alertId) {
                 const alertToRemove = document.getElementById(alertId);
                 if(alertToRemove) { alertToRemove.remove(); }
             }
            <?php if ($error_message): ?> showAlert('<?php echo addslashes($error_message); ?>', false); <?php endif; ?>
            <?php if ($success_message): ?> showAlert('<?php echo addslashes($success_message); ?>', true); <?php endif; ?>
         });
    </script>
</body>
</html>
