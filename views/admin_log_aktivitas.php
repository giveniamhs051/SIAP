<?php
// views/admin_log_aktivitas.php
$namaPengguna = $namaPengguna ?? 'Admin';
$daftarLog = $daftarLog ?? [];
$filterAktif = $filterAktif ?? [];

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
        input[type="text"][id="filter-tanggal"] {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="%236b7280" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" /></svg>');
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1rem;
            padding-right: 2.5rem;
        }
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
                    <a href="index.php?c=DashboardController&m=index" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white ...">
                        <svg class="w-5 h-5 mr-3 opacity-75" ...>...</svg>
                        Beranda
                    </a>
                    <a href="index.php?c=AdminController&m=kelolaPengguna" class="flex items-center px-4 py-2.5 rounded-lg text-sidebar-text/80 hover:bg-sidebar-hover hover:text-white ...">
                        <svg class="w-5 h-5 mr-3 opacity-75" ...>...</svg>
                        Kelola Pengguna
                    </a>
                    <a href="index.php?c=AdminController&m=logAktivitas" class="flex items-center px-4 py-2.5 rounded-lg bg-brand-yellow text-brand-blue font-semibold ...">
                        <svg class="w-5 h-5 mr-3" ...>...</svg>
                        Log Aktivitas
                    </a>
                </nav>
            </div>
            <div class="p-4 border-t border-gray-700/50">
                 <a href="index.php?c=AuthController&m=logout" class="flex items-center px-4 py-2.5 ...">
                    <svg class="w-5 h-5 mr-3 opacity-75" ...>...</svg>
                    Keluar
                </a>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto bg-white">
            <header class="h-16 bg-header-bg flex items-center justify-between px-6 border-b sticky top-0 z-10">
                 <div class="relative">
                    <input type="text" placeholder="Telusuri Log..." class="w-80 px-4 py-2 pl-10 bg-gray-100 rounded-full ... text-sm">
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
                            <a href="#" class="block px-4 py-2 text-sm ...">Profil Saya</a>
                            <a href="index.php?c=AuthController&m=logout" class="block px-4 py-2 text-sm ...">Logout</a>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Log Aktivitas Sistem</h2>

                <form action="index.php" method="GET" class="flex flex-wrap items-center gap-4 mb-6 p-4 bg-white rounded-lg shadow-md border border-gray-100">
                    <input type="hidden" name="c" value="AdminController">
                    <input type="hidden" name="m" value="logAktivitas">
                    
                    <div class="flex items-center space-x-2">
                        <label for="filter-tanggal" class="text-sm font-medium text-gray-600">Tanggal:</label>
                        <input type="text" id="filter-tanggal" name="tanggal" 
                               value="<?php echo htmlspecialchars($filterAktif['tanggal_mulai'] ?? '' . ($filterAktif['tanggal_selesai'] ? ' - ' . $filterAktif['tanggal_selesai'] : '')); ?>" 
                               placeholder="Pilih rentang tanggal" class="w-48 px-3 py-1.5 border border-gray-300 rounded-md text-sm ...">
                    </div>
                    <div class="flex items-center space-x-2">
                        <label for="filter-role" class="text-sm font-medium text-gray-600">Role:</label>
                        <select id="filter-role" name="role" class="px-3 py-1.5 border border-gray-300 rounded-md text-sm ... bg-white">
                            <option value="">Semua Role</option>
                            <option value="admin" <?php echo ($filterAktif['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="vendor" <?php echo ($filterAktif['role'] ?? '') == 'vendor' ? 'selected' : ''; ?>>Vendor</option>
                            <option value="penyewa" <?php echo ($filterAktif['role'] ?? '') == 'penyewa' ? 'selected' : ''; ?>>Penyewa</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label for="filter-aksi" class="text-sm font-medium text-gray-600">Aksi:</label>
                        <select id="filter-aksi" name="aksi" class="px-3 py-1.5 border border-gray-300 rounded-md text-sm ... bg-white">
                            <option value="">Semua Aksi</option>
                            <option value="LOGIN" <?php echo ($filterAktif['aksi'] ?? '') == 'LOGIN' ? 'selected' : ''; ?>>Login</option>
                            <option value="CREATE" <?php echo ($filterAktif['aksi'] ?? '') == 'CREATE' ? 'selected' : ''; ?>>Create</option>
                            <option value="UPDATE" <?php echo ($filterAktif['aksi'] ?? '') == 'UPDATE' ? 'selected' : ''; ?>>Update</option>
                            <option value="DELETE" <?php echo ($filterAktif['aksi'] ?? '') == 'DELETE' ? 'selected' : ''; ?>>Delete</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-1.5 bg-brand-blue text-white rounded-md text-sm font-medium ...">
                        Filter
                    </button>
                    <a href="index.php?c=AdminController&m=logAktivitas" class="px-4 py-1.5 bg-gray-200 text-gray-700 rounded-md text-sm font-medium ...">
                        Reset
                    </a>
                </form>

                <div class="bg-white rounded-lg shadow-md overflow-x-auto border border-gray-100">
                    <table class="w-full text-left text-sm">
                         <thead class="bg-gray-50/50 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 ... text-xs">Tanggal & Waktu</th>
                                <th class="py-3 px-4 ... text-xs">Pengguna</th>
                                <th class="py-3 px-4 ... text-xs">Aksi</th>
                                <th class="py-3 px-4 ... text-xs">Objek</th>
                                <th class="py-3 px-4 ... text-xs">IP Address</th>
                            </tr>
                        </thead>
                         <tbody class="divide-y divide-gray-100">
                            <?php if (empty($daftarLog)): ?>
                                <tr>
                                    <td colspan="5" class="py-6 px-4 text-center text-gray-500 italic">Tidak ada aktivitas log yang cocok dengan filter.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($daftarLog as $log): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 text-gray-600 whitespace-nowrap">
                                            <?php echo htmlspecialchars($log['timestamp']); ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($log['user_nama']); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($log['user_role']); ?></p>
                                        </td>
                                        <td class="py-3 px-4">
                                            <?php
                                                $aksi = htmlspecialchars($log['aksi']);
                                                $aksiClass = 'bg-gray-100 text-gray-700';
                                                if ($aksi == 'LOGIN') $aksiClass = 'bg-green-100 text-green-700';
                                                if ($aksi == 'CREATE') $aksiClass = 'bg-blue-100 text-blue-700';
                                                if ($aksi == 'UPDATE') $aksiClass = 'bg-yellow-100 text-yellow-700';
                                                if ($aksi == 'DELETE') $aksiClass = 'bg-red-100 text-red-700';
                                            ?>
                                            <span class="px-2.5 py-1 <?php echo $aksiClass; ?> rounded-full text-xs font-semibold">
                                                <?php echo $aksi; ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-gray-700">
                                            <?php echo htmlspecialchars($log['objek']); ?>
                                        </td>
                                        <td class="py-3 px-4 text-gray-600">
                                            <?php echo htmlspecialchars($log['ip_address']); ?>
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
    
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
    <script>
         document.addEventListener('DOMContentLoaded', function() {
            // Script untuk Alert (di-copy dari profile_vendor_edit.php)
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

            // --- Litepicker (Range) ---
            const datepickerEl = document.getElementById('filter-tanggal');
            if(datepickerEl) {
                 new Litepicker({
                    element: datepickerEl,
                    singleMode: false,
                    allowRepick: true,
                    format: 'YYYY-MM-DD',
                    separator: ' - ', // Penting untuk range
                    buttonText: { apply: 'Terapkan', reset: 'Reset' },
                 });
            }
         });
    </script>
</body>
</html>
