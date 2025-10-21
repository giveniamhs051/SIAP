<?php
// File: app/core/Controller.php
// Deskripsi: Kelas controller dasar yang akan di-extend oleh controller lain.

class Controller {
    /**
     * Memuat file view.
     * @param string $view Nama file view (tanpa .php)
     * @param array $data Data yang akan diekstrak untuk digunakan di view
     */
    public function view($view, $data = []) {
        // Mengekstrak array data menjadi variabel individual
        extract($data);
        
        $viewFile = 'app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die('View ' . $view . ' tidak ditemukan.');
        }
    }

    /**
     * Memuat dan menginstansiasi model.
     * @param string $model Nama kelas model
     * @return object Instance dari model
     */
    public function model($model) {
        $modelFile = 'app/models/' . $model . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            die('Model ' . $model . ' tidak ditemukan.');
        }
    }
}
?>
