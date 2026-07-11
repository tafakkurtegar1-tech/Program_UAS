<?php
session_start();
require '../function/koneksi.php';

// Membuka koneksi database
koneksi_buka();

// Proteksi: Pastikan hanya admin/atasan yang bisa mengakses file ini
if (empty($_SESSION['username']) || ($_SESSION['username'] !== 'admin' && $_SESSION['username'] !== '678901')) {
    header("Location: ../index.php");
    exit;
}

// Tangkap ID Pengajuan Cuti yang akan dihapus dari URL
if (isset($_GET['id'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Jalankan query menghapus data di tabel pengajuancuti
    $query_hapus = mysqli_query($koneksi, "DELETE FROM pengajuancuti WHERE idpengajuancuti = '$id_hapus'");

    if ($query_hapus) {
        // Juga bersihkan log transaksi di tabel approvecuti jika data tersebut pernah diapprove
        mysqli_query($koneksi, "DELETE FROM approvecuti WHERE idpengajuancuti = '$id_hapus'");

        echo "<script>
                alert('Sukses! Data pengajuan $id_hapus telah dihapus dari sistem.');
                window.location.href='index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');
                window.location.href='index.php';
              </script>";
    }
} else {
    header("Location: index.php");
}

// Tutup koneksi database
koneksi_tutup();
?>