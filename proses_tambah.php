<?php
session_start();
require_once 'koneksi.php';

// Proteksi: Pastikan hanya user login yang bisa akses proses ini
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    // 1. Ambil dan bersihkan data teks inputan
    $nomor_dokumen = $koneksi->real_escape_string($_POST['nomor_dokumen']);
    $nama_produk   = $koneksi->real_escape_string($_POST['nama_produk']);
    $deskripsi     = $koneksi->real_escape_string($_POST['deskripsi']);

    // 2. Insert ke tabel utama (produk_dokumen) terlebih dahulu
    $query_utama = "INSERT INTO produk_dokumen (nomor_dokumen, nama_produk, deskripsi) VALUES ('$nomor_dokumen', '$nama_produk', '$deskripsi')";
    
    if ($koneksi->query($query_utama)) {
        // Ambil ID dokumen yang baru saja ter-insert otomatis (Auto Increment)
        $id_dokumen_baru = $koneksi->insert_id;

        // 3. Proses Upload Multiple File
        if (!empty($_FILES['berkas']['name'][0])) {
            $total_files = count($_FILES['berkas']['name']);
            $folder_tujuan = "uploads/";

            // Looping sebanyak jumlah file yang dipilih user
            for ($i = 0; $i < $total_files; $i++) {
                $nama_file_asli = $_FILES['berkas']['name'][$i];
                $tmp_file       = $_FILES['berkas']['tmp_name'][$i];
                
                // Hindari nama file kembar dengan menambahkan timestamp unik di depannya
                $nama_file_baru = time() . "_" . uniqid() . "_" . $nama_file_asli;
                $path_tujuan    = $folder_tujuan . $nama_file_baru;

                // Pindahkan file dari folder sementara server ke folder proyek 'uploads'
                if (move_uploaded_file($tmp_file, $path_tujuan)) {
                    // Masukkan informasi nama file ke tabel pendukung (dokumen_files)
                    $query_file = "INSERT INTO dokumen_files (id_dokumen, nama_file) VALUES ('$id_dokumen_baru', '$nama_file_baru')";
                    $koneksi->query($query_file);
                }
            }
        }

        // Jika semua proses sukses, lempar kembali ke dashboard dengan alert sukses
        echo "<script>
                alert('Data dan berkas berhasil disimpan!');
                window.location.href = 'dashboard.php';
              </script>";
        exit;
    } else {
        echo "Gagal menyimpan data utama: " . $koneksi->error;
    }
} else {
   header("Location: dashboard.php?status=sukses_tambah");
exit;
}
?>