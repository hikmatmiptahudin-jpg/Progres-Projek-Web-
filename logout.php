<?php
session_start();
// Hapus semua data di dalam session
$_SESSION = [];
session_unset();
session_destroy();

// Alihkan kembali ke halaman login
header("Location: login.php");
exit;
?>