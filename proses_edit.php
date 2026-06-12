<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $id_dokumen     = (int) $_POST['id_dokumen'];
    $nomor_dokumen  = $koneksi->real_escape_string($_POST['nomor_dokumen']);
    $nama_produk    = $koneksi->real_escape_string($_POST['nama_produk']);
    $deskripsi      = $koneksi->real_escape_string($_POST['deskripsi']);

    if ($id_dokumen <= 0) {
        echo "ID dokumen tidak valid.";
        exit;
    }

    $query_utama = "UPDATE produk_dokumen SET nomor_dokumen = '$nomor_dokumen', nama_produk = '$nama_produk', deskripsi = '$deskripsi' WHERE id_dokumen = '$id_dokumen'";

    if ($koneksi->query($query_utama)) {
        if (!empty($_FILES['berkas']['name'][0])) {
            $total_files = count($_FILES['berkas']['name']);
            $folder_tujuan = 'uploads/';

            for ($i = 0; $i < $total_files; $i++) {
                $nama_file_asli = $_FILES['berkas']['name'][$i];
                $tmp_file       = $_FILES['berkas']['tmp_name'][$i];

                if (empty($nama_file_asli)) {
                    continue;
                }

                $nama_file_baru = time() . '_' . uniqid() . '_' . $nama_file_asli;
                $path_tujuan    = $folder_tujuan . $nama_file_baru;

                if (move_uploaded_file($tmp_file, $path_tujuan)) {
                    $query_file = "INSERT INTO dokumen_files (id_dokumen, nama_file) VALUES ('$id_dokumen', '$nama_file_baru')";
                    $koneksi->query($query_file);
                }
            }
        }

        header("Location: dashboard.php?status=sukses_edit");
        exit;
    }

    echo "Gagal memperbarui data: " . $koneksi->error;
} else {
    header("Location: dashboard.php");
    exit;
}
?>