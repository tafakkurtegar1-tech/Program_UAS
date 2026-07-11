<?php
session_start();
require '../function/koneksi.php';

// Buka koneksi database
koneksi_buka();

// Tangkap data yang dikirim dari formulir aplikasi.js
// Catatan: Menghilangkan hidden-character/spasi tak terlihat agar variabel terbaca normal
$idpengajuancuti = isset($_POST['idpengajuancuti']) ? mysqli_real_escape_string($koneksi, $_POST['idpengajuancuti']) : 0;
$idcuti          = mysqli_real_escape_string($koneksi, $_POST['idcuti']);
$tanggalmulai    = mysqli_real_escape_string($koneksi, $_POST['tanggalmulai']);
$lamacuti        = mysqli_real_escape_string($koneksi, $_POST['lamacuti']);
$alasancuti      = mysqli_real_escape_string($koneksi, $_POST['alasancuti']);

// Ambil NIK dari Karyawan yang sedang login
$nik = $_SESSION['username'];

// Ambil Nama Asli Karyawan serta Sisa Cuti dari tabel karyawan berdasarkan NIK
$nama_karyawan = 'Karyawan';
$sisacuti_sekarang = 0;

$q_karyawan = mysqli_query($koneksi, "SELECT nama, sisacuti FROM karyawan WHERE nik = '$nik'");
if ($r_karyawan = mysqli_fetch_assoc($q_karyawan)) {
    $nama_karyawan = $r_karyawan['nama'];
    $sisacuti_sekarang = (int)$r_karyawan['sisacuti'];
}

// Atur status awal pengajuan cuti baru
$status = 'Proses'; 

if ($idpengajuancuti == 0) {
    // =======================================================
    // 1. MEMBUAT PENGAJUAN CUTI BARU
    // =======================================================
    
    // VALIDASI LOGIKA: Jika yang login bukan user 'admin', cek sisa kuota cutinya
    if ($nik !== 'admin') {
        if ((int)$lamacuti > $sisacuti_sekarang) {
            // TAMPILAN ERROR YANG JAUH LEBIH MENARIK DAN ELEGAN
            ?>
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="utf-8">
                <title>Pengajuan Cuti Gagal</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
                <link href="../css/bootstrap-responsive.min.css" rel="stylesheet">
            </head>
            <body style="background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                
                <div class="container" style="margin-top: 100px; max-width: 600px;">
                    <div class="row">
                        <div class="span12" style="float: none; margin: 0 auto; text-align: center;">
                            
                            <div class="hero-unit" style="padding: 40px 30px; background-color: #ffffff; border-top: 6px solid #e43f5a; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 8px;">
                                <div style="font-size: 60px; margin-bottom: 20px;">⚠️</div>
                                <h2 style="color: #e43f5a; font-size: 26px; font-weight: bold; margin-bottom: 15px; line-height: 1.2;">Pengajuan Cuti Ditolak Sistem</h2>
                                
                                <p style="font-size: 15px; color: #555; line-height: 1.6; margin-bottom: 30px;">
                                    Maaf, jumlah hari cuti yang Anda minta (<strong style="color: #e43f5a;"><?php echo $lamacuti; ?> Hari</strong>) 
                                    melebihi sisa kuota cuti tahunan aktif Anda yang tersisa sebanyak <strong><?php echo $sisacuti_sekarang; ?> Hari</strong>.
                                </p>
                                
                                <a href="index.php" class="btn btn-large btn-danger" style="font-weight: bold; padding: 12px 30px; font-size: 15px; border-radius: 4px; box-shadow: 0 2px 5px rgba(228,63,90,0.3);">
                                    ⬅️ Kembali ke Halaman Utama
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            </body>
            </html>
            <?php
            koneksi_tutup();
            exit();
        }
    }
    
    // Membuat ID Pengajuan otomatis (Contoh: PC001, PC002, dst)
    $q_id = mysqli_query($koneksi, "SELECT idpengajuancuti FROM pengajuancuti ORDER BY idpengajuancuti DESC LIMIT 1");
    if (mysqli_num_rows($q_id) > 0) {
        $row_id = mysqli_fetch_assoc($q_id);
        $id_terakhir = $row_id['idpengajuancuti'];
        $angka = (int) substr($id_terakhir, 2);
        $angka++;
        $id_baru = "PC" . sprintf("%03d", $angka);
    } else {
        $id_baru = "PC001";
    }

    // Query simpan ke dalam database tabel pengajuancuti
    $query = "INSERT INTO pengajuancuti (idpengajuancuti, nik, nama, idcuti, tanggalmulai, lamacuti, alasancuti, status) 
              VALUES ('$id_baru', '$nik', '$nama_karyawan', '$idcuti', '$tanggalmulai', '$lamacuti', '$alasancuti', '$status')";
    
    if (mysqli_query($koneksi, $query)) {
        // JIKA BERHASIL: Otomatis potong sisa cuti karyawan di tabel karyawan
        if ($nik !== 'admin') {
            mysqli_query($koneksi, "UPDATE karyawan SET sisacuti = sisacuti - $lamacuti WHERE nik = '$nik'");
        }
        
        // TAMPILAN PROSES SIMPAN BARU BERHASIL
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="utf-8">
            <title>Pengajuan Cuti Sukses</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
            <link href="../css/bootstrap-responsive.min.css" rel="stylesheet">
        </head>
        <body style="background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
            
            <div class="container" style="margin-top: 100px; max-width: 600px;">
                <div class="row">
                    <div class="span12" style="float: none; margin: 0 auto; text-align: center;">
                        
                        <div class="hero-unit" style="padding: 40px 30px; background-color: #ffffff; border-top: 6px solid #28a745; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 8px;">
                            <div style="font-size: 60px; margin-bottom: 20px;">✅</div>
                            <h2 style="color: #28a745; font-size: 26px; font-weight: bold; margin-bottom: 15px; line-height: 1.2;">Pengajuan Cuti Berhasil!</h2>
                            
                            <p style="font-size: 15px; color: #555; line-height: 1.6; margin-bottom: 30px;">
                                Pengajuan cuti Anda berhasil disimpan dan saat ini berstatus <strong>Proses</strong> menunggu persetujuan admin/manajemen.
                            </p>
                            
                            <a href="index.php" class="btn btn-large btn-success" style="font-weight: bold; padding: 12px 30px; font-size: 15px; border-radius: 4px; box-shadow: 0 2px 5px rgba(40,167,69,0.3);">
                                ⬅️ Kembali ke Halaman Utama
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </body>
        </html>
        <?php
    } else {
        echo "Error Insert: " . mysqli_error($koneksi);
    }

} else {
    // =======================================================
    // 2. JIKA MODE EDIT / UPDATE DATA LAMA
    // =======================================================
    $query = "UPDATE pengajuancuti SET 
                idcuti = '$idcuti', 
                tanggalmulai = '$tanggalmulai', 
                lamacuti = '$lamacuti', 
                alasancuti = '$alasancuti' 
              WHERE idpengajuancuti = '$idpengajuancuti'";
              
    if (mysqli_query($koneksi, $query)) {
        
        // TAMPILAN PROSES UPDATE/EDIT BERHASIL
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="utf-8">
            <title>Perubahan Cuti Sukses</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
            <link href="../css/bootstrap-responsive.min.css" rel="stylesheet">
        </head>
        <body style="background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
            
            <div class="container" style="margin-top: 100px; max-width: 600px;">
                <div class="row">
                    <div class="span12" style="float: none; margin: 0 auto; text-align: center;">
                        
                        <div class="hero-unit" style="padding: 40px 30px; background-color: #ffffff; border-top: 6px solid #007bff; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 8px;">
                            <div style="font-size: 60px; margin-bottom: 20px;">🔄</div>
                            <h2 style="color: #007bff; font-size: 26px; font-weight: bold; margin-bottom: 15px; line-height: 1.2;">Perubahan Data Berhasil!</h2>
                            
                            <p style="font-size: 15px; color: #555; line-height: 1.6; margin-bottom: 30px;">
                                Data pengajuan cuti lama Anda berhasil diperbarui di sistem.
                            </p>
                            
                            <a href="index.php" class="btn btn-large btn-primary" style="font-weight: bold; padding: 12px 30px; font-size: 15px; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,123,255,0.3);">
                                ⬅️ Kembali ke Halaman Utama
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </body>
        </html>
        <?php
    } else {
        echo "Error Update: " . mysqli_error($koneksi);
    }
}

// Tutup koneksi database
koneksi_tutup();
?>