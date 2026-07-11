<?php
session_start();
require 'function/koneksi.php';

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    if (!empty($username) && !empty($password)) {
        // SESUAI SCREENSHOT: Tabel 'userlogin', kolom 'username' & 'password'
        $query = "SELECT * FROM userlogin WHERE username='$username' AND password='$password'";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // Daftarkan session untuk halaman dalam
            $_SESSION['username'] = $row['username'];
            $_SESSION['nama']     = $row['username']; // Karena di tabel hanya ada username
            
            // Penentuan role sederhana (jika username admin maka Admin, selain itu Staff)
            $_SESSION['role']     = (strtolower($row['username']) == 'admin') ? 'Admin' : 'Staff'; 

            header("Location: pengajuancuti/index.php");
            exit;
        } else {
            $error = "Username atau Password Anda salah!";
        }
    } else {
        $error = "Silakan isi Username dan Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Login - Manajemen Cuti Karyawan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f4f6f9; 
            padding-top: 100px; 
        }
        .form-signin { 
            max-width: 330px; 
            padding: 19px 29px 29px; 
            margin: 0 auto 20px; 
            background-color: #D0E7E6; 
            border: 1px solid #e5e5e5; 
            border-radius: 5px; box-shadow: 0 1px 2px rgba(0,0,0,.05); 
        }
        .form-signin .form-signin-heading { 
            margin-bottom: 10px; 
            text-align: center; 
            font-size: 20px; 
            font-weight: bold; 
        }
        .form-signin input[type="text"], .form-signin input[type="password"] { 
            font-size: 16px; 
            height: auto; 
            margin-bottom: 15px; 
            padding: 7px 9px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- NAVBAR ATAS MENTOK KE UJUNG KANAN DAN KIRI -->
        <div class="navbar navbar-fixed-top">
        <div class="navbar-inner" style="background: linear-gradient(to right, #1f4068, #162447); border-bottom: 3px solid #293681; padding: 10px 0;">
        <!-- MENGGUNAKAN CONTAINER-FLUID AGAR LOGO DAN TOMBOL BISA KE UJUNG LAYAR -->
            <div class="container-fluid" style="padding-left: 20px; padding-right: 20px;">
            
            <!-- Sisi Kiri Ujung: Judul PT -->
            <a class="brand" href="#" style="color: #95ccdd; font-weight: bold; padding: 0; margin-top: 2px;">
                🏢 PT TETARA SOLUSI DIGITAL
            </a>

            <!-- Sisi Kanan Ujung: Teks dan Tombol Keluar Mentok Kanan -->
            <div class="pull-right" style="color: #fff; text-align: right; margin-right: 0;">
                <span class="muted" style="color: #cbd5e1 !important; font-style: italic; margin-right: 15px; font-size: 13px; line-height: 24px; display: inline-block; vertical-align: middle;">
                    Sistem Informasi Cuti Karyawan v1.0
                </span>
                
                <!-- TOMBOL KELUAR DI UJUNG KANAN -->
                <button type="button" class="btn btn-mini btn-danger" onclick="keluarAplikasi()" style="font-weight: bold; padding: 4px 12px; border-radius: 4px; display: inline-block; vertical-align: middle; margin: 0;">
                    ❌ Keluar
                </button>
            </div>

        </div>
    </div>
</div>
        <form class="form-signin" action="" method="POST">
            <h2 class="form-signin-heading">SISTEM CUTI LOGIN</h2>
            <hr>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <label>Username / NIK</label>
            <input type="text" name="username" class="input-block-level" placeholder="Masukkan Username" required autofocus>
            
            <label>Password</label>
            <input type="password" name="password" class="input-block-level" placeholder="Masukkan Password" required>
            
            <button class="btn btn-large btn-primary btn-block" type="submit" name="login">Masuk 🚪</button>
        </form>
    </div>
    <script type="text/javascript">
    function keluarAplikasi() {
        if (confirm("Apakah Anda yakin ingin keluar dan menutup aplikasi ini?")) {
            window.open('', '_self', '');
            window.close();
            window.location.href = "about:blank";
        }
    }
    </script>
</body>
</html>