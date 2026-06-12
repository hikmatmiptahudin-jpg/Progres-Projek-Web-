<?php
// Konfigurasi Database
$host     = "localhost";
$username = "root";
$password = "";
$database = "db_proyek_web";

// Membuat koneksi ke MySQL
$koneksi = new mysqli($host, $username, $password, $database);

// Memeriksa apakah koneksi berhasil atau gagal
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}

// Set charset ke UTF-8 agar mendukung karakter universal
$koneksi->set_charset("utf8mb4");

// Koneksi berhasil (Bisa dikomentari jika sudah masuk tahap produksi)
// echo "Koneksi Berhasil!"; 
?>