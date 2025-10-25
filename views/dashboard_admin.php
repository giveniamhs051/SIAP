<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Selamat Datang, Admin <?php echo htmlspecialchars($namaPengguna ?? 'Pengguna'); ?>!</h1>
        <p>Ini adalah halaman dashboard Anda.</p>
         <p>Role Anda: <?php echo htmlspecialchars($rolePengguna ?? 'Tidak diketahui'); ?></p>
        <a href="index.php?c=AuthController&m=logout" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>