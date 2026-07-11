<?php
session_start();
require '../function/koneksi.php';

$username_login = $_SESSION['username'];
$nama_tampil = $_SESSION['username']; 

// Mengambil nama asli dari tabel karyawan berdasarkan NIK yang sedang login
$q_karyawan = mysqli_query($koneksi, "SELECT nama FROM karyawan WHERE nik = '$username_login'");
if ($r_karyawan = mysqli_fetch_assoc($q_karyawan)) {
    $nama_tampil = $r_karyawan['nama'];
}

// Menangkap ID untuk mode Edit/Ubah (jika 0 artinya buat pengajuan baru)
$id = isset($_POST['id']) ? mysqli_real_escape_string($koneksi, $_POST['id']) : 0;

$idcuti = '';
$tanggalmulai = '';
$lamacuti = '';
$alasancuti = '';

if ($id != 0) {
    $q_edit = mysqli_query($koneksi, "SELECT * FROM pengajuancuti WHERE idpengajuancuti = '$id'");
    if ($row_edit = mysqli_fetch_assoc($q_edit)) {
        $idcuti = $row_edit['idcuti'];
        $tanggalmulai = $row_edit['tanggalmulai'];
        $lamacuti = $row_edit['lamacuti'];
        $alasancuti = $row_edit['alasancuti'];
    }
}
?>
<form class="form-horizontal" id="form-cuti">
    <input type="hidden" name="idpengajuancuti" value="<?php echo $id; ?>">

    <div class="control-group">
        <label class="control-label">NIP / NIK</label>
        <div class="controls">
            <input type="text" class="input-block-level" value="<?php echo $username_login; ?>" readonly>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label">Nama Karyawan</label>
        <div class="controls">
            <input type="text" class="input-block-level" value="<?php echo $nama_tampil; ?>" readonly>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label">Kategori Cuti</label>
        <div class="controls">
            <select name="idcuti" class="input-block-level" required>
                <option value="">-- Pilih Kategori Cuti --</option>
                <?php
                // SESUAI SCREENSHOT: Mengambil data dari tabel jeniscuti, kolom 'jeniscuti'
                $q_cuti = mysqli_query($koneksi, "SELECT * FROM jeniscuti WHERE idcuti != 'CT000'");
                while ($r_cuti = mysqli_fetch_assoc($q_cuti)) {
                    $selected = ($idcuti == $r_cuti['idcuti']) ? 'selected' : '';
                    // Menggunakan $r_cuti['jeniscuti'] karena nama kolomnya 'jeniscuti'
                    echo "<option value='".$r_cuti['idcuti']."' $selected>".$r_cuti['jeniscuti']."</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label">Tanggal Mulai Cuti</label>
        <div class="controls">
            <input type="date" name="tanggalmulai" class="input-block-level" value="<?php echo $tanggalmulai; ?>" required>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label">Lama Hari</label>
        <div class="controls">
            <input type="number" name="lamacuti" class="input-block-level" placeholder="Contoh: 3" value="<?php echo $lamacuti; ?>" required>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label">Alasan Cuti</label>
        <div class="controls">
            <textarea name="alasancuti" class="input-block-level" rows="3" placeholder="Tuliskan alasan cuti Anda..." required><?php echo $alasancuti; ?></textarea>
        </div>
    </div>
</form>