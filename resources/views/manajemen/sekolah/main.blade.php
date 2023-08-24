@extends('layout.app')

@section('title')
    Manajemen Data Sekolah
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
@endsection

@section('content')
    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed">
        @include('layout.sidebar')
        @include('layout.header')
        <main id="main-container">
            <div class="bg-body-light">
                <div class="content content-full">
                    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                        <div class="flex-grow-1">
                            <h1 class="h3 fw-bold mb-2">
                                Manajemen Data Sekolah
                            </h1>
                            <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                                Halaman untuk manajemen data sekolah yang mengikuti program bis sekolah.
                            </h2>
                        </div>
                        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    Manajemen Data Sekolah
                                </li>
                            </ol>
                        </nav>
                    </div> {{-- d-flex flex-column --}}
                </div> {{-- content content-full --}}
            </div> {{-- bg-body-light --}}
            <div class="content">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            List Data Sekolah
                        </h3>
                        <div class="block-options">
                            <a role="button" id="btnTambahData" class="btn text-primary btn-block-option" data-bs-toggle="modal" data-bs-target="#modal"><i class="fa fa-plus"></i> Tambah Data</a>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th>Kode Sekolah</th>
                                    <th>Nama Sekolah</th>
                                    <th>Alamat</th>
                                    <th>Admin</th>
                                    <th>E-Mail</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td>{{ $data->kode_sekolah }}</td>
                                        <td>{{ $data->nama_sekolah }}</td>
                                        <td>
                                            <a href="https://maps.google.com?q={{ $data->latlong_sekolah }}" title="Titik Lokasi: {{ $data->latlong_sekolah }}" target="_blank">{{ $data->alamat_sekolah }}</a>
                                        </td>
                                        <td>{{ $data->nama_admin_sekolah }}</td>
                                        <td>{{ $data->email_admin_sekolah }}</td>
                                        <td>
                                            <div class="dropdown dropstart">
                                                <label role="button" class="text-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Pilih Opsi"><i class="fa fa-gear"></i> Opsi</label>
                                                <div class="dropdown-menu fs-sm" aria-labelledby="btnAksi">
                                                    <a role="button" class="dropdown-item text-warning btnEditData" data-bs-toggle="modal" data-bs-target="#modal" data-bs-id="{{ $data->kode_sekolah }}" title="Edit Data"><i class="fa fa-pencil"></i> Ubah Data</a>
                                                    <div class="dropdown-divider"></div>
                                                    <form id="delete-form-{{ $data->kode_sekolah }}" action="{{ route('sekolah.hapus', $data->kode_sekolah) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="text" name="namaHps" value="{{ $data->nama_sekolah }}" hidden>
                                                        <a role="button" class="dropdown-item text-danger delete-link" id="delete-link-{{ $data->kode_sekolah }}" title="Hapus Data"><i class="fa fa-trash"></i> Hapus Data</a>
                                                    </form>
                                                    <form id="reset-form-{{ $data->id }}" action="{{ route('user.reset', $data->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="text" name="emailRst" value="{{ $data->email_admin_sekolah }}" hidden>
                                                        <a role="button" class="dropdown-item text-secondary reset-link" id="reset-link-{{ $data->id }}" title="Reset Password"><i class="fa fa-key"></i> Reset Password</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>{{-- table-responsive --}}
                </div> {{-- block-content --}}
            </div> {{-- Block --}}
        </div> {{-- Page Content --}}
    </main> {{-- Main Container --}}
    @include('layout.footer')
</div> {{-- Page Container --}}
@endsection

@section('modal')
    <div class="modal fade" id="modal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-lg"></div></div>
@endsection

@section('script')
    <script src="{{ asset('js/pages/das_sekolah.js') }}"></script>
    <script>
        $('#modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            $.ajax({
                url: '{{ route("sekolah.modal") }}',
                type: 'POST',
                data: {
                    kode_sekolah: button.data('bs-id'),
                    _token: '{{ csrf_token() }}',
                },
                dataType: 'JSON',
                success: function(response) {
                    $('#modal').find('.modal-dialog').html(response.modal);
                    if(response.kode_sekolah) {
                        $('#ig-kodeSekolah').tooltip('dispose');
                        $('input[name="sekolah_namaSekolah"]').tooltip('dispose');
                        $('textarea[name="sekolah_alamatSekolah"]').tooltip('dispose');
                        $('input[name="sekolah_latlongSekolah"]').tooltip('dispose');
                        $('input[name="sekolah_namaAdminSekolah"]').tooltip('dispose');
                        $('#ig-email').tooltip('dispose');
                    } else {
                        $('#ig-kodeSekolah').tooltip({title: 'Masukkan Kode Sekolah'});
                        $('input[name="sekolah_namaSekolah"]').tooltip({title: 'Masukkan Nama Sekolah'});
                        $('textarea[name="sekolah_alamatSekolah"]').tooltip({title: 'Masukkan Alamat Sekolah'});
                        $('input[name="sekolah_latlongSekolah"]').tooltip({title: 'Masukkan Lat, Long Sekolah'});
                        $('input[name="sekolah_namaAdminSekolah"]').tooltip({title: 'Masukkan Nama Admin Sekolah'});
                        $('#ig-email').tooltip({title: 'Masukkan E-Mail Admin Sekolah'});
                    }
                    $('#notAvailable').hide(); $('#available').hide();
                    $('#email-notAvailable').hide(); $('#email-available').hide();
                    $('input[name="sekolah_namaSekolah"]').keyup(function() {
                        var data = $(this).val();
                        if (data.length === 0) {
                            $('input[name="sekolah_namaSekolah"]').tooltip('dispose');
                            $('input[name="sekolah_namaSekolah"]').tooltip({title: 'Masukkan Nama Sekolah'});
                        } else {
                            $('input[name="sekolah_namaSekolah"]').tooltip('dispose');
                        }
                    });
                    $('textarea[name="sekolah_alamatSekolah"]').keyup(function() {
                        var data = $(this).val();
                        if (data.length === 0) {
                            $('textarea[name="sekolah_alamatSekolah"]').tooltip('dispose');
                            $('textarea[name="sekolah_alamatSekolah"]').tooltip({title: 'Masukkan Alamat Sekolah'});
                        } else {
                            $('textarea[name="sekolah_alamatSekolah"]').tooltip('dispose');
                        }
                    });
                    $('input[name="sekolah_latlongSekolah"]').keyup(function() {
                        var data = $(this).val();
                        if (data.length === 0) {
                            $('input[name="sekolah_latlongSekolah"]').tooltip('dispose');
                            $('input[name="sekolah_latlongSekolah"]').tooltip({title: 'Masukkan Lat, Long Sekolah'});
                        } else {
                            $('input[name="sekolah_latlongSekolah"]').tooltip('dispose');
                        }
                    });
                    $('input[name="sekolah_namaAdminSekolah"]').keyup(function() {
                        var data = $(this).val();
                        if (data.length === 0) {
                            $('input[name="sekolah_namaAdminSekolah"]').tooltip('dispose');
                            $('input[name="sekolah_namaAdminSekolah"]').tooltip({title: 'Masukkan Nama Admin Sekolah'});
                        } else {
                            $('input[name="sekolah_namaAdminSekolah"]').tooltip('dispose');
                        }
                    });
                    $('input[name="sekolah_kodeSekolah"]').on('input', function() { // {{-- Kode Sekolah --}}
                        var inputValue = $(this).val().toUpperCase().replace(/\s+/g, '');
                        $(this).val(inputValue);
                    }).keyup(function() {
                        var data = $(this).val();
                        if (data.length === 0) {
                            $('#ig-kodeSekolah').tooltip('dispose');
                            $('#ig-kodeSekolah').tooltip({title: 'Masukkan Kode Sekolah'});
                            $('#notAvailable').hide(); $('#available').hide();
                        } else {
                            $.ajax({
                                url: "{{ route('sekolah.kodeSekolah') }}",
                                method: 'POST',
                                data: {
                                    kode_sekolah: data,
                                    _token: "{{ csrf_token() }}",
                                },
                                dataType: 'JSON',
                                success: function(response) {
                                    if (response.status == 'success') {
                                        $('#ig-kodeSekolah').tooltip('dispose');
                                        $('#ig-kodeSekolah').tooltip({title: response.message});
                                        $('#notAvailable').show(); $('#available').hide();
                                    } else if (response.status == 'error') {
                                        $('#ig-kodeSekolah').tooltip('dispose');
                                        $('#ig-kodeSekolah').tooltip({title: response.message});
                                        $('#available').show(); $('#notAvailable').hide();
                                    }
                                }
                            });
                        }
                    });
                    $('input[name="sekolah_emailAdminSekolah"]').keyup(function() { // {{-- Email Admin Sekolah --}}
                        var data = $(this).val();
                        if (data.length === 0) {
                            $('#ig-email').tooltip('dispose');
                            $('#ig-email').tooltip({title: 'Masukkan E-Mail Admin Sekolah'});
                            $('#email-notAvailable').hide(); $('#email-available').hide();
                        } else {
                            $.ajax({
                                url: "{{ route('sekolah.emailAdminSekolah') }}",
                                method: 'POST',
                                data: {
                                    email: data,
                                    _token: "{{ csrf_token() }}",
                                },
                                dataType: 'JSON',
                                success: function(response) {
                                    if (response.status == 'success') {
                                        $('#ig-email').tooltip('dispose');
                                        $('#ig-email').tooltip({title: response.message});
                                        $('#email-notAvailable').show(); $('#email-available').hide();
                                        if (response.emailExists) {
                                            $('#ig-email').tooltip('dispose');
                                            $('#ig-email').tooltip({title: "E-Mail Tidak Berubah"});
                                            $('#email-available').show(); $('#email-notAvailable').hide();
                                        }
                                    } else if (response.status == 'error') {
                                        $('#ig-email').tooltip('dispose');
                                        $('#ig-email').tooltip({title: response.message});
                                        $('#email-available').show(); $('#email-notAvailable').hide();
                                    }
                                }
                            });
                        }
                    });
                    $('#btn-submit').on('click', function(e) {
                        e.preventDefault();
                        if($('.i-notavailable').is('.i-notavailable:visible')) { // {{-- Kode Sekolah / Email sudah terdaftar/tidak valid --}}
                            Swal.fire('Mohon cek kembali inputan Anda!', '', 'warning');
                            $('#form').find('input[type="text"], input[type="email"], textarea').each(function() {
                                if ($(this).val() == "") {
                                    $(this).css('border-color', '#ff0000');
                                    $(this).on('focus', function() {
                                        $(this).css('border-color', '#ccc');
                                    });
                                }
                                if($('#notAvailable').is('#notAvailable:visible')) {
                                    $('input[name="sekolah_kodeSekolah"]').css('border-color', '#ff0000');
                                    $('input[name="sekolah_kodeSekolah"]').on('focus', function() {
                                        $('input[name="sekolah_kodeSekolah"]').css('border-color', '#ccc');
                                    });
                                }
                                if($('#email-notAvailable').is('#email-notAvailable:visible')) {
                                    $('input[name="sekolah_emailAdminSekolah"]').css('border-color', '#ff0000');
                                    $('input[name="sekolah_emailAdminSekolah"]').on('focus', function() {
                                        $('input[name="sekolah_emailAdminSekolah"]').css('border-color', '#ccc');
                                    });
                                }
                            });
                        } else if($('.i-notavailable').is('.i-notavailable:hidden')) { // {{-- Inputan sudah benar, divalidasi lagi --}}
                            if ($('#form').find('input[name="sekolah_namaSekolah"]').val() == "" || $('#form').find('textarea[name="sekolah_alamatSekolah"]').val() == "" || $('#form').find('input[name="sekolah_latlongSekolah"]').val() == "" || $('#form').find('input[name="sekolah_namaAdminSekolah"]').val() == "") {
                                Swal.fire('Lengkapi Inputan Formulir!', '', 'error');
                                $('#form').find('input[type="text"], input[type="email"], textarea').each(function() {
                                    if ($(this).val() == "") {
                                        $(this).css('border-color', '#ff0000');
                                        $(this).on('focus', function() {
                                            $(this).css('border-color', '#ccc');
                                        });
                                    }
                                });
                            } else if($('#form').find('input[type="email"]').val() == "" || $('#form').find('input[name="sekolah_kodeSekolah"]').val() == "") {
                                Swal.fire('Lengkapi Inputan Formulir!', '', 'error');
                                $('#form').find('input[type="text"], input[type="email"], textarea').each(function() {
                                    if ($(this).val() == "") {
                                        $(this).css('border-color', '#ff0000');
                                        $(this).on('focus', function() {
                                            $(this).css('border-color', '#ccc');
                                        });
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: 'Konfirmasi',
                                    text: 'Apakah data yang Anda input sudah benar?',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ya, benar',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.value) {
                                        $('#form').submit();
                                    }
                                });
                            }
                        }
                    }); // {{-- Submit Button --}}
                }, // {{-- Ajax Success --}}
            }); // {{-- Ajax Function --}}
        }); // {{-- Modal Action --}}
    </script>
@endsection
