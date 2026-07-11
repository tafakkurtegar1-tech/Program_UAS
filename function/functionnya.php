<?php
/*************************************************************************
php easy :: pagination scripts set - Version One
==========================================================================
Author:      php easy code, www.phpeasycode.com
Web Site:    http://www.phpeasycode.com
Contact:     webmaster@phpeasycode.com
*************************************************************************/

// Fungsi Pagination bawaan dosen (Tetap dipertahankan asli)
function paginate_one($reload, $page, $tpages) {
    $firstlabel = "&laquo;&nbsp;";
    $prevlabel  = "&lsaquo;&nbsp;";
    $nextlabel  = "&nbsp;&rsaquo;";
    $lastlabel  = "&nbsp;&raquo;";
    
    $out = "<ul class=\"pagination\">";
    
    // first
    if($page>1) {
        $out.= "<li><a href=\"" . $reload . "\">" . $firstlabel . "</a></li>";
    } else {
        $out.= "<li><span>" . $firstlabel . "</span></li>";
    }
    
    // previous
    if($page==1) {
        $out.= "<li><span>" . $prevlabel . "</span></li>";
    } elseif($page==2) {
        $out.= "<li><a href=\"" . $reload . "\">" . $prevlabel . "</a></li>";
    } else {
        $out.= "<li><a href=\"" . $reload . "&amp;page=" . ($page-1) . "\">" . $prevlabel . "</a></li>";
    }
    
    // pagenum
    $tpages = ($tpages < 1) ? 1 : $tpages;
    $out.= "<li><span>Halaman " . $page . " dari " . $tpages . "</span></li>";
    
    // next
    if($page<$tpages) {
        $out.= "<li><a href=\"" . $reload . "&amp;page=" . ($page+1) . "\">" . $nextlabel . "</a></li>";
    } else {
        $out.= "<li><span>" . $nextlabel . "</span></li>";
    }
    
    // last
    if($page<$tpages) {
        $out.= "<li><a href=\"" . $reload . "&amp;page=" . $tpages . "\">" . $lastlabel . "</a></li>";
    } else {
        $out.= "<li><span>" . $lastlabel . "</span></li>";
    }
    
    $out.= "</ul>";
    return $out;
}

/**
 * FUNGSI MENU NAVIGASI (SIDEBAR) BERDASARKAN LEVEL JABATAN DATABASE DBCUTI
 */
function menu_navigasi() {
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Staff';
    
    // --- 1. MENU UNTUK LEVEL STAFF ---
    if ($role == 'Staff') {
    ?>
        <ul class="menu">
            <li class="item2"><a href="#">Menu Cuti Karyawan</a>
                <ul>
                    <li><a href="../pengajuancuti/">Form Pengajuan Cuti</a></li>
                    <li><a href="../cekpengajuancuti/">Cek Status & Riwayat Cuti</a></li>
                </ul>
            </li>
            <li class="item5"><a href="#">Pengaturan</a>
                <ul>
                    <li><a href="../gantipassword/">Ganti Password</a></li>
                </ul>
            </li>
        </ul>
    <?php
    }
    
    // --- 2. MENU UNTUK LEVEL MANAGER / HRD ---
    elseif ($role == 'Manager' || $role == 'HRD') {
    ?>
        <ul class="menu">
            <li class="item2"><a href="#">Menu Cuti Karyawan</a>
                <ul>
                    <li><a href="../pengajuancuti/">Form Pengajuan Cuti</a></li>
                    <li><a href="../cekpengajuancuti/">Cek Status & Riwayat Cuti</a></li>
                </ul>
            </li>
            <li class="item3"><a href="#">Panel Kelola Atasan</a>
                <ul>
                    <li><a href="../approval/">Persetujuan Cuti (Approval)</a></li>
                    <li><a href="../cekpengajuancuti/print.php" target="_blank">Cetak Laporan Sisa Cuti</a></li>
                </ul>
            </li>
            <li class="item5"><a href="#">Pengaturan</a>
                <ul>
                    <li><a href="../gantipassword/">Ganti Password</a></li>
                </ul>
            </li>
        </ul>
    <?php
    }
    
    // --- 3. MENU UNTUK LEVEL DIREKTUR ---
    elseif ($role == 'Direktur') {
    ?>
        <ul class="menu">
            <li class="item3"><a href="#">Panel Direksi</a>
                <ul>
                    <li><a href="../approval/">Persetujuan Cuti (Approval)</a></li>
                    <li><a href="../cekpengajuancuti/print.php" target="_blank">Laporan Sisa Cuti Pegawai</a></li>
                </ul>
            </li>
            <li class="item5"><a href="#">Pengaturan</a>
                <ul>
                    <li><a href="../gantipassword/">Ganti Password</a></li>
                </ul>
            </li>
        </ul>
    <?php
    }
}
?>