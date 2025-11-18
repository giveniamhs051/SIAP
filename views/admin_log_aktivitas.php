<?php
// views/admin_log_aktivitas.php
$namaPengguna = $namaPengguna ?? 'Admin';
$daftarLog = $daftarLog ?? [];
$filterAktif = $filterAktif ?? [];

// Pesan flash (jika ada)
$error_message = $_SESSION['error_message'] ?? null;
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);

// Helper Warna Random (Konsisten dengan halaman Kelola Pengguna)
function getRandomColorClass($name) {
    if (empty($name)) return 'bg-gray-500';
    $colors = [
        'bg-red-500', 'bg-orange-500', 'bg-amber-500', 'bg-yellow-500', 
        'bg-lime-500', 'bg-green-500', 'bg-emerald-500', 'bg-teal-500', 
        'bg-cyan-500', 'bg-sky-500', 'bg-blue-500', 'bg-indigo-500', 
        'bg-violet-500', 'bg-purple-500', 'bg-fuchsia-500', 'bg-pink-500', 'bg-rose-500'
    ];
    $index = ord(strtoupper(substr($name, 0, 1))) % count($colors);
    return $colors[$index];
}

// Helper Format Tanggal Waktu
function formatDateTime($datetime) {
    try {
        $date = date_create($datetime);
        if (!$date) return $datetime;
        // Format: 12 Feb 2025, 14:30
        return date_format($date, 'd M Y, H:i');
    } catch (Exception $e) {
        return $datetime;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - SIAP Mendaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c4c4c4; border-radius: 6px; }
        [x-cloak] { display: none !important; }
        
        /* Custom style untuk input datepicker */
        input[type="text"][id="filter-tanggal"] {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='%236b7280' class='w-6 h-6'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25rem;
        }
    </style>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'brand-blue': '#1E3A5F', 'brand-yellow': '#FFBE00', 'brand-gray': '#F8F9FA' } } }
        }
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-brand-gray">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR (Konsisten) -->
        <aside class="w-64 bg-brand-blue text-white shadow-lg flex flex-col justify-between">
            <div>
                 <div class="h-20 flex items-center justify-center p-4 border-b border-gray-700/50">
                    <img src="src/logo-siap.png" alt="Logo" class="h-36"> 
                </div>
                <nav class="mt-4 px-3 space-y-1">
                    <a href="index.php?c=DashboardController&m=index" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-blue-800 transition">Beranda</a>
                    <a href="index.php?c=AdminController&m=kelolaPengguna" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-blue-800 transition">Kelola Pengguna</a>
                    <a href="index.php?c=AdminController&m=logAktivitas" class="flex items-center px-4 py-2.5 rounded-lg bg-brand-yellow text-brand-blue font-semibold">Log Aktivitas</a>
                </nav>
            </div>
            <div class="p-4 border-t border-gray-700/50">
                 <a href="index.php?c=AuthController&m=logout" class="flex items-center px-4 py-2.5 hover:bg-blue-800 rounded-lg">Keluar</a>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto bg-white">
            <!-- HEADER (Konsisten dengan Profil Bulat) -->
            <header class="h-16 bg-white flex items-center justify-between px-6 border-b sticky top-0 z-10 shadow-sm">
                 <div class="relative text-gray-500 text-sm">
                    <span class="font-medium">Dashboard Admin</span> / <span class="text-brand-blue font-semibold">Log Aktivitas</span>
                </div>
                 <div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" type="button" class="flex items-center cursor-pointer group">
                         <!-- Profil Bulat di Header -->
                         <span class="w-9 h-9 bg-brand-blue rounded-full flex items-center justify-center text-white text-sm font-bold mr-2 shadow-sm">
                            <?php echo strtoupper(substr(htmlspecialchars($namaPengguna), 0, 1)); ?>
                        </span>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-brand-blue transition-colors">
                            <?php echo htmlspecialchars($namaPengguna); ?>
                        </span>
                        <svg class="w-4 h-4 text-gray-400 ml-1 group-hover:text-brand-blue transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-20" style="display: none;"> 
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                            <a href="index.php?c=AuthController&m=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Log Aktivitas Sistem</h2>

                <!-- FORM FILTER YANG LEBIH RAPI -->
                <form action="index.php" method="GET" class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
                    <input type="hidden" name="c" value="AdminController">
                    <input type="hidden" name="m" value="logAktivitas">
                    
                    <div class="flex flex-wrap items-end gap-4">
                        <!-- Filter Tanggal -->
                        <div class="w-full sm:w-auto">
                            <label for="filter-tanggal" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Rentang Tanggal</label>
                            <input type="text" id="filter-tanggal" name="tanggal" 
                                   value="<?php echo htmlspecialchars($filterAktif['tanggal_mulai'] ?? '' . ($filterAktif['tanggal_selesai'] ? ' - ' . $filterAktif['tanggal_selesai'] : '')); ?>" 
                                   placeholder="Pilih tanggal..." 
                                   class="w-64 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-brand-yellow focus:border-brand-yellow shadow-sm cursor-pointer">
                        </div>

                        <!-- Filter Role -->
                        <div class="w-full sm:w-auto">
                            <label for="filter-role" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Role Pengguna</label>
                            <select id="filter-role" name="role" class="w-40 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-brand-yellow focus:border-brand-yellow bg-white shadow-sm">
                                <option value="">Semua Role</option>
                                <option value="admin" <?php echo ($filterAktif['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                <option value="vendor" <?php echo ($filterAktif['role'] ?? '') == 'vendor' ? 'selected' : ''; ?>>Vendor</option>
                                <option value="penyewa" <?php echo ($filterAktif['role'] ?? '') == 'penyewa' ? 'selected' : ''; ?>>Penyewa</option>
                            </select>
                        </div>

                        <!-- Filter Aksi -->
                        <div class="w-full sm:w-auto">
                            <label for="filter-aksi" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Jenis Aksi</label>
                            <select id="filter-aksi" name="aksi" class="w-40 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-brand-yellow focus:border-brand-yellow bg-white shadow-sm">
                                <option value="">Semua Aksi</option>
                                <option value="LOGIN" <?php echo ($filterAktif['aksi'] ?? '') == 'LOGIN' ? 'selected' : ''; ?>>Login</option>
                                <option value="CREATE" <?php echo ($filterAktif['aksi'] ?? '') == 'CREATE' ? 'selected' : ''; ?>>Create</option>
                                <option value="UPDATE" <?php echo ($filterAktif['aksi'] ?? '') == 'UPDATE' ? 'selected' : ''; ?>>Update</option>
                                <option value="DELETE" <?php echo ($filterAktif['aksi'] ?? '') == 'DELETE' ? 'selected' : ''; ?>>Delete</option>
                            </select>
                        </div>

                        <!-- Tombol Filter -->
                        <div class="flex gap-2 pb-0.5">
                            <button type="submit" class="px-5 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-opacity-90 transition shadow-sm">
                                Terapkan
                            </button>
                            <a href="index.php?c=AdminController&m=logAktivitas" class="px-5 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- TABEL LOG -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                             <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="py-3 px-6 font-semibold text-gray-600 uppercase text-xs w-48">Waktu</th>
                                    <th class="py-3 px-6 font-semibold text-gray-600 uppercase text-xs">Pengguna</th>
                                    <th class="py-3 px-6 font-semibold text-gray-600 uppercase text-xs w-32">Aksi</th>
                                    <th class="py-3 px-6 font-semibold text-gray-600 uppercase text-xs">Keterangan Objek</th>
                                    <th class="py-3 px-6 font-semibold text-gray-600 uppercase text-xs w-32">IP Address</th>
                                </tr>
                            </thead>
                             <tbody class="divide-y divide-gray-100">
                                <?php if (empty($daftarLog)): ?>
                                    <tr>
                                        <td colspan="5" class="py-8 px-6 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <p>Belum ada aktivitas log yang tercatat.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($daftarLog as $log): ?>
                                        <tr class="hover:bg-gray-50 transition-colors group">
                                            <td class="py-4 px-6 text-gray-500 whitespace-nowrap text-xs">
                                                <?php echo formatDateTime($log['timestamp']); ?>
                                            </td>
                                            <td class="py-4 px-6">
                                                <div class="flex items-center space-x-3">
                                                    <!-- AVATAR BULAT DENGAN WARNA RANDOM -->
                                                    <?php $bgClass = getRandomColorClass($log['user_nama'] ?? '?'); ?>
                                                    <span class="w-8 h-8 <?php echo $bgClass; ?> rounded-full flex-shrink-0 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                                        <?php echo strtoupper(substr(htmlspecialchars($log['user_nama'] ?? '?'), 0, 1)); ?>
                                                    </span>
                                                    <div>
                                                        <p class="font-medium text-gray-800 text-sm"><?php echo htmlspecialchars($log['user_nama'] ?? 'Unknown'); ?></p>
                                                        <p class="text-xs text-gray-500 capitalize"><?php echo htmlspecialchars($log['user_role'] ?? '-'); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-6">
                                                <?php
                                                    $aksi = htmlspecialchars($log['aksi']);
                                                    $aksiClass = 'bg-gray-100 text-gray-600 border-gray-200';
                                                    // Badge Colors
                                                    if (strtoupper($aksi) == 'LOGIN') $aksiClass = 'bg-green-50 text-green-700 border-green-200';
                                                    if (strtoupper($aksi) == 'CREATE') $aksiClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                                    if (strtoupper($aksi) == 'UPDATE') $aksiClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                                    if (strtoupper($aksi) == 'DELETE') $aksiClass = 'bg-red-50 text-red-700 border-red-200';
                                                ?>
                                                <span class="px-2.5 py-1 border <?php echo $aksiClass; ?> rounded-md text-xs font-semibold inline-block text-center w-20 shadow-sm">
                                                    <?php echo $aksi; ?>
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 text-gray-700 text-sm truncate max-w-xs" title="<?php echo htmlspecialchars($log['objek']); ?>">
                                                <?php echo htmlspecialchars($log['objek']); ?>
                                            </td>
                                            <td class="py-4 px-6 text-gray-500 text-xs font-mono">
                                                <?php echo htmlspecialchars($log['ip_address']); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Footer kecil info jumlah data -->
                <?php if (!empty($daftarLog)): ?>
                <div class="mt-4 text-right text-xs text-gray-400">
                    Menampilkan <?php echo count($daftarLog); ?> aktivitas terakhir
                </div>
                <?php endif; ?>
                
            </div> 
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
    <script>
         document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Datepicker
            const datepickerEl = document.getElementById('filter-tanggal');
            if(datepickerEl) {
                 new Litepicker({
                    element: datepickerEl,
                    singleMode: false, // Mode Range
                    allowRepick: true,
                    format: 'YYYY-MM-DD',
                    separator: ' - ',
                    numberOfMonths: 1,
                    numberOfColumns: 1,
                    mobileFriendly: true,
                    buttonText: { apply: 'Terapkan', reset: 'Reset' },
                 });
            }
         });
    </script>
</body>
</html>