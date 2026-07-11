<?php
session_start();
require '../function/koneksi.php';

// Buka koneksi database
koneksi_buka();

// Tangkap data dari AJAX aplikasi.js
$idpengajuancuti = isset($_POST['id']) ? mysqli_real_escape_string($koneksi, $_POST['id']) : '';
$status_baru     = isset($_POST['status']) ? mysqli_real_escape_string($koneksi, $_POST['status']) : '';
$username_atasan = $_SESSION['username']; // NIK manager yang sedang login

if (!empty($idpengajuancuti) && !empty($status_baru)) {
    
    // 1. Update status di tabel pengajuancuti (menjadi 'Disetujui' atau 'Ditolak')
    $q_update = mysqli_query($koneksi, "UPDATE pengajuancuti SET status = '$status_baru' WHERE idpengajuancuti = '$idpengajuancuti'");
    
    if ($q_update) {
        
        // 2. Catat riwayat persetujuan ke tabel approvecuti agar sinkron dengan database dosen
        // Membuat ID Approve otomatis (Contoh: AP001, AP002, dst)
        $q_id = mysqli_query($koneksi, "SELECT idapprovecuti FROM approvecuti ORDER BY idapprovecuti DESC LIMIT 1");
        if (mysqli_num_rows($q_id) > 0) {
            $row_id = mysqli_fetch_assoc($q_id);
            $id_terakhir = $row_id['idapprovecuti'];
            $angka = (int) substr($id_terakhir, 2);
            $angka++;
            $id_baru = "AP" . sprintf("%03d", $angka);
        } else {
            $id_baru = "AP001";
        }
        
        $tanggal_sekarang = date('Y-m-d');
        
        // Cari nama asli atasan untuk kolom approveby
        $approveby = $username_atasan;
        $q_atasan = mysqli_query($koneksi, "SELECT nama FROM karyawan WHERE nik = '$username_atasan'");
        if ($r_atasan = mysqli_fetch_assoc($q_atasan)) {
            $approveby = $r_atasan['nama'];
        }
        
        // Masukkan data log ke tabel approvecuti sesuai struktur phpMyAdmin Anda
        $q_insert_log = mysqli_query($koneksi, "INSERT INTO approvecuti (idapprovecuti, idpengajuancuti, tanggalapprove, approveby) 
                                                VALUES ('$id_baru', '$idpengajuancuti', '$tanggal_sekarang', '$approveby')");
        
        echo "sukses";
    } else {
        echo "Gagal memperbarui status: " . mysqli_error($koneksi);
    }
} else {
    echo "Data parameter tidak lengkap.";
}

// Tutup koneksi database
koneksi_tutup();
?>