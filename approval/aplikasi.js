$(function() {
    
    // 1. FITUR TOMBOL APPROVAL (SETUJUI / TOLAK)
    $('.btn-aksi').live('click', function() {
        var id_pengajuan = $(this).attr('data-id');
        var status_aksi  = $(this).attr('data-status'); 
        
        var konfirmasi = confirm("Apakah Anda yakin ingin melakukan tindakan '" + status_aksi + "' pada pengajuan " + id_pengajuan + "?");
        
        if (konfirmasi) {
            $.ajax({
                type: 'POST',
                url: 'approval.proses.php',
                data: { 
                    id: id_pengajuan, 
                    status: status_aksi 
                },
                success: function(respons) {
                    if (respons.trim() == "sukses") {
                        alert("Berhasil! Status pengajuan " + id_pengajuan + " sekarang menjadi: " + status_aksi);
                        location.reload();
                    } else {
                        alert("Gagal memproses aksi: " + respons);
                    }
                }
            });
        }
    });

    // 2. FITUR PENCARIAN OTOMATIS (LIVE SEARCH)
    $('#input-cari').keyup(function() {
        var kata_kunci = $(this).val();
        
        $.ajax({
            type: 'POST',
            url: 'search.php',
            data: { keyword: kata_kunci },
            success: function(hasil_pencarian) {
                // Menimpa isi table body dengan hasil dari search.php
                $('#data-tabel-cuti').html(hasil_pencarian);
            }
        });
    });

});