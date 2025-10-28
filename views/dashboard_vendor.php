<?php
// Ambil data dari controller (DashboardController.php)
// Variabel $namaPengguna dan $rolePengguna sudah tersedia
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Vendor - SIAP Mendaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        // Konfigurasi Tailwind (sesuaikan warna dengan mockup pesanan)
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
                        'button-detail': '#E5E7EB',
                        'button-detail-hover': '#D1D5DB'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-gray">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-brand-blue text-sidebar-text shadow-lg flex flex-col justify-between">
            <div>
                <div class="h-20 flex items-center justify-center p-4">
                     <img src="src/logo-siap.png" alt="Logo SIAP Mendaki" class="w-">
                </div>
                <nav class="mt-4 px-4 space-y-2">
                    <a href="index.php?c=DashboardController&m=index" class="flex items-center px-4 py-2.5 text-sm rounded-lg bg-brand-yellow text-brand-blue font-medium">
                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                        Beranda </a>
                    <a href="index.php?c=BarangController&m=index" class="flex items-center px-4 py-2.5 text-sm rounded-lg hover:bg-sidebar-hover transition-colors">
                        <svg class="w-5 h-5 mr-3 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                        Barang
                    </a>
                    <a href="index.php?c=PesananController&m=index" class="flex items-center px-4 py-2.5 text-sm rounded-lg hover:bg-sidebar-hover transition-colors">
                        <svg class="w-5 h-5 mr-3 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" /></svg>
                        Pesanan
                    </a>
                    <a href="#" class="flex items-center px-4 py-2.5 text-sm rounded-lg hover:bg-sidebar-hover transition-colors">
                       <svg class="w-5 h-5 mr-3 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                        Notifikasi
                    </a>
                    <a href="#" class="flex items-center px-4 py-2.5 text-sm rounded-lg hover:bg-sidebar-hover transition-colors">
                        <svg class="w-5 h-5 mr-3 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 18H7.5m3-6h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 12H7.5" /></svg>
                        Pengaturan
                    </a>
                </nav>
            </div>
            <div class="p-4 border-t border-gray-700"> <a href="index.php?c=AuthController&m=logout" class="flex items-center px-4 py-2.5 text-sm rounded-lg hover:bg-sidebar-hover transition-colors">
                    <svg class="w-5 h-5 mr-3 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" /></svg>
                    Keluar </a>
            </div>
        </aside>
        <main class="flex-1 overflow-y-auto bg-white"> <header class="h-16 bg-header-bg flex items-center justify-between px-6 border-b">
                 <div class="relative">
                    <input type="text" placeholder="Telusuri..." class="w-80 px-4 py-2 pl-10 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-brand-yellow text-sm">
                     <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                </div>
                 <div class="flex items-center space-x-5">
                    <button class="text-gray-500 hover:text-gray-700 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
                        <span class="absolute -top-1 -right-1 flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span></span>
                    </button>
                    <div class="flex items-center cursor-pointer group">
                         <span class="w-9 h-9 bg-brand-blue rounded-full flex items-center justify-center text-white text-sm font-semibold mr-2"><?php echo strtoupper(substr(htmlspecialchars($namaPengguna ?? 'V'), 0, 1)); ?></span>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-brand-blue"><?php echo htmlspecialchars($namaPengguna ?? 'Nama Toko'); ?></span>
                        <svg class="w-4 h-4 text-gray-400 ml-1 group-hover:text-brand-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </div>
                </div>
            </header>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border border-gray-200">
                        <div class="p-3 bg-gray-100 rounded-lg">
                           <svg class="w-6 h-6 text-brand-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A9.06 9.06 0 0 1 6 18.719m12 0a9.06 9.06 0 0 0-6-2.177M12 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 10.039a5.216 5.216 0 0 1-5.216-5.216c0-2.479 1.6-4.643 3.9-5.591A5.25 5.25 0 0 1 12 3a5.25 5.25 0 0 1 5.316 4.638 5.216 5.216 0 0 1 3.9 5.591c0 2.87-2.345 5.216-5.216 5.216ZM9 12a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm6 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" /></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">200+</p>
                            <p class="text-sm text-gray-500">Penyewa</p>
                        </div>
                    </div>
                     <div class="bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border border-gray-200">
                        <div class="p-3 bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6 text-brand-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">20+</p>
                            <p class="text-sm text-gray-500">Total Produk</p>
                        </div>
                    </div>
                     <div class="bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border border-gray-200">
                        <div class="p-3 bg-gray-100 rounded-lg">
                           <svg class="w-6 h-6 text-brand-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.75A.75.75 0 0 1 3 4.5h.75m0 0H21m-18 0h18M3 6h18M3 9h18M3 12h18M3 15h18M3 18h18M3 21h18M12 6.75h.008v.008H12V6.75Z" /></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">150+</p>
                            <p class="text-sm text-gray-500">Total Penyewaan</p>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-lg shadow-md border border-gray-200">
                        </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                     <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Trend Penyewaan</h3>
                        <div class="flex items-center space-x-4 mb-4 text-xs">
                            <div class="flex items-center"><span class="w-3 h-3 bg-blue-500 rounded-full mr-1.5"></span>Current Month</div>
                            <div class="flex items-center"><span class="w-3 h-3 bg-brand-yellow rounded-full mr-1.5"></span>Last Month</div>
                        </div>
                        <canvas id="trendChart"></canvas>
                    </div>
                    <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Produk</h3>
                         <div class="flex items-center space-x-4 mb-4 text-xs">
                            <div class="flex items-center"><span class="w-3 h-3 bg-brand-yellow rounded-full mr-1.5"></span>This Week</div>
                            <div class="flex items-center"><span class="w-3 h-3 bg-blue-300 rounded-full mr-1.5"></span>Last Week</div>
                        </div>
                        <canvas id="productStatsChart"></canvas>
                    </div>
                </div>
                 <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pesanan</h3>
                        <div class="overflow-x-auto">
                             <table class="w-full text-left text-sm">
                                <thead class="text-gray-500 border-b">
                                    <tr>
                                        <th class="py-2 font-normal">Nama Barang</th>
                                        <th class="py-2 font-normal">Kode Pesanan</th>
                                        <th class="py-2 font-normal">Nama Penyewa</th>
                                        <th class="py-2 font-normal">Harga</th>
                                        <th class="py-2 font-normal">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    <?php for($i = 0; $i < 5; $i++): // Contoh loop ?>
                                    <tr>
                                        <td class="py-3 flex items-center">
                                            <span class="p-2 bg-pink-100 rounded-lg mr-3 text-xs">🛍️</span>
                                            Tenda Dome #<?php echo $i+1; ?>
                                        </td>
                                        <td class="py-3 text-gray-600">#2025182<?php echo 9-$i; ?></td>
                                        <td class="py-3 text-gray-700">Abdul Fatah</td>
                                        <td class="py-3 text-gray-700">100.000</td>
                                        <td class="py-3">
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">● Selesai</span>
                                        </td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                         <h3 class="text-lg font-semibold text-gray-800 mb-4">Penyewaan Terpopuler</h3>
                         <div class="space-y-4">
                            <?php $items = ['Tenda', 'Carrier', 'Sleeping Bag', 'Kompor']; $widths = ['90%', '80%', '65%', '50%']; ?>
                            <?php foreach($items as $index => $item): ?>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-700"><?php echo $item; ?></span>
                                <div class="w-1/2 bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-brand-yellow h-1.5 rounded-full" style="width: <?php echo $widths[$index]; ?>"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                         </div>
                    </div>
                </div>
            </div>
        </main>
        </div>

    <script>
        // Data & Konfigurasi Chart.js (Sama seperti sebelumnya)
        document.addEventListener('DOMContentLoaded', () => {
             const trendCtx = document.getElementById('trendChart')?.getContext('2d');
             if (trendCtx) {
                 new Chart(trendCtx, { /* ... Konfigurasi chart ... */
                     type: 'line', data: { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'], datasets: [{ label: 'Current Month', data: [65, 59, 80, 81, 85, 90], borderColor: '#3B82F6', backgroundColor: 'rgba(59, 130, 246, 0.1)', fill: true, tension: 0.4 }, { label: 'Last Month', data: [50, 55, 60, 65, 60, 68], borderColor: '#FFBE00', backgroundColor: 'rgba(255, 190, 0, 0.1)', fill: true, tension: 0.4 }] }, options: { responsive: true, scales: { y: { beginAtZero: false, suggestedMin: 40 } }, plugins: { legend: { display: false } } }
                 });
             }
            const productCtx = document.getElementById('productStatsChart')?.getContext('2d');
            if(productCtx) {
                new Chart(productCtx, { /* ... Konfigurasi chart ... */
                     type: 'bar', data: { labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu'], datasets: [{ label: 'This Week', data: [12, 19, 13, 15, 12], backgroundColor: '#FFBE00', borderRadius: 4 }, { label: 'Last Week', data: [8, 15, 7, 9, 10], backgroundColor: '#93C5FD', borderRadius: 4 }] }, options: { responsive: true, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
                });
            }
        });
    </script>
</body>
</html>