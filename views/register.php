<?php
    // Helper untuk keamanan dasar output
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }

    // Cek session untuk pesan error
    $error_message = $_SESSION['error_message'] ?? null;
    unset($_SESSION['error_message']); // Hapus setelah dibaca
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - SIAP Mendaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'brand-blue': '#174962', 'brand-yellow': '#FFBE00' } } }
        }
    </script>
</head>
<body class="bg-gray-50">

    <div class="min-h-screen flex items-center justify-center">
        <div class="flex flex-col md:flex-row bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4">
            <div class="hidden md:block w-full md:w-1/2 rounded-l-2xl bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=2070&auto=format&fit=crop')">
                <div class="bg-black bg-opacity-40 h-full w-full rounded-l-2xl flex flex-col justify-end p-8">
                    <h2 class="text-white text-3xl font-bold">Mulai Petualanganmu</h2>
                    <p class="text-white mt-2">Sewa semua perlengkapan pendakian terbaik di sini.</p>
                </div>
            </div>
            <div class="w-full md:w-1/2 p-8 md:p-12">
                <div class="text-center md:text-left mb-8">
                    <h1 class="text-3xl font-bold text-brand-blue">SIAP Mendaki</h1>
                    <p class="text-gray-500">Sistem Informasi Alat Pendakian</p>
                </div>
                <div id="register-form">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Buat Akun Baru</h2>
                    <p class="text-gray-600 mb-6">Lengkapi data untuk memulai.</p>

                    <form id="registerFormElement" action="index.php?c=AuthController&m=registerProcess" method="POST" class="space-y-4">
                        <div>
                            <label for="register-name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" id="register-name" name="nama" placeholder="Nama lengkap Anda" class="mt-1 block w-full px-4 py-3 bg-gray-100 border-transparent rounded-lg focus:ring-brand-yellow focus:border-brand-yellow focus:bg-white" required>
                        </div>
                        <div>
                            <label for="register-email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="register-email" name="email" placeholder="contoh@email.com" class="mt-1 block w-full px-4 py-3 bg-gray-100 border-transparent rounded-lg focus:ring-brand-yellow focus:border-brand-yellow focus:bg-white" required>
                        </div>

                        <div>
                            <label for="register-telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="tel" id="register-telepon" name="nomor_telepon" placeholder="Contoh: 081234567890" class="mt-1 block w-full px-4 py-3 bg-gray-100 border-transparent rounded-lg focus:ring-brand-yellow focus:border-brand-yellow focus:bg-white" required>
                        </div>

                        <div>
                            <label for="register-password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                            <input type="password" id="register-password" name="password" placeholder="Minimal 8 karakter" class="mt-1 block w-full px-4 py-3 bg-gray-100 border-transparent rounded-lg focus:ring-brand-yellow focus:border-brand-yellow focus:bg-white" required>
                        </div>
                        <div>
                            <label for="register-confirm-password" class="block text-sm font-medium text-gray-700">Konfirmasi Kata Sandi</label>
                            <input type="password" id="register-confirm-password" name="confirm_password" placeholder="Ulangi kata sandi" class="mt-1 block w-full px-4 py-3 bg-gray-100 border-transparent rounded-lg focus:ring-brand-yellow focus:border-brand-yellow focus:bg-white" required>
                        </div>

                         <div>
                            <label class="block text-sm font-medium text-gray-700">Daftar sebagai:</label>
                            <div class="mt-2 flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="role" value="penyewa" class="focus:ring-brand-yellow text-brand-blue" checked required>
                                    <span class="ml-2 text-sm text-gray-700">Penyewa</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="role" value="vendor" class="focus:ring-brand-yellow text-brand-blue" required>
                                    <span class="ml-2 text-sm text-gray-700">Vendor</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="role" value="admin" class="focus:ring-brand-yellow text-brand-blue" required>
                                    <span class="ml-2 text-sm text-gray-700">Admin</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="w-full bg-brand-blue text-white font-semibold py-3 px-4 rounded-lg hover:bg-opacity-90 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue">
                                Daftar
                            </button>
                        </div>
                    </form>
                    <p class="text-center text-sm text-gray-600 mt-6">
                        Sudah punya akun? <a href="index.php?c=AuthController&m=loginView" class="font-semibold text-brand-blue hover:underline">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div id="alert-container" class="fixed top-5 right-5 z-50 space-y-2"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi showAlert (tetap sama)
            function showAlert(message, isSuccess = true) {
                const alertContainer = document.getElementById('alert-container');
                const alertId = 'alert-' + Date.now();
                const alertColor = isSuccess ? 'bg-green-500' : 'bg-red-500';
                const alertElement = document.createElement('div');
                alertElement.id = alertId;
                alertElement.className = `flex items-center p-4 mb-4 text-sm text-white rounded-lg ${alertColor} shadow-lg`; // Dihapus: animate-pulse
                alertElement.innerHTML = `
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <div>${message}</div>
                `;
                alertContainer.appendChild(alertElement);
                setTimeout(() => {
                    const alertToRemove = document.getElementById(alertId);
                    if(alertToRemove) { alertToRemove.remove(); }
                }, 4000); // Tampilkan selama 4 detik
            }

            // PERBAIKAN: Hapus event listener submit JavaScript
            /*
            const registerFormElement = document.getElementById('registerFormElement');
            registerFormElement.addEventListener('submit', function(e) {
                e.preventDefault(); // <-- Ini dihapus
                // ... (Kode simulasi dihapus) ...
            });
            */

            // PERBAIKAN: Tampilkan pesan error dari PHP (Session)
            <?php if ($error_message): ?>
                showAlert('<?php echo addslashes($error_message); ?>', false);
            <?php endif; ?>
        });
    </script>

</body>
</html>