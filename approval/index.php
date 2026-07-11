<?php   
session_start();
ob_start();
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

include '../function/functionnya.php';
require '../function/koneksi.php';
koneksi_buka();

if(empty($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['doLogout']) && $_GET['doLogout'] == "true"){
    session_destroy();
    header("Location: ../index.php");
    exit;
}

$nik_atasan = $_SESSION['username'];
$nama_atasan = 'Manager/HRD';
$role_atasan = 'Management';
$q_atasan = mysqli_query($koneksi, "SELECT nama, level FROM karyawan WHERE nik = '$nik_atasan'");
if ($r_atasan = mysqli_fetch_assoc($q_atasan)) {
    $nama_atasan = $r_atasan['nama'];
    $role_atasan = $r_atasan['level'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Panel Approval Cuti - Atasan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="../css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    <link href="../css/sweetalert.css" rel="stylesheet" type="text/css">
</head>
<body>
  
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner" style="background: linear-gradient(to right, #1f4068, #162447); border-bottom: 3px solid #e43f5a; padding: 5px 0;">
            <div class="container-fluid">
                <a class="brand" href="#" style="color: #95CCDD; font-weight: bold; font-family: 'Arial Black', Gadget, sans-serif; letter-spacing: 2px; text-shadow: 2px 2px 4px rgba(0,0,0,0.6);">
                    🏢 PT TETARA SOLUSI DIGITAL
                </a>
                <div class="btn-group pull-right">
                    <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
                        <i class="icon-user icon-white"></i> <?php echo $nama_atasan . " (" . $role_atasan . ")"; ?>
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" style="padding: 10px; min-width: 100px;">
                        <a href="?doLogout=true" class="btn btn-danger btn-block">📜 Sign Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" style="margin-top: 75px;">
        <div class="row-fluid">
            <div class="span12">
                
                <div class="hero-unit" style="padding: 20px; margin-bottom: 20px; background-color: #f0f4f8; border-left: 5px solid #1f4068;">
                    <h2>Selamat Datang, <?php echo $nama_atasan; ?>!</h2>
                    <p>Halaman khusus manajemen PT TETARA SOLUSI DIGITAL untuk memverifikasi permohonan izin cuti staff secara real-time.</p>
                    <a href="../pengajuancuti/index.php" class="btn btn-info">➡️ Masuk Halaman Karyawan Saya</a>
                </div>

                <div class="block">
                    <div class="navbar navbar-inner block-header">
                        <div class="muted pull-left" style="margin-top: 5px;">📋 Daftar Permohonan Cuti Karyawan (Menunggu Persetujuan)</div>
                        <div class="pull-right">
                            <div style="margin: 0; padding: 2px 0;">
                                <input type="text" id="input-cari" class="input-medium search-query" placeholder="Ketik nama / NIK..." style="margin-bottom: 0;">
                                <button type="button" class="btn btn-mini btn-primary" style="padding: 4px 10px; font-weight: bold; margin-bottom: 2px;">🔍 Cari</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="block-content collapse in" style="padding: 15px;">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID Pengajuan</th>
                                    <th>NIP Karyawan</th>
                                    <th>Nama Karyawan</th>
                                    <th>Kategori Cuti</th>
                                    <th>Tgl Mulai</th>
                                    <th>Lama (Hari)</th>
                                    <th>Alasan Cuti</th>
                                    <th>Status Saat Ini</th>
                                    <th style="text-align: center; width: 240px;">Tindakan / Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="data-tabel-cuti">
                                <?php
                                $query_tabel = mysqli_query($koneksi, "SELECT * FROM pengajuancuti ORDER BY idpengajuancuti DESC");
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
                                    <td style="vertical-align: middle;"><?php echo $row['nik']; ?></td>
                                    <td style="vertical-align: middle;"><?php echo $row['nama']; ?></td>
                                    <td style="vertical-align: middle;"><?php echo $nama_cuti; ?></td>
                                    <td style="vertical-align: middle;"><?php echo $row['tanggalmulai']; ?></td>
                                    <td style="vertical-align: middle;"><?php echo $row['lamacuti']; ?> Hari</td>
                                    <td style="vertical-align: middle;"><?php echo $row['alasancuti']; ?></td>
                                    <td style="vertical-align: middle;"><span class="label <?php echo $badge_class; ?>"><?php echo $status_display; ?></span></td>
                                    
                                    <td style="text-align: center; vertical-align: middle; white-space: nowrap;">
                                        <?php if (strtolower($status_display) == 'proses' || strtolower($status_display) == 'pending'): ?>
                                            <button class="btn btn-mini btn-success btn-aksi" data-id="<?php echo $row['idpengajuancuti']; ?>" data-status="Disetujui" style="font-weight: bold; margin-right: 4px;">
                                                <i class="icon-ok icon-white"></i> Setujui
                                            </button>
                                            <button class="btn btn-mini btn-danger btn-aksi" data-id="<?php echo $row['idpengajuancuti']; ?>" data-status="Ditolak" style="font-weight: bold; margin-right: 4px;">
                                                <i class="icon-remove icon-white"></i> Tolak
                                            </button>
                                        <?php else: ?>
                                            <span class="muted" style="font-size: 11px; margin-right: 6px;">🔒 Selesai</span>
                                        <?php endif; ?>

                                        <a href="hapus_approval.proses.php?id=<?php echo $row['idpengajuancuti']; ?>" 
                                           class="btn btn-mini btn-danger" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus permanen data pengajuan <?php echo $row['idpengajuancuti']; ?> ini?')"
                                           style="font-weight: bold;">
                                            🗑️ Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='9' style='text-align:center;' class='muted'>Belum ada data pengajuan cuti masuk dari karyawan.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="../js/jquery-1.8.3.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/sweetalert-dev.js"></script>
    <script src="aplikasi.js"></script>
</body>
</html>
<?php 
koneksi_tutup(); 
ob_flush(); 
?>