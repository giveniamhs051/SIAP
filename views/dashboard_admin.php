<?php
// views/dashboard_admin.php
$namaPengguna = $namaPengguna ?? 'Admin';
$stats = $stats ?? ['admin' => 0, 'vendor' => 0, 'penyewa' => 0, 'logs_today' => 0];

// Helper Warna Random
function getRandomColorClass($name) {
    if (empty($name)) return 'bg-gray-500';
    $colors = ['bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500'];
    $index = ord(strtoupper(substr($name, 0, 1))) % count($colors);
    return $colors[$index];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIAP Mendaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
    <script>
        tailwind.config = { theme: { extend: { colors: { 'brand-blue': '#1E3A5F', 'brand-yellow': '#FFBE00', 'brand-gray': '#F8F9FA' } } } }
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-brand-gray">
    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR -->
        <aside class="w-64 bg-brand-blue text-white shadow-lg flex flex-col justify-between">
            <div>
                 <div class="h-20 flex items-center justify-center p-4 border-b border-gray-700/50">
                    <img src="src/logo-siap.png" alt="Logo" class="h-36"> 
                </div>
                <nav class="mt-4 px-3 space-y-1">
                    <a href="index.php?c=DashboardController&m=index" class="flex items-center px-4 py-2.5 rounded-lg bg-brand-yellow text-brand-blue font-semibold">Beranda</a>
                    <a href="index.php?c=AdminController&m=kelolaPengguna" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-blue-800 transition">Kelola Pengguna</a>
                    <a href="index.php?c=AdminController&m=logAktivitas" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-blue-800 transition">Log Aktivitas</a>
                </nav>
            </div>
            <div class="p-4 border-t border-gray-700/50">
                 <a href="index.php?c=AuthController&m=logout" class="flex items-center px-4 py-2.5 hover:bg-blue-800 rounded-lg">Keluar</a>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto bg-white">
            <!-- HEADER -->
            <header class="h-16 bg-white flex items-center justify-between px-6 border-b sticky top-0 z-10 shadow-sm">
                 <div class="text-gray-500 font-medium">Dashboard Overview</div>
                 <div class="flex items-center cursor-pointer" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                        <?php $bgClass = getRandomColorClass($namaPengguna); ?>
                        <span class="w-9 h-9 <?php echo $bgClass; ?> rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm">
                            <?php echo strtoupper(substr($namaPengguna, 0, 1)); ?>
                        </span>
                        <span class="text-sm font-medium text-gray-700"><?php echo htmlspecialchars($namaPengguna); ?></span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" class="absolute top-14 right-6 w-48 bg-white rounded-lg shadow-lg border py-1 z-20" style="display: none;">
                        <a href="index.php?c=AuthController&m=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
                    </div>
                </div>
            </header>

            <div class="p-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Selamat Datang, Admin!</h1>
                
                <!-- STATS CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <!-- Card Penyewa -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Penyewa</p>
                            <h3 class="text-2xl font-bold text-gray-800"><?php echo $stats['penyewa']; ?></h3>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <!-- Card Vendor -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Vendor</p>
                            <h3 class="text-2xl font-bold text-gray-800"><?php echo $stats['vendor']; ?></h3>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>
                    <!-- Card Admin -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Admin</p>
                            <h3 class="text-2xl font-bold text-gray-800"><?php echo $stats['admin']; ?></h3>
                        </div>
                        <div class="p-3 bg-red-100 rounded-full text-red-600">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                    </div>
                    <!-- Card Aktivitas -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Aktivitas Hari Ini</p>
                            <h3 class="text-2xl font-bold text-gray-800"><?php echo $stats['logs_today']; ?></h3>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full text-yellow-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- CHART SECTION -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Pengguna</h3>
                        <canvas id="userChart" height="100"></canvas>
                    </div>
                    <div class="bg-brand-blue p-6 rounded-xl shadow-sm text-white flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Halo Admin!</h3>
                            <p class="text-blue-100 text-sm">Pantau aktivitas sistem dan kelola pengguna dengan mudah melalui dashboard ini.</p>
                        </div>
                        <img src="https://placehold.co/400x300/1E3A5F/FFFFFF?text=Admin+Illustration" alt="Illustration" class="mt-4 opacity-50 rounded">
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        // Chart.js Setup
        const ctx = document.getElementById('userChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Penyewa', 'Vendor', 'Admin'],
                datasets: [{
                    label: 'Jumlah Pengguna',
                    data: [<?php echo $stats['penyewa']; ?>, <?php echo $stats['vendor']; ?>, <?php echo $stats['admin']; ?>],
                    backgroundColor: ['#10B981', '#3B82F6', '#EF4444'],
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>