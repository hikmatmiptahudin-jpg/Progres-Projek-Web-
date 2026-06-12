<?php
session_start();
require_once 'koneksi.php';

// Proteksi halaman log
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Mengatur Header HTTP agar browser mengenali ini sebagai file excel unduhan
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Dokumen_E-Contract_" . date('Y-m-d') . ".xls");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ekspor Data Dokumen</title>
    <style>
        body { font-family: sans-serif; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <center>
        <h2>LAPORAN DATA DOKUMEN & PRODUK E-CONTRACT</h2>
        <p>Tanggal Unduh: <?= date('d-m-Y H:i:s'); ?></p>
    </center>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Dokumen</th>
                <th>Nama Produk / Proyek</th>
                <th>Deskripsi</th>
                <th>Status Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = "SELECT * FROM produk_dokumen ORDER BY id_dokumen DESC";
            $result = $koneksi->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $status_ttd = (!empty($row['ttd_digital'])) ? 'Sudah Tanda Tangan' : 'Pending / Belum TTD';
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['nomor_dokumen']; ?></td>
                        <td><?= $row['nama_produk']; ?></td>
                        <td><?= $row['deskripsi']; ?></td>
                        <td><?= $status_ttd; ?></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>Tidak ada data tersedia</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>