<?php
require_once 'koneksi.php';

// Kita generate ulang password hash yang valid untuk 'admin123'
$password_baru = password_hash('admin123', PASSWORD_BCRYPT);

// Update data password milik user 'admin' di database
$query = "UPDATE users SET password = '$password_baru' WHERE username = 'admin'";

if ($koneksi->query($query)) {
    echo "<h3>Password Berhasil Diperbarui!</h3>";
    echo "Sekarang silakan kembali ke halaman <a href='login.php'>Login</a> dan masukkan:";
    echo "<br><b>Username:</b> admin";
    echo "<br><b>Password:</b> admin123";
} else {
    echo "Gagal memperbarui database: " . $koneksi->error;
}
?>