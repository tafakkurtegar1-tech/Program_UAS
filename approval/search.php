<?php
session_start();
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

require '../function/koneksi.php';
koneksi_buka();

$kata_kunci = isset($_POST['keyword']) ? mysqli_real_escape_string($koneksi, $_POST['keyword']) : '';

if (!empty($kata_kunci)) {
    $query_tabel = mysqli_query($koneksi, "SELECT * FROM pengajuancuti WHERE nama LIKE '%$kata_kunci%' OR nik LIKE '%$kata_kunci%' ORDER BY idpengajuancuti DESC");
} else {
    $query_tabel = mysqli_query($koneksi, "SELECT * FROM pengajuancuti ORDER BY idpengajuancuti DESC");
}

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
    <td style="text-align: center; vertical-align: middle;">
        <?php if (strtolower($status_display) == 'proses' || strtolower($status_display) == 'pending'): ?>
            <div style="white-space: nowrap;">
                <button class="btn btn-mini btn-success btn-aksi" data-id="<?php echo $row['idpengajuancuti']; ?>" data-status="Disetujui" style="font-weight: bold; margin-right: 4px;">
                    <i class="icon-ok icon-white"></i> Setujui
                </button>
                <button class="btn btn-mini btn-danger btn-aksi" data-id="<?php echo $row['idpengajuancuti']; ?>" data-status="Ditolak" style="font-weight: bold;">
                    <i class="icon-remove icon-white"></i> Tolak
                </button>
            </div>
        <?php else: ?>
            <span class="muted" style="font-size: 11px;">🔒 Selesai Diproses</span>
        <?php endif; ?>
    </td>
</tr>
<?php 
    }
} else {
    echo "<tr><td colspan='9' style='text-align:center;' class='muted'>Data yang Anda cari tidak ditemukan.</td></tr>";
}

koneksi_tutup();
?>