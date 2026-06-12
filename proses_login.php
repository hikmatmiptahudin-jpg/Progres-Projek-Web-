<?php
session_start();
// Menghubungkan ke file koneksi database yang kita buat di Tahap 1
require_once 'koneksi.php';

if (isset($_POST['login'])) {
    // Mengamankan inputan dari SQL Injection
    $username = $koneksi->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Query mencari user berdasarkan username
    $query  = "SELECT * FROM users WHERE username = '$username'";
    $result = $koneksi->query($query);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Memverifikasi password hash yang ada di database
        if (password_verify($password, $row['password'])) {
            // Set session user jika berhasil login
            $_SESSION['id_user']      = $row['id_user'];
            $_SESSION['username']     = $row['username'];
            $_SESSION['nama_lengkap']  = $row['nama_lengkap'];
            $_SESSION['role']          = $row['role'];

            // Alihkan ke halaman dashboard utama
            header("Location: dashboard.php");
            exit;
        }
    }
    
    // Jika username atau password tidak cocok, kembalikan ke login dengan status gagal
    header("Location: login.php?pesan=gagal");
    exit;
} else {
    header("Location: login.php");
    exit;
}
?>