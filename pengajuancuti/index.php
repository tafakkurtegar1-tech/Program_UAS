<?php   
session_start();
ob_start();
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

include '../function/functionnya.php';
require '../function/koneksi.php';
koneksi_buka();

// Proteksi halaman, jika belum login tendang ke luar
if(empty($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

// Fitur Logout
if (isset($_GET['doLogout']) && $_GET['doLogout'] == "true"){
    session_destroy();
    header("Location: ../index.php");
    exit;
}

$nik_karyawan = $_SESSION['username'];

// AMBIL DATA PROFIL UTUH DARI TABEL KARYAWAN
$nama_karyawan = 'Karyawan';
$divisi_karyawan = '-';
$sisa_cuti = 0;

$q_karyawan = mysqli_query($koneksi, "SELECT nama, divisi, sisacuti FROM karyawan WHERE nik = '$nik_karyawan'");
if ($r_karyawan = mysqli_fetch_assoc($q_karyawan)) {
    $nama_karyawan = $r_karyawan['nama'];
    $divisi_karyawan = $r_karyawan['divisi'];
    $sisa_cuti = $r_karyawan['sisacuti'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Panel Pengajuan Cuti Karyawan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="../css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    <style>
        /* DIUBAH: Memaksa warna latar belakang bawah yang gelap menjadi abu-abu terang bersih */
        body {
            background-color: #f4f6f9 !important;
        }

        /* CSS KHUSUS UNTUK MEMAKSA MODAL BERADA TEPAT DI TENGAH LAYAR & RAPI */
        .modal {
            position: fixed;
            top: 50% !important;
            left: 50% !important;
            margin-top: 0 !important;
            margin-left: 0 !important;
            transform: translate(-50%, -50%) !important;
            width: 550px; /* Lebar kotak modal yang pas */
            box-shadow: 0 5px 25px rgba(0,0,0,0.5) !important;
            border: none !important;
            border-radius: 8px !important;
        }
        .modal-body label {
            margin-top: 10px;
            margin-bottom: 4px;
            font-weight: bold;
            color: #333;
        }
        .modal-body input, .modal-body select, .modal-body textarea {
            width: 90%;
            max-width: 90%;
            margin-bottom: 5px !important;
            padding: 6px 10px !important;
            border-radius: 4px !important;
        }
    </style>
</head>
<body>
  
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner" style="background: linear-gradient(to right, #1f4068, #162447); border-bottom: 3px solid #e43f5a; padding: 5px 0;">
            <div class="container-fluid">
                <a class="brand" href="#" style="color: #95CCDD; font-weight: bold; font-family: 'Arial Black', Gadget, sans-serif; letter-spacing: 2px; text-shadow: 2px 2px 4px rgba(0,0,0,0.6);">
                    🏢 PT TETARA SOLUSI DIGITAL
                </a>
                <div class="btn-group pull-right">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" style="background: #ffffff; color: #162447; font-weight: bold; border: 1px solid #ccc;">
                        <i class="icon-user"></i> <?php echo $nama_karyawan; ?>
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" style="padding: 10px; min-width: 100px;">
                        <a href="?doLogout=true" class="btn btn-danger btn-block">📜 Sign Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" style="margin-top: 85px; padding-left: 20px; padding-right: 20px;">
        <div class="row-fluid">
            <div class="span12">
                
                <div class="hero-unit" style="padding: 25px; margin-bottom: 25px; background-color: #f0f4f8; border-left: 5px solid #1f4068; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <h2 style="margin-top: 0; font-size: 24px; line-height: 1.2;">Sistem Portal Karyawan</h2>
                    <p style="font-size: 14px; color: #555; margin-bottom: 15px;">
                        Selamat datang, <strong><?php echo $nama_karyawan; ?></strong> [<?php echo $divisi_karyawan; ?>]. 
                        Saat ini sisa kuota cuti tahunan Anda adalah: <span class="label label-info" style="font-size: 13px; padding: 2px 8px; font-weight: bold;"><?php echo $sisa_cuti; ?> Hari</span>
                    </p>
                    
                    <div style="display: block; clear: both;">
                        <?php if ($sisa_cuti > 0 || $_SESSION['username'] == 'admin') { ?>
                            <a href="#modalFormCuti" data-toggle="modal" class="btn btn-success" style="font-weight: bold; padding: 8px 16px; margin-right: 10px; font-size: 14px;">
                                ➕ Buat Pengajuan Cuti Baru
                            </a>
                        <?php } else { ?>
                            <button class="btn btn-success" disabled style="font-weight: bold; padding: 8px 16px; margin-right: 10px; font-size: 14px; opacity: 0.5;">
                                ❌ Kuota Cuti Anda Habis
                            </button>
                        <?php } ?>

                        <?php 
                        // Tombol Approval khusus admin dan Silva
                        if ($_SESSION['username'] == 'admin' || $_SESSION['username'] == '678901') { 
                        ?>
                            <a href="../approval/index.php" class="btn btn-warning" style="font-weight: bold; padding: 8px 16px; font-size: 14px;">
                                ⬅️ Masuk Panel Approval Atasan
                            </a>
                        <?php } ?>
                    </div>
                </div>

                <div class="block" style="border: 1px solid #dbe2e8; border-radius: 4px; background: #ffffff; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div class="navbar navbar-inner block-header" style="margin-bottom: 0; min-height: 34px;">
                        <div class="muted pull-left" style="padding-top: 5px; font-weight: bold;">📋 Riwayat Pengajuan Cuti Anda</div>
                    </div>
                    <div class="block-content collapse in" style="padding: 15px;">
                        <table class="table table-striped table-bordered" style="margin-bottom: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">ID Pengajuan</th>
                                    <th style="width: 15%;">Kategori Cuti</th>
                                    <th style="width: 12%;">Tgl Mulai</th>
                                    <th style="width: 10%;">Lama Cuti</th>
                                    <th style="width: 33%;">Alasan Keperluan</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="text-align: center; width: 10%;">Cetak</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_tabel = mysqli_query($koneksi, "SELECT * FROM pengajuancuti WHERE nik = '$nik_karyawan' ORDER BY idpengajuancuti DESC");
                                if ($query_tabel && mysqli_num_rows($query_tabel) > 0) {
                                    while ($row = mysqli_fetch_array($query_tabel)) {
                                        $status_display = $row['status'];
                                        $badge_class = "label-warning"; 
                                        
                                        if (strtolower($status_display) == 'disetujui' || strtolower($status_display) == 'diterima' || strtolower($status_display) == 'approved') {
                                            $badge_class = "label-success";
                                        } elseif (strtolower($status_display) == 'ditolak' || strtolower($status_display) == 'rejected') {
                                            $badge_class = "label-important";
                                        }

                                        $id_c = $row['idcuti'];
                                        $nama_cuti = $id_c;
                                        $q_c = mysqli_query($koneksi, "SELECT jeniscuti FROM jeniscuti WHERE idcuti='$id_c'");
                                        if($q_c && $r_c = mysqli_fetch_assoc($q_c)) {
                                            $nama_cuti = $r_c['jeniscuti'];
                                        }
                                ?>
                                <tr>
                                    <td style="vertical-align: middle;"><?php echo $row['idpengajuancuti']; ?></td>
                                    <td style="vertical-align: middle;"><?php echo $nama_cuti; ?></td>
                                    <td style="vertical-align: middle;"><?php echo $row['tanggalmulai']; ?></td>
                                    <td style="vertical-align: middle;"><?php echo $row['lamacuti']; ?> Hari</td>
                                    <td style="vertical-align: middle;"><?php echo $row['alasancuti']; ?></td>
                                    <td style="vertical-align: middle;"><span class="label <?php echo $badge_class; ?>"><?php echo $status_display; ?></span></td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <?php if (strtolower($status_display) == 'disetujui' || strtolower($status_display) == 'approved'): ?>
                                            <a href="../cetak_cuti.php?id=<?php echo $row['idpengajuancuti']; ?>" target="_blank" class="btn btn-mini btn-info" style="font-weight: bold; padding: 4px 8px;">
                                                🖨️ Cetak PDF
                                            </a>
                                        <?php if (isset($badge_class)): ?>
                                        <?php endif; ?>
                                        <?php else: ?>
                                            <span class="muted" style="font-size: 11px;">🔒 Dikunci</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='7' style='text-align:center;' class='muted'>Anda belum pernah mengajukan cuti.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="modalFormCuti" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header" style="background-color: #1f4068; color: #fff; padding: 12px 20px;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #fff; opacity: 0.8; margin-top: 2px;">×</button>
            <h3 id="myModalLabel" style="font-size: 18px; font-weight: bold; margin: 0;">📝 Form Isian Pengajuan Cuti Baru</h3>
        </div>
        <form method="POST" action="pengajuancuti.proses.php" style="margin: 0;">
            <div class="modal-body" style="max-height: 420px; padding: 15px 25px 25px 25px;">
                
                <label>NIK Karyawan</label>
                <input type="text" name="nik" class="span12" value="<?php echo $nik_karyawan; ?>" readonly style="background-color: #eee;">
                
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="span12" value="<?php echo $nama_karyawan; ?>" readonly style="background-color: #eee;">
                
                <label>Kategori / Jenis Cuti</label>
                <select name="idcuti" class="span12" required style="height: 34px;">
                    <option value="">-- Pilih Jenis Cuti --</option>
                    <?php
                    $q_cuti = mysqli_query($koneksi, "SELECT * FROM jeniscuti");
                    while($r_cuti = mysqli_fetch_array($q_cuti)){
                        echo "<option value='".$r_cuti['idcuti']."'>".$r_cuti['jeniscuti']."</option>";
                    }
                    ?>
                </select>
                
                <label>Tanggal Mulai Cuti</label>
                <input type="date" name="tanggalmulai" class="span12" required style="line-height: 25px; height: 34px;">
                
                <label>Lama Cuti (Hari)</label>
                <input type="number" name="lamacuti" class="span12" placeholder="Contoh: 3" min="1" required style="height: 34px;">
                
                <label>Alasan Keperluan Cuti</label>
                <textarea name="alasancuti" class="span12" rows="3" placeholder="Tulis alasan keperluan cuti Anda dengan jelas..." required></textarea>
                
            </div>
            <div class="modal-footer" style="background-color: #f5f5f5; margin-top: 0; padding: 15px 25px;">
                <button class="btn" data-dismiss="modal" aria-hidden="true" style="font-weight: bold; padding: 6px 14px;">❌ Batal</button>
                <button type="submit" name="btnSimpan" class="btn btn-primary" style="font-weight: bold; padding: 6px 20px;">🚀 Kirim Pengajuan</button>
            </div>
        </form>
    </div>

    <script src="../js/jquery-1.8.3.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>
<?php 
koneksi_tutup(); 
ob_flush(); 
?>