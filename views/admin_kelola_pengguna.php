<?php
// views/admin_kelola_pengguna.php
$namaPengguna = $namaPengguna ?? 'Admin';
$daftarPengguna = $daftarPengguna ?? [];
$keyword = $keyword ?? '';

// Pesan flash
$error_message = $_SESSION['error_message'] ?? null;
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);

// Helper Warna Random untuk Profil
function getRandomColorClass($name) {
    $colors = [
        'bg-red-500', 'bg-orange-500', 'bg-amber-500', 'bg-yellow-500', 
        'bg-lime-500', 'bg-green-500', 'bg-emerald-500', 'bg-teal-500', 
        'bg-cyan-500', 'bg-sky-500', 'bg-blue-500', 'bg-indigo-500', 
        'bg-violet-500', 'bg-purple-500', 'bg-fuchsia-500', 'bg-pink-500', 'bg-rose-500'
    ];
    // Gunakan nilai numerik dari karakter pertama nama untuk memilih warna secara konsisten
    $index = ord(strtoupper(substr($name, 0, 1))) % count($colors);
    return $colors[$index];
}
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
        [x-cloak] { display: none !important; }
    </style>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'brand-blue': '#1E3A5F', 'brand-yellow': '#FFBE00', 'brand-gray': '#F8F9FA' } } }
        }
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-brand-gray" x-data="{ showAddModal: false }">
    
    <div id="alert-container" class="fixed top-5 right-5 z-[100] space-y-2"></div>

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR (Sama seperti sebelumnya) -->
        <aside class="w-64 bg-brand-blue text-white shadow-lg flex flex-col justify-between">
            <div>
                 <div class="h-20 flex items-center justify-center p-4 border-b border-gray-700/50">
                    <img src="src/logo-siap.png" alt="Logo" class="h-36"> 
                </div>
                <nav class="mt-4 px-3 space-y-1">
                    <a href="index.php?c=DashboardController&m=index" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-blue-800 transition">Beranda</a>
                    <a href="index.php?c=AdminController&m=kelolaPengguna" class="flex items-center px-4 py-2.5 rounded-lg bg-brand-yellow text-brand-blue font-semibold">Kelola Pengguna</a>
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
                 <!-- Form Pencarian Global -->
                 <form action="index.php" method="GET" class="relative">
                    <input type="hidden" name="c" value="AdminController">
                    <input type="hidden" name="m" value="kelolaPengguna">
                    <input type="text" name="q" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Cari pengguna..." class="w-80 px-4 py-2 pl-10 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-brand-yellow text-sm">
                     <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                </form>

                 <div class="flex items-center cursor-pointer">
                     <span class="w-9 h-9 bg-brand-blue rounded-full flex items-center justify-center text-white text-sm font-bold mr-2 shadow-sm">
                        <?php echo strtoupper(substr(htmlspecialchars($namaPengguna), 0, 1)); ?>
                    </span>
                    <span class="text-sm font-medium text-gray-700"><?php echo htmlspecialchars($namaPengguna); ?></span>
                </div>
            </header>

            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Kelola Data Pengguna</h2>
                    
                    <!-- TOMBOL TAMBAH PENGGUNA (Buka Modal) -->
                    <button @click="showAddModal = true" class="flex items-center bg-brand-blue text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-colors text-sm font-medium shadow">
                        <!-- Simbol Tambah -->
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Pengguna
                    </button>
                </div>

                <!-- TABEL DATA -->
                <div class="bg-white rounded-lg shadow-md overflow-x-auto border border-gray-100">
                    <table class="w-full text-left text-sm">
                         <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Nama Pengguna</th>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Role</th>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase text-xs">No. Telepon</th>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Status</th>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Aksi</th>
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
                                                <!-- LOGIKA WARNA RANDOM -->
                                                <?php $bgClass = getRandomColorClass($pengguna['nama']); ?>
                                                <span class="w-9 h-9 <?php echo $bgClass; ?> rounded-full flex items-center justify-center text-white font-bold shadow-sm">
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
                                            <span class="px-2.5 py-1 <?php echo $roleClass; ?> rounded-full text-xs font-semibold capitalize">
                                                <?php echo $role; ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-gray-700"><?php echo htmlspecialchars($pengguna['telepon']); ?></td>
                                        <td class="py-3 px-4">
                                            <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Aktif</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                <a href="index.php?c=AdminController&m=hapusPengguna&id=<?php echo $pengguna['user_id']; ?>&role=<?php echo $pengguna['role']; ?>" 
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')"
                                                   class="p-1.5 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition" title="Hapus">
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.576 0c-.342.052-.682.107-1.022.166m11.554 0A48.108 48.108 0 0 0 9.25 5.397M4.772 5.79m14.456 0a48.108 48.108 0 0 1-3.478-.397m-12.576 0c.342.052.682.107 1.022.166m-1.022-.165L5.84 3.12a2.25 2.25 0 0 1 2.244-2.077h6.832a2.25 2.25 0 0 1 2.244 2.077L19.228 5.79m-14.456 0A48.108 48.108 0 0 1 9.25 5.397" />
                                                    </svg>
                                                </a>
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

    <!-- MODAL TAMBAH PENGGUNA -->
    <div x-show="showAddModal" 
         x-cloak
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden"
             @click.outside="showAddModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="bg-brand-blue px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white">Tambah Pengguna Baru</h3>
                <button @click="showAddModal = false" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <form action="index.php?c=AdminController&m=tambahPengguna" method="POST" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-yellow focus:border-brand-yellow">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-yellow focus:border-brand-yellow">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="tel" name="telepon" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-yellow focus:border-brand-yellow">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-yellow focus:border-brand-yellow">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-yellow focus:border-brand-yellow bg-white">
                        <option value="penyewa">Penyewa</option>
                        <option value="vendor">Vendor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-brand-blue text-white rounded-lg hover:bg-opacity-90 font-medium">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
         document.addEventListener('DOMContentLoaded', function() {
             function showAlert(message, isSuccess = true) {
                const alertContainer = document.getElementById('alert-container');
                const alertId = 'alert-' + Date.now();
                const alertColor = isSuccess ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
                const alertElement = document.createElement('div');
                alertElement.id = alertId;
                alertElement.className = `border px-4 py-3 rounded relative shadow ${alertColor}`;
                alertElement.innerHTML = `<strong class="font-bold">${isSuccess ? 'Berhasil!' : 'Error!'}</strong> <span class="block sm:inline">${message}</span>`;
                alertContainer.appendChild(alertElement);
                setTimeout(() => { 
                    const el = document.getElementById(alertId); 
                    if(el) el.remove(); 
                }, 5000);
            }
            <?php if ($error_message): ?> showAlert('<?php echo addslashes($error_message); ?>', false); <?php endif; ?>
            <?php if ($success_message): ?> showAlert('<?php echo addslashes($success_message); ?>', true); <?php endif; ?>
         });
    </script>
</body>
</html>