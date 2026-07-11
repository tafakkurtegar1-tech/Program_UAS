<?php
session_start();
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

// Ambil kata kunci pencarian yang dikirim via AJAX
$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : ''; 

// Simpan output ke buffer agar bisa dikonversi ke JSON
ob_start();

// Panggil berkas koneksi
require '../function/koneksi.php';
koneksi_buka();

$nik = $_SESSION['username'];

// Query pencarian berdasarkan NIK user yang login dan mencocokkan ID Pengajuan atau Alasan Cuti
$query_cari = mysql_query("SELECT * FROM pengajuancuti WHERE nik = '$nik' AND (idpengajuancuti LIKE '%$keyword%' OR alasancuti LIKE '%$keyword%') ORDER BY idpengajuancuti DESC");

if (mysql_num_rows($query_cari) > 0) {
    while ($row = mysql_fetch_array($query_cari)) {
        $status_display = $row['status'];
        $badge_class = "label-warning";
        
        if (strtolower($status_display) == 'disetujui' || strtolower($status_display) == 'diterima') {
            $badge_class = "label-success";
        } elseif (strtolower($status_display) == 'ditolak') {
            $badge_class = "label-important";
        }
        ?>
        <tr>
            <td><?php echo $row['idpengajuancuti']; ?></td>
            <td><?php echo $row['nik']; ?></td>
            <td><?php echo $row['nama']; ?></td>
            <td><?php echo $row['idcuti']; ?></td>
            <td><?php echo $row['tanggalmulai']; ?></td>
            <td><?php echo $row['lamacuti']; ?> Hari</td>
            <td><?php echo $row['alasancuti']; ?></td>
            <td><span class="label <?php echo $badge_class; ?>"><?php echo $status_display; ?></span></td>
            <td style="text-align: center;">
                <?php if (strtolower($status_display) == 'disetujui' || strtolower($status_display) == 'diterima'): ?>
                    <a href="../cetak_cuti.php?id=<?php echo $row['idpengajuancuti']; ?>" target="_blank" class="btn btn-mini btn-info" style="font-weight: bold;">
                        <i class="icon-print icon-white"></i> Cetak PDF
                    </a>
                <?php else: ?>
                    <span class="muted" style="font-size: 11px;">🔒 Menunggu</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }
} else {
    echo "<tr><td colspan='9' style='text-align:center;' class='muted'>Data pengajuan tidak ditemukan.</td></tr>";
}

koneksi_tutup();

$html = ob_get_contents(); 
ob_end_clean();

// Kirimkan kembali hasil pencarian dalam bentuk JSON ke aplikasi.js
echo json_encode(array('hasil' => $html));
?>
