@extends('layout.app')

@section('title')
    Manajemen Data Admin
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
                                Manajemen Data Admin
                            </h1>
                            <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                                Halaman untuk manajemen data admin yang mengelola program bus sekolah.
                            </h2>
                        </div>
                        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    Manajemen Data Admin
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
                            List Data Admin
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
                                        <th>Nama</th>
                                        <th>Role</th>
                                        <th>E-Mail</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $admin)
                                        <tr>
                                            <td>{{ $admin->nama }}</td>
                                            <td>{{ $admin->role }}</td>
                                            <td>{{ $admin->email }}</td>
                                            <td>
                                                <div class="dropdown dropstart">
                                                    <label role="button" class="text-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Pilih Opsi"><i class="fa fa-gear"></i> Opsi</label>
                                                    <div class="dropdown-menu fs-sm" aria-labelledby="btnAksi">
                                                        <a role="button" class="dropdown-item text-warning btnEditData" data-bs-toggle="modal" data-bs-target="#modal" data-bs-id="{{ $admin->id }}"><i class="fa fa-pencil"></i> Ubah Data</a>
                                                        @if($admin->id != Auth::user()->id && Auth::user()->role == "super" && $admin->role != "admin_sekolah")
                                                            <div class="dropdown-divider"></div>
                                                            <form id="delete-form-{{ $admin->id }}" action="{{ route('admin.hapus', $admin->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="text" name="nameHps" value="{{ $admin->nama }}" hidden>
                                                                <a role="button" class="dropdown-item text-danger delete-link" id="delete-link-{{ $admin->id }}" title="Hapus Data"><i class="fa fa-trash"></i> Hapus Data</a>
                                                            </form>
                                                            <form id="reset-form-{{ $admin->id }}" action="{{ route('user.reset', $admin->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="text" name="emailRst" value="{{ $admin->email }}" hidden>
                                                                <a role="button" class="dropdown-item text-secondary reset-link" id="reset-link-{{ $admin->id }}" title="Reset Password"><i class="fa fa-key"></i> Reset Password</a>
                                                            </form>
                                                        @endif
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
    <script src="{{ asset('js/pages/das_admin.js') }}"></script>
    <script>
        $('#modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            $.ajax({
                url: '{{ route("admin.modal") }}',
                type: 'POST',
                data: {
                    id: button.data('bs-id'),
                    _token: '{{ csrf_token() }}',
                },
                dataType: 'JSON',
                success: function(response) {
                    $('#modal').find('.modal-dialog').html(response.modal);
                    if(response.id) {
                        $('input[name="admin_name"]').tooltip('dispose');
                        $('#ig-email').tooltip('dispose');
                    } else {
                        $('input[name="admin_name"]').tooltip({title: 'Masukkan Nama Admin'});
                        $('#ig-email').tooltip({title: 'Masukkan E-Mail Admin'});
                    }
                    $('#notAvailable').hide(); $('#available').hide();
                    $('input[name="admin_name"]').keyup(function() {
                        var data = $(this).val();
                        if (data.length === 0) {
                            $('input[name="admin_name"]').tooltip('dispose');
                            $('input[name="admin_name"]').tooltip({title: 'Masukkan Nama Admin'});
                        } else {
                            $('input[name="admin_name"]').tooltip('dispose');
                        }
                    });
                    $('input[name="admin_email"]').keyup(function() {
                        var data = $(this).val();
                        if (data.length === 0) {
                            $('#ig-email').tooltip('dispose');
                            $('#ig-email').tooltip({title: 'Masukkan E-Mail Admin'});
                            $('#notAvailable').hide(); $('#available').hide();
                        } else {
                            $.ajax({
                                url: "{{ route('admin.email') }}",
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
                                        $('#notAvailable').show(); $('#available').hide();
                                        if (response.emailExists) {
                                            $('#ig-email').tooltip('dispose');
                                            $('#ig-email').tooltip({title: "E-Mail Tidak Berubah"});
                                            $('#available').show(); $('#notAvailable').hide();
                                        }
                                    } else if (response.status == 'error') {
                                        $('#ig-email').tooltip('dispose');
                                        $('#ig-email').tooltip({title: response.message});
                                        $('#available').show(); $('#notAvailable').hide();
                                    }
                                }
                            });
                        }
                    });
                    $('#btn-submit').on('click', function(e) {
                        e.preventDefault();
                        if($('.i-notavailable').is('.i-notavailable:visible')) { // {{-- Email sudah terdaftar/tidak valid --}}
                            Swal.fire('Mohon cek kembali inputan Anda!', '', 'warning');
                            $('#form').find('input[type="email"]').each(function() {
                                $(this).css('border-color', '#ff0000');
                                $(this).on('focus', function() {
                                    $(this).css('border-color', '#ccc');
                                });
                            });
                        } else if($('.i-notavailable').is('.i-notavailable:hidden')) { // {{-- Inputan sudah benar, divalidasi lagi --}}
                            if ($('#form').find('input[type="text"]').val() != "" && $('#form').find('input[type="email"]').val() != "") {
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
                            } else {
                                Swal.fire('Lengkapi Inputan Formulir!', '', 'error');
                                $('#form').find('input[type="text"], input[type="email"]').each(function() {
                                    if ($(this).val() == "") {
                                        $(this).css('border-color', '#ff0000');
                                        $(this).on('focus', function() {
                                            $(this).css('border-color', '#ccc');
                                        });
                                    }
                                });
                            }
                        } else { Swal.fire('Oops! Ada Yang Error!', '', 'warning'); }
                    }); // {{-- Submit Button --}}
                }, // {{-- Ajax Success --}}
            }); // {{-- Ajax Function --}}
        }); // {{-- Modal Action --}}
    </script>
@endsection
