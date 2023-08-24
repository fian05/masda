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
    $('#btnTambahData').tooltip({title: 'Tambah Data Bis'});
    $('.btnEditData').tooltip({title: 'Ubah Data Bis'});
    $('.delete-link').tooltip({title: 'Hapus Data Bis'});
}

// Action untuk Sweetalert
$(document).on('click', '.delete-link', function(e) {
    e.preventDefault();
    var platNomor = $(this).attr('id').split('-')[2];
    Swal.fire({
        title: 'Konfirmasi',
        html: 'Anda yakin ingin menghapus data bis <b>' + platNomor.replace(/(\d+)/g, ' $1 ') + '</b> ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus data',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.value) {
            $('#delete-form-' + platNomor).submit();
        }
    });
});