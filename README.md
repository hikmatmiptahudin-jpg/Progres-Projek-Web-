<<<<<<< HEAD
# Progres-Projek-Web-
=======
# E-Contract System 

E-Contract System adalah aplikasi berbasis web sederhana yang dirancang untuk mengelola manajemen dokumen produk atau proyek serta mendukung proses verifikasi tanda tangan digital (Digital Signature) secara langsung pada papan digital berbasis HTML5 Canvas.

Aplikasi ini dibangun menggunakan bahasa pemrograman **PHP Native**, basis data **MySQL**, dan framework **Bootstrap 5** untuk antarmuka pengguna yang responsif.

---

## Fitur Utama

1. **Sistem Autentikasi Keamanan (Login & Proteksi Sesi):**
   - Pembatasan hak akses halaman `dashboard.php` menggunakan session PHP.
   - Keamanan kata sandi menggunakan enkripsi algoritma berekstensi `password_hash() BCRYPT`.

2. **Manajemen Dokumen Mutakhir (CRUD & Multiple Upload):**
   - Menambahkan data produk/proyek baru sekaligus mengunggah beberapa berkas pendukung (*multiple file uploads*) dalam satu formulir kerja.
   - Pemisahan nama file secara otomatis menggunakan penanda *timestamp* unik guna menghindari duplikasi data berkas di direktori server.

3. **Verifikasi Tanda Tangan Digital (HTML5 Canvas):**
   - Menuliskan guratan tanda tangan digital langsung pada layar komputer maupun *smartphone*.
   - Konversi guratan gambar menjadi string berbasis format data `Base64 Image DataURL` yang disimpan langsung di dalam database secara efisien tanpa membebani penyimpanan server.

4. **Widget Multimedia Interaktif & Notifikasi Suara (Text-to-Speech):**
   - Integrasi widget eksternal resmi dari **Tenor GIF Embed API** sebagai pengganti notifikasi konvensional saat verifikasi sukses (*Good Afternoon Sticker*) dan konfirmasi penutupan sesi logout (*Peach Goma Wave*).
   - Dukungan fitur asisten suara interaktif bawaan peramban (`window.speechSynthesis`) yang membacakan status konfirmasi dalam Bahasa Indonesia.

5. **Ringkasan Informasi Eksekutif (Dashboard Info Cards):**
   - Modul agregasi data statistik real-time yang memetakan jumlah total dokumen terkumpul, total dokumen sukses ttd, dan sisa dokumen tertunda (*pending*).

6. **Ekspor Data & Cetak Dokumen:**
   - Fitur konversi instan seluruh basis data menjadi file spreadsheet via komponen `ekspor_excel.php`.
   - Modul cetak laporan cetak fisik atau konversi dokumen digital (.pdf) dengan optimasi CSS media-print khusus yang otomatis menyembunyikan tombol aksi serta navigasi navbar aplikasi.

---

## Struktur Direktori File

```text
├── uploads/                  # Direktori penyimpanan berkas pendukung terunggah
├── buat_password.php         # Script utilitas generator enkripsi password hash awal
├── dashboard.php             # Halaman panel utama sistem & manajemen data tabel
├── ekspor_excel.php          # Aksi pembentuk dan pengunduh laporan Microsoft Excel (.xls)
├── koneksi.php               # Konfigurasi parameter kredensial pangkalan data MySQLi
├── login.php                 # Tampilan antarmuka formulir otorisasi masuk sistem
├── logout.php                # Pembersihan seluruh riwayat session dan keluar sistem
├── proses_edit.php           # Pemroses logika manipulasi modifikasi rekam dokumen
├── proses_login.php          # Validasi pencocokan akun masuk beserta verifikasi password_verify
├── proses_simpan_ttd.php     # Sinkronisasi AJAX penerima konversi string Base64 tanda tangan
└── proses_tambah.php         # Logika penyimpanan berkas baru beserta sistem loop upload file 
>>>>>>> 83f58c3 (first commit)
