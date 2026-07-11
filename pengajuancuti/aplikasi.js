$(function() {
    // 1. KETIKA TOMBOL "BUAT PENGAJUAN CUTI BARU" ATAU "UBAH" DIKLIK
    $('.tambah, .btn-edit').live('click', function() {
        var id = $(this).attr('id');
        // Jika tombol ubah diklik lewat tabel, ambil data-id nya
        if($(this).hasClass('btn-edit')) {
            id = $(this).attr('data-id');
        }

        // Tampilkan loading di dalam modal sebelum form muncul
        $('#data-pengajuancuti').html('<div style="text-align:center; padding:20px;">⏳ Memuat Formulir...</div>');

        // Ambil data form via AJAX dari pengajuancuti.form.php
        $.ajax({
            type: 'POST',
            url: 'pengajuancuti.form.php',
            data: { id: id },
            success: function(html) {
                $('#data-pengajuancuti').html(html);
            }
        });
    });

    // 2. KETIKA TOMBOL "KIRIM PENGAJUAN 🚀" DI FORMULIR DIKLIK
    $('#simpan-pengajuancuti').live('click', function() {
        var form = $('#form-cuti');
        
        // Validasi bawaan browser (memastikan yang required sudah diisi)
        if (form[0].checkValidity()) {
            var data_form = form.serialize();

            // Kirim data ke file pengajuancuti.proses.php
            $.ajax({
                type: 'POST',
                url: 'pengajuancuti.proses.php',
                data: data_form,
                success: function(respons) {
                    if (respons.trim() == "sukses") {
                        alert("Bagus! Pengajuan cuti berhasil disimpan/dikirim. 🎉");
                        // Tutup modal popup
                        $('#myModal').modal('hide');
                        // Segarkan halaman riwayat agar data baru langsung muncul
                        location.reload();
                    } else {
                        alert("Waduh, Gagal menyimpan data: " + respons);
                    }
                }
            });
        } else {
            // Jika ada field yang kosong, trigger validasi bawaan html5
            form[0].reportValidity();
        }
    });
});