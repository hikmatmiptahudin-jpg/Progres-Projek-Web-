<?php
session_start();
require_once 'koneksi.php';

// Pastikan request datang dari AJAX POST dengan data yang lengkap
if (isset($_POST['id_dokumen']) && isset($_POST['gambar_ttd'])) {
    $id_dokumen = $koneksi->real_escape_string($_POST['id_dokumen']);
    $gambar_ttd = $koneksi->real_escape_string($_POST['gambar_ttd']);

    // Melakukan update data string gambar Base64 ke dalam database
    $query = "UPDATE produk_dokumen SET ttd_digital = '$gambar_ttd' WHERE id_dokumen = '$id_dokumen'";

    if ($koneksi->query($query)) {
        echo "sukses"; // Response wajib berupa kata 'sukses' agar dibaca benar oleh AJAX
    } else {
        echo "Database Error: " . $koneksi->error;
    }
} else {
    echo "Akses ditolak. Data tidak lengkap.";
}
?>