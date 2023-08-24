$(document).ready(function() {
    $('.table').DataTable({
        columnDefs: [
            { orderable: false, targets: [3] }
        ],
        language: {
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tidak ditemukan.",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 - 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            search: "Cari",
            decimal: ",",
            thousands: ".",
            paginate: {
                previous: "Sebelumnya",
                next: "Selanjutnya"
            }
        }
    });
    initTooltip();
});

function initTooltip() {
    $('#btnTambahData').tooltip({title: 'Tambah Data Admin'});
    $('.btnEditData').tooltip({title: 'Ubah Data Admin'});
    $('.delete-link').tooltip({title: 'Hapus Data Admin'});
    $('.reset-link').tooltip({title: 'Reset Password Admin'});
}

// Action untuk Sweetalert
$(document).on('click', '.reset-link', function(e) {
    e.preventDefault();
    var id = $(this).attr('id').split('-')[2];
    var email = $('#reset-form-' + id).find('input[name="emailRst"]').val();
    Swal.fire({
        title: 'Konfirmasi',
        html: 'Password <b>'+email+'</b> akan diset ulang ke default "12345678". Apakah Anda yakin?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, reset password',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.value) {
            $('#reset-form-' + id).submit();
        }
    });
});

$(document).on('click', '.delete-link', function(e) {
    e.preventDefault();
    var id = $(this).attr('id').split('-')[2];
    var name = $('#delete-form-' + id).find('input[name="nameHps"]').val();
    Swal.fire({
        title: 'Konfirmasi',
        html: 'Anda yakin ingin menghapus data <b>'+name+'</b> ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus data',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.value) {
            $('#delete-form-' + id).submit();
        }
    });
});