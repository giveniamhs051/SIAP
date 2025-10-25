<?php
    // Helper untuk keamanan dasar output
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }

    // Cek session untuk pesan error atau sukses
    $error_message = $_SESSION['error_message'] ?? null;
    $success_message = $_SESSION['success_message'] ?? null;

    // Hapus pesan dari session setelah dibaca agar tidak muncul lagi
    unset($_SESSION['error_message']);
    unset($_SESSION['success_message']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIAP Mendaki</title>
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
                <div id="login-form">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Masuk ke Akun</h2>
                    <p class="text-gray-600 mb-6">Selamat datang kembali!</p>

                    <form id="loginFormElement" action="index.php?c=AuthController&m=loginProcess" method="POST" class="space-y-4">
                        <div>
                            <label for="login-email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="login-email" name="email" placeholder="contoh@email.com" class="mt-1 block w-full px-4 py-3 bg-gray-100 border-transparent rounded-lg focus:ring-brand-yellow focus:border-brand-yellow focus:bg-white" required>
                        </div>
                        <div>
                            <label for="login-password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                            <input type="password" id="login-password" name="password" placeholder="Masukkan kata sandi" class="mt-1 block w-full px-4 py-3 bg-gray-100 border-transparent rounded-lg focus:ring-brand-yellow focus:border-brand-yellow focus:bg-white" required>
                        </div>
                        <div class="text-right">
                            <a href="#" class="text-sm text-brand-blue hover:underline">Lupa kata sandi?</a>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-brand-blue text-white font-semibold py-3 px-4 rounded-lg hover:bg-opacity-90 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue">
                                Masuk
                            </button>
                        </div>
                    </form>
                    <p class="text-center text-sm text-gray-600 mt-6">
                        Belum punya akun? <a href="index.php?c=AuthController&m=registerView" class="font-semibold text-brand-blue hover:underline">Daftar di sini</a>
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
            const loginFormElement = document.getElementById('loginFormElement');
            loginFormElement.addEventListener('submit', function(e) {
                e.preventDefault(); // <-- Ini dihapus
                // ... (Kode simulasi dihapus) ...
            });
            */

            // PERBAIKAN: Tampilkan pesan dari PHP (Session)
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