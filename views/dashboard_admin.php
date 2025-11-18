<?php
// views/dashboard_admin.php
// (File ini tadinya sangat sederhana, kita buat agar mirip dengan dashboard vendor)
$namaPengguna = $namaPengguna ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIAP Mendaki</title>
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
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-brand-blue text-sidebar-text shadow-lg flex flex-col justify-between">
            <div>
                 <div class="h-20 flex items-center justify-center p-4 border-b border-gray-700/50">
                    <img src="src/logo-siap.png" alt="Logo SIAP Mendaki Putih" class="h-36"> 
                </div>
                <nav class="mt-4 px-3 space-y-1">
                    <a href="index.php?c=DashboardController&m=index" class="flex items-center px-4 py-2.5 rounded-lg bg-brand-yellow text-brand-blue font-semibold transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">...</svg>
                        Beranda
                    </a>
                    <a href="index.php?c=AdminController&m=kelolaPengguna" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 opacity-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">...</svg>
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
                    <input type="text" placeholder="Telusuri..." class="w-80 px-4 py-2 pl-10 bg-gray-100 rounded-full ... text-sm">
                     <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" ...>...</svg>
                </div>
                 <div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" type="button" class="flex items-center cursor-pointer group">
                         <span class="w-9 h-9 bg-brand-blue ... mr-2"><?php echo strtoupper(substr(htmlspecialchars($namaPengguna), 0, 1)); ?></span>
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
                <h1 class="text-2xl font-semibold text-gray-800">Selamat Datang, <?php echo htmlspecialchars($namaPengguna); ?>!</h1>
                <p class="text-gray-600">Ini adalah halaman dashboard Admin. Silakan pilih menu di sidebar.</p>
                
                </div>
        </main>
    </div>
</body>
</html>
