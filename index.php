<?php
// index.php (Root folder SIAP/)

// 0. Tampilkan Error untuk Debugging (Hapus di produksi)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Mulai Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tentukan Controller dan Method Default
$controllerName = $_GET['c'] ?? "AuthController"; // Default ke AuthController jika tidak ada 'c'
$methodName = $_GET['m'] ?? "loginView";          // Default ke loginView jika tidak ada 'm'

// --- Tambahan: Jika sudah login, arahkan ke Dashboard ---
if (isset($_SESSION['user_role']) && ($controllerName == "AuthController" && ($methodName == "loginView" || $methodName == "registerView"))) {
     $controllerName = "DashboardController";
     $methodName = "index";
}
// --- Akhir Tambahan ---

// Path ke file controller
$controllerFile = "controllers/" . $controllerName . ".php";

// Cek apakah file controller ada
if (file_exists($controllerFile)) {
    // 1. Muat Base Controller dan Base Model TERLEBIH DAHULU
    require_once "controllers/Controller.php";
    require_once "models/Model.php";

    // 2. Muat Controller yang diminta (misal: AuthController.php)
    require_once $controllerFile;

    // Cek apakah class controller ada
    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        // Cek apakah method ada di dalam controller
        if (method_exists($controller, $methodName)) {
            // Panggil method
            $controller->$methodName();
        } else {
            die("Error: Method '$methodName' not found in controller '$controllerName'.");
        }
    } else {
        die("Error: Controller class '$controllerName' not found.");
    }
} else {
    // Default jika controller tidak ditemukan (misal arahkan ke login atau tampilkan 404)
    // Untuk sekarang, kita tampilkan error saja
     if ($controllerName === "AuthController" && $methodName === "loginView") {
        // Jika default controller/method tidak ada, ini masalah serius
        die("Error: Default controller AuthController or its file not found.");
     } else {
        // Jika controller lain yang diminta tidak ada, mungkin arahkan ke halaman utama atau error
        // die("Error: Controller file '$controllerFile' not found.");
        // Atau redirect ke halaman login/dashboard default
        header("Location: index.php?c=AuthController&m=loginView");
        exit;
     }
}
?>