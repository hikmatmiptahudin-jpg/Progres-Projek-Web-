<?php
session_start();
// Jika sudah login, langsung lempar ke halaman dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Contract System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { max-width: 400px; margin-top: 10%; }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="card login-container shadow-sm p-4 w-100 bg-white rounded">
        <h3 class="text-center mb-4 font-weight-bold">Sign In</h3>
        
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
            <div class="alert alert-danger text-center py-2" role="alert">
                Username atau Password salah!
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'belum_login'): ?>
            <div class="alert alert-warning text-center py-2" role="alert">
                Silakan login terlebih dahulu!
            </div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required autocomplete="off">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100 py-2">Masuk</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>