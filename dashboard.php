<?php
session_start();

// PROTEKSI HALAMAN: Jika tidak ada session username (belum login), tendang ke login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php?pesan=belum_login");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - E-Contract System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f4f6f9; }
        .navbar-brand { font-weight: bold; }
        .wrapper { min-height: 100vh; display: flex; flex-direction: column; }
        .main-content { flex: 1; padding: 30px 0; }

        /* --- CSS KHUSUS KETIKA DICETAK MENJADI PDF --- */
        @media print {
            nav, .btn, .btn-group-wrapper, .modal, .dataTables_filter, .dataTables_length, .dataTables_info, .dataTables_paginate, th:last-child, td:last-child {
                display: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            body {
                background-color: #fff;
                padding: 0;
            }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fa-solid fa-file-contract me-2"></i>E-Contract</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item me-3">
                        <span class="nav-link text-white">
                            Halo, <strong><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></strong> 
                            <span class="badge bg-secondary ms-1"><?= ucfirst($_SESSION['role']); ?></span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger btn-sm" href="logout.php" onclick="return confirm('Yakin ingin logout?')">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <div class="row mb-4">
                <div class="col">
                    <h2 class="fw-bold">Sistem Manajemen Dokumen & Produk</h2>
                    <p class="text-muted">Kelola data, upload berkas, dan verifikasi tanda tangan digital di sini.</p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-dark"><i class="fa-solid fa-table me-2 text-primary"></i>Daftar Produk / Dokumen</h5>
                    <div class="btn-group-wrapper">
                        <a href="ekspor_excel.php" class="btn btn-success btn-sm me-1">
                            <i class="fa-solid fa-file-excel me-1"></i> Ekspor Excel
                        </a>
                        <button onclick="window.print()" class="btn btn-secondary btn-sm me-1">
                            <i class="fa-solid fa-print me-1"></i> Cetak PDF
                        </button>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Data Baru
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabelData" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Dokumen</th>
                                    <th>Nama Produk / Proyek</th>
                                    <th>Deskripsi</th>
                                    <th>Berkas</th>
                                    <th>Tanda Tangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once 'koneksi.php';

                                $query = "SELECT * FROM produk_dokumen ORDER BY id_dokumen DESC";
                                $result = $koneksi->query($query);

                                if ($result && $result->num_rows > 0) {
                                    $no = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        $id_dok = $row['id_dokumen'];
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($row['nomor_dokumen']); ?></td>
                                            <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                                            <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                                            <td>
                                                <?php
                                                $query_file = "SELECT * FROM dokumen_files WHERE id_dokumen = '$id_dok'";
                                                $result_file = $koneksi->query($query_file);
                                                
                                                if ($result_file && $result_file->num_rows > 0) {
                                                    echo "<ul class='ps-3 m-0 small'>";
                                                    while ($file = $result_file->fetch_assoc()) {
                                                        $nama_tampil = explode("_", $file['nama_file'], 3);
                                                        $nama_asli = isset($nama_tampil[2]) ? $nama_tampil[2] : $file['nama_file'];
                                                        
                                                        echo "<li><a href='uploads/" . $file['nama_file'] . "' target='_blank'>" . htmlspecialchars($nama_asli) . "</a></li>";
                                                    }
                                                    echo "</ul>";
                                                } else {
                                                    echo "<span class='text-muted small'>Tidak ada berkas</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($row['ttd_digital'])): ?>
                                                    <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> Sudah TTD</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i> Pending</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-warning btn-sm btn-edit"
                                                    data-id="<?= $id_dok; ?>"
                                                    data-nomor="<?= htmlspecialchars($row['nomor_dokumen'], ENT_QUOTES); ?>"
                                                    data-nama="<?= htmlspecialchars($row['nama_produk'], ENT_QUOTES); ?>"
                                                    data-deskripsi="<?= htmlspecialchars($row['deskripsi'], ENT_QUOTES); ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEdit">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </button>
                                                <button class="btn btn-success btn-sm btn-ttd" data-id="<?= $id_dok; ?>" data-bs-toggle="modal" data-bs-target="#modalTTD">
                                                    <i class="fa-solid fa-pen-nib"></i> TTD
                                                </button>
                                                <a href="proses_hapus.php?id=<?= $id_dok; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini beserta seluruh berkasnya?')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Belum ada data dokumen yang tersimpan</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahLabel"><i class="fa-solid fa-plus me-2"></i>Tambah Produk / Dokumen Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nomor_dokumen" class="form-label fw-bold">Nomor Dokumen / SKU</label>
                            <input type="text" class="form-control" id="nomor_dokumen" name="nomor_dokumen" placeholder="Contoh: DOC-2026-001" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nama_produk" class="form-label fw-bold">Nama Produk / Proyek</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" placeholder="Masukkan nama produk" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-bold">Deskripsi / Keterangan</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Jelaskan detail spesifikasi produk atau dokumen..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="berkas" class="form-label fw-bold">Upload Berkas Pendukung (Bisa pilih banyak file)</label>
                        <input type="file" class="form-control" id="berkas" name="berkas[]" multiple required>
                        <small class="text-muted">*Format berkas bebas (PDF, JPG, PNG, Excel, dll). Kamu bisa memilih lebih dari 1 file sekaligus menggunakan tombol Ctrl / Shift.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalEditLabel"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Produk / Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_dokumen" id="edit_id_dokumen">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_nomor_dokumen" class="form-label fw-bold">Nomor Dokumen / SKU</label>
                            <input type="text" class="form-control" id="edit_nomor_dokumen" name="nomor_dokumen" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_nama_produk" class="form-label fw-bold">Nama Produk / Proyek</label>
                            <input type="text" class="form-control" id="edit_nama_produk" name="nama_produk" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label fw-bold">Deskripsi / Keterangan</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_berkas" class="form-label fw-bold">Tambah Berkas Baru (Opsional)</label>
                        <input type="file" class="form-control" id="edit_berkas" name="berkas[]" multiple>
                        <small class="text-muted">Berkas yang sudah ada tidak dihapus. Jika memilih file baru, file tersebut akan ditambahkan ke data ini.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan" class="btn btn-warning"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTTD" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalTTDLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalTTDLabel"><i class="fa-solid fa-signature me-2"></i>Goreskan Tanda Tangan Digital</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted small mb-2">Gunakan mouse atau layar sentuh HP untuk menandatangani berkas:</p>
                
                <input type="hidden" id="ttd_id_dokumen">
                
                <canvas id="canvasSignature" width="400" height="200" style="border: 2px dashed #ccc; background-color: #fafafa; border-radius: 8px; cursor: crosshair;"></canvas>
                
                <div class="mt-2">
                    <button type="button" class="btn btn-warning btn-sm" id="btn_clear_canvas">
                        <i class="fa-solid fa-eraser me-1"></i> Bersihkan Papan
                    </button>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btn_simpan_ttd"><i class="fa-solid fa-check me-1"></i> Verifikasi TTD</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAnimasiSukses" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 text-center shadow-lg" style="border-radius: 15px;">
            <div class="modal-body p-4">
                
                <div style="width: 150px; height: 150px; margin: 0 auto; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <div class="tenor-gif-embed" data-postid="1717137426646686750" data-share-method="host" data-aspect-ratio="1" data-width="100%">
                        <a href="https://tenor.com/view/good-afternoon-gif-1717137426646686750">Good Afternoon Sticker</a>from <a href="https://tenor.com/search/good+afternoon-stickers">Good Afternoon Stickers</a>
                    </div>
                </div>
                
                <h5 class="fw-bold text-success mt-3" id="judulAnimasiSukses">Berhasil!</h5>
                <p class="text-muted small m-0" id="pesanAnimasiSukses">Aksi Anda telah berhasil diproses.</p>
                <button type="button" class="btn btn-success btn-sm mt-3 px-4" data-bs-dismiss="modal" id="btnTutupAnimasi">Selesai</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" async src="https://tenor.com/embed.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables Bahasa Indonesia
        $('#tabelData').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
            }
        });

        // --- FUNGSI AUDIO NOTIFIKASI (Text-to-Speech) ---
        function suarakanNotifikasi(teks) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                
                const speech = new SpeechSynthesisUtterance(teks);
                speech.lang = 'id-ID';
                speech.rate = 1.0;
                speech.pitch = 1.0;
                window.speechSynthesis.speak(speech);
            }
        }

        // --- FUNGSI UNTUK MEMICU POPUP MODAL ---
        function tampilkanMediaSukses(judul, pesan, teksSuara, aksiReload = false) {
            $('#judulAnimasiSukses').text(judul);
            $('#pesanAnimasiSukses').text(pesan);
            
            // Buka Jendela Popup Modal
            let modalAnimasi = new bootstrap.Modal(document.getElementById('modalAnimasiSukses'));
            modalAnimasi.show();
            
            // Jalankan Notifikasi Suara
            suarakanNotifikasi(teksSuara);

            // Handler ketika tombol selesai di-klik
            $('#btnTutupAnimasi').off('click').on('click', function() {
                if (aksiReload) {
                    window.location.href = 'dashboard.php';
                }
            });
        }

        // --- DETEKSI PARAMETER URL DARI REDIRECT TAMBAH DATA ---
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('status') === 'sukses_tambah') {
            tampilkanMediaSukses(
                "Simpan Berhasil!",
                "Data dokumen baru berhasil ditambahkan.",
                "Data dokumen baru berhasil disimpan ke dalam sistem.",
                true
            );

            const pancingAutoplay = () => {
                suarakanNotifikasi("Data dokumen baru berhasil disimpan ke dalam sistem.");
                document.removeEventListener('click', pancingAutoplay);
            };
            document.addEventListener('click', pancingAutoplay);
        } else if (urlParams.get('status') === 'sukses_edit') {
            tampilkanMediaSukses(
                "Edit Berhasil!",
                "Data dokumen berhasil diperbarui.",
                "Data dokumen berhasil diperbarui di dalam sistem.",
                true
            );

            const pancingAutoplay = () => {
                suarakanNotifikasi("Data dokumen berhasil diperbarui di dalam sistem.");
                document.removeEventListener('click', pancingAutoplay);
            };
            document.addEventListener('click', pancingAutoplay);
        }

        $(document).on('click', '.btn-edit', function() {
            $('#edit_id_dokumen').val($(this).data('id'));
            $('#edit_nomor_dokumen').val($(this).data('nomor'));
            $('#edit_nama_produk').val($(this).data('nama'));
            $('#edit_deskripsi').val($(this).data('deskripsi'));
            $('#edit_berkas').val('');
        });

        // --- OPERASI HTML5 CANVAS SIGNATURE ---
        const canvas = document.getElementById('canvasSignature');
        const ctx = canvas.getContext('2d');
        let isDrawing = false;

        ctx.strokeStyle = "#000000";
        ctx.lineWidth = 3;
        ctx.lineCap = "round";

        function getMousePos(e) {
            const rect = canvas.getBoundingClientRect();
            return {
                x: (e.clientX || e.touches[0].clientX) - rect.left,
                y: (e.clientY || e.touches[0].clientY) - rect.top
            };
        }

        function startDrawing(e) {
            isDrawing = true;
            const pos = getMousePos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
            e.preventDefault();
        }

        function draw(e) {
            if (!isDrawing) return;
            const pos = getMousePos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            e.preventDefault();
        }

        function stopDrawing() { isDrawing = false; }

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        window.addEventListener('mouseup', stopDrawing);

        canvas.addEventListener('touchstart', startDrawing);
        canvas.addEventListener('touchmove', draw);
        window.addEventListener('touchend', stopDrawing);

        $('#btn_clear_canvas').click(function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });

        $(document).on('click', '.btn-ttd', function() {
            const idDokumen = $(this).data('id');
            $('#ttd_id_dokumen').val(idDokumen);
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });

        // Kirim Gambar Canvas Base64 via AJAX
        $('#btn_simpan_ttd').click(function() {
            const idDokumen = $('#ttd_id_dokumen').val();
            const dataURL = canvas.toDataURL('image/png');

            const blankCanvas = document.createElement('canvas');
            blankCanvas.width = canvas.width;
            blankCanvas.height = canvas.height;
            if (dataURL === blankCanvas.toDataURL('image/png')) {
                alert("Silakan goreskan tanda tangan terlebih dahulu!");
                return;
            }

            $.ajax({
                url: 'proses_simpan_ttd.php',
                type: 'POST',
                data: {
                    id_dokumen: idDokumen,
                    gambar_ttd: dataURL
                },
                success: function(response) {
                    if (response.trim() === "sukses") {
                        // Sembunyikan modal canvas tanda tangan
                        bootstrap.Modal.getInstance(document.getElementById('modalTTD')).hide();
                        
                        // Jalankan popup animasi beserta suaranya
                        tampilkanMediaSukses(
                            "Verifikasi Sukses!",
                            "Tanda tangan digital Anda telah berhasil direkam.",
                            "Tanda tangan digital berhasil diverifikasi dan diperbarui.",
                            false
                        );

                        // Reload tabel utama ketika modal animasi ditutup oleh user
                        $('#btnTutupAnimasi').off('click').on('click', function() {
                            window.location.reload();
                        });
                    } else {
                        alert("Gagal menyimpan TTD: " + response);
                    }
                },
                error: function() {
                    alert("Terjadi kesalahan sistem jaringan. Pastikan file proses_simpan_ttd.php sudah ada.");
                }
            });
        });
    });
</script>
</body>
</html>