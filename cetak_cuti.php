<?php
session_start();
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

require 'function/koneksi.php';
koneksi_buka();

// Ambil ID Pengajuan Cuti dari URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';

if (empty($id)) {
    die("<script>alert('ID Pengajuan tidak valid!'); window.close();</script>");
}

// Ambil detail data pengajuan cuti gabung dengan nama kategori cutinya
$query = mysqli_query($koneksi, "SELECT p.*, j.jeniscuti FROM pengajuancuti p 
                                 LEFT JOIN jeniscuti j ON p.idcuti = j.idcuti 
                                 WHERE p.idpengajuancuti = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("<script>alert('Data tidak ditemukan di database!'); window.close();</script>");
}

// Ambil detail informasi approve dari tabel approvecuti
$query_app = mysqli_query($koneksi, "SELECT * FROM approvecuti WHERE idpengajuancuti = '$id' LIMIT 1");
$data_app = mysqli_fetch_assoc($query_app);
$tanggal_approve = $data_app['tanggalapprove'] ? date('d-m-Y', strtotime($data_app['tanggalapprove'])) : date('d-m-Y');
$nama_atasan     = $data_app['approveby'] ? $data_app['approveby'] : 'Manajemen';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>PDF_Surat_Cuti_<?php echo $data['idpengajuancuti']; ?></title>
    <style>
        /* CSS Khusus agar tampilan persis seperti cetakan PDF lembar A4 */
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, 
            sans-serif; font-size: 14px; 
            line-height: 1.6; 
            color: #333; 
            padding: 10px; 
        }
        .wrapper { 
            width: 100%; 
            max-width: 700px; 
            margin: 0 auto; 
            background: #fff; 
            padding: 20px; 
        }
        .kopsurat { 
            text-align: center; 
            border-bottom: 3px solid #000; 
            padding-bottom: 10px; 
            margin-bottom: 30px; 
        }
        .kopsurat h2 { 
            margin: 0; 
            font-size: 22px; 
            text-transform: uppercase; 
            font-weight: bold; 
        }
        .kopsurat p { 
            margin: 4px 0 0 0; 
            font-size: 12px; 
            color: #555; 
        }
        .judul-surat { 
            text-align: center; 
            text-decoration: underline; 
            font-weight: bold; 
            font-size: 16px; 
            margin-bottom: 35px; 
            letter-spacing: 1px; 
        }
        .tabel-data { 
            width: 100%; 
            margin-bottom: 40px; 
            border-collapse: collapse; 
        }
        .tabel-data td { 
            padding: 10px 5px; 
            vertical-align: top; 
        }
        .tabel-data td.label { 
            width: 28%; 
            font-weight: bold; 
            color: #555; 
        }
        .tabel-data td.titik { 
            width: 3%; 
            text-align: center; 
        }
        .badge-status { 
            background-color: #28a745; 
            color: white; 
            padding: 4px 10px; 
            font-weight: bold; 
            font-size: 12px; 
            border-radius: 3px; 
            display: inline-block; 
            text-transform: uppercase; 
        }
        .tabel-ttd { 
            width: 100%; 
            margin-top: 30px; 
        }
        .tabel-ttd td { 
            width: 50%; 
            text-align: center; 
            vertical-align: top; 
        }
        .space-ttd { 
            height: 90px; 
        }
        
        /* Mengunci ukuran kertas saat printer browser/PDF printer terbuka */
        @media print {
            body { 
                padding: 0; 
                background: none; 
            }
            .wrapper { 
                width: 100%; 
                padding: 0; 
            }
            .btn-kembali { 
                display: none; 
            }
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <div class="kopsurat">
            <h2>PT. TETARA SOLUSI DIGITAL</h2>
            <p>Jl. Jend. Sudirman No. 45, Gedung Pusat Bisnis Lantai 4, Garut</p>
            <p>Telp: (0262) 555-1234 | Email: tetara@sample.co.id</p>
        </div>

        <div class="judul-surat">SURAT PERSETUJUAN IZIN CUTI KARYAWAN</div>

        <p>Menerangkan bahwa permohonan izin cuti yang diajukan oleh karyawan di bawah ini telah diverifikasi dan **DISETUJUI** oleh pihak manajemen:</p>

        <table class="tabel-data">
            <tr>
                <td class="label">ID Pengajuan</td>
                <td class="titik">:</td>
                <td style="font-weight: bold; color: #000;"><?php echo $data['idpengajuancuti']; ?></td>
            </tr>
            <tr>
                <td class="label">NIK / NIP</td>
                <td class="titik">:</td>
                <td><?php echo $data['nik']; ?></td>
            </tr>
            <tr>
                <td class="label">Nama Karyawan</td>
                <td class="titik">:</td>
                <td><?php echo $data['nama']; ?></td>
            </tr>
            <tr>
                <td class="label">Kategori Cuti</td>
                <td class="titik">:</td>
                <td><?php echo $data['jeniscuti'] ? $data['jeniscuti'] : $data['idcuti']; ?></td>
            </tr>
            <tr>
                <td class="label">Tanggal Mulai Cuti</td>
                <td class="titik">:</td>
                <td><?php echo date('d-m-Y', strtotime($data['tanggalmulai'])); ?></td>
            </tr>
            <tr>
                <td class="label">Durasi Cuti</td>
                <td class="titik">:</td>
                <td><?php echo $data['lamacuti']; ?> Hari Kerja</td>
            </tr>
            <tr>
                <td class="label">Alasan Cuti</td>
                <td class="titik">:</td>
                <td><em>"<?php echo $data['alasancuti']; ?>"</em></td>
            </tr>
            <tr>
                <td class="label">Status Konfirmasi</td>
                <td class="titik">:</td>
                <td><span class="badge-status"><?php echo $data['status']; ?></span></td>
            </tr>
        </table>

        <p>Demikian surat keputusan izin cuti ini diterbitkan untuk dapat dipergunakan sebagaimana mestinya.</p>

        <table class="tabel-ttd">
            <tr>
                <td>
                    Pemohon / Karyawan,
                    <div class="space-ttd"></div>
                    <strong>( <?php echo $data['nama']; ?> )</strong>
                </td>
                <td>
                    Garut, <?php echo $tanggal_approve; ?><br>
                    Mewakili Manajemen PT TETARA SOLUSI DIGITAL,
                    <div class="space-ttd"></div>
                    <strong>( <?php echo $nama_atasan; ?> )</strong>
                    <br><span style="font-size: 11px; color:#777;">Authorized Manager</span>
                </td>
            </tr>
        </table>
    </div>

    <script type="text/javascript">
        // Jalankan fungsi cetak begitu halaman selesai dimuat
        window.print();

        // Kode di bawah ini akan otomatis berjalan SETELAH user 
        // mengklik tombol 'Cetak' maupun 'Cancel' pada dialog printer browser
        window.location.href = "pengajuancuti/index.php";
    </script>
</body>
</html>
<?php 
koneksi_tutup(); 
?>