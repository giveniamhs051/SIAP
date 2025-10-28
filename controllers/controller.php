<?php
// controllers/Controller.php
class Controller {

    /**
     * Memuat file model dan mengembalikannya.
     * @param string $modelName Nama file Model (tanpa .php)
     * @return object Instance dari Model
     */
    public function loadModel($modelName) {
        $modelFile = "models/" . $modelName . ".php";
        if (file_exists($modelFile)) {
            require_once $modelFile; // Model.php (base) sudah di-load di index.php
            if (class_exists($modelName)) {
                 return new $modelName();
            } else {
                 die("Error: Model class '$modelName' not found in '$modelFile'.");
            }

        } else {
            die("Error: Model file '$modelFile' not found.");
        }
    }

    /**
     * Memuat file view dan mengirimkan data ke dalamnya.
     * @param string $viewName Nama file view (tanpa .php)
     * @param array $data Data yang akan diekstrak menjadi variabel di view
     */
    public function loadView($viewName, $data = []) {
        // Mengekstrak array $data menjadi variabel individual
        // Contoh: $data = ['judul' => 'Tes'] akan membuat variabel $judul = 'Tes' di view
        extract($data);

        $viewFile = "views/" . $viewName . ".php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Error: View file '$viewFile' not found.");
        }
    }

     /**
      * Redirect ke URL lain dalam aplikasi.
      * @param string $controller Nama Controller tujuan
      * @param string $method Nama Method tujuan
      * @param array $params Parameter tambahan untuk URL (misal: ['id' => 1])
      */
     protected function redirect($controller, $method = 'index', $params = []) {
         $url = "index.php?c=$controller&m=$method";
         if (!empty($params)) {
             $url .= '&' . http_build_query($params);
         }
         header("Location: " . $url);
         exit();
     }
}
?>